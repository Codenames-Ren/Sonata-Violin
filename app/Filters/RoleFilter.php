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

        // Belum login
        if (!$role) {
            return redirect()->to('/login');
        }

        $path = service('uri')->getPath();

        // Instruktor dilarang ke dashboard
        if ($role === 'instruktur' && $path === 'dashboard') {
            return redirect()->to('/jadwal');
        }

        // Jika route butuh role tertentu
        if ($arguments && !in_array($role, $arguments)) {
            // Redirect sesuai role
            if ($role === 'instruktur') {
                return redirect()->to('/jadwal')->with('error', 'Akses ditolak.');
            }

            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}
