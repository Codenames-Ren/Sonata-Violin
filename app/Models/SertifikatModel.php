<?php
namespace App\Models;
use CodeIgniter\Model;

class SertifikatModel extends Model
{
    protected $table = 'sertifikat';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'no_sertifikat',
        'kelas_siswa_id',
        'pendaftaran_id',
        'jadwal_kelas_id',
        'tanggal_lulus',
        'tanggal_cetak',
        'status',
        'file_path'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ================== CRUD METHODS ==================

    /**
     * Get all sertifikat dengan info lengkap
     */
    public function getAllSertifikat()
    {
        return $this->select('
                sertifikat.*,
                pendaftaran.nama AS nama_siswa,
                pendaftaran.email,
                pendaftaran.no_hp,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai
            ')
            ->join('pendaftaran', 'pendaftaran.id = sertifikat.pendaftaran_id')
            ->join('kelas_siswa', 'kelas_siswa.id = sertifikat.kelas_siswa_id')
            ->join('jadwal_kelas', 'jadwal_kelas.id = sertifikat.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->orderBy('sertifikat.tanggal_lulus', 'DESC')
            ->findAll();
    }

    /**
     * Get detail sertifikat by ID
     */
    public function getDetailSertifikat($sertifikatId)
    {
        return $this->select('
                sertifikat.*,
                pendaftaran.nama AS nama_siswa,
                pendaftaran.email,
                pendaftaran.no_hp,
                pendaftaran.tgl_lahir,
                pendaftaran.alamat,
                pendaftaran.foto_profil,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.jumlah_pertemuan,
                instruktur.nama AS nama_instruktur,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai
            ')
            ->join('pendaftaran', 'pendaftaran.id = sertifikat.pendaftaran_id')
            ->join('kelas_siswa', 'kelas_siswa.id = sertifikat.kelas_siswa_id')
            ->join('jadwal_kelas', 'jadwal_kelas.id = sertifikat.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->where('sertifikat.id', $sertifikatId)
            ->first();
    }

    /**
     * Get sertifikat by no_sertifikat
     */
    public function getByNoSertifikat($noSertifikat)
    {
        return $this->where('no_sertifikat', $noSertifikat)->first();
    }

    // ================== SISWA LULUS (BELUM ADA SERTIFIKAT) ==================

    /**
     * Get siswa yang lulus tapi belum punya sertifikat
     * Kriteria: status kelas_siswa = "lulus" DAN belum ada di tabel sertifikat
     */
    public function getSiswaLulusBelumSertifikat()
    {
        return $this->db->table('kelas_siswa')
            ->select('
                kelas_siswa.id AS kelas_siswa_id,
                kelas_siswa.status,
                pendaftaran.id AS pendaftaran_id,
                pendaftaran.nama AS nama_siswa,
                pendaftaran.email,
                pendaftaran.no_hp,
                jadwal_kelas.id AS jadwal_kelas_id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur
            ')
            ->join('pendaftaran', 'pendaftaran.id = kelas_siswa.pendaftaran_id')
            ->join('jadwal_kelas', 'jadwal_kelas.id = kelas_siswa.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('sertifikat', 'sertifikat.kelas_siswa_id = kelas_siswa.id', 'left')
            ->where('kelas_siswa.status', 'lulus')
            ->where('sertifikat.id IS NULL') // Belum ada sertifikat
            ->orderBy('pendaftaran.nama', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Cek apakah siswa sudah punya sertifikat
     */
    public function cekSertifikatExists($kelasSiswaId)
    {
        return $this->where('kelas_siswa_id', $kelasSiswaId)->first();
    }

    // ================== GENERATE NOMOR SERTIFIKAT ==================

    /**
     * Generate nomor sertifikat unik
     * Format: CERT/TAHUN/BULAN/NOMOR_URUT
     * Contoh: CERT/2025/01/001
     */
    public function generateNoSertifikat()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $prefix = "CERT/{$tahun}/{$bulan}/";

        // Cari nomor terakhir di bulan ini
        $lastSertifikat = $this->like('no_sertifikat', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastSertifikat) {
            // Extract nomor urut dari no_sertifikat terakhir
            $lastNumber = (int) substr($lastSertifikat['no_sertifikat'], -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    // ================== CETAK SERTIFIKAT ==================

    /**
     * Update status cetak dan tanggal cetak
     */
    public function tandaiSudahCetak($sertifikatId, $filePath = null)
    {
        $data = [
            'status' => 'sudah_cetak',
            'tanggal_cetak' => date('Y-m-d H:i:s')
        ];

        if ($filePath) {
            $data['file_path'] = $filePath;
        }

        return $this->update($sertifikatId, $data);
    }

    /**
     * Batch update status cetak untuk multiple sertifikat
     */
    public function batchTandaiSudahCetak($sertifikatIds)
    {
        return $this->whereIn('id', $sertifikatIds)
            ->set([
                'status' => 'sudah_cetak',
                'tanggal_cetak' => date('Y-m-d H:i:s')
            ])
            ->update();
    }

    // ================== FILTER & SEARCH ==================

    /**
     * Get sertifikat dengan filter
     * @param array $filters ['status', 'tanggal_start', 'tanggal_end', 'paket_id', 'search']
     */
    public function getSertifikatWithFilter($filters = [])
    {
        $builder = $this->db->table('sertifikat')
            ->select('
                sertifikat.*,
                pendaftaran.nama AS nama_siswa,
                pendaftaran.email,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur
            ')
            ->join('pendaftaran', 'pendaftaran.id = sertifikat.pendaftaran_id')
            ->join('kelas_siswa', 'kelas_siswa.id = sertifikat.kelas_siswa_id')
            ->join('jadwal_kelas', 'jadwal_kelas.id = sertifikat.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left');

        // Filter status
        if (!empty($filters['status'])) {
            $builder->where('sertifikat.status', $filters['status']);
        }

        // Filter tanggal lulus
        if (!empty($filters['tanggal_start'])) {
            $builder->where('DATE(sertifikat.tanggal_lulus) >=', $filters['tanggal_start']);
        }
        if (!empty($filters['tanggal_end'])) {
            $builder->where('DATE(sertifikat.tanggal_lulus) <=', $filters['tanggal_end']);
        }

        // Filter paket
        if (!empty($filters['paket_id'])) {
            $builder->where('jadwal_kelas.paket_id', $filters['paket_id']);
        }

        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('pendaftaran.nama', $filters['search'])
                ->orLike('sertifikat.no_sertifikat', $filters['search'])
                ->orLike('paket_kursus.nama_paket', $filters['search'])
                ->groupEnd();
        }

        return $builder->orderBy('sertifikat.tanggal_lulus', 'DESC')
            ->get()
            ->getResultArray();
    }

    // ================== STATISTIK ==================

    /**
     * Get jumlah sertifikat berdasarkan status
     */
    public function getStatistikByStatus()
    {
        return $this->select('
                status,
                COUNT(*) as jumlah
            ')
            ->groupBy('status')
            ->findAll();
    }

    /**
     * Get jumlah sertifikat per bulan
     */
    public function getStatistikPerBulan($tahun = null)
    {
        $builder = $this->db->table('sertifikat')
            ->select('
                MONTH(tanggal_lulus) as bulan,
                YEAR(tanggal_lulus) as tahun,
                COUNT(*) as jumlah
            ')
            ->groupBy('YEAR(tanggal_lulus), MONTH(tanggal_lulus)')
            ->orderBy('tahun DESC, bulan DESC');

        if ($tahun) {
            $builder->where('YEAR(tanggal_lulus)', $tahun);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get total sertifikat yang sudah dicetak
     */
    public function getTotalSudahCetak()
    {
        return $this->where('status', 'sudah_cetak')->countAllResults();
    }

    /**
     * Get total sertifikat yang belum dicetak
     */
    public function getTotalBelumCetak()
    {
        return $this->where('status', 'belum_cetak')->countAllResults();
    }
}