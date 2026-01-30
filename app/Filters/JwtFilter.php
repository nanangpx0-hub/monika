<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getServer('HTTP_AUTHORIZATION');

        if (!$header) {
            return response()->setJSON(['status' => 401, 'message' => 'Token Required'])->setStatusCode(401);
        }

        $token = null;
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            return response()->setJSON(['status' => 401, 'message' => 'Token Invalid'])->setStatusCode(401);
        }

        try {
            $key = getenv('JWT_SECRET') ?: 'monika_secret_key_2026';
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            // Attach user data to request specifically for API controllers if needed, 
            // or just allow pass through.
            // For CI4, we can set it to a global or registry if we want to access it later.

        } catch (\Exception $e) {
            return response()->setJSON(['status' => 401, 'message' => 'Token Expired or Invalid'])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
