<?php

namespace App\Controllers;

use App\Models\OperatorModel;

class AuthController extends BaseController
{
    protected $operator;

    public function __construct()
    {
        $this->operator = new OperatorModel();
    }

    public function index()
    {
        return view('auth/login');
    }

    public function loginProcess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->operator
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
        session()->set([
            'logged_in' => true,
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'nama'      => $user['nama_lengkap'],
            'role'      => $user['role'],
        ]);

        // LOGIN BERHASIL - Return JSON
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Selamat datang, ' . $user['nama_lengkap'] . '!'
        ]);
    }

    public function registerProcess()
    {
        $data = [
            'username'      => $this->request->getPost('username'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => 'operator',
            'status'        => 'aktif',
            'created_at'    => date('Y-m-d H:i:s'),
            'deleted_at'    => null
        ];

        $this->operator->insert($data);

        return redirect()->to('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
