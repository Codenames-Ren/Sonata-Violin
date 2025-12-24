<?php
namespace App\Models;
use CodeIgniter\Model;

class DetailProgressModel extends Model
{
    protected $table = 'detail_progress';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'progress_kursus_id',
        'pertemuan_ke',
        'tanggal',
        'materi',
        'catatan',
        'status',
        'created_by'
    ];
    protected $useTimestamps = true;

    /**
     * Ambil detail progress + info instruktur
     */
    public function getDetailByProgress($progressId)
    {
        return $this->select('
                detail_progress.*,
                instruktur.nama AS nama_instruktur
            ')
            ->join('instruktur', 'instruktur.id = detail_progress.created_by', 'left')
            ->where('detail_progress.progress_kursus_id', $progressId)
            ->orderBy('detail_progress.pertemuan_ke', 'ASC')
            ->findAll();
    }

    /**
     * Cek pertemuan udah ada atau belum
     */
    public function cekPertemuanExists($progressId, $pertemuanKe, $excludeId = null)
    {
        $builder = $this->where('progress_kursus_id', $progressId)
            ->where('pertemuan_ke', $pertemuanKe);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->first();
    }
}