<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProgressKursusModel;
use App\Models\DetailProgressModel;
use App\Models\JadwalKelasModel;
use App\Models\PaketModel;

class ProgressKursusController extends BaseController
{
    protected $progressModel;
    protected $detailModel;
    protected $jadwalModel;
    protected $paketModel;

    public function __construct()
    {
        $this->progressModel = new ProgressKursusModel();
        $this->detailModel = new DetailProgressModel();
        $this->jadwalModel = new JadwalKelasModel();
        $this->paketModel = new PaketModel();
    }

    public function index()
    {
        $role = session('role');
        $instrukturId = session('instruktur_id');

        // Instruktur hanya lihat progress kelas yang dia ampu
        if ($role === 'instruktur') {
            $progressList = $this->progressModel->getProgressByInstruktur($instrukturId);
            
            $data = [
                'progress' => $progressList,
                'kelasAvailable' => [],
                'role' => $role,
                'page_title' => 'Progress Kursus Saya',
                'page_subtitle' => 'Daftar kelas yang Anda bimbing'
            ];
        } else {

            // Admin & Operator lihat semua + bisa create baru
            $data = [
                'progress' => $this->progressModel->getProgressLengkap(),
                'kelasAvailable' => $this->progressModel->getKelasWithoutProgress(),
                'role' => $role,
                'page_title' => 'Manajemen Progress Kursus',
                'page_subtitle' => 'Kelola progress pembelajaran kelas'
            ];
        }

        return view('progress_kursus/progress_kursus', $data);
    }

    // Create new progress only for admin and operator
    public function create()
    {
        if (session('role') === 'instruktur') {
            return redirect()->to('/progress-kursus')->with('error', 'Akses ditolak!');
        }

        $rules = [
            'jadwal_kelas_id' => 'required|numeric',
            'total_pertemuan' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        // Validasi cek jadwal sudah ada punya progress atau belum
        $existing = $this->progressModel
            ->where('jadwal_kelas_id', $this->request->getPost('jadwal_kelas_id'))
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Kelas ini sudah memiliki progress!');
        }

        $data = [
            'jadwal_kelas_id' => $this->request->getPost('jadwal_kelas_id'),
            'total_pertemuan' => $this->request->getPost('total_pertemuan'),
            'pertemuan_terlaksana' => 0,
            'status' => 'aktif'
        ];

        if ($this->progressModel->insert($data)) {
            return redirect()->to('/progress-kursus')
                ->with('success', 'Progress kursus berhasil dibuat!');
        }

        return redirect()->back()
            ->with('error', 'Gagal membuat progress.');
    }

    // DETAIL: LIHAT & KELOLA DETAIL PROGRESS
    public function detail($id)
    {
        $role = session('role');
        $instrukturId = session('instruktur_id');

        $progress = $this->progressModel
            ->select('
                progress_kursus.*,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                jadwal_kelas.instruktur_id,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = progress_kursus.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->where('progress_kursus.id', $id)
            ->first();

        if (!$progress) {
            return redirect()->to('/progress-kursus')
                ->with('error', 'Progress tidak ditemukan!');
        }

        // Cek akses instruktur
        if ($role === 'instruktur' && $progress['instruktur_id'] != $instrukturId) {
            return redirect()->to('/progress-kursus')
                ->with('error', 'Anda tidak memiliki akses ke kelas ini!');
        }

        $detailProgress = $this->detailModel->getDetailByProgress($id);

        $data = [
            'progress' => $progress,
            'detailProgress' => $detailProgress,
            'role' => $role,
            'page_title' => 'Detail Progress Kursus',
            'page_subtitle' => $progress['nama_paket'] . ' - ' . $progress['hari']
        ];

        return view('progress_kursus/detail', $data);
    }

    // CREATE DETAIL: INSTRUKTUR ISI PROGRESS DI SETIAP PERTEMUAN
    public function createDetail($progressId)
    {
        $role = session('role');
        $instrukturId = session('instruktur_id');

        if ($role !== 'instruktur') {
            return redirect()->back()
                ->with('error', 'Hanya instruktur yang bisa mengisi progress!');
        }

        // Validasi progress ada & milik instruktur ini
        $progress = $this->progressModel
            ->select('progress_kursus.*, jadwal_kelas.instruktur_id')
            ->join('jadwal_kelas', 'jadwal_kelas.id = progress_kursus.jadwal_kelas_id')
            ->where('progress_kursus.id', $progressId)
            ->first();

        if (!$progress || $progress['instruktur_id'] != $instrukturId) {
            return redirect()->back()
                ->with('error', 'Akses ditolak!');
        }

        $rules = [
            'pertemuan_ke' => 'required|numeric|greater_than[0]',
            'tanggal' => 'required|valid_date',
            'materi' => 'required|min_length[10]',
            'status' => 'required|in_list[scheduled,completed,cancelled]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $pertemuanKe = $this->request->getPost('pertemuan_ke');

        // Cek pertemuan ke-X udah ada atau belum
        $exists = $this->detailModel->cekPertemuanExists($progressId, $pertemuanKe);
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Pertemuan ke-{$pertemuanKe} sudah ada!");
        }

        // Validasi jangan sampai melebihi total pertemuan
        if ($pertemuanKe > $progress['total_pertemuan']) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Pertemuan tidak boleh melebihi total {$progress['total_pertemuan']} pertemuan!");
        }

        $data = [
            'progress_kursus_id' => $progressId,
            'pertemuan_ke' => $pertemuanKe,
            'tanggal' => $this->request->getPost('tanggal'),
            'materi' => $this->request->getPost('materi'),
            'catatan' => $this->request->getPost('catatan'),
            'status' => $this->request->getPost('status'),
            'created_by' => $instrukturId
        ];

        if ($this->detailModel->insert($data)) {
            if ($data['status'] === 'completed') {
                $this->progressModel->update($progressId, [
                    'pertemuan_terlaksana' => $progress['pertemuan_terlaksana'] + 1
                ]);
            }

            return redirect()->to('/progress-kursus/detail/' . $progressId)
                ->with('success', 'Detail pertemuan berhasil ditambahkan!');
        }

        return redirect()->back()
            ->with('error', 'Gagal menambahkan detail pertemuan.');
    }

    // UPDATE DETAIL: EDIT PERTEMUAN
    public function updateDetail($progressId, $detailId)
    {
        $role = session('role');
        $instrukturId = session('instruktur_id');

        if ($role !== 'instruktur') {
            return redirect()->back()
                ->with('error', 'Hanya instruktur yang bisa edit progress!');
        }

        $progress = $this->progressModel
            ->select('progress_kursus.*, jadwal_kelas.instruktur_id')
            ->join('jadwal_kelas', 'jadwal_kelas.id = progress_kursus.jadwal_kelas_id')
            ->where('progress_kursus.id', $progressId)
            ->first();

        $detail = $this->detailModel->find($detailId);

        if (!$progress || !$detail || $progress['instruktur_id'] != $instrukturId) {
            return redirect()->back()
                ->with('error', 'Akses ditolak!');
        }

        $rules = [
            'pertemuan_ke' => 'required|numeric|greater_than[0]',
            'tanggal' => 'required|valid_date',
            'materi' => 'required|min_length[10]',
            'status' => 'required|in_list[scheduled,completed,cancelled]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $pertemuanKe = $this->request->getPost('pertemuan_ke');

        $exists = $this->detailModel->cekPertemuanExists($progressId, $pertemuanKe, $detailId);
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Pertemuan ke-{$pertemuanKe} sudah ada!");
        }

        if ($pertemuanKe > $progress['total_pertemuan']) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Pertemuan tidak boleh melebihi total {$progress['total_pertemuan']} pertemuan!");
        }

        $oldStatus = $detail['status'];
        $newStatus = $this->request->getPost('status');

        $updateData = [
            'pertemuan_ke' => $pertemuanKe,
            'tanggal' => $this->request->getPost('tanggal'),
            'materi' => $this->request->getPost('materi'),
            'catatan' => $this->request->getPost('catatan'),
            'status' => $newStatus
        ];

        if ($this->detailModel->update($detailId, $updateData)) {
            $currentCount = $progress['pertemuan_terlaksana'];
            
            if ($oldStatus !== 'completed' && $newStatus === 'completed') {
                $this->progressModel->update($progressId, [
                    'pertemuan_terlaksana' => $currentCount + 1
                ]);
            } elseif ($oldStatus === 'completed' && $newStatus !== 'completed') {
                $this->progressModel->update($progressId, [
                    'pertemuan_terlaksana' => max(0, $currentCount - 1)
                ]);
            }

            return redirect()->to('/progress-kursus/detail/' . $progressId)
                ->with('success', 'Detail pertemuan berhasil diupdate!');
        }

        return redirect()->back()
            ->with('error', 'Gagal mengupdate detail pertemuan.');
    }
}