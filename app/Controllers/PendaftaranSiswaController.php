<?php

namespace App\Controllers;

use App\Models\PendaftaranModel;
use App\Models\PembayaranModel;
use App\Models\PaketModel;
use App\Services\EmailService;

class PendaftaranSiswaController extends BaseController
{
    protected $pendaftaran;
    protected $pembayaran;
    protected $paket;
    protected $db;
    protected $emailService;

    public function __construct()
    {
        $this->pendaftaran = new PendaftaranModel();
        $this->pembayaran  = new PembayaranModel();
        $this->paket       = new PaketModel();
        $this->db          = \Config\Database::connect();
        $this->emailService = new EmailService();
    }

    private function generateNomorPendaftaran()
    {
        $tahun = date('Y');

        $row = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM pendaftaran 
            WHERE YEAR(tanggal_daftar) = ?
        ", [$tahun])->getRow();

        $urutan = str_pad(($row->total ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        return "SV-REG-$tahun-$urutan";
    }

    public function create()
    {
        $paketId = $this->request->getPost('paket_id');
        $paket   = $this->paket->find($paketId);

        if (!$paket || $paket['status'] !== 'aktif') {
            session()->setFlashdata('error', 'Paket tidak valid.');
            return redirect()->to('/');
        }

        $noPendaftaran = $this->generateNomorPendaftaran();

        $this->db->transBegin();

        $foto = $this->request->getFile('foto_profil');
        $namaFoto = null;

        if ($foto && $foto->isValid()) {
            $namaFoto = $foto->getRandomName();
            $foto->move('uploads/pendaftaran', $namaFoto);
        }

        // INSERT PENDAFTARAN
        $this->pendaftaran->insert([
            'no_pendaftaran'  => $noPendaftaran,
            'paket_id'        => $paketId,
            'tanggal_mulai'   => $paket['tanggal_mulai'],
            'tanggal_selesai' => $paket['tanggal_selesai'],
            'status'          => 'pending',

            // holding data siswa
            'nama'        => $this->request->getPost('nama'),
            'alamat'      => $this->request->getPost('alamat'),
            'no_hp'       => $this->request->getPost('no_hp'),
            'email'       => $this->request->getPost('email'),
            'tgl_lahir'   => $this->request->getPost('tgl_lahir'),
            'foto_profil' => $namaFoto,
        ]);

        $idPendaftaran = $this->pendaftaran->getInsertID();

        // UPLOAD BUKTI PEMBAYARAN
        $bukti = $this->request->getFile('bukti_transaksi');
        if (!$bukti || !$bukti->isValid()) {
            $this->db->transRollback();
            session()->setFlashdata('error', 'Bukti pembayaran wajib diupload.');
            return redirect()->to('/');
        }

        $namaBukti = $bukti->getRandomName();
        $bukti->move('uploads/bukti_pembayaran', $namaBukti);

        // INSERT PEMBAYARAN
        $this->pembayaran->insert([
            'no_pendaftaran'  => $noPendaftaran,
            'pendaftaran_id'  => $idPendaftaran,
            'nominal'         => $this->request->getPost('nominal'),
            'bukti_transaksi' => $namaBukti,
            'tanggal_upload'  => date('Y-m-d H:i:s'),
            'status'          => 'pending',
        ]);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            session()->setFlashdata('error', 'Pendaftaran gagal diproses.');
            return redirect()->to('/');
        }

        $this->db->transCommit();
 
        // KIRIM NOTIFIKASI EMAIL KETIKA PENDAFTARAN BERHASIL
        try {
            $this->emailService->pendaftaranBerhasil([
                'email'          => $this->request->getPost('email'),
                'nama'           => $this->request->getPost('nama'),
                'no_pendaftaran' => $noPendaftaran,
                'nama_paket'     => $paket['nama_paket'],
                'batch'          => $paket['batch'],
                'harga'          => $paket['harga'],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal kirim email pendaftaran: ' . $e->getMessage());
        }

        session()->setFlashdata('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $noPendaftaran . '. Data akan diverifikasi oleh admin.');
        return redirect()->to('/');
    }
}