<?php

// Security Test Script for Data Cleaning Logic

function cleanData(array $data): array
{
    $cleaned = [];
    foreach ($data as $key => $value) {
        if (is_string($value)) {
            // Documentation: Step 1 - Remove leading/trailing whitespace
            $value = trim($value);

            // Documentation: Step 2 - Remove Null Bytes and other invisible control characters (except common whitespace)
            // This prevents binary stuffing and some SQLi/XSS bypasses
            $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);

            // Documentation: Step 3 - Strip HTML and PHP tags to prevent XSS injection
            $value = strip_tags($value);

            // Documentation: Step 4 - Convert special characters to HTML entities
            // This ensures that characters like <, >, &, ", ' are treated as data, not code.
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        $cleaned[$key] = $value;
    }
    return $cleaned;
}

// Test Scenarios
$scenarios = [
    'Normal Input' => [
        'input' => ['nama' => '  John Doe  ', 'alamat' => 'Jl. Merdeka No. 1'],
        'expected' => ['nama' => 'John Doe', 'alamat' => 'Jl. Merdeka No. 1']
    ],
    'XSS Script Tag' => [
        'input' => ['komentar' => '<script>alert("hacked")</script>Halo'],
        'expected' => ['komentar' => 'alert(&quot;hacked&quot;)Halo'] // strip_tags removes <script>, content remains
    ],
    'XSS Image OnError' => [
        'input' => ['avatar' => '<img src=x onerror=alert(1)>'],
        'expected' => ['avatar' => ''] // strip_tags removes the whole tag
    ],
    'SQL Injection Attempt (Simple)' => [
        'input' => ['user' => "' OR '1'='1"],
        'expected' => ['user' => '&#039; OR &#039;1&#039;=&#039;1'] // htmlspecialchars encodes quotes
    ],
    'Null Byte Injection' => [
        'input' => ['file' => "image.png\0.php"],
        'expected' => ['file' => 'image.png.php'] // null byte removed
    ]
];

echo "Running Security Cleaning Tests...\n\n";

foreach ($scenarios as $name => $test) {
    echo "Test: $name\n";
    $result = cleanData($test['input']);
    
    $passed = true;
    foreach ($test['expected'] as $key => $expVal) {
        if ($result[$key] !== $expVal) {
            $passed = false;
            echo "  [FAIL] Key '$key'\n";
            echo "    Input:    " . $test['input'][$key] . "\n";
            echo "    Expected: " . $expVal . "\n";
            echo "    Got:      " . $result[$key] . "\n";
        }
    }
    
    if ($passed) {
        echo "  [PASS]\n";
    }
    echo "\n";
}
