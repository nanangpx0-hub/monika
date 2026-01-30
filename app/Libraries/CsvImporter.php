<?php

namespace App\Libraries;

/**
 * CSV Importer Library
 * Handles CSV file parsing and validation for user imports
 */
class CsvImporter
{
    protected $errors = [];
    protected $validRows = [];
    protected $requiredFields = [];
    protected $fieldMapping = [];

    /**
     * Set required fields for validation
     */
    public function setRequiredFields(array $fields): self
    {
        $this->requiredFields = $fields;
        return $this;
    }

    /**
     * Set field mapping (CSV header => Database field)
     */
    public function setFieldMapping(array $mapping): self
    {
        $this->fieldMapping = $mapping;
        return $this;
    }

    /**
     * Parse CSV file and return data array
     */
    public function parse(string $filePath, bool $hasHeader = true): array
    {
        $this->errors = [];
        $this->validRows = [];

        if (!file_exists($filePath)) {
            $this->errors[] = "File tidak ditemukan: $filePath";
            return [];
        }

        $file = fopen($filePath, 'r');
        if (!$file) {
            $this->errors[] = "Gagal membuka file: $filePath";
            return [];
        }

        $headers = [];
        $rowNumber = 0;
        $data = [];

        while (($row = fgetcsv($file)) !== false) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // First row as header
            if ($hasHeader && $rowNumber === 1) {
                $headers = array_map('trim', $row);
                continue;
            }

            // Validate row count matches header count
            if ($hasHeader && count($row) !== count($headers)) {
                $this->errors[] = "Baris $rowNumber: Jumlah kolom tidak sesuai dengan header";
                continue;
            }

            // Map row data to associative array
            if ($hasHeader) {
                $rowData = array_combine($headers, $row);
            } else {
                $rowData = $row;
            }

            // Apply field mapping
            if (!empty($this->fieldMapping)) {
                $mappedData = [];
                foreach ($this->fieldMapping as $csvField => $dbField) {
                    if (isset($rowData[$csvField])) {
                        $mappedData[$dbField] = trim($rowData[$csvField]);
                    }
                }
                $rowData = $mappedData;
            }

            // Validate required fields
            $rowErrors = $this->validateRow($rowData, $rowNumber);
            if (!empty($rowErrors)) {
                $this->errors = array_merge($this->errors, $rowErrors);
                continue;
            }

            $this->validRows[] = $rowData;
            $data[] = $rowData;
        }

        fclose($file);
        return $data;
    }

    /**
     * Validate a single row
     */
    protected function validateRow(array $row, int $rowNumber): array
    {
        $errors = [];

        foreach ($this->requiredFields as $field) {
            if (!isset($row[$field]) || empty(trim($row[$field]))) {
                $errors[] = "Baris $rowNumber: Field '$field' wajib diisi";
            }
        }

        // Validate email format
        if (isset($row['email']) && !empty($row['email']) && !filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Baris $rowNumber: Format email tidak valid";
        }

        // Validate NIK (16 digits)
        if (isset($row['nik_ktp']) && !empty($row['nik_ktp'])) {
            if (!ctype_digit($row['nik_ktp']) || strlen($row['nik_ktp']) !== 16) {
                $errors[] = "Baris $rowNumber: NIK harus 16 digit angka";
            }
        }

        // Validate phone number
        if (isset($row['phone_number']) && !empty($row['phone_number'])) {
            $phone = preg_replace('/[^0-9]/', '', $row['phone_number']);
            if (strlen($phone) < 10 || strlen($phone) > 15) {
                $errors[] = "Baris $rowNumber: Nomor telepon tidak valid";
            }
        }

        return $errors;
    }

    /**
     * Get all errors from parsing
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get valid rows
     */
    public function getValidRows(): array
    {
        return $this->validRows;
    }

    /**
     * Check if parsing had errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get error count
     */
    public function getErrorCount(): int
    {
        return count($this->errors);
    }

    /**
     * Get valid row count
     */
    public function getValidCount(): int
    {
        return count($this->validRows);
    }
}
