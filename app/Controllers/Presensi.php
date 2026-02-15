<?php

namespace App\Controllers;

use App\Models\PresensiModel;
use DateTime;
use DateTimeZone;

class Presensi extends BaseController
{
    private const OFFICE_LAT = -8.1551;
    private const OFFICE_LONG = 113.6946;
    private const MAX_DISTANCE_METER = 100;

    protected PresensiModel $presensiModel;

    public function __construct()
    {
        $this->presensiModel = new PresensiModel();
    }

    public function index()
    {
        if (! session()->get('is_logged_in') || ! session()->get('id_user')) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = (int) session()->get('id_user');
        $today = $this->todayDate();

        $todayAttendance = $this->presensiModel
            ->where('user_id', $userId)
            ->where('tgl', $today)
            ->first();

        $hasMasuk = ! empty($todayAttendance['jam_masuk']);
        $hasPulang = ! empty($todayAttendance['jam_pulang']);

        return view('presensi/index', [
            'title' => 'Presensi',
            'presensi_hari_ini' => $todayAttendance,
            'has_masuk' => $hasMasuk,
            'has_pulang' => $hasPulang,
            'office_lat' => self::OFFICE_LAT,
            'office_long' => self::OFFICE_LONG,
            'max_distance' => self::MAX_DISTANCE_METER,
        ]);
    }

    public function submit()
    {
        if (! $this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON($this->jsonResponse('error', 'Request tidak valid.'));
        }

        if (! session()->get('is_logged_in') || ! session()->get('id_user')) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON($this->jsonResponse('error', 'Sesi login tidak valid. Silakan login ulang.'));
        }

        $validationRules = [
            'image' => 'required',
            'lat' => 'required|decimal',
            'long' => 'required|decimal',
            'type' => 'required|in_list[masuk,pulang]',
        ];

        if (! $this->validate($validationRules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON($this->jsonResponse('error', 'Input tidak valid.', $this->validator->getErrors()));
        }

        $userId = (int) session()->get('id_user');
        $type = (string) $this->request->getPost('type');
        $lat = filter_var($this->request->getPost('lat'), FILTER_VALIDATE_FLOAT);
        $long = filter_var($this->request->getPost('long'), FILTER_VALIDATE_FLOAT);
        $imageBase64 = trim((string) $this->request->getPost('image'));

        if ($lat === false || $long === false) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON($this->jsonResponse('error', 'Koordinat tidak valid.'));
        }

        $distance = $this->distanceInMeter($lat, $long, self::OFFICE_LAT, self::OFFICE_LONG);
        if ($distance > self::MAX_DISTANCE_METER) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON($this->jsonResponse(
                    'error',
                    'Presensi ditolak. Anda berada di luar radius kantor (' . round($distance, 2) . ' meter).'
                ));
        }

        $imageBinary = $this->decodeBase64Image($imageBase64);
        if ($imageBinary === null) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON($this->jsonResponse('error', 'Format gambar tidak valid.'));
        }

        $now = $this->nowInJakarta();
        $today = $now->format('Y-m-d');
        $time = $now->format('H:i:s');
        $filename = $now->format('YmdHis') . '_' . $userId . '_' . $type . '.jpg';

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'presensi';
        if (! is_dir($uploadDir) && ! mkdir($uploadDir, 0755, true) && ! is_dir($uploadDir)) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON($this->jsonResponse('error', 'Folder upload tidak dapat dibuat.'));
        }

        $filePath = $uploadDir . DIRECTORY_SEPARATOR . $filename;
        if (file_put_contents($filePath, $imageBinary) === false) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON($this->jsonResponse('error', 'Gagal menyimpan file gambar.'));
        }

        $locationJson = json_encode([
            'lat' => (float) $lat,
            'long' => (float) $long,
        ], JSON_UNESCAPED_SLASHES);

        $ipAddress = substr((string) $this->request->getIPAddress(), 0, 45);
        $userAgent = substr((string) $this->request->getUserAgent(), 0, 255);

        $todayAttendance = $this->presensiModel
            ->where('user_id', $userId)
            ->where('tgl', $today)
            ->first();

        if ($type === 'masuk') {
            if ($todayAttendance && ! empty($todayAttendance['jam_masuk'])) {
                @unlink($filePath);
                return $this->response
                    ->setStatusCode(409)
                    ->setJSON($this->jsonResponse('error', 'Anda sudah melakukan presensi masuk hari ini.'));
            }

            $payload = [
                'jam_masuk' => $time,
                'foto_masuk' => $filename,
                'lokasi_masuk' => $locationJson,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ];

            $ok = true;
            if ($todayAttendance) {
                $ok = $this->presensiModel->update((int) $todayAttendance['id'], $payload);
            } else {
                $ok = $this->presensiModel->insert(array_merge($payload, [
                    'user_id' => $userId,
                    'tgl' => $today,
                ]));
            }

            if (! $ok) {
                @unlink($filePath);
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON($this->jsonResponse('error', 'Gagal menyimpan presensi masuk.'));
            }

            return $this->response->setJSON($this->jsonResponse('success', 'Presensi masuk berhasil disimpan.'));
        }

        if (! $todayAttendance || empty($todayAttendance['jam_masuk'])) {
            @unlink($filePath);
            return $this->response
                ->setStatusCode(409)
                ->setJSON($this->jsonResponse('error', 'Anda belum melakukan presensi masuk hari ini.'));
        }

        if (! empty($todayAttendance['jam_pulang'])) {
            @unlink($filePath);
            return $this->response
                ->setStatusCode(409)
                ->setJSON($this->jsonResponse('error', 'Anda sudah melakukan presensi pulang hari ini.'));
        }

        $ok = $this->presensiModel->update((int) $todayAttendance['id'], [
            'jam_pulang' => $time,
            'foto_pulang' => $filename,
            'lokasi_pulang' => $locationJson,
        ]);

        if (! $ok) {
            @unlink($filePath);
            return $this->response
                ->setStatusCode(500)
                ->setJSON($this->jsonResponse('error', 'Gagal menyimpan presensi pulang.'));
        }

        return $this->response->setJSON($this->jsonResponse('success', 'Presensi pulang berhasil disimpan.'));
    }

    private function decodeBase64Image(string $data): ?string
    {
        if (! preg_match('/^data:image\/(?:jpeg|jpg|png|webp);base64,/', $data)) {
            return null;
        }

        $raw = substr($data, strpos($data, ',') + 1);
        $raw = str_replace(' ', '+', $raw);
        $decoded = base64_decode($raw, true);

        if ($decoded === false || $decoded === '') {
            return null;
        }

        return $decoded;
    }

    private function distanceInMeter(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function nowInJakarta(): DateTime
    {
        return new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    }

    private function todayDate(): string
    {
        return $this->nowInJakarta()->format('Y-m-d');
    }

    private function jsonResponse(string $status, string $message, array $errors = []): array
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
        ];

        if ($errors !== []) {
            $response['errors'] = $errors;
        }

        return $response;
    }
}
