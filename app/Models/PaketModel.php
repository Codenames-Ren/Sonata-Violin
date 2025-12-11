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
        'tanggal_mulai',
        'tanggal_selesai',
        'periode_mulai',
        'periode_selesai',
        'batch',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
