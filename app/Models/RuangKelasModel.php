<?php

namespace App\Models;

use CodeIgniter\Model;

class RuangKelasModel extends Model
{
    protected $table            = 'ruang_kelas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes  = true;

    protected $allowedFields = [
        'nama_ruang',
        'kapasitas',
        'fasilitas',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
