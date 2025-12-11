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
                ->select('pendaftaran.*, paket_kursus.nama_paket')
                ->join('paket_kursus', 'paket_kursus.id = pendaftaran.paket_id', 'left')
                ->where('pendaftaran.deleted_at', null)
                ->orderBy('pendaftaran.tanggal_daftar', 'DESC')
                ->findAll(),

            'paket' => $this->paket
                ->where('status', 'aktif')
                ->findAll(),

            'page_title'    => 'Data Pendaftaran',
            'page_subtitle' => 'Manajemen Pendaftaran Siswa'
        ];

        return view('pendaftaran/pendaftaran', $data);
    }

    //CREATE
    public function create()
    {
        $paketId = $this->request->getPost('paket_id');
        $paket   = $this->paket->find($paketId);

        if (!$paket) return redirect()->back()->with('error', 'Paket tidak ditemukan.');

        // Foto profil
        $foto = $this->request->getFile('foto_profil');
        $namaFoto = null;
        if ($foto && $foto->isValid()) {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/pendaftaran', $namaFoto);
        }

        $this->pendaftaran->insert([
            'paket_id'        => $paketId,
            'tanggal_daftar'  => date('Y-m-d H:i:s'),
            'tanggal_mulai'   => $paket['tanggal_mulai'],
            'tanggal_selesai' => $paket['tanggal_selesai'],
            'status'          => 'pending',

            'nama'        => $this->request->getPost('nama'),
            'alamat'      => $this->request->getPost('alamat'),
            'no_hp'       => $this->request->getPost('no_hp'),
            'email'       => $this->request->getPost('email'),
            'tgl_lahir'   => $this->request->getPost('tgl_lahir'),
            'foto_profil' => $namaFoto,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil ditambahkan.');
    }

    //UPDATE
    public function update($id)
    {
        $p = $this->pendaftaran->find($id);
        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        if ($p['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pendaftaran pending yang boleh diedit.');
        }

        $paketId = $this->request->getPost('paket_id');
        $paket = $this->paket->find($paketId);

        if (!$paket) return redirect()->back()->with('error', 'Paket tidak valid.');

        $this->pendaftaran->update($id, [
            'paket_id'        => $paketId,
            'tanggal_mulai'   => $paket['tanggal_mulai'],
            'tanggal_selesai' => $paket['tanggal_selesai'],
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    //VERIFIKASI → JADI SISWA AKTIF
    public function verifikasi($id)
    {
        $p = $this->pendaftaran->find($id);
        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        if ($p['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Pendaftaran sudah diproses.');
        }

        $this->db->transBegin();

        // Transfer foto
        $fotoBaru = null;
        if ($p['foto_profil']) {
            $src = FCPATH . 'uploads/pendaftaran/' . $p['foto_profil'];
            $dst = FCPATH . 'uploads/siswa/' . $p['foto_profil'];

            if (file_exists($src)) {
                copy($src, $dst);
                $fotoBaru = $p['foto_profil'];
            }
        }

        // Insert siswa baru
        $this->siswa->insert([
            'nama'        => $p['nama'],
            'alamat'      => $p['alamat'],
            'no_hp'       => $p['no_hp'],
            'email'       => $p['email'],
            'tgl_lahir'   => $p['tgl_lahir'],
            'foto_profil' => $fotoBaru,
            'status'      => 'aktif',
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        $idSiswa = $this->siswa->getInsertID();

        $this->pendaftaran->update($id, [
            'status'   => 'aktif',
            'siswa_id' => $idSiswa,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Verifikasi gagal.');
        }

        $this->db->transCommit();

        return redirect()->back()->with('success', 'Pendaftaran diverifikasi & siswa aktif.');
    }

    //BATALKAN
    public function batalkan($id)
    {
        $p = $this->pendaftaran->find($id);

        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');
        if ($p['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pending yang bisa dibatalkan.');
        }

        $this->pendaftaran->update($id, [
            'status' => 'batal',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pendaftaran dibatalkan.');
    }

    //SELESAI / LULUS
    public function selesai($id)
    {
        $p = $this->pendaftaran->find($id);
        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        if ($p['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Hanya siswa aktif yang bisa diselesaikan.');
        }

        $this->pendaftaran->update($id, [
            'status' => 'selesai',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($p['siswa_id']) {
            $this->siswa->update($p['siswa_id'], [
                'status' => 'lulus',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('success', 'Belajar selesai & status siswa = Lulus.');
    }

    //MENGUNDURKAN DIRI
    public function mengundurkanDiri($id)
    {
        $p = $this->pendaftaran->find($id);
        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        if (!in_array($p['status'], ['aktif'])) {
            return redirect()->back()->with('error', 'Hanya siswa aktif yang boleh mengundurkan diri.');
        }

        $this->pendaftaran->update($id, [
            'status' => 'mundur',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($p['siswa_id']) {
            $this->siswa->update($p['siswa_id'], [
                'status' => 'nonaktif',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('success', 'Siswa resmi mengundurkan diri.');
    }

    //DELETE (ADMIN ONLY)
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $p = $this->pendaftaran->find($id);

        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        // Hanya boleh hapus PENDING atau BATAL
        if (!in_array($p['status'], ['pending', 'batal'])) {
            return redirect()->back()->with('error', 'Pendaftaran aktif / selesai / mundur tidak boleh dihapus.');
        }

        $this->pendaftaran->update($id, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pendaftaran diarsipkan.');
    }
}
