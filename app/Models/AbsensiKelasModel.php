<?php
namespace App\Models;
use CodeIgniter\Model;

class AbsensiKelasModel extends Model
{
    protected $table = 'absensi_kelas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jadwal_kelas_id',
        'tanggal',
        'status',
        'open_by',
        'open_when',
        'close_when'
    ];

    public function getAllAbsensi()
    {
        return $this->select('
                absensi_kelas.id AS absensi_id,
                absensi_kelas.tanggal,
                absensi_kelas.status AS status_absensi,
                absensi_kelas.open_when,
                absensi_kelas.close_when,
                jadwal_kelas.id AS jadwal_id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang,
                COUNT(DISTINCT ks.id) AS jumlah_siswa,
                COUNT(DISTINCT abs.id) AS jumlah_hadir
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = absensi_kelas.jadwal_kelas_id')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->join('kelas_siswa ks', 'ks.jadwal_kelas_id = jadwal_kelas.id AND ks.status = "aktif"', 'left')
            ->join('absensi_siswa abs', 'abs.absensi_kelas_id = absensi_kelas.id AND abs.status = "hadir"', 'left')
            ->groupBy('absensi_kelas.id')
            ->orderBy('absensi_kelas.tanggal DESC, jadwal_kelas.jam_mulai')
            ->findAll();
    }

    public function getJadwalBelumDibuka()
    {
        $hariIni = $this->getHariIndonesia(date('N'));
        $tanggalHariIni = date('Y-m-d');

        // Build raw WHERE clause untuk hari
        $whereClause = "FIND_IN_SET(" . $this->db->escape($hariIni) . ", REPLACE(jadwal_kelas.hari, ' ', ''))";

        return $this->db->table('jadwal_kelas')
            ->select('
                jadwal_kelas.id AS jadwal_id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang,
                COUNT(DISTINCT ks.id) AS jumlah_siswa
            ')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->join('kelas_siswa ks', 'ks.jadwal_kelas_id = jadwal_kelas.id AND ks.status = "aktif"', 'left')
            ->join('absensi_kelas ak', 
                'ak.jadwal_kelas_id = jadwal_kelas.id AND ak.tanggal = ' . $this->db->escape($tanggalHariIni), 
                'left'
            )
            ->where('jadwal_kelas.status', 'aktif')
            ->where('jadwal_kelas.jam_selesai >', date('H:i:s'))
            ->where($whereClause, null, false)
            ->where('ak.id IS NULL')
            ->groupBy('jadwal_kelas.id')
            ->orderBy('jadwal_kelas.jam_mulai')
            ->get()
            ->getResultArray();
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

    public function getOpenByInstruktur($instrukturId)
    {
        return $this->select('
                absensi_kelas.id AS absensi_id,
                absensi_kelas.tanggal,
                absensi_kelas.status AS status_absensi,
                absensi_kelas.open_when,
                jadwal_kelas.id AS jadwal_id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang,
                COUNT(DISTINCT ks.id) AS jumlah_siswa
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = absensi_kelas.jadwal_kelas_id')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->join('kelas_siswa ks', 'ks.jadwal_kelas_id = jadwal_kelas.id AND ks.status = "aktif"', 'left')
            ->where('jadwal_kelas.instruktur_id', $instrukturId)
            ->where('absensi_kelas.status', 'open')
            ->groupBy('absensi_kelas.id')
            ->orderBy('absensi_kelas.tanggal DESC, jadwal_kelas.jam_mulai')
            ->findAll();
    }

    public function getDetailAbsensi($absensiKelasId)
    {
        return $this->select('
                absensi_kelas.id,
                absensi_kelas.jadwal_kelas_id,
                absensi_kelas.tanggal,
                absensi_kelas.status,
                absensi_kelas.open_by,
                absensi_kelas.open_when,
                absensi_kelas.close_when,
                jadwal_kelas.id AS jadwal_id,
                jadwal_kelas.instruktur_id,
                jadwal_kelas.hari,
                jadwal_kelas.jam_mulai,
                jadwal_kelas.jam_selesai,
                paket_kursus.nama_paket,
                paket_kursus.level,
                paket_kursus.jumlah_pertemuan,
                instruktur.nama AS nama_instruktur,
                ruang_kelas.nama_ruang
            ')
            ->join('jadwal_kelas', 'jadwal_kelas.id = absensi_kelas.jadwal_kelas_id')
            ->join('instruktur', 'instruktur.id = jadwal_kelas.instruktur_id', 'left')
            ->join('paket_kursus', 'paket_kursus.id = jadwal_kelas.paket_id', 'left')
            ->join('ruang_kelas', 'ruang_kelas.id = jadwal_kelas.ruang_kelas_id', 'left')
            ->where('absensi_kelas.id', $absensiKelasId)
            ->first();
    }
}