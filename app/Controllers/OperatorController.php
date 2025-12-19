<?php

namespace App\Controllers;

use App\Models\OperatorModel;
use App\Models\InstrukturModel;

class OperatorController extends BaseController
{
    protected $operator;

    public function __construct()
    {
        $this->operator = new OperatorModel();
    }

    public function operators()
    {
        $instrukturModel = new InstrukturModel();

        $data['operators'] = $this->operator
            ->where('deleted_at', null)
            ->findAll();

        $data['instrukturs'] = $instrukturModel
            ->where('deleted_at', null)
            ->findAll();

        $data['page_title'] = "Management Operator";
        $data['page_subtitle'] = "Kelola akun operator & admin";

        return view('operators/operator', $data);
    }

    public function create()
    {
        $role = $this->request->getPost('role');

        // Cek username unik
        $existingUser = $this->operator
            ->where('username', $this->request->getPost('username'))
            ->where('deleted_at', null)
            ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'Username sudah digunakan!');
        }

        // Password wajib
        if (empty($this->request->getPost('password'))) {
            return redirect()->back()->with('error', 'Password wajib diisi.');
        }

        $data = [
            'username'      => $this->request->getPost('username'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $role,
            'status'        => 'aktif',
            'created_at'    => date('Y-m-d H:i:s'),
            'deleted_at'    => null
        ];

        // 🔐 KHUSUS INSTRUKTUR
        if ($role === 'instruktur') {
            $instrukturId = $this->request->getPost('instruktur_id');

            if (empty($instrukturId)) {
                return redirect()->back()->with('error', 'Instruktur wajib dipilih.');
            }

            $data['instruktur_id'] = $instrukturId;
        } else {
            $data['instruktur_id'] = null;
        }

        $this->operator->insert($data);

        return redirect()->back()->with('success', 'Akun berhasil ditambahkan!');
    }

    public function checkUsername()
    {
        $username = $this->request->getPost('username');
        $opId = $this->request->getPost('op_id');

        $query = $this->operator
            ->where('username', $username)
            ->where('deleted_at', null);

        if (!empty($opId)) {
            $query->where('id !=', $opId);
        }

        $exists = $query->first();

        return $this->response->setJSON([
            'available' => !$exists
        ]);
    }

    public function update($id)
    {
        $op = $this->operator->find($id);

        if (!$op) {
            return redirect()->back()->with('error', 'Akun tidak ditemukan.');
        }

        $username = $this->request->getPost('username');
        $role     = $this->request->getPost('role');
        $password = $this->request->getPost('password');

        // Cek username unik (kecuali dirinya sendiri)
        $existingUser = $this->operator
            ->where('username', $username)
            ->where('id !=', $id)
            ->where('deleted_at', null)
            ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'Username sudah digunakan!');
        }

        // Admin tidak boleh turun role
        if ($op['role'] === 'admin' && $role !== 'admin') {
            return redirect()->back()->with('error', 'Role admin tidak boleh diubah.');
        }

        // Data update dasar
        $updateData = [
            'username'     => $username,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role'         => $role,
        ];

        // Update password hanya jika diisi
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->operator->update($id, $updateData);

        return redirect()->back()->with('success', 'Data akun berhasil diperbarui.');
    }

    public function delete($id)
    {
        $op = $this->operator->find($id);

        if (!$op) {
            return redirect()->back()->with('error', 'Operator tidak ditemukan.');
        }

        if ($op['role'] === 'admin') {
            return redirect()->back()->with('error', 'Admin tidak dapat dihapus.');
        }

        $this->operator->update($id, [
            'deleted_at' => date('Y-m-d H:i:s'),
            'status'     => 'nonaktif'
        ]);

        return redirect()->back()->with('success', 'Operator berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $op = $this->operator->find($id);

        if (!$op) {
            return redirect()->back()->with('error', 'Operator tidak ditemukan.');
        }

        if ($op['role'] === 'admin') {
            return redirect()->back()->with('error', 'Admin tidak dapat dinonaktifkan.');
        }

        $newStatus = $op['status'] === 'aktif' ? 'nonaktif' : 'aktif';

        $this->operator->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status operator berhasil diperbarui.');
    }
}
