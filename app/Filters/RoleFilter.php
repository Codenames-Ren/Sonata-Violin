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
        
        if (!$role) {
            return redirect()->to('/login');
        }

        $path = service('uri')->getPath();

        if ($role === 'instruktur' && $path === 'dashboard') {
            return redirect()->to('/jadwal-kelas');
        }

        // Jika route butuh role tertentu
        if ($arguments && !in_array($role, $arguments)) {
            if ($role === 'instruktur') {
                return redirect()->to('/jadwal-kelas');
            }
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action
    }
}