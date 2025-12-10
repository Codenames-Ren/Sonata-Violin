<?php

namespace App\Controllers;

use App\Models\PendaftaranModel;
use App\Models\SiswaModel;
use App\Models\PaketModel;

class PendaftaranController extends BaseController
{
    protected $pendaftaran;
    protected $siswa;
    protected $paket;
    protected $db;

    public function __construct()
    {
        $this->pendaftaran = new PendaftaranModel();
        $this->siswa       = new SiswaModel();
        $this->paket       = new PaketModel();
        $this->db          = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'pendaftaran' => $this->pendaftaran
                ->where('deleted_at', null)
                ->select('pendaftaran.*, paket_kursus.nama_paket')
                ->join('paket_kursus', 'paket_kursus.id = pendaftaran.paket_id', 'left')
                ->where('pendaftaran.deleted_at', null)
                ->findAll(),

            'paket' => $this->paket
                ->findAll(),

            'page_title'    => 'Data Pendaftaran',
            'page_subtitle' => 'Manajemen Pendaftaran Siswa'
        ];

        return view('pendaftaran/pendaftaran', $data);
    }

    // CREATE (ADMIN / OFFLINE)
    public function create()
    {
        $foto = $this->request->getFile('foto_profil');
        $namaFoto = null;

        if ($foto && $foto->isValid()) {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/pendaftaran', $namaFoto);
        }

        $this->pendaftaran->insert([
            'paket_id'         => $this->request->getPost('paket_id'),
            'tanggal_daftar'  => date('Y-m-d H:i:s'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'status'          => 'pending',

            // DATA HOLDING SISWA
            'nama'        => $this->request->getPost('nama'),
            'alamat'      => $this->request->getPost('alamat'),
            'no_hp'       => $this->request->getPost('no_hp'),
            'email'       => $this->request->getPost('email'),
            'tgl_lahir'   => $this->request->getPost('tgl_lahir'),
            'foto_profil' => $namaFoto,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil ditambahkan (status pending).');
    }

    // UPDATE DATA PENDAFTARAN
    public function update($id)
    {
        $this->pendaftaran->update($id, [
            'paket_id'         => $this->request->getPost('paket_id'),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    // VERIFIKASI → PINDAH KE MASTER SISWA
    public function verifikasi($id)
    {
        $p = $this->pendaftaran->find($id);

        if (!$p) {
            return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        if ($p['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Pendaftaran sudah diproses.');
        }

        $this->db->transBegin();

        // =========================
        // PINDAHKAN FOTO PROFIL
        // =========================
        $fotoLama = $p['foto_profil']; // dari uploads/pendaftaran
        $fotoBaru = null;

        if ($fotoLama) {
            $pathLama = FCPATH . 'uploads/pendaftaran/' . $fotoLama;
            $pathBaru = FCPATH . 'uploads/siswa/' . $fotoLama;

            if (file_exists($pathLama)) {
                copy($pathLama, $pathBaru);
                $fotoBaru = $fotoLama;
            }
        }

        // =========================
        // INSERT KE MASTER SISWA
        // =========================
        $this->siswa->insert([
            'nama'        => $p['nama'],
            'alamat'      => $p['alamat'],
            'no_hp'       => $p['no_hp'],
            'email'       => $p['email'],
            'tgl_lahir'   => $p['tgl_lahir'],
            'foto_profil' => $fotoBaru, // ✅ SUDAH DARI uploads/siswa
            'status'      => 'aktif',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        $idSiswa = $this->siswa->getInsertID();

        // =========================
        // UPDATE PENDAFTARAN
        // =========================
        $this->pendaftaran->update($id, [
            'status'      => 'aktif',
            'siswa_id'    => $idSiswa,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // =========================
        // TRANSACTION CHECK
        // =========================
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memverifikasi data.');
        }

        $this->db->transCommit();

        return redirect()->back()->with('success', 'Pendaftaran diverifikasi & siswa berhasil diaktifkan.');
    }

    // BATALKAN
    public function batalkan($id)
    {
        $this->pendaftaran->update($id, [
            'status'     => 'batal',
            'updated_at'=> date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pendaftaran dibatalkan.');
    }

    // SELESAI
    public function selesai($id)
    {
        $this->pendaftaran->update($id, [
            'status'     => 'selesai',
            'updated_at'=> date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pendaftaran dinyatakan selesai.');
    }

    // DELETE (ONLY ADMIN)
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin yang boleh menghapus data transaksi.');
        }

        $this->pendaftaran->update($id, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Data pendaftaran berhasil diarsipkan.');
    }
}
