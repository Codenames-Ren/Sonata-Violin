<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasSiswaModel extends Model
{
    protected $table      = 'kelas_siswa';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jadwal_kelas_id',
        'pendaftaran_id',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    /**
     * Ambil siswa dalam 1 kelas
     */
    public function getSiswaByKelas($kelasId)
    {
        return $this->select('
                kelas_siswa.*,
                pendaftaran.nama,
                pendaftaran.email,
                pendaftaran.no_hp
            ')
            ->join(
                'pendaftaran',
                'pendaftaran.id = kelas_siswa.pendaftaran_id'
            )
            ->where('kelas_siswa.jadwal_kelas_id', $kelasId)
            ->findAll();
    }
}
