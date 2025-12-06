<?php

namespace App\Controllers;

use App\Models\PaketModel;

class PaketController extends BaseController
{
    protected $paket;

    public function __construct()
    {
        $this->paket = new PaketModel();
    }

    public function paket()
    {
        $data['paket'] = $this->paket->findAll();

        $data['page_title'] = 'Paket Kursus';
        $data['page_subtitle'] = 'Kelola daftar paket kursus yang tersedia';

        return view('paket/paket', $data);
    }

    // CREATE
    public function create()
    {
        $this->paket->insert([
            'nama_paket'        => $this->request->getPost('nama_paket'),
            'level'             => $this->request->getPost('level'),
            'durasi'            => $this->request->getPost('durasi'),
            'jumlah_pertemuan'  => $this->request->getPost('jumlah_pertemuan'),
            'harga'             => $this->request->getPost('harga'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
        ]);

        return redirect()->back()->with('success', 'Paket berhasil ditambahkan!');
    }

    // UPDATE
    public function update($id)
    {
        $this->paket->update($id, [
            'nama_paket'        => $this->request->getPost('nama_paket'),
            'level'             => $this->request->getPost('level'),
            'durasi'            => $this->request->getPost('durasi'),
            'jumlah_pertemuan'  => $this->request->getPost('jumlah_pertemuan'),
            'harga'             => $this->request->getPost('harga'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
        ]);

        return redirect()->back()->with('success', 'Paket berhasil diperbarui!');
    }

    // DELETE
    public function delete($id)
    {
        $this->paket->delete($id);

        return redirect()->back()->with('success', 'Paket berhasil dihapus!');
    }
}
