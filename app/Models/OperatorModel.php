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
        'instruktur_id',
        'status',
        'created_at',
        'deleted_at'
    ];

    protected $useSoftDeletes   = true;
    protected $useTimestamps    = false;

    // Helper list operator + nama instruktur
    public function getWithInstruktur()
    {
        return $this->select('operator.*, instruktur.nama AS nama_instruktur')
            ->join('instruktur', 'instruktur.id = operator.instruktur_id', 'left')
            ->where('operator.deleted_at', null)
            ->findAll();
    }
}
