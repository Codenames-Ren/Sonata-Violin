<?php

namespace App\Models;

use CodeIgniter\Model;

class InstrukturModel extends Model
{
    protected $table                =   'instruktur';
    protected $primaryKey           =   'id';

    protected $allowedFields        = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'foto_profil',
        'tgl_lahir',
        'keahlian',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimeStamps = false;

    protected $returnType    = 'array';
}