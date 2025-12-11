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
        $data['paket'] = $this->paket->orderBy('created_at', 'DESC')->findAll();

        $data['page_title'] = 'Paket Kursus';
        $data['page_subtitle'] = 'Kelola daftar paket kursus yang tersedia';

        return view('paket/paket', $data);
    }

    //    Helper: Ambil angka bulan dari string durasi
    //    Contoh: "3 Bulan" -> 3
    private function extractMonths($durasi)
    {
        if (!$durasi) return null;

        if (preg_match('/\d+/', $durasi, $m)) {
            return intval($m[0]);
        }

        return null;
    }


    //    Helper: Hitung tanggal_selesai berdasarkan:
    //    tanggal_mulai + durasi(bulan)
    private function hitungTanggalSelesai($tanggal_mulai, $durasi)
    {
        if (empty($tanggal_mulai) || empty($durasi)) {
            return null;
        }

        $months = $this->extractMonths($durasi);

        if (!$months) return null;

        $date = new \DateTime($tanggal_mulai);
        $date->modify("+{$months} months");

        return $date->format('Y-m-d');
    }

    //CREATE
    public function create()
    {
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $durasi         = $this->request->getPost('durasi');

        $tanggal_selesai = $this->hitungTanggalSelesai($tanggal_mulai, $durasi);

        $this->paket->insert([
            'nama_paket'        => $this->request->getPost('nama_paket'),
            'level'             => $this->request->getPost('level'),
            'durasi'            => $durasi,
            'jumlah_pertemuan'  => $this->request->getPost('jumlah_pertemuan'),
            'harga'             => $this->request->getPost('harga'),
            'deskripsi'         => $this->request->getPost('deskripsi'),

            // Jadwal kelas
            'tanggal_mulai'     => $tanggal_mulai,
            'tanggal_selesai'   => $tanggal_selesai,

            // Periode pendaftaran
            'periode_mulai'     => $this->request->getPost('periode_mulai'),
            'periode_selesai'   => $this->request->getPost('periode_selesai'),

            'batch'             => $this->request->getPost('batch'),
            'status'            => $this->request->getPost('status') ?? 'aktif',
        ]);

        return redirect()->back()->with('success', 'Paket berhasil ditambahkan!');
    }

    //UPDATE
    public function update($id)
    {
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $durasi         = $this->request->getPost('durasi');

        $tanggal_selesai = $this->hitungTanggalSelesai($tanggal_mulai, $durasi);

        $this->paket->update($id, [
            'nama_paket'        => $this->request->getPost('nama_paket'),
            'level'             => $this->request->getPost('level'),
            'durasi'            => $durasi,
            'jumlah_pertemuan'  => $this->request->getPost('jumlah_pertemuan'),
            'harga'             => $this->request->getPost('harga'),
            'deskripsi'         => $this->request->getPost('deskripsi'),

            // Jadwal kelas
            'tanggal_mulai'     => $tanggal_mulai,
            'tanggal_selesai'   => $tanggal_selesai,

            // Periode pendaftaran
            'periode_mulai'     => $this->request->getPost('periode_mulai'),
            'periode_selesai'   => $this->request->getPost('periode_selesai'),

            'batch'             => $this->request->getPost('batch'),
            'status'            => $this->request->getPost('status'),
        ]);

        return redirect()->back()->with('success', 'Paket berhasil diperbarui!');
    }


    //DELETE
    public function delete($id)
    {
        $this->paket->delete($id);

        return redirect()->back()->with('success', 'Paket berhasil dihapus!');
    }

    //SWITCH STATUS (Aktif ↔ Nonaktif)
    public function status($id)
    {
        $paket = $this->paket->find($id);

        if (!$paket) {
            return redirect()->back()->with('error', 'Paket tidak ditemukan.');
        }

        $newStatus = ($paket['status'] === 'aktif') ? 'nonaktif' : 'aktif';

        $this->paket->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status paket diperbarui!');
    }
}
