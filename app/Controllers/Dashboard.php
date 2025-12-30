<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\InstrukturModel;
use App\Models\JadwalKelasModel;
use App\Models\PembayaranModel;
use App\Models\RuangKelasModel;
use App\Models\PendaftaranModel;
use App\Models\PaketModel;

class Dashboard extends BaseController
{
    protected $siswa;
    protected $instruktur;
    protected $jadwal;
    protected $pembayaran;
    protected $ruang;
    protected $pendaftaran;
    protected $paket;
    protected $db;

    public function __construct()
    {
        $this->siswa = new SiswaModel();
        $this->instruktur = new InstrukturModel();
        $this->jadwal = new JadwalKelasModel();
        $this->pembayaran = new PembayaranModel();
        $this->ruang = new RuangKelasModel();
        $this->pendaftaran = new PendaftaranModel();
        $this->paket = new PaketModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // ========== STAT CARDS ==========
        
        // Total Siswa Aktif
        $totalSiswaAktif = $this->siswa
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->countAllResults();

        // Siswa Baru Bulan Ini
        $siswaBulanIni = $this->siswa
            ->where('status', 'aktif')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->countAllResults();

        // Total Instruktur & Yang Aktif Mengajar
        $totalInstruktur = $this->instruktur
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->countAllResults();

        $instrukturMengajar = $this->db->query("
            SELECT COUNT(DISTINCT instruktur_id) as total
            FROM jadwal_kelas
            WHERE status = 'aktif'
        ")->getRow()->total;

        // Kelas Hari Ini
        $hariIni = $this->getHariIndonesia(date('l'));
        $kelasHariIni = $this->db->query("
            SELECT COUNT(*) as total
            FROM jadwal_kelas
            WHERE status = 'aktif'
            AND FIND_IN_SET(?, hari) > 0
        ", [$hariIni])->getRow()->total;

        // Kelas Sedang Berlangsung (jam sekarang)
        $jamSekarang = date('H:i:s');
        $kelasBerlangsung = $this->db->query("
            SELECT COUNT(*) as total
            FROM jadwal_kelas
            WHERE status = 'aktif'
            AND FIND_IN_SET(?, hari) > 0
            AND jam_mulai <= ?
            AND jam_selesai >= ?
        ", [$hariIni, $jamSekarang, $jamSekarang])->getRow()->total;

        // Pembayaran Pending
        $pembayaranPending = $this->pembayaran
            ->where('status', 'pending')
            ->countAllResults();

        // Total Nominal Pending
        $nominalPending = $this->pembayaran
            ->selectSum('nominal')
            ->where('status', 'pending')
            ->get()
            ->getRow()
            ->nominal ?? 0;

        // ========== SECOND ROW STATS ==========

        // Pendaftaran Baru Bulan Ini
        $pendaftaranBulanIni = $this->pendaftaran
            ->where('MONTH(tanggal_daftar)', date('m'))
            ->where('YEAR(tanggal_daftar)', date('Y'))
            ->countAllResults();

        // Pendaftaran Bulan Lalu
        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunLalu = date('Y', strtotime('-1 month'));
        $pendaftaranBulanLalu = $this->pendaftaran
            ->where('MONTH(tanggal_daftar)', $bulanLalu)
            ->where('YEAR(tanggal_daftar)', $tahunLalu)
            ->countAllResults();

        $selisihPendaftaran = $pendaftaranBulanIni - $pendaftaranBulanLalu;

        // Income Bulan Ini (dari pembayaran verified)
        $incomeBulanIni = $this->pembayaran
            ->selectSum('nominal')
            ->where('status', 'verified')
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->get()
            ->getRow()
            ->nominal ?? 0;

        // Target Income (misalnya Rp 50jt)
        $targetIncome = 50000000;
        $persenIncome = $targetIncome > 0 ? ($incomeBulanIni / $targetIncome) * 100 : 0;

        // Ruangan Tersedia
        $totalRuang = $this->ruang
            ->where('deleted_at', null)
            ->countAllResults();

        $ruangAktif = $this->ruang
            ->where('status', 'aktif')
            ->where('deleted_at', null)
            ->countAllResults();

        // ========== JADWAL KELAS TERDEKAT ==========
        $jadwalTerdekat = $this->getJadwalTerdekat();

        // ========== SISWA PAKET HAMPIR HABIS ==========
        $paketHampirHabis = $this->getPaketHampirHabis();

        // ========== DATA KE VIEW ==========
        $data = [
            // Stats Cards
            'totalSiswaAktif' => $totalSiswaAktif,
            'siswaBulanIni' => $siswaBulanIni,
            'totalInstruktur' => $totalInstruktur,
            'instrukturMengajar' => $instrukturMengajar,
            'kelasHariIni' => $kelasHariIni,
            'kelasBerlangsung' => $kelasBerlangsung,
            'pembayaranPending' => $pembayaranPending,
            'nominalPending' => $nominalPending,
            
            // Second Row
            'pendaftaranBulanIni' => $pendaftaranBulanIni,
            'selisihPendaftaran' => $selisihPendaftaran,
            'incomeBulanIni' => $incomeBulanIni,
            'targetIncome' => $targetIncome,
            'persenIncome' => round($persenIncome, 1),
            'totalRuang' => $totalRuang,
            'ruangAktif' => $ruangAktif,
            
            // Jadwal & Distribusi
            'jadwalTerdekat' => $jadwalTerdekat,
            'paketHampirHabis' => $paketHampirHabis,
            
            'page_title' => 'Dashboard',
            'page_subtitle' => 'Overview Sonata Violin'
        ];

        return view('dashboard', $data);
    }

    // Helper: Jadwal Terdekat (3 kelas)
    private function getJadwalTerdekat()
    {
        $hariIni = $this->getHariIndonesia(date('l'));
        $jamSekarang = date('H:i:s');

        // Kelas hari ini yang belum selesai
        $kelasHariIni = $this->db->query("
            SELECT 
                jk.*,
                i.nama as nama_instruktur,
                pk.nama_paket,
                rk.nama_ruang,
                COUNT(ks.id) as jumlah_siswa,
                'hari_ini' as kategori
            FROM jadwal_kelas jk
            LEFT JOIN instruktur i ON i.id = jk.instruktur_id
            LEFT JOIN paket_kursus pk ON pk.id = jk.paket_id
            LEFT JOIN ruang_kelas rk ON rk.id = jk.ruang_kelas_id
            LEFT JOIN kelas_siswa ks ON ks.jadwal_kelas_id = jk.id AND ks.status = 'aktif'
            WHERE jk.status = 'aktif'
            AND FIND_IN_SET(?, jk.hari) > 0
            AND jk.jam_selesai > ?
            GROUP BY jk.id
            ORDER BY jk.jam_mulai ASC
            LIMIT 2
        ", [$hariIni, $jamSekarang])->getResultArray();

        // Kelas besok
        $besok = $this->getHariIndonesia(date('l', strtotime('+1 day')));
        $kelasBesok = $this->db->query("
            SELECT 
                jk.*,
                i.nama as nama_instruktur,
                pk.nama_paket,
                rk.nama_ruang,
                COUNT(ks.id) as jumlah_siswa,
                'besok' as kategori
            FROM jadwal_kelas jk
            LEFT JOIN instruktur i ON i.id = jk.instruktur_id
            LEFT JOIN paket_kursus pk ON pk.id = jk.paket_id
            LEFT JOIN ruang_kelas rk ON rk.id = jk.ruang_kelas_id
            LEFT JOIN kelas_siswa ks ON ks.jadwal_kelas_id = jk.id AND ks.status = 'aktif'
            WHERE jk.status = 'aktif'
            AND FIND_IN_SET(?, jk.hari) > 0
            GROUP BY jk.id
            ORDER BY jk.jam_mulai ASC
            LIMIT 1
        ", [$besok])->getResultArray();

        return array_merge($kelasHariIni, $kelasBesok);
    }

    // Helper: Paket Hampir Habis (seminggu lagi)
    private function getPaketHampirHabis()
    {
        $semingguLagi = date('Y-m-d', strtotime('+7 days'));

        return $this->pendaftaran
            ->select('pendaftaran.*, siswa.nama')
            ->join('siswa', 'siswa.id = pendaftaran.siswa_id')
            ->where('pendaftaran.status', 'aktif')
            ->where('pendaftaran.tanggal_selesai <=', $semingguLagi)
            ->where('pendaftaran.tanggal_selesai >=', date('Y-m-d'))
            ->countAllResults();
    }

    // Helper: Konversi Hari ke Indonesia
    private function getHariIndonesia($day)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        return $days[$day] ?? $day;
    }
}