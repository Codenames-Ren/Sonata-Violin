<?php
namespace App\Models;
use CodeIgniter\Model;

class ProgressKursusModel extends Model
{
    protected $table = 'progress_kursus';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jadwal_kelas_id',
        'total_pertemuan',
        'pertemuan_terlaksana',
        'status'
    ];
    protected $useTimestamps = true;

    /**
     * List semua progress dengan info kelas lengkap
     */
    public function getProgressLengkap()
    {
        return $this->select('
                progress_kursus.*,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = progress_kursus.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->orderBy('progress_kursus.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Ambil kelas yang BELUM ada progressnya
     */
    public function getKelasWithoutProgress()
    {
        return $this->db->table('jadwal_kelas')
            ->select('
                jadwal_kelas.*,
                paket_kursus.nama_paket,
                paket_kursus.jumlah_pertemuan,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang
            ')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->where('jadwal_kelas.status', 'aktif')
            ->whereNotIn('jadwal_kelas.id', function($builder) {
                return $builder->select('jadwal_kelas_id')
                    ->from('progress_kursus');
            })
            ->get()
            ->getResultArray();
    }

    public function getProgressByInstruktur($instrukturId)
    {
        return $this->select('
                progress_kursus.*,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = progress_kursus.jadwal_kelas_id')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->where('jadwal_kelas.instruktur_id', $instrukturId)
            ->where('progress_kursus.status', 'aktif')
            ->findAll();
    }
}