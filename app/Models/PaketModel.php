<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $table            = 'paket_kursus';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'nama_paket',
        'level',
        'durasi',
        'jumlah_pertemuan',
        'harga',
        'deskripsi',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
