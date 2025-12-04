<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session('role');

        // Jika belum login
        if (!$role) {
            return redirect()->to('/login');
        }

        // Jika route butuh role tertentu
        if ($arguments && !in_array($role, $arguments)) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
