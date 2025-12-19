<?php

namespace App\Controllers;

use App\Models\PendaftaranModel;
use App\Models\PembayaranModel;
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
        $this->pembayaran  = new PembayaranModel();
        $this->siswa       = new SiswaModel();
        $this->paket       = new PaketModel();
        $this->db          = \Config\Database::connect();
    }

    private function generateNomorPendaftaran()
    {
        $tahun = date("Y");

        $query = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM pendaftaran 
            WHERE YEAR(tanggal_daftar) = '$tahun'
        ")->getRow();

        $urutan = str_pad($query->total + 1, 4, "0", STR_PAD_LEFT);

        return "SV-REG-$tahun-$urutan";
    }

    public function index()
    {
        $data = [
            'pendaftaran' => $this->pendaftaran
                ->select('pendaftaran.*, 
                        paket_kursus.nama_paket,
                        pembayaran.nominal,
                        pembayaran.bukti_transaksi')
                ->join('paket_kursus', 'paket_kursus.id = pendaftaran.paket_id', 'left')
                ->join('pembayaran', 'pembayaran.pendaftaran_id = pendaftaran.id', 'left')
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

    // CREATE
    public function create()
    {
        $paketId = $this->request->getPost('paket_id');
        $paket   = $this->paket->find($paketId);
        if (!$paket) return redirect()->back()->with('error', 'Paket tidak ditemukan.');

        // Generate nomor pendaftaran
        $noDaftar = $this->generateNomorPendaftaran();

        // Foto profil siswa (holding)
        $foto = $this->request->getFile('foto_profil');
        $namaFoto = null;

        if ($foto && $foto->isValid()) {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/pendaftaran', $namaFoto);
        }

        // Insert ke pendaftaran
        $this->pendaftaran->insert([
            'no_pendaftaran' => $noDaftar,
            'paket_id'        => $paketId,
            'tanggal_mulai'   => $paket['tanggal_mulai'],
            'tanggal_selesai' => $paket['tanggal_selesai'],
            'status'          => 'pending',

            // data siswa holding
            'nama'        => $this->request->getPost('nama'),
            'alamat'      => $this->request->getPost('alamat'),
            'no_hp'       => $this->request->getPost('no_hp'),
            'email'       => $this->request->getPost('email'),
            'tgl_lahir'   => $this->request->getPost('tgl_lahir'),
            'foto_profil' => $namaFoto,
        ]);

        // Ambil ID pendaftaran baru
        $idPendaftaran = $this->pendaftaran->getInsertID();

        // Ambil nominal & bukti transaksi
        $nominal = $this->request->getPost('nominal');
        $bukti = $this->request->getFile('bukti_transaksi');

        $namaBukti = null;

        if ($bukti && $bukti->isValid()) {
            $namaBukti = $bukti->getRandomName();
            $bukti->move('uploads/bukti_pembayaran', $namaBukti);
        }

        // Insert ke tabel pembayaran
        $this->pembayaran->insert([
            'no_pendaftaran'  => $noDaftar,
            'pendaftaran_id'  => $idPendaftaran,
            'nominal'         => $nominal,
            'bukti_transaksi' => $namaBukti,
            'tanggal_upload'  => date('Y-m-d H:i:s'),
            'status'          => 'pending',
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Pendaftaran & pembayaran berhasil ditambahkan.');
    }

    // UPDATE
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

        $fotoBaru = $this->request->getFile('foto_profil');
        $namaFoto = $p['foto_profil']; // default pakai yang lama

        if ($fotoBaru && $fotoBaru->isValid()) {
            $namaFoto = $fotoBaru->getRandomName();
            $fotoBaru->move('uploads/pendaftaran', $namaFoto);
        }

        $this->pendaftaran->update($id, [
            'paket_id'        => $paketId,
            'tanggal_mulai'   => $paket['tanggal_mulai'],
            'tanggal_selesai' => $paket['tanggal_selesai'],
            'nama'            => $this->request->getPost('nama'),
            'alamat'          => $this->request->getPost('alamat'),
            'no_hp'           => $this->request->getPost('no_hp'),
            'email'           => $this->request->getPost('email'),
            'tgl_lahir'       => $this->request->getPost('tgl_lahir'),
            'foto_profil'     => $namaFoto, // ← FIX PENTING
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

        $nominal = $this->request->getPost('nominal');
        $buktiBaru = $this->request->getFile('bukti_transaksi');

        $existingPayment = $this->pembayaran
            ->where('pendaftaran_id', $id)
            ->first();

        if ($existingPayment) {

            $dataUpdate = [
                'nominal' => $nominal,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Jika upload bukti baru
            if ($buktiBaru && $buktiBaru->isValid()) {
                $namaBuktiBaru = $buktiBaru->getRandomName();
                $buktiBaru->move('uploads/bukti_pembayaran', $namaBuktiBaru);

                $dataUpdate['bukti_transaksi'] = $namaBuktiBaru;
                $dataUpdate['tanggal_upload'] = date('Y-m-d H:i:s');
            }

            $this->pembayaran->update($existingPayment['id'], $dataUpdate);
        }

        return redirect()->back()->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    // VERIFIKASI
    public function verifikasi($id)
    {
        $p = $this->pendaftaran->find($id);
        if (!$p) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if ($p['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Pendaftaran sudah diproses.');
        }

        // CEK STATUS PEMBAYARAN
        $pembayaran = $this->pembayaran
            ->where('pendaftaran_id', $id)
            ->first();

        if (!$pembayaran) {
            return redirect()->back()->with(
                'error',
                'Data pembayaran belum tersedia.'
            );
        }

        if ($pembayaran['status'] !== 'verified') {
            return redirect()->back()->with(
                'error',
                'Pendaftaran tidak bisa diverifikasi sebelum pembayaran diverifikasi.'
            );
        }

        $this->db->transBegin();

        // Copy foto
        $fotoBaru = null;
        if ($p['foto_profil']) {
            $src = FCPATH . 'uploads/pendaftaran/' . $p['foto_profil'];
            $dst = FCPATH . 'uploads/siswa/' . $p['foto_profil'];
            if (file_exists($src)) copy($src, $dst);
            $fotoBaru = $p['foto_profil'];
        }

        // Insert siswa
        $this->siswa->insert([
            'no_pendaftaran' => $p['no_pendaftaran'],
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

        // Update pendaftaran
        $this->pendaftaran->update($id, [
            'status'   => 'aktif',
            'siswa_id' => $idSiswa,
        ]);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Verifikasi gagal.');
        }

        $this->db->transCommit();
        return redirect()->back()->with('success', 'Pendaftaran diverifikasi & siswa aktif.');
    }

    // MUNDUR
    public function mengundurkanDiri($id)
    {
        $p = $this->pendaftaran->find($id);
        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        if ($p['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Hanya siswa aktif yang boleh mengundurkan diri.');
        }

        $this->pendaftaran->update($id, [
            'status' => 'mundur'
        ]);

        if ($p['siswa_id']) {
            $this->siswa->update($p['siswa_id'], [
                'status' => 'nonaktif'
            ]);
        }

        return redirect()->back()->with('success', 'Siswa mengundurkan diri.');
    }

    // SELESAI
    public function selesai($id)
    {
        $p = $this->pendaftaran->find($id);
        if (!$p) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        if ($p['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Hanya siswa aktif yang bisa diselesaikan.');
        }

        $this->pendaftaran->update($id, [
            'status' => 'selesai'
        ]);

        if ($p['siswa_id']) {
            $this->siswa->update($p['siswa_id'], [
                'status' => 'lulus'
            ]);
        }

        return redirect()->back()->with('success', 'Siswa dinyatakan lulus.');
    }
}
