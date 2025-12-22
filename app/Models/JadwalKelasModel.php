<?php
namespace App\Models;
use CodeIgniter\Model;

class JadwalKelasModel extends Model
{
    protected $table      = 'jadwal_kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'instruktur_id',
        'paket_id',           // TAMBAHKAN INI
        'ruang_kelas_id',     // TAMBAHKAN INI
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kapasitas',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;

    public function getJadwalLengkap()
    {
        return $this->select('
                jadwal_kelas.*,
                instruktur.nama AS nama_instruktur,
                instruktur.keahlian AS keahlian_instruktur,
                paket_kursus.nama_paket,
                paket_kursus.level AS tingkat,
                paket_kursus.jumlah_pertemuan,
                paket_kursus.periode_mulai,
                paket_kursus.periode_selesai,
                ruang_kelas.nama_ruang,
                ruang_kelas.kapasitas AS kapasitas_ruang,
                COUNT(ks.id) AS jumlah_siswa
            ')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->join('kelas_siswa ks', 'ks.jadwal_kelas_id = jadwal_kelas.id AND ks.status = "aktif"', 'left')
            ->groupBy('jadwal_kelas.id')
            ->orderBy('
                FIELD(jadwal_kelas.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"),
                jadwal_kelas.jam_mulai
            ')
            ->findAll();
    }


    // ============ TAMBAH METHOD BARU INI ============
    /**
     * Ambil jadwal berdasarkan instruktur_id (untuk instruktur)
     */
    public function getJadwalByInstruktur($instrukturId)
    {
        return $this->select('
                jadwal_kelas.*,
                instruktur.nama AS nama_instruktur,
                instruktur.keahlian AS keahlian_instruktur,
                paket_kursus.nama_paket,
                paket_kursus.level AS tingkat,
                paket_kursus.jumlah_pertemuan,
                paket_kursus.periode_mulai,
                paket_kursus.periode_selesai,
                ruang_kelas.nama_ruang,
                ruang_kelas.kapasitas AS kapasitas_ruang,
                COUNT(ks.id) AS jumlah_siswa
            ')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->join('kelas_siswa ks', 'ks.jadwal_kelas_id = jadwal_kelas.id AND ks.status = "aktif"', 'left')
            ->where('jadwal_kelas.instruktur_id', $instrukturId)
            ->where('jadwal_kelas.status', 'aktif')
            ->groupBy('jadwal_kelas.id')
            ->orderBy('
                FIELD(jadwal_kelas.hari, "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"),
                jadwal_kelas.jam_mulai
            ')
            ->findAll();
    }

    /**
     * Ambil detail 1 jadwal dengan JOIN lengkap
     */
    public function getJadwalById($id)
    {
        return $this->select('
                jadwal_kelas.*,
                instruktur.nama AS nama_instruktur,
                instruktur.email AS email_instruktur,
                instruktur.no_hp AS hp_instruktur,
                instruktur.keahlian AS keahlian_instruktur,
                instruktur.foto_profil AS foto_instruktur,
                paket_kursus.nama_paket,
                paket_kursus.level AS tingkat,
                paket_kursus.durasi,
                paket_kursus.jumlah_pertemuan,
                paket_kursus.harga,
                ruang_kelas.nama_ruang,
                ruang_kelas.kapasitas AS kapasitas_ruang,
                ruang_kelas.fasilitas
            ')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->where('jadwal_kelas.id', $id)
            ->first();
    }

    /**
     * Cek bentrok jadwal (instruktur / ruang)
     */
    public function cekBentrok($data, $excludeId = null)
    {
        $builder = $this->where('hari', $data['hari'])
            ->where('status', 'aktif')
            ->groupStart()
                ->where('instruktur_id', $data['instruktur_id'])
                ->orWhere('ruang_kelas_id', $data['ruang_kelas_id'])
            ->groupEnd()
            ->groupStart()
                ->where('jam_mulai <', $data['jam_selesai'])
                ->where('jam_selesai >', $data['jam_mulai'])
            ->groupEnd();

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->first();
    }
}