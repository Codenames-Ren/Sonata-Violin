<?php
namespace App\Models;
use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // ================== LAPORAN PROFIT ==================
    
    /**
     * @param array $filters ['tanggal_start', 'tanggal_end', 'paket_id', 'status']
     */
    public function getLaporanProfit($filters = [])
    {
        $builder = $this->db->table('pembayaran')
            ->select('
                pembayaran.id,
                pembayaran.no_pendaftaran,
                pembayaran.nominal,
                pembayaran.status,
                pembayaran.tanggal_upload,
                pembayaran.created_at,
                pendaftaran.nama AS nama_siswa,
                pendaftaran.email,
                pendaftaran.no_hp,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.harga AS harga_paket
            ')
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id')
            ->join('paket_kursus', 'paket_kursus.id = pendaftaran.paket_id', 'left')
            ->where('pembayaran.status', 'verified');
        
        // Filter tanggal
        if (!empty($filters['tanggal_start'])) {
            $builder->where('DATE(pembayaran.created_at) >=', $filters['tanggal_start']);
        }
        if (!empty($filters['tanggal_end'])) {
            $builder->where('DATE(pembayaran.created_at) <=', $filters['tanggal_end']);
        }
        
        // Filter paket
        if (!empty($filters['paket_id'])) {
            $builder->where('pendaftaran.paket_id', $filters['paket_id']);
        }
        
        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('pendaftaran.nama', $filters['search'])
                ->orLike('pembayaran.no_pendaftaran', $filters['search'])
                ->orLike('paket_kursus.nama_paket', $filters['search'])
                ->groupEnd();
        }
        
        return $builder->orderBy('pembayaran.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get total profit (sum)
     */
    public function getTotalProfit($filters = [])
    {
        $builder = $this->db->table('pembayaran')
            ->select('SUM(pembayaran.nominal) as total_profit')
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id')
            ->where('pembayaran.status', 'verified');
        
        if (!empty($filters['tanggal_start'])) {
            $builder->where('DATE(pembayaran.created_at) >=', $filters['tanggal_start']);
        }
        if (!empty($filters['tanggal_end'])) {
            $builder->where('DATE(pembayaran.created_at) <=', $filters['tanggal_end']);
        }
        if (!empty($filters['paket_id'])) {
            $builder->where('pendaftaran.paket_id', $filters['paket_id']);
        }
        
        $result = $builder->get()->getRowArray();
        return $result['total_profit'] ?? 0;
    }

    // ================== LAPORAN PENDAFTARAN ==================
    
    /**
     * Get laporan pendaftaran dengan filter
     * @param array $filters ['tanggal_start', 'tanggal_end', 'paket_id', 'status']
     */
    public function getLaporanPendaftaran($filters = [])
    {
        $builder = $this->db->table('pendaftaran')
            ->select('
                pendaftaran.id,
                pendaftaran.no_pendaftaran,
                pendaftaran.nama,
                pendaftaran.email,
                pendaftaran.no_hp,
                pendaftaran.tanggal_daftar,
                pendaftaran.tanggal_mulai,
                pendaftaran.tanggal_selesai,
                pendaftaran.status,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.harga
            ')
            ->join('paket_kursus', 'paket_kursus.id = pendaftaran.paket_id', 'left')
            ->where('pendaftaran.deleted_at IS NULL'); // Exclude soft deleted
        
        // Filter tanggal
        if (!empty($filters['tanggal_start'])) {
            $builder->where('DATE(pendaftaran.tanggal_daftar) >=', $filters['tanggal_start']);
        }
        if (!empty($filters['tanggal_end'])) {
            $builder->where('DATE(pendaftaran.tanggal_daftar) <=', $filters['tanggal_end']);
        }
        
        // Filter paket
        if (!empty($filters['paket_id'])) {
            $builder->where('pendaftaran.paket_id', $filters['paket_id']);
        }
        
        // Filter status
        if (!empty($filters['status'])) {
            $builder->where('pendaftaran.status', $filters['status']);
        }
        
        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('pendaftaran.nama', $filters['search'])
                ->orLike('pendaftaran.no_pendaftaran', $filters['search'])
                ->orLike('pendaftaran.email', $filters['search'])
                ->groupEnd();
        }
        
        return $builder->orderBy('pendaftaran.tanggal_daftar', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get statistik pendaftaran per bulan
     */
    public function getStatistikPendaftaran($filters = [])
    {
        $builder = $this->db->table('pendaftaran')
            ->select('
                COUNT(*) as jumlah,
                MONTH(tanggal_daftar) as bulan,
                YEAR(tanggal_daftar) as tahun
            ')
            ->where('deleted_at IS NULL')
            ->groupBy('YEAR(tanggal_daftar), MONTH(tanggal_daftar)')
            ->orderBy('tahun DESC, bulan DESC');
        
        if (!empty($filters['tanggal_start'])) {
            $builder->where('DATE(tanggal_daftar) >=', $filters['tanggal_start']);
        }
        if (!empty($filters['tanggal_end'])) {
            $builder->where('DATE(tanggal_daftar) <=', $filters['tanggal_end']);
        }
        
        return $builder->get()->getResultArray();
    }

    // ================== LAPORAN ABSENSI PER KELAS ==================
    
    /**
     * Get laporan absensi dengan detail kehadiran
     * @param array $filters ['tanggal_start', 'tanggal_end', 'jadwal_kelas_id', 'instruktur_id']
     */
    public function getLaporanAbsensiPerKelas($filters = [])
    {
        $builder = $this->db->table('jadwal_kelas')
            ->select('
                jadwal_kelas.id AS jadwal_id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                jadwal_kelas.created_at AS tanggal_mulai_kelas,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.jumlah_pertemuan,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang,
                COUNT(DISTINCT ks.id) AS jumlah_siswa,
                COUNT(DISTINCT absensi_kelas.id) AS pertemuan_terlaksana
            ')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->join('kelas_siswa ks', 'ks.jadwal_kelas_id = jadwal_kelas.id AND ks.status = "aktif"', 'left')
            ->join('absensi_kelas', 'absensi_kelas.jadwal_kelas_id = jadwal_kelas.id', 'left')
            ->where('jadwal_kelas.status', 'aktif')
            ->groupBy('jadwal_kelas.id');
        
        // Filter tanggal (tanggal mulai kelas)
        if (!empty($filters['tanggal_start'])) {
            $builder->where('DATE(jadwal_kelas.created_at) >=', $filters['tanggal_start']);
        }
        if (!empty($filters['tanggal_end'])) {
            $builder->where('DATE(jadwal_kelas.created_at) <=', $filters['tanggal_end']);
        }
        
        // Filter jadwal kelas
        if (!empty($filters['jadwal_kelas_id'])) {
            $builder->where('jadwal_kelas.id', $filters['jadwal_kelas_id']);
        }
        
        // Filter instruktur
        if (!empty($filters['instruktur_id'])) {
            $builder->where('jadwal_kelas.instruktur_id', $filters['instruktur_id']);
        }
        
        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('paket_kursus.nama_paket', $filters['search'])
                ->orLike('instruktur.nama', $filters['search'])
                ->orLike('ruang_kelas.nama_ruang', $filters['search'])
                ->groupEnd();
        }
        
        return $builder->orderBy('jadwal_kelas.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get detail absensi siswa per kelas
     */
    public function getDetailAbsensiPerSiswa($jadwalKelasId)
    {
        $builder = $this->db->table('kelas_siswa')
            ->select('
                kelas_siswa.id AS kelas_siswa_id,
                pendaftaran.nama AS nama_siswa,
                pendaftaran.email,
                pendaftaran.no_hp,
                COUNT(DISTINCT absensi_kelas.id) AS total_pertemuan_terlaksana,
                COUNT(DISTINCT CASE WHEN abs_siswa.status = "hadir" THEN abs_siswa.id END) AS total_hadir,
                COUNT(DISTINCT CASE WHEN abs_siswa.status = "izin" THEN abs_siswa.id END) AS total_izin,
                COUNT(DISTINCT CASE WHEN abs_siswa.status = "sakit" THEN abs_siswa.id END) AS total_sakit,
                COUNT(DISTINCT CASE WHEN abs_siswa.status = "alpha" THEN abs_siswa.id END) AS total_alpha
            ')
            ->join('pendaftaran', 'pendaftaran.id = kelas_siswa.pendaftaran_id')
            ->join('absensi_kelas', 'absensi_kelas.jadwal_kelas_id = kelas_siswa.jadwal_kelas_id', 'left')
            ->join('absensi_siswa abs_siswa', 'abs_siswa.kelas_siswa_id = kelas_siswa.id AND abs_siswa.absensi_kelas_id = absensi_kelas.id', 'left')
            ->where('kelas_siswa.jadwal_kelas_id', $jadwalKelasId)
            ->where('kelas_siswa.status', 'aktif')
            ->groupBy('kelas_siswa.id')
            ->orderBy('pendaftaran.nama', 'ASC');
        
        $results = $builder->get()->getResultArray();
        
        // Hitung persentase kehadiran
        foreach ($results as &$row) {
            $totalPertemuan = $row['total_pertemuan_terlaksana'];
            if ($totalPertemuan > 0) {
                $row['persentase_kehadiran'] = round(($row['total_hadir'] / $totalPertemuan) * 100, 1);
            } else {
                $row['persentase_kehadiran'] = 0;
            }
        }
        
        return $results;
    }

    public function getInfoKelas($jadwalKelasId)
    {
        return $this->db->table('jadwal_kelas')
            ->select('
                jadwal_kelas.id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                jadwal_kelas.created_at AS tanggal_mulai_kelas,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.jumlah_pertemuan,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang,
                COUNT(DISTINCT ks.id) AS jumlah_siswa
            ')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->join('kelas_siswa ks', 'ks.jadwal_kelas_id = jadwal_kelas.id AND ks.status = "aktif"', 'left')
            ->where('jadwal_kelas.id', $jadwalKelasId)
            ->groupBy('jadwal_kelas.id')
            ->get()
            ->getRowArray();
    }

    // ================== LAPORAN PROGRESS KURSUS ==================
    
    /**
     * Get laporan progress kursus
     * @param array $filters ['jadwal_kelas_id', 'instruktur_id', 'status']
     */
    public function getLaporanProgress($filters = [])
    {
        $builder = $this->db->table('progress_kursus')
            ->select('
                progress_kursus.*,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.jumlah_pertemuan AS target_pertemuan,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang,
                ROUND((progress_kursus.pertemuan_terlaksana / progress_kursus.total_pertemuan * 100), 2) AS persentase_progress
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = progress_kursus.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left');
        
        // Filter jadwal kelas
        if (!empty($filters['jadwal_kelas_id'])) {
            $builder->where('progress_kursus.jadwal_kelas_id', $filters['jadwal_kelas_id']);
        }
        
        // Filter instruktur
        if (!empty($filters['instruktur_id'])) {
            $builder->where('jadwal_kelas.instruktur_id', $filters['instruktur_id']);
        }
        
        // Filter status
        if (!empty($filters['status'])) {
            $builder->where('progress_kursus.status', $filters['status']);
        }
        
        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('paket_kursus.nama_paket', $filters['search'])
                ->orLike('instruktur.nama', $filters['search'])
                ->groupEnd();
        }
        
        return $builder->orderBy('progress_kursus.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get detail progress per pertemuan
     */
    public function getDetailProgressPertemuan($progressKursusId)
    {
        return $this->db->table('detail_progress')
            ->select('
                detail_progress.*,
                instruktur.nama AS nama_instruktur
            ')
            ->join('instruktur', 'instruktur.id = detail_progress.created_by', 'left')
            ->where('detail_progress.progress_kursus_id', $progressKursusId)
            ->orderBy('detail_progress.pertemuan_ke', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get info kelas untuk detail progress
     */
    public function getInfoProgressKursus($progressKursusId)
    {
        return $this->db->table('progress_kursus')
            ->select('
                progress_kursus.*,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.jumlah_pertemuan,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang,
                ROUND((progress_kursus.pertemuan_terlaksana / progress_kursus.total_pertemuan * 100), 2) AS persentase_progress
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = progress_kursus.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->where('progress_kursus.id', $progressKursusId)
            ->get()
            ->getRowArray();
    }

    // ================== HELPER METHODS ==================
    
    /**
     * Get list paket untuk filter dropdown
     */
    public function getListPaket()
    {
        return $this->db->table('paket_kursus')
            ->select('id, nama_paket, level')
            ->where('status', 'aktif')
            ->orderBy('nama_paket', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get list instruktur untuk filter dropdown
     */
    public function getListInstruktur()
    {
        return $this->db->table('instruktur')
            ->select('id, nama')
            ->where('status', 'aktif')
            ->orderBy('nama', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get list jadwal kelas untuk filter dropdown
     */
    public function getListJadwalKelas()
    {
        return $this->db->table('jadwal_kelas')
            ->select('
                jadwal_kelas.id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                paket_kursus.nama_paket,
                paket_kursus.level
            ')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->where('jadwal_kelas.status', 'aktif')
            ->orderBy('paket_kursus.nama_paket', 'ASC')
            ->get()
            ->getResultArray();
    }
}