<?php

namespace App\Controllers;

use App\Models\PembayaranModel;

class PembayaranController extends BaseController
{
    protected $pembayaran;

    public function __construct()
    {
        $this->pembayaran = new PembayaranModel();
    }

    // LIST PEMBAYARAN
    public function index()
    {
        $data = [
            'pembayaran' => $this->pembayaran
                ->select('
                    pembayaran.*,
                    pendaftaran.nama
                ')
                ->join(
                    'pendaftaran',
                    'pendaftaran.id = pembayaran.pendaftaran_id',
                    'left'
                )
                ->orderBy('pembayaran.created_at', 'DESC')
                ->findAll(),

            'page_title' => 'Verifikasi Pembayaran',
            'page_subtitle' => 'Cek bukti pembayaran calon siswa.'
        ];

        return view('pembayaran/verifikasi', $data);
    }

    // VERIFIKASI PEMBAYARAN
    public function verify($id)
    {
        $p = $this->pembayaran->find($id);
        if (!$p) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($p['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Pembayaran sudah diproses.');
        }

        $this->pembayaran->update($id, [
            'status'     => 'verified',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pembayaran diverifikasi.');
    }

    // TOLAK PEMBAYARAN (WAJIB CATATAN)
    public function reject($id)
    {
        $p = $this->pembayaran->find($id);
        if (!$p) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($p['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Pembayaran sudah diproses.');
        }

        $catatan = $this->request->getPost('catatan');

        if (!$catatan) {
            return redirect()->back()->with(
                'error',
                'Catatan penolakan wajib diisi.'
            );
        }

        $this->pembayaran->update($id, [
            'status'     => 'rejected',
            'catatan'    => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pembayaran ditolak dengan catatan.');
    }

    // RESUBMIT BUKTI PEMBAYARAN (KHUSUS REJECTED)
    public function resubmit($id)
    {
        $p = $this->pembayaran->find($id);
        if (!$p) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($p['status'] !== 'rejected') {
            return redirect()->back()->with('error', 'Resubmit hanya untuk pembayaran ditolak.');
        }

        $bukti = $this->request->getFile('bukti_transaksi');
        if (!$bukti || !$bukti->isValid()) {
            return redirect()->back()->with('error', 'Bukti pembayaran wajib diupload.');
        }

        $namaBukti = $bukti->getRandomName();
        $bukti->move('uploads/bukti_pembayaran', $namaBukti);

        $this->pembayaran->update($id, [
            'bukti_transaksi' => $namaBukti,
            'tanggal_upload'  => date('Y-m-d H:i:s'),
            'status'          => 'pending',
            'updated_at'      => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Bukti dikirim ulang.');
    }
}
