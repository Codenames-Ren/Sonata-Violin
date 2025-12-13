<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'no_pendaftaran',
        'pendaftaran_id',
        'nominal',
        'bukti_transaksi',
        'tanggal_upload',
        'status',
        'catatan',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
