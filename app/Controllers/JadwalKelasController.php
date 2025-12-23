<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalKelasModel;
use App\Models\PaketModel;
use App\Models\RuangKelasModel;
use App\Models\InstrukturModel;
use App\Models\PendaftaranModel;
use App\Models\KelasSiswaModel;

class JadwalKelasController extends BaseController
{
    protected $jadwalModel;
    protected $paketModel;
    protected $ruangModel;
    protected $instrukturModel;
    protected $pendaftaranModel;
    protected $kelasSiswaModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalKelasModel();
        $this->paketModel = new PaketModel();
        $this->ruangModel = new RuangKelasModel();
        $this->instrukturModel = new InstrukturModel();
        $this->pendaftaranModel = new PendaftaranModel();
        $this->kelasSiswaModel = new KelasSiswaModel();
    }

    public function index()
    {
        $role = session('role');
        $instrukturId = session('instruktur_id');

        $sudahDiassign = $this->kelasSiswaModel
            ->select('pendaftaran_id')
            ->where('status', 'aktif')
            ->findAll();
        
        $pendaftaranIdTerpakai = array_column($sudahDiassign, 'pendaftaran_id');

        // Jika instruktur, hanya ambil jadwal yang dia ampu
        if ($role === 'instruktur') {
            $kelas = $this->jadwalModel->getJadwalByInstruktur($instrukturId);
            
            $data = [
                'kelas' => $kelas,
                'role' => $role,
                'paket' => [],
                'ruang' => [],
                'instruktur' => [],
                'pendaftaran' => [],
                'page_title' => 'Jadwal Kelas Saya',
                'page_subtitle' => 'Daftar kelas yang Anda bimbing'
            ];
        } else {
            $data = [
                'kelas' => $this->jadwalModel->getJadwalLengkap(),
                'role' => $role,
                'paket' => $this->paketModel->where('status', 'aktif')->findAll(),
                'ruang' => $this->ruangModel->where('status', 'aktif')->findAll(),
                'instruktur' => $this->instrukturModel->where('status', 'aktif')->findAll(),
                'pendaftaran' => $this->pendaftaranModel
                    ->select('pendaftaran.*, paket_kursus.nama_paket, paket_kursus.id as paket_id')
                    ->join('paket_kursus', 'paket_kursus.id = pendaftaran.paket_id')
                    ->where('pendaftaran.status', 'aktif')
                    ->whereNotIn('pendaftaran.id', !empty($pendaftaranIdTerpakai) ? $pendaftaranIdTerpakai : [0])
                    ->findAll(),
                'page_title' => 'Manajemen Jadwal Kelas',
                'page_subtitle' => 'Kelola jadwal kelas Sonata Violin'
            ];
        }

        return view('jadwal_kelas/jadwal_kelas', $data);
    }

    public function create()
    {
        if (session('role') === 'instruktur') {
            return redirect()->to('/jadwal-kelas')->with('error', 'Akses ditolak!');
        }

        $rules = [
            'paket_id' => 'required|numeric',
            'ruang_kelas_id' => 'required|numeric',
            'instruktur_id' => 'required|numeric',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $hariArray = $this->request->getPost('hari');
        
        if (empty($hariArray) || !is_array($hariArray)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal 1 hari!');
        }

        $hariString = implode(', ', $hariArray);

        $data = [
            'paket_id' => $this->request->getPost('paket_id'),
            'ruang_kelas_id' => $this->request->getPost('ruang_kelas_id'),
            'instruktur_id' => $this->request->getPost('instruktur_id'),
            'hari' => $hariString,
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'status' => 'aktif'
        ];

        $bentrok = $this->jadwalModel->cekBentrok($data);
        if ($bentrok) {
            return redirect()->back()->withInput()->with('error', 'Jadwal bentrok! Instruktur atau ruang sudah terpakai di waktu tersebut.');
        }

        if ($this->jadwalModel->insert($data)) {
            return redirect()->to('/jadwal-kelas')->with('success', 'Jadwal kelas berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal.');
    }

    public function update($id)
    {
        if (session('role') === 'instruktur') {
            return redirect()->to('/jadwal-kelas')->with('error', 'Akses ditolak!');
        }

        $rules = [
            'paket_id' => 'required|numeric',
            'ruang_kelas_id' => 'required|numeric',
            'instruktur_id' => 'required|numeric',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $hariArray = $this->request->getPost('hari');
        
        if (empty($hariArray) || !is_array($hariArray)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal 1 hari!');
        }

        $hariString = implode(', ', $hariArray);

        $data = [
            'paket_id' => $this->request->getPost('paket_id'),
            'ruang_kelas_id' => $this->request->getPost('ruang_kelas_id'),
            'instruktur_id' => $this->request->getPost('instruktur_id'),
            'hari' => $hariString,
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai')
        ];

        $bentrok = $this->jadwalModel->cekBentrok($data, $id);
        if ($bentrok) {
            return redirect()->back()->withInput()->with('error', 'Jadwal bentrok! Instruktur atau ruang sudah terpakai di waktu tersebut.');
        }

        if ($this->jadwalModel->update($id, $data)) {
            return redirect()->to('/jadwal-kelas')->with('success', 'Jadwal kelas berhasil diupdate!');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate jadwal.');
    }

    public function assignSiswa()
    {
        if (session('role') === 'instruktur') {
            return redirect()->to('/jadwal-kelas')->with('error', 'Akses ditolak!');
        }

        $jadwalId = $this->request->getPost('jadwal_kelas_id');
        $pendaftaranId = $this->request->getPost('pendaftaran_id');

        if (!$jadwalId || !$pendaftaranId) {
            return redirect()->back()->with('error', 'Data tidak lengkap!');
        }

        $sudahAda = $this->kelasSiswaModel
            ->where('jadwal_kelas_id', $jadwalId)
            ->where('pendaftaran_id', $pendaftaranId)
            ->first();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Siswa sudah terdaftar di kelas ini!');
        }

        $jadwal = $this->jadwalModel->getJadwalById($jadwalId);
        $jumlahSiswa = $this->kelasSiswaModel
            ->where('jadwal_kelas_id', $jadwalId)
            ->where('status', 'aktif')
            ->countAllResults();

        if ($jumlahSiswa >= $jadwal['kapasitas_ruang']) {
            return redirect()->back()->with('error', 'Kelas sudah penuh! Kapasitas maksimal: ' . $jadwal['kapasitas_ruang']);
        }

        $data = [
            'jadwal_kelas_id' => $jadwalId,
            'pendaftaran_id' => $pendaftaranId,
            'status' => 'aktif'
        ];

        if ($this->kelasSiswaModel->insert($data)) {
            return redirect()->to('/jadwal-kelas')->with('success', 'Siswa berhasil di-assign ke kelas!');
        }

        return redirect()->back()->with('error', 'Gagal assign siswa.');
    }

    public function detail($id)
    {
        $role = session('role');
        $instrukturId = session('instruktur_id');

        $jadwal = $this->jadwalModel->getJadwalById($id);
        
        if (!$jadwal) {
            return redirect()->to('/jadwal-kelas')->with('error', 'Jadwal tidak ditemukan!');
        }

        if ($role === 'instruktur' && $jadwal['instruktur_id'] != $instrukturId) {
            return redirect()->to('/jadwal-kelas')->with('error', 'Anda tidak memiliki akses ke kelas ini!');
        }

        $siswa = $this->kelasSiswaModel->getSiswaByKelas($id);

        $siswaAvailable = [];
        if (in_array($role, ['admin', 'operator'])) {
            $sudahDiassign = $this->kelasSiswaModel
                ->select('pendaftaran_id')
                ->where('status', 'aktif')
                ->findAll();
            
            $pendaftaranIdTerpakai = array_column($sudahDiassign, 'pendaftaran_id');

            $siswaAvailable = $this->pendaftaranModel
                ->select('pendaftaran.*, paket_kursus.nama_paket')
                ->join('paket_kursus', 'paket_kursus.id = pendaftaran.paket_id')
                ->where('pendaftaran.status', 'aktif')
                ->where('pendaftaran.paket_id', $jadwal['paket_id'])
                ->whereNotIn('pendaftaran.id', !empty($pendaftaranIdTerpakai) ? $pendaftaranIdTerpakai : [0])
                ->findAll();
        }

        $data = [
            'jadwal' => $jadwal,
            'siswa' => $siswa,
            'siswaAvailable' => $siswaAvailable,
            'role' => $role,
            'page_title' => 'Jadwal Kelas',
            'page_subtitle' => 'Informasi kelas ' . $jadwal['nama_paket'] . ' - ' . $jadwal['hari']
        ];

        return view('jadwal_kelas/detail', $data);
    }

    public function removeSiswa($jadwalId, $siswaId)
    {
        if (session('role') === 'instruktur') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak!'
            ]);
        }

        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Invalid request');
        }

        $deleted = $this->kelasSiswaModel
            ->where('jadwal_kelas_id', $jadwalId)
            ->where('id', $siswaId)
            ->delete();

        if ($deleted) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Siswa berhasil dihapus dari kelas'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus siswa'
        ]);
    }
}