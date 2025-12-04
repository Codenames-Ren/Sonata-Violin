<?php

namespace App\Models;

use CodeIgniter\Model;

class OperatorModel extends Model
{
    protected $table            = 'operator';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'username',
        'password',
        'nama_lengkap',
        'role',
        'status',     
        'created_at',
        'deleted_at'  
    ];

    protected $useSoftDeletes   = true; 
    protected $useTimestamps    = false;
}
