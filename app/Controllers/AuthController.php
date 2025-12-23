<?php
namespace App\Controllers;
use App\Models\OperatorModel;

class AuthController extends BaseController
{
    protected $operatorModel;

    public function __construct()
    {
        $this->operatorModel = new OperatorModel();
    }

    public function index()
    {
        return view('auth/login');
    }

    public function loginProcess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->operatorModel
                    ->where('username', $username)
                    ->where('deleted_at', null)
                    ->first();

        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username tidak terdaftar!'
            ]);
        }

        // CEK STATUS AKUN
        if ($user['status'] === 'nonaktif') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akun ini telah dinonaktifkan.'
            ]);
        }

        // CEK PASSWORD
        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username atau Password salah!'
            ]);
        }

        // SET SESSION
        $sessionData = [
            'logged_in' => true,
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'nama'      => $user['nama_lengkap'],
            'role'      => $user['role']
        ];

        if ($user['role'] === 'instruktur') {
            $sessionData['instruktur_id'] = $user['instruktur_id'];
        }

        session()->set($sessionData);

        // Redirect berdasarkan role
        $redirectUrl = ($user['role'] === 'instruktur') ? '/jadwal-kelas' : '/dashboard';

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Selamat datang, ' . $user['nama_lengkap'] . '!',
            'redirect' => $redirectUrl
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}