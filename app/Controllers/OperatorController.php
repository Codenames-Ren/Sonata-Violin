<?php

namespace App\Controllers;

use App\Models\OperatorModel;

class OperatorController extends BaseController
{
    protected $operator;

    public function __construct()
    {
        $this->operator = new OperatorModel();
    }

    public function operators()
    {
        $data['operators'] = $this->operator
            ->where('deleted_at', null)
            ->findAll();

        $data['page_title'] = "Management Operator";
        $data['page_subtitle'] = "Kelola akun operator & admin";

        return view('operators/operator', $data);
    }

    public function create()
    {
        if ($this->request->getPost('role') === 'admin' && empty($this->request->getPost('password'))) {
            return redirect()->back()->with('error', 'Password wajib untuk membuat akun admin.');
        }

        $this->operator->insert([
            'username'      => $this->request->getPost('username'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'status'        => 'aktif',
            'created_at'    => date('Y-m-d H:i:s'),
            'deleted_at'    => null
        ]);

        return redirect()->back()->with('success', 'Operator berhasil ditambahkan!');
    }

    public function update($id)
    {
        $op = $this->operator->find($id);

        if (!$op) {
            return redirect()->back()->with('error', 'Operator tidak ditemukan.');
        }

        if ($op['role'] === 'admin' && $this->request->getPost('role') !== 'admin') {
            return redirect()->back()->with('error', 'Admin tidak boleh diubah menjadi operator.');
        }

        $updateData = [
            'username'     => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role'         => $this->request->getPost('role'),
        ];

        if (!empty($this->request->getPost('password'))) {
            $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->operator->update($id, $updateData);

        return redirect()->back()->with('success', 'Data operator berhasil diperbarui!');
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

        return redirect()->back()->with('success', 'Operator berhasil dihapus (soft delete).');
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
