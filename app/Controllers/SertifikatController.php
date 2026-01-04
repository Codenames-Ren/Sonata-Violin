<?php
namespace App\Controllers;

use App\Models\SertifikatModel;
use App\Models\KelasSiswaModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

class SertifikatController extends BaseController
{
    protected $sertifikatModel;
    protected $kelasSiswaModel;
    protected $session;

    public function __construct()
    {
        $this->sertifikatModel = new SertifikatModel();
        $this->kelasSiswaModel = new KelasSiswaModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'filesystem']);
        $this->db = \Config\Database::connect();
    }

    // LIST SERTIFIKAT
    public function index()
    {
        $filters = [
            'status' => $this->request->getGet('status'),
            'tanggal_start' => $this->request->getGet('tanggal_start'),
            'tanggal_end' => $this->request->getGet('tanggal_end'),
            'paket_id' => $this->request->getGet('paket_id'),
            'search' => $this->request->getGet('search')
        ];

        // Export handling
        $export = $this->request->getGet('export');
        if ($export === 'excel') {
            return $this->exportExcel($filters);
        }
        if ($export === 'pdf') {
            return $this->exportPDF($filters);
        }

        $dataSertifikat = $this->sertifikatModel->getSertifikatWithFilter($filters);

        // Pagination
        $perPage = 15;
        $currentPage = $this->request->getGet('page') ?? 1;
        $totalData = count($dataSertifikat);
        $totalPages = ceil($totalData / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($dataSertifikat, $offset, $perPage);

        // Encode ID untuk link preview & cetak
        $paginatedData = array_map(function($item) {
            $item['id_encoded'] = encode_id($item['id']);
            return $item;
        }, $paginatedData);

        // Statistik
        $totalSudahCetak = $this->sertifikatModel->getTotalSudahCetak();
        $totalBelumCetak = $this->sertifikatModel->getTotalBelumCetak();

        $data = [
            'page_title' => 'Sertifikat',
            'page_subtitle' => 'Kelola data sertifikat siswa Sonata Violin',
            'title' => 'Kelola Sertifikat',
            'menu_active' => 'sertifikat',
            'dataSertifikat' => $paginatedData,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_data' => $totalData
            ],
            'totalSudahCetak' => $totalSudahCetak,
            'totalBelumCetak' => $totalBelumCetak,
            'listPaket' => $this->sertifikatModel->db->table('paket_kursus')
                ->select('id, nama_paket')
                ->where('status', 'aktif')
                ->get()
                ->getResultArray()
        ];

        return view('sertifikat/index', $data);
    }

    // SISWA LULUS YANG BELUM PUNYA SERTIFIKAT
    public function siswaLulus()
    {
        $this->db->query("
            UPDATE kelas_siswa ks
            JOIN pendaftaran p ON p.id = ks.pendaftaran_id
            JOIN siswa s ON s.no_pendaftaran = p.no_pendaftaran
            SET ks.status = 'lulus'
            WHERE s.status = 'lulus' 
            AND p.status = 'selesai'
            AND ks.status = 'aktif'
        ");
        
        $siswaLulus = $this->sertifikatModel->getSiswaLulusBelumSertifikat();

        $data = [
            'page_title' => 'Generate Sertifikat',
            'page_subtitle' => 'Buat sertifikat untuk siswa yang lulus Sonata Violin',
            'title' => 'Siswa Lulus - Generate Sertifikat',
            'menu_active' => 'sertifikat',
            'submenu_active' => 'siswa_lulus',
            'siswaLulus' => $siswaLulus
        ];

        return view('sertifikat/siswa_lulus', $data);
    }

    // GENERATE SERTIFIKAT
    public function generateSingle($kelasSiswaId)
    {
        // Cek apakah sudah ada sertifikat
        $existing = $this->sertifikatModel->cekSertifikatExists($kelasSiswaId);
        if ($existing) {
            return redirect()->back()->with('error', 'Sertifikat sudah pernah dibuat untuk siswa ini!');
        }

        $kelasSiswa = $this->kelasSiswaModel
            ->select('
                kelas_siswa.id,
                kelas_siswa.status,
                kelas_siswa.pendaftaran_id,
                kelas_siswa.jadwal_kelas_id
            ')
            ->where('kelas_siswa.id', $kelasSiswaId)
            ->first();

        if (!$kelasSiswa || $kelasSiswa['status'] !== 'lulus') {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan atau status bukan lulus!');
        }

        // Generate nomor sertifikat
        $noSertifikat = $this->sertifikatModel->generateNoSertifikat();

        // Insert ke database
        $dataSertifikat = [
            'no_sertifikat' => $noSertifikat,
            'kelas_siswa_id' => $kelasSiswaId,
            'pendaftaran_id' => $kelasSiswa['pendaftaran_id'],
            'jadwal_kelas_id' => $kelasSiswa['jadwal_kelas_id'],
            'tanggal_lulus' => date('Y-m-d'), // Pake hari ini aja
            'status' => 'belum_cetak'
        ];

        if ($this->sertifikatModel->insert($dataSertifikat)) {
            return redirect()->to('/sertifikat/siswa-lulus')->with('success', 'Sertifikat berhasil digenerate!');
        } else {
            return redirect()->back()->with('error', 'Gagal generate sertifikat!');
        }
    }

    //Generate Batch
    public function generateBatch()
    {
        $kelasSiswaIds = $this->request->getPost('kelas_siswa_ids');
        
        if (empty($kelasSiswaIds) || !is_array($kelasSiswaIds)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 siswa!');
        }

        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($kelasSiswaIds as $kelasSiswaId) {
            // Cek apakah sudah ada
            $existing = $this->sertifikatModel->cekSertifikatExists($kelasSiswaId);
            if ($existing) {
                $failed++;
                $errors[] = "Siswa dengan ID {$kelasSiswaId} sudah punya sertifikat";
                continue;
            }

            $kelasSiswa = $this->kelasSiswaModel
                ->select('
                    kelas_siswa.id,
                    kelas_siswa.status,
                    kelas_siswa.pendaftaran_id,
                    kelas_siswa.jadwal_kelas_id
                ')
                ->where('kelas_siswa.id', $kelasSiswaId)
                ->first();

            if (!$kelasSiswa || $kelasSiswa['status'] !== 'lulus') {
                $failed++;
                $errors[] = "Siswa ID {$kelasSiswaId} tidak valid atau bukan status lulus";
                continue;
            }

            // Generate
            $noSertifikat = $this->sertifikatModel->generateNoSertifikat();
            $dataSertifikat = [
                'no_sertifikat' => $noSertifikat,
                'kelas_siswa_id' => $kelasSiswaId,
                'pendaftaran_id' => $kelasSiswa['pendaftaran_id'],
                'jadwal_kelas_id' => $kelasSiswa['jadwal_kelas_id'],
                'tanggal_lulus' => date('Y-m-d'),
                'status' => 'belum_cetak'
            ];

            if ($this->sertifikatModel->insert($dataSertifikat)) {
                $success++;
            } else {
                $failed++;
                $errors[] = "Gagal insert sertifikat untuk Siswa ID {$kelasSiswaId}";
            }
        }

        $message = "Berhasil generate {$success} sertifikat";
        if ($failed > 0) {
            $message .= ", {$failed} gagal. " . implode(', ', $errors);
        }

        return redirect()->to('/sertifikat/siswa-lulus')->with($failed > 0 ? 'warning' : 'success', $message);
    }

    // CETAK SERTIFIKAT PDF
    public function cetak($hash)
    {
        // Decode hash jadi ID
        $sertifikatId = decode_id($hash);
        
        if (!$sertifikatId) {
            return redirect()->to('/sertifikat')->with('error', 'Sertifikat tidak ditemukan!');
        }
        
        $sertifikat = $this->sertifikatModel->getDetailSertifikat($sertifikatId);

        if (!$sertifikat) {
            return redirect()->back()->with('error', 'Sertifikat tidak ditemukan!');
        }

        // Generate PDF
        $pdfContent = $this->generateSertifikatPDF($sertifikat);

        // Save file
        $uploadPath = WRITEPATH . 'uploads/sertifikat/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = 'CERT_' . $sertifikat['no_sertifikat'] . '_' . time() . '.pdf';
        $filename = str_replace('/', '-', $filename); // Replace slash in cert number
        $filepath = $uploadPath . $filename;

        file_put_contents($filepath, $pdfContent);

        // Update status
        $this->sertifikatModel->tandaiSudahCetak($sertifikatId, 'uploads/sertifikat/' . $filename);

        // Download
        return $this->response->download($filepath, null)->setFileName($filename);
    }

    // Cetak multiple sertifikat (ZIP)
    public function cetakBatch()
    {
        $sertifikatIds = $this->request->getPost('sertifikat_ids');
        
        if (empty($sertifikatIds) || !is_array($sertifikatIds)) {
            return redirect()->back()->with('error', 'Pilih minimal 1 sertifikat!');
        }

        $uploadPath = WRITEPATH . 'uploads/sertifikat/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $zipFilename = 'Sertifikat_Batch_' . date('YmdHis') . '.zip';
        $zipPath = $uploadPath . $zipFilename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP!');
        }

        foreach ($sertifikatIds as $sertifikatId) {
            $sertifikat = $this->sertifikatModel->getDetailSertifikat($sertifikatId);
            
            if (!$sertifikat) {
                continue;
            }

            // Generate PDF
            $pdfContent = $this->generateSertifikatPDF($sertifikat);

            $filename = 'CERT_' . str_replace('/', '-', $sertifikat['no_sertifikat']) . '.pdf';
            
            // Add to ZIP
            $zip->addFromString($filename, $pdfContent);

            // Save individual file
            $filepath = $uploadPath . $filename;
            file_put_contents($filepath, $pdfContent);

            // Update status
            $this->sertifikatModel->tandaiSudahCetak($sertifikatId, 'uploads/sertifikat/' . $filename);
        }

        $zip->close();

        // Download ZIP
        return $this->response->download($zipPath, null)->setFileName($zipFilename);
    }

    //Helper
    private function generateSertifikatPDF($sertifikat)
    {
        // Load template sertifikat
        $html = view('sertifikat/template_pdf', [
            'sertifikat' => $sertifikat
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->output();
    }

    // VIEW PREVIEW SERTIFIKAT 
    public function preview($hash)
    {
        // Decode hash jadi ID
        $sertifikatId = decode_id($hash);
        
        if (!$sertifikatId) {
            return redirect()->to('/sertifikat')->with('error', 'Sertifikat tidak ditemukan!');
        }
        
        $sertifikat = $this->sertifikatModel->getDetailSertifikat($sertifikatId);
        
        if (!$sertifikat) {
            return redirect()->back()->with('error', 'Sertifikat tidak ditemukan!');
        }
        
        $data = [
            'page_title' => 'Preview',
            'page_subtitle' => 'Pastikan sertifikat yang akan dicetak sudah sesuai',
            'title' => 'Preview Sertifikat',
            'sertifikat' => $sertifikat
        ];
        
        return view('sertifikat/preview', $data);
    }

    // DELETE SERTIFIKAT 
    public function delete($sertifikatId)
    {
        $sertifikat = $this->sertifikatModel->find($sertifikatId);

        if (!$sertifikat) {
            return redirect()->back()->with('error', 'Sertifikat tidak ditemukan!');
        }

        // Delete file jika ada
        if (!empty($sertifikat['file_path']) && file_exists(WRITEPATH . $sertifikat['file_path'])) {
            unlink(WRITEPATH . $sertifikat['file_path']);
        }

        if ($this->sertifikatModel->delete($sertifikatId)) {
            return redirect()->to('/sertifikat')->with('success', 'Sertifikat berhasil dihapus!');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus sertifikat!');
        }
    }

    // EXPORT TO EXCEL
    private function exportExcel($filters)
    {
        $dataSertifikat = $this->sertifikatModel->getSertifikatWithFilter($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'DATA SERTIFIKAT');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'No Sertifikat');
        $sheet->setCellValue('C3', 'Nama Siswa');
        $sheet->setCellValue('D3', 'Email');
        $sheet->setCellValue('E3', 'Paket');
        $sheet->setCellValue('F3', 'Instruktur');
        $sheet->setCellValue('G3', 'Tanggal Lulus');
        $sheet->setCellValue('H3', 'Status');
        
        $sheet->getStyle('A3:H3')->getFont()->setBold(true);
        $sheet->getStyle('A3:H3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        $row = 4;
        $no = 1;
        foreach ($dataSertifikat as $data) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data['no_sertifikat']);
            $sheet->setCellValue('C' . $row, $data['nama_siswa']);
            $sheet->setCellValue('D' . $row, $data['email']);
            $sheet->setCellValue('E' . $row, $data['nama_paket'] . ' - ' . $data['level']);
            $sheet->setCellValue('F' . $row, $data['nama_instruktur']);
            $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($data['tanggal_lulus'])));
            $sheet->setCellValue('H' . $row, ucwords(str_replace('_', ' ', $data['status'])));
            $row++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Sertifikat_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // STATISTIK 
    public function getStatistik()
    {
        $statistik = [
            'total_sertifikat' => $this->sertifikatModel->countAll(),
            'sudah_cetak' => $this->sertifikatModel->getTotalSudahCetak(),
            'belum_cetak' => $this->sertifikatModel->getTotalBelumCetak(),
            'per_bulan' => $this->sertifikatModel->getStatistikPerBulan(date('Y'))
        ];

        return $this->response->setJSON($statistik);
    }

    // EXPORT TO PDF
    private function exportPDF($filters)
    {
        $dataSertifikat = $this->sertifikatModel->getSertifikatWithFilter($filters);

        // Generate periode text
        $periode = 'Semua Periode';
        if (!empty($filters['tanggal_start']) && !empty($filters['tanggal_end'])) {
            $periode = date('d/m/Y', strtotime($filters['tanggal_start'])) . ' - ' . date('d/m/Y', strtotime($filters['tanggal_end']));
        } elseif (!empty($filters['tanggal_start'])) {
            $periode = 'Dari ' . date('d/m/Y', strtotime($filters['tanggal_start']));
        } elseif (!empty($filters['tanggal_end'])) {
            $periode = 'Sampai ' . date('d/m/Y', strtotime($filters['tanggal_end']));
        }

        // Generate status filter text
        $statusFilter = 'Semua Status';
        if (!empty($filters['status'])) {
            $statusFilter = ucwords(str_replace('_', ' ', $filters['status']));
        }

        $data = [
            'dataSertifikat' => $dataSertifikat,
            'periode' => $periode,
            'statusFilter' => $statusFilter,
            'totalSertifikat' => count($dataSertifikat)
        ];

        // Load view
        $html = view('sertifikat/laporan_pdf', $data);

        // Setup Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $filename = 'Laporan_Sertifikat_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
}