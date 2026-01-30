<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in (handled by AuthFilter, but double check)
        if (!session()->get('is_logged_in')) {
            return redirect()->to('login');
        }

        // Check if user is Admin (id_role == 1)
        if (session()->get('id_role') != 1) {
            return redirect()->to('dashboard')->with('error', 'Akses ditolak. Anda bukan Administrator.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
