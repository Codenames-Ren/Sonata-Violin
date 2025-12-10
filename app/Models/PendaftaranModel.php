<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes  = true;

    protected $allowedFields = [
        'siswa_id',
        'paket_id',
        'tanggal_daftar',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'updated_at',
        'deleted_at',

        // DATA DIRI SISWA (HOLDING)
        'nama',
        'alamat',
        'no_hp',
        'email',
        'tgl_lahir',
        'foto_profil'
    ];


    protected $useTimestamps = true;
    protected $createdField  = 'tanggal_daftar';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
