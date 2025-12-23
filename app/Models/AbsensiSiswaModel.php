<?php
namespace App\Models;
use CodeIgniter\Model;

class AbsensiSiswaModel extends Model
{
    protected $table = 'absensi_siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'absensi_kelas_id',
        'kelas_siswa_id',
        'status',
        'keterangan'
    ];


    public function getSiswaWithAbsensi($absensiKelasId)
    {
        $absensiKelasModel = new \App\Models\AbsensiKelasModel();
        $absensi = $absensiKelasModel->find($absensiKelasId);
        
        if (!$absensi) {
            return [];
        }

        $jadwalKelasId = $absensi['jadwal_kelas_id'];

        return $this->db->table('kelas_siswa')
            ->select('
                kelas_siswa.id AS kelas_siswa_id,
                pendaftaran.id AS pendaftaran_id,
                pendaftaran.nama,
                pendaftaran.email,
                pendaftaran.no_hp,
                absensi_siswa.id AS absensi_siswa_id,
                absensi_siswa.status AS status_absen,
                absensi_siswa.keterangan
            ')
            ->join('pendaftaran', 'pendaftaran.id = kelas_siswa.pendaftaran_id')
            ->join('absensi_siswa', 
                'absensi_siswa.kelas_siswa_id = kelas_siswa.id 
                AND absensi_siswa.absensi_kelas_id = ' . $absensiKelasId, 
                'left'
            )
            ->where('kelas_siswa.jadwal_kelas_id', $jadwalKelasId)
            ->where('kelas_siswa.status', 'aktif')
            ->orderBy('pendaftaran.nama', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getByAbsensiKelas($absensiKelasId)
    {
        return $this->select('
                absensi_siswa.*,
                pendaftaran.nama,
                pendaftaran.email
            ')
            ->join('kelas_siswa', 'kelas_siswa.id = absensi_siswa.kelas_siswa_id')
            ->join('pendaftaran', 'pendaftaran.id = kelas_siswa.pendaftaran_id')
            ->where('absensi_siswa.absensi_kelas_id', $absensiKelasId)
            ->findAll();
    }
}