<?php

namespace App\Models;

use CodeIgniter\Model;

Class SiswaModel extends Model
{
    protected $table            =   'siswa';
    protected $primaryKey       =   'id';
    protected $returnType       =   'array';

    protected $allowedFields    =   [
        'nama',
        'alamat',
        'no_hp',
        'foto_profil',
        'tgl_lahir',
        'email',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimeStamps = false;
}