<?php

namespace App\Controllers;

use App\Models\RuangKelasModel;

class RuangKelasController extends BaseController
{
    protected $ruang;

    public function __construct()
    {
        $this->ruang = new RuangKelasModel();
    }

    public function ruangKelas()
    {
        $data = [
            'ruang_kelas' => $this->ruang
                        ->where('deleted_at', null)
                        ->findAll(),

            'page_title' => 'Manajemen Ruang Kelas',
            'page_subtitle' => 'Kelola data ruangan kelas Sonata Violin'
        ];

        return view('ruang_kelas/ruang_kelas', $data);
    }

    // CREATE
    public function create()
    {
        $this->ruang->insert([
            'nama_ruang' => $this->request->getPost('nama_ruang'),
            'kapasitas'  => $this->request->getPost('kapasitas'),
            'fasilitas'  => $this->request->getPost('fasilitas'),
            'status'     => 'aktif'
        ]);

        return redirect()->back()->with('success', 'Ruang kelas berhasil ditambahkan!');
    }

    // UPDATE
    public function update($id)
    {
        $this->ruang->update($id, [
            'nama_ruang' => $this->request->getPost('nama_ruang'),
            'kapasitas'  => $this->request->getPost('kapasitas'),
            'fasilitas'  => $this->request->getPost('fasilitas'),
        ]);

        return redirect()->back()->with('success', 'Data ruang kelas berhasil diupdate!');
    }

    // DELETE
    public function delete($id)
    {
        $this->ruang->update($id, [
            'status'     => 'nonaktif',
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Ruang kelas berhasil diarsipkan!');
    }

    // TOGGLE STATUS
    public function toggleStatus($id)
    {
        $r = $this->ruang->find($id);

        if (!$r) {
            return redirect()->back()->with('error', 'Ruang kelas tidak ditemukan!');
        }

        $newStatus = $r['status'] === 'aktif' ? 'nonaktif' : 'aktif';

        $this->ruang->update($id, [
            'status' => $newStatus
        ]);

        return redirect()->back()->with('success', 'Status ruang kelas berhasil diubah!');
    }
}
