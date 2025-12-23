<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalKelasModel;
use App\Models\KelasSiswaModel;
use App\Models\AbsensiKelasModel;
use App\Models\AbsensiSiswaModel;

class AbsensiController extends BaseController
{
    protected $jadwalModel;
    protected $kelasSiswaModel;
    protected $absensiKelasModel;
    protected $absensiSiswaModel;

    public function __construct()
    {
        $this->jadwalModel        = new JadwalKelasModel();
        $this->kelasSiswaModel   = new KelasSiswaModel();
        $this->absensiKelasModel = new AbsensiKelasModel();
        $this->absensiSiswaModel = new AbsensiSiswaModel();
    }

    public function index()
    {
        // Validasi role: admin, operator, instruktur
        if (!in_array(session('role'), ['admin', 'operator', 'instruktur'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $role         = session('role');
        $instrukturId = session('instruktur_id');

        if (in_array($role, ['admin', 'operator'])) {
            $absensi = $this->absensiKelasModel->getAllAbsensi();
            
            $jadwalTersedia = $this->absensiKelasModel->getJadwalBelumDibuka();
        } 
        else {
            $absensi = $this->absensiKelasModel->getOpenByInstruktur($instrukturId);
            $jadwalTersedia = [];
        }

        foreach ($absensi as &$abs) {
            $abs['hari_absensi'] = $this->getHariIndonesia(date('N', strtotime($abs['tanggal'])));
        }

        return view('absensi/absensi', [
            'absensi' => $absensi,
            'role'    => $role,
            'jadwal'  => $jadwalTersedia ?? [],
            'hariIni' => $this->getHariIndonesia(date('N'))
        ]);
    }

    public function open($jadwalId)
    {
        // Validasi role: hanya admin & operator
        if (!in_array(session('role'), ['admin', 'operator'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $jadwal = $this->jadwalModel->find($jadwalId);
        if (!$jadwal || $jadwal['status'] !== 'aktif') {
            return redirect()->back()->with('error', 'Jadwal kelas tidak valid');
        }

        $hariIni = $this->getHariIndonesia(date('N'));
        $hariJadwal = array_map('trim', explode(',', $jadwal['hari']));

        if (!in_array($hariIni, $hariJadwal)) {
            return redirect()->back()->with(
                'error',
                "Hari ini {$hariIni}, jadwal hanya untuk: {$jadwal['hari']}"
            );
        }

        $jamSekarang = date('H:i:s');

        if ($jamSekarang > $jadwal['jam_selesai']) {
            return redirect()->back()->with(
                'error',
                'Absensi tidak bisa dibuka, jam belajar sudah selesai pukul ' . 
                date('H:i', strtotime($jadwal['jam_selesai']))
            );
        }

        $existing = $this->absensiKelasModel
            ->where('jadwal_kelas_id', $jadwalId)
            ->where('tanggal', date('Y-m-d'))
            ->first();

        if ($existing) {
            return redirect()->back()->with(
                'error',
                'Absensi untuk jadwal ini hari ini sudah dibuat'
            );
        }

        $this->absensiKelasModel->insert([
            'jadwal_kelas_id' => $jadwalId,
            'tanggal'         => date('Y-m-d'),
            'status'          => 'open',
            'open_by'         => session('user_id'),
            'open_when'       => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil dibuka');
    }

    public function close($absensiKelasId)
    {
        // Validasi role: hanya admin & operator
        if (!in_array(session('role'), ['admin', 'operator'])) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        $absensi = $this->absensiKelasModel->find($absensiKelasId);
        if (!$absensi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($absensi['status'] === 'close') {
            return redirect()->back()->with('error', 'Absensi sudah ditutup');
        }

        $this->absensiKelasModel->update($absensiKelasId, [
            'status'      => 'close',
            'close_when'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil ditutup');
    }

    public function detail($absensiKelasId)
    {
        // Validasi role: admin, operator, instruktur
        if (!in_array(session('role'), ['admin', 'operator', 'instruktur'])) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }
        
        $role         = session('role');
        $instrukturId = session('instruktur_id');

        $absensi = $this->absensiKelasModel->getDetailAbsensi($absensiKelasId);

        if (!$absensi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Proteksi instruktur: hanya bisa lihat kelas sendiri
        if ($role === 'instruktur' && $absensi['instruktur_id'] != $instrukturId) {
            return redirect()->to('/absensi')->with('error', 'Anda tidak memiliki akses ke absensi ini');
        }

        $absensi['hari_absensi'] = $this->getHariIndonesia(date('N', strtotime($absensi['tanggal'])));
        $siswaAbsen = $this->absensiSiswaModel->getSiswaWithAbsensi($absensiKelasId);

        return view('absensi/detail', [
            'absensi'     => $absensi,
            'siswaAbsen'  => $siswaAbsen,
            'role'        => $role
        ]);
    }

    public function submit()
    {        
        // Validasi role: hanya instruktur
        if (session('role') !== 'instruktur') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak'
            ]);
        }

        $absensiKelasId = $this->request->getPost('absensi_kelas_id');
        $dataAbsen      = $this->request->getPost('absen');
            
        $absensi = $this->absensiKelasModel->find($absensiKelasId);
                
        if (!$absensi || $absensi['status'] !== 'open') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Absensi sudah ditutup'
            ]);
        }

        $jadwal = $this->jadwalModel->find($absensi['jadwal_kelas_id']); 
        
        if ($jadwal['instruktur_id'] != session('instruktur_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak'
            ]);
        }

        if (empty($dataAbsen)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada data absensi'
            ]);
        }

        try {            
            
            foreach ($dataAbsen as $kelasSiswaId => $status) {
                
                $existing = $this->absensiSiswaModel
                    ->where('absensi_kelas_id', $absensiKelasId)
                    ->where('kelas_siswa_id', $kelasSiswaId)
                    ->first();

                if ($existing) {
                    $result = $this->absensiSiswaModel->update($existing['id'], [
                        'status' => $status
                    ]);
                } else {
                    $result = $this->absensiSiswaModel->insert([
                        'absensi_kelas_id' => $absensiKelasId,
                        'kelas_siswa_id'   => $kelasSiswaId,
                        'status'           => $status
                    ]);
                }
            }
           
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Absensi berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    private function getHariIndonesia($dayNumber)
    {
        $hari = [
            1 => 'Senin',
            2 => 'Selasa', 
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        return $hari[$dayNumber] ?? 'Senin';
    }
}