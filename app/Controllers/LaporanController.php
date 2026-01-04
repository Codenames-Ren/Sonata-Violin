<?php
namespace App\Controllers;

use App\Models\LaporanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController extends BaseController
{
    protected $laporanModel;
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->session = \Config\Services::session();
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    // Halaman index - menu pilihan laporan
    public function index()
    {
        $bulanIni = date('Y-m');
        $tanggalStart = $bulanIni . '-01';
        $tanggalEnd = date('Y-m-t');
        
        $totalProfitBulanIni = $this->laporanModel->getTotalProfit([
            'tanggal_start' => $tanggalStart,
            'tanggal_end' => $tanggalEnd
        ]);
        
        // Total Pendaftaran Bulan Ini
        $dataPendaftaran = $this->laporanModel->getLaporanPendaftaran([
            'tanggal_start' => $tanggalStart,
            'tanggal_end' => $tanggalEnd
        ]);
        $totalPendaftaranBulanIni = count($dataPendaftaran);
        
        // Total Kelas Aktif
        $totalKelasAktif = $this->db->table('jadwal_kelas')
            ->where('status', 'aktif')
            ->countAllResults();
        
        // Total Sertifikat Pending (belum cetak)
        $sertifikatModel = new \App\Models\SertifikatModel();
        $totalSertifikatPending = $sertifikatModel->getTotalBelumCetak();
        
        $data = [
            'title' => 'Laporan',
            'menu_active' => 'laporan',
            'totalProfitBulanIni' => $totalProfitBulanIni,
            'totalPendaftaranBulanIni' => $totalPendaftaranBulanIni,
            'totalKelasAktif' => $totalKelasAktif,
            'totalSertifikatPending' => $totalSertifikatPending,
            'page_title' => 'Laporan',
            'page_subtitle' => 'Kelola data laporan dan audit trail Sonata Violin',
        ];
        
        return view('laporan/index', $data);
    }

    // Get Statistik
    public function getStatistik()
    {
        // Bulan ini
        $bulanIni = date('Y-m');
        $tanggalStart = $bulanIni . '-01';
        $tanggalEnd = date('Y-m-t');
        
        // Bulan lalu
        $bulanLalu = date('Y-m', strtotime('-1 month'));
        $tanggalStartBulanLalu = $bulanLalu . '-01';
        $tanggalEndBulanLalu = date('Y-m-t', strtotime('-1 month'));
        
        // Total profit bulan ini
        $totalProfitBulanIni = $this->laporanModel->getTotalProfit([
            'tanggal_start' => $tanggalStart,
            'tanggal_end' => $tanggalEnd
        ]);
        
        // Total profit bulan lalu
        $totalProfitBulanLalu = $this->laporanModel->getTotalProfit([
            'tanggal_start' => $tanggalStartBulanLalu,
            'tanggal_end' => $tanggalEndBulanLalu
        ]);
        
        // Hitung persentase growth
        $profitPercent = 0;
        if ($totalProfitBulanLalu > 0) {
            $profitPercent = round((($totalProfitBulanIni - $totalProfitBulanLalu) / $totalProfitBulanLalu) * 100, 1);
        } elseif ($totalProfitBulanIni > 0) {
            $profitPercent = 100;
        }
        
        // Data pendaftaran
        $dataPendaftaran = $this->laporanModel->getLaporanPendaftaran([
            'tanggal_start' => $tanggalStart,
            'tanggal_end' => $tanggalEnd
        ]);
        
        // Total kelas aktif
        $totalKelas = $this->db->table('jadwal_kelas')
            ->where('status', 'aktif')
            ->countAllResults();
        
        // Total sertifikat pending
        try {
            $sertifikatModel = new \App\Models\SertifikatModel();
            $totalSertifikat = $sertifikatModel->getTotalBelumCetak();
        } catch (\Exception $e) {
            $totalSertifikat = 0;
        }
        
        return $this->response->setJSON([
            'totalProfit' => $totalProfitBulanIni,
            'totalPendaftaran' => count($dataPendaftaran),
            'totalKelas' => $totalKelas,
            'totalSertifikat' => $totalSertifikat,
            'profitPercent' => $profitPercent
        ]);
    }
    
    // LAPORAN PROFIT
    public function profit()
    {
        $filters = [
            'tanggal_start' => $this->request->getGet('tanggal_start'),
            'tanggal_end' => $this->request->getGet('tanggal_end'),
            'paket_id' => $this->request->getGet('paket_id'),
            'search' => $this->request->getGet('search')
        ];

        // Cek apakah ada request export
        $export = $this->request->getGet('export');
        if ($export === 'excel') {
            return $this->exportProfitExcel($filters);
        } elseif ($export === 'pdf') {
            return $this->exportProfitPDF($filters);
        }

        $dataProfit = $this->laporanModel->getLaporanProfit($filters);
        $totalProfit = $this->laporanModel->getTotalProfit($filters);

        // Pagination
        $isMobile = $this->request->getUserAgent()->isMobile();
        $perPage = $isMobile ? 5 : 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $totalData = count($dataProfit);
        $totalPages = ceil($totalData / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($dataProfit, $offset, $perPage);

        // ========== AJAX REQUEST HANDLING ==========
        if ($this->request->isAJAX() || $this->request->getGet('ajax')) {
            return $this->response->setJSON([
                'success' => true,
                'dataProfit' => $paginatedData,
                'totalProfit' => $totalProfit,
                'pagination' => [
                    'current_page' => (int)$currentPage,
                    'total_pages' => (int)$totalPages,
                    'per_page' => (int)$perPage,
                    'total_data' => (int)$totalData,
                    'showing' => count($paginatedData)
                ],
                'filters' => $filters
            ]);
        }
        
        // Normal view response
        $data = [
            'title' => 'Laporan Profit',
            'menu_active' => 'laporan',
            'submenu_active' => 'profit',
            'dataProfit' => $paginatedData,
            'totalProfit' => $totalProfit,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_data' => $totalData
            ],
            'listPaket' => $this->laporanModel->getListPaket(),
            'page_title' => 'Laporan Profit',
            'page_subtitle' => 'Track keuntungan profit Sonata Violin dengan mudah',
        ];

        return view('laporan/profit', $data);
    }

    // PDF EXPORT 
    private function exportProfitPDF($filters)
    {
        try {
            ini_set('memory_limit', '256M');
            set_time_limit(300);

            // Ambil data dengan filter yang sama
            $dataProfit = $this->laporanModel->getLaporanProfit($filters);
            $totalProfit = $this->laporanModel->getTotalProfit($filters);

            // Periode
            $periode = 'Semua Periode';
            if (!empty($filters['tanggal_start']) && !empty($filters['tanggal_end'])) {
                $periode = date('d/m/Y', strtotime($filters['tanggal_start'])) . ' - ' . date('d/m/Y', strtotime($filters['tanggal_end']));
            }

            // Generate HTML
            $html = view('laporan/pdf/profit_pdf', [
                'dataProfit' => $dataProfit,
                'totalProfit' => $totalProfit,
                'periode' => $periode
            ]);

            // Configure Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('chroot', FCPATH); 
            $options->set('defaultFont', 'Arial'); 
            
            // Initialize Dompdf
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $filename = 'Laporan_Profit_' . date('YmdHis') . '.pdf';
            $dompdf->stream($filename, ['Attachment' => true]);
            
            exit;
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal export PDF: ' . $e->getMessage());
        }
    }

    private function exportProfitExcel($filters)
    {
        // Ambil data dengan filter yang sama
        $dataProfit = $this->laporanModel->getLaporanProfit($filters);
        $totalProfit = $this->laporanModel->getTotalProfit($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'LAPORAN PROFIT');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Periode
        $periode = 'Semua Periode';
        if (!empty($filters['tanggal_start']) && !empty($filters['tanggal_end'])) {
            $periode = date('d/m/Y', strtotime($filters['tanggal_start'])) . ' - ' . date('d/m/Y', strtotime($filters['tanggal_end']));
        }
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:H2');

        // Total Profit
        $sheet->setCellValue('A3', 'Total Profit: Rp ' . number_format($totalProfit, 0, ',', '.'));
        $sheet->mergeCells('A3:H3');
        $sheet->getStyle('A3')->getFont()->setBold(true);

        // Header Tabel
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'No Pendaftaran');
        $sheet->setCellValue('C5', 'Nama Siswa');
        $sheet->setCellValue('D5', 'Email');
        $sheet->setCellValue('E5', 'No HP');
        $sheet->setCellValue('F5', 'Paket');
        $sheet->setCellValue('G5', 'Nominal');
        $sheet->setCellValue('H5', 'Tanggal Approve');
        
        $sheet->getStyle('A5:H5')->getFont()->setBold(true);
        $sheet->getStyle('A5:H5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Data
        $row = 6;
        $no = 1;
        foreach ($dataProfit as $data) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data['no_pendaftaran']);
            $sheet->setCellValue('C' . $row, $data['nama_siswa']);
            $sheet->setCellValue('D' . $row, $data['email']);
          
            $sheet->setCellValueExplicit(
                'E' . $row, 
                $data['no_hp'], 
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            
            $sheet->setCellValue('F' . $row, $data['nama_paket'] . ' - ' . $data['level']);
            $sheet->setCellValue('G' . $row, 'Rp ' . number_format($data['nominal'], 0, ',', '.'));
            $sheet->setCellValue('H' . $row, date('d/m/Y H:i', strtotime($data['created_at'])));
            $row++;
        }

        // Auto width
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'Laporan_Profit_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // LAPORAN PENDAFTARAN
    public function pendaftaran()
    {
        $filters = [
            'tanggal_start' => $this->request->getGet('tanggal_start'),
            'tanggal_end' => $this->request->getGet('tanggal_end'),
            'paket_id' => $this->request->getGet('paket_id'),
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search')
        ];

        // Export handling
        $export = $this->request->getGet('export');
        if ($export === 'excel') {
            return $this->exportPendaftaranExcel($filters);
        } elseif ($export === 'pdf') {
            return $this->exportPendaftaranPDF($filters);
        }

        $dataPendaftaran = $this->laporanModel->getLaporanPendaftaran($filters);
        
        // Hitung statistik per status
        $statistik = [
            'total' => count($dataPendaftaran),
            'pending' => 0,
            'aktif' => 0,
            'batal' => 0,
            'selesai' => 0,
            'mundur' => 0
        ];
        
        foreach ($dataPendaftaran as $daftar) {
            $status = $daftar['status'];
            if (isset($statistik[$status])) {
                $statistik[$status]++;
            }
        }

        // Pagination
        $isMobile = $this->request->getUserAgent()->isMobile();
        $perPage = $isMobile ? 5 : 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $totalData = count($dataPendaftaran);
        $totalPages = ceil($totalData / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($dataPendaftaran, $offset, $perPage);

        // AJAX REQUEST HANDLING
        if ($this->request->isAJAX() || $this->request->getGet('ajax')) {
            return $this->response->setJSON([
                'success' => true,
                'dataPendaftaran' => $paginatedData,
                'statistik' => $statistik,
                'pagination' => [
                    'current_page' => (int)$currentPage,
                    'total_pages' => (int)$totalPages,
                    'per_page' => (int)$perPage,
                    'total_data' => (int)$totalData
                ],
                'filters' => $filters
            ]);
        }

        $data = [
            'title' => 'Laporan Pendaftaran',
            'menu_active' => 'laporan',
            'submenu_active' => 'pendaftaran',
            'dataPendaftaran' => $paginatedData,
            'statistik' => $statistik,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_data' => $totalData
            ],
            'listPaket' => $this->laporanModel->getListPaket(),
            'page_title' => 'Laporan Pendaftaran',
            'page_subtitle' => 'Cetak laporan dari pendaftaran siswa di Sonata Violin dengan mudah',
        ];

        return view('laporan/pendaftaran', $data);
    }

    private function exportPendaftaranExcel($filters)
    {
        // Ambil data dengan filter yang sama
        $dataPendaftaran = $this->laporanModel->getLaporanPendaftaran($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN PENDAFTARAN');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $periode = 'Semua Periode';
        if (!empty($filters['tanggal_start']) && !empty($filters['tanggal_end'])) {
            $periode = date('d/m/Y', strtotime($filters['tanggal_start'])) . ' - ' . date('d/m/Y', strtotime($filters['tanggal_end']));
        }
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        $sheet->mergeCells('A2:H2');

        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'No Pendaftaran');
        $sheet->setCellValue('C4', 'Nama');
        $sheet->setCellValue('D4', 'Email');
        $sheet->setCellValue('E4', 'No HP');
        $sheet->setCellValue('F4', 'Paket');
        $sheet->setCellValue('G4', 'Tanggal Daftar');
        $sheet->setCellValue('H4', 'Status');
        
        $sheet->getStyle('A4:H4')->getFont()->setBold(true);
        $sheet->getStyle('A4:H4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        $row = 5;
        $no = 1;
        foreach ($dataPendaftaran as $data) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data['no_pendaftaran']);
            $sheet->setCellValue('C' . $row, $data['nama']);
            $sheet->setCellValue('D' . $row, $data['email']);
            
            $sheet->setCellValueExplicit(
                'E' . $row, 
                $data['no_hp'], 
                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
            
            $sheet->setCellValue('F' . $row, $data['nama_paket'] . ' - ' . $data['level']);
            $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($data['tanggal_daftar'])));
            $sheet->setCellValue('H' . $row, ucfirst($data['status']));
            $row++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Pendaftaran_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function exportPendaftaranPDF($filters)
    {
        // Ambil data dengan filter yang sama
        $dataPendaftaran = $this->laporanModel->getLaporanPendaftaran($filters);

        $periode = 'Semua Periode';
        if (!empty($filters['tanggal_start']) && !empty($filters['tanggal_end'])) {
            $periode = date('d/m/Y', strtotime($filters['tanggal_start'])) . ' - ' . date('d/m/Y', strtotime($filters['tanggal_end']));
        }

        $html = view('laporan/pdf/pendaftaran_pdf', [
            'dataPendaftaran' => $dataPendaftaran,
            'periode' => $periode
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Laporan_Pendaftaran_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    // LAPORAN ABSENSI (LIST KELAS)
    public function absensi()
    {
        $filters = [
            'tanggal_start' => $this->request->getGet('tanggal_start'),
            'tanggal_end' => $this->request->getGet('tanggal_end'),
            'jadwal_kelas_id' => $this->request->getGet('jadwal_kelas_id'),
            'instruktur_id' => $this->request->getGet('instruktur_id'),
            'search' => $this->request->getGet('search')
        ];

        $dataAbsensi = $this->laporanModel->getLaporanAbsensiPerKelas($filters);

        // Pagination adaptive
        $isMobile = $this->request->getUserAgent()->isMobile();
        $perPage = $isMobile ? 5 : 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $totalData = count($dataAbsensi);
        $totalPages = ceil($totalData / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($dataAbsensi, $offset, $perPage);

        // AJAX REQUEST HANDLING
        if ($this->request->isAJAX() || $this->request->getGet('ajax')) {
            // Encode jadwal_id buat link
            $paginatedData = array_map(function($item) {
                $item['jadwal_id_encoded'] = encode_id($item['jadwal_id']);
                return $item;
            }, $paginatedData);
            
            return $this->response->setJSON([
                'success' => true,
                'dataAbsensi' => $paginatedData,
                'pagination' => [
                    'current_page' => (int)$currentPage,
                    'total_pages' => (int)$totalPages,
                    'per_page' => (int)$perPage,
                    'total_data' => (int)$totalData
                ],
                'filters' => $filters
            ]);
        }

        $data = [
            'title' => 'Laporan Absensi',
            'menu_active' => 'laporan',
            'submenu_active' => 'absensi',
            'dataAbsensi' => $paginatedData,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_data' => $totalData
            ],
            'listJadwal' => $this->laporanModel->getListJadwalKelas(),
            'listInstruktur' => $this->laporanModel->getListInstruktur(),
            'page_title' => 'Laporan Absensi Kelas',
            'page_subtitle' => 'Rekap kehadiran siswa per kelas'
        ];

        return view('laporan/absensi', $data);
    }

    // DETAIL ABSENSI PER KELAS (LIST SISWA)
    public function detailAbsensi($hash)
    {
        // Decode hash jadi ID
        $jadwalKelasId = decode_id($hash);
        
        if (!$jadwalKelasId) {
            return redirect()->to('/laporan/absensi')->with('error', 'Data tidak ditemukan');
        }
        
        // Export handling
        $export = $this->request->getGet('export');
        if ($export === 'excel') {
            return $this->exportDetailAbsensiExcel($jadwalKelasId);
        } elseif ($export === 'pdf') {
            return $this->exportDetailAbsensiPDF($jadwalKelasId);
        }
        
        // Get info kelas
        $infoKelas = $this->laporanModel->getInfoKelas($jadwalKelasId);
        
        if (!$infoKelas) {
            return redirect()->to('laporan/absensi')->with('error', 'Kelas tidak ditemukan');
        }
        
        // Get detail absensi per siswa
        $dataSiswa = $this->laporanModel->getDetailAbsensiPerSiswa($jadwalKelasId);
        
        // Hitung statistik
        $statistik = [
            'total_siswa' => count($dataSiswa),
            'total_pertemuan' => $infoKelas['jumlah_pertemuan'],
            'pertemuan_terlaksana' => 0,
            'total_hadir' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_alpha' => 0,
            'rata_rata_kehadiran' => 0
        ];
        
        foreach ($dataSiswa as $siswa) {
            $statistik['pertemuan_terlaksana'] = max($statistik['pertemuan_terlaksana'], $siswa['total_pertemuan_terlaksana']);
            $statistik['total_hadir'] += $siswa['total_hadir'];
            $statistik['total_izin'] += $siswa['total_izin'];
            $statistik['total_sakit'] += $siswa['total_sakit'];
            $statistik['total_alpha'] += $siswa['total_alpha'];
        }
        
        if (count($dataSiswa) > 0) {
            $totalKehadiranSemua = $statistik['total_hadir'];
            $totalKemungkinan = $statistik['pertemuan_terlaksana'] * count($dataSiswa);
            if ($totalKemungkinan > 0) {
                $statistik['rata_rata_kehadiran'] = round(($totalKehadiranSemua / $totalKemungkinan) * 100, 1);
            }
        }
        
        // Pagination
        $isMobile = $this->request->getUserAgent()->isMobile();
        $perPage = $isMobile ? 5 : 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $totalData = count($dataSiswa);
        $totalPages = ceil($totalData / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($dataSiswa, $offset, $perPage);
        
        $data = [
            'title' => 'Detail Absensi Kelas',
            'menu_active' => 'laporan',
            'submenu_active' => 'absensi',
            'infoKelas' => $infoKelas,
            'dataSiswa' => $paginatedData,
            'statistik' => $statistik,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_data' => $totalData
            ],
            'jadwalKelasId' => $jadwalKelasId,
            'page_title' => 'Detail Absensi - ' . $infoKelas['nama_paket'],
            'page_subtitle' => 'Rekap kehadiran siswa kelas ' . $infoKelas['nama_paket'] . ' - ' . $infoKelas['level']
        ];
        
        return view('laporan/detail_absensi', $data);
    }

    // Export Excel Detail Absensi
    private function exportDetailAbsensiExcel($jadwalKelasId)
    {
        $infoKelas = $this->laporanModel->getInfoKelas($jadwalKelasId);
        $dataSiswa = $this->laporanModel->getDetailAbsensiPerSiswa($jadwalKelasId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'LAPORAN ABSENSI SISWA PER KELAS');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Info Kelas
        $sheet->setCellValue('A2', 'Paket: ' . $infoKelas['nama_paket'] . ' - ' . $infoKelas['level']);
        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A3', 'Instruktur: ' . $infoKelas['nama_instruktur'] . ' | Ruang: ' . $infoKelas['nama_ruang'] . ' | Jadwal: ' . $infoKelas['hari'] . ' ' . substr($infoKelas['jam_mulai'], 0, 5) . '-' . substr($infoKelas['jam_selesai'], 0, 5));
        $sheet->mergeCells('A3:I3');

        // Header Tabel
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'Nama Siswa');
        $sheet->setCellValue('C5', 'Email');
        $sheet->setCellValue('D5', 'No HP');
        $sheet->setCellValue('E5', 'Hadir');
        $sheet->setCellValue('F5', 'Izin');
        $sheet->setCellValue('G5', 'Sakit');
        $sheet->setCellValue('H5', 'Alpha');
        $sheet->setCellValue('I5', '% Kehadiran');
        
        $sheet->getStyle('A5:I5')->getFont()->setBold(true);
        $sheet->getStyle('A5:I5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Data
        $row = 6;
        $no = 1;
        foreach ($dataSiswa as $siswa) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $siswa['nama_siswa']);
            $sheet->setCellValue('C' . $row, $siswa['email']);
            $sheet->setCellValueExplicit('D' . $row, $siswa['no_hp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $row, $siswa['total_hadir']);
            $sheet->setCellValue('F' . $row, $siswa['total_izin']);
            $sheet->setCellValue('G' . $row, $siswa['total_sakit']);
            $sheet->setCellValue('H' . $row, $siswa['total_alpha']);
            $sheet->setCellValue('I' . $row, $siswa['persentase_kehadiran'] . '%');
            $row++;
        }

        // Auto width
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'Detail_Absensi_' . str_replace(' ', '_', $infoKelas['nama_paket']) . '_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Export PDF Detail Absensi
    private function exportDetailAbsensiPDF($jadwalKelasId)
    {
        $infoKelas = $this->laporanModel->getInfoKelas($jadwalKelasId);
        $dataSiswa = $this->laporanModel->getDetailAbsensiPerSiswa($jadwalKelasId);

        $html = view('laporan/pdf/detail_absensi_pdf', [
            'infoKelas' => $infoKelas,
            'dataSiswa' => $dataSiswa
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Detail_Absensi_' . str_replace(' ', '_', $infoKelas['nama_paket']) . '_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    // LAPORAN PROGRESS 
    public function progress()
    {
        $filters = [
            'jadwal_kelas_id' => $this->request->getGet('jadwal_kelas_id'),
            'instruktur_id' => $this->request->getGet('instruktur_id'),
            'status' => $this->request->getGet('status'),
            'search' => $this->request->getGet('search')
        ];

        // Export handling
        $export = $this->request->getGet('export');
        if ($export === 'excel') {
            return $this->exportProgressExcel($filters);
        } elseif ($export === 'pdf') {
            return $this->exportProgressPDF($filters);
        }

        $dataProgress = $this->laporanModel->getLaporanProgress($filters);

        // Pagination
        $perPage = 15;
        $currentPage = $this->request->getGet('page') ?? 1;
        $totalData = count($dataProgress);
        $totalPages = ceil($totalData / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($dataProgress, $offset, $perPage);

        // Encode ID sebelum kirim ke view
        $paginatedData = array_map(function($item) {
            $item['id_encoded'] = encode_id($item['id']);
            return $item;
        }, $paginatedData);

        $data = [
            'title' => 'Laporan Progress Kursus',
            'menu_active' => 'laporan',
            'submenu_active' => 'progress',
            'dataProgress' => $paginatedData,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_data' => $totalData
            ],
            'listJadwal' => $this->laporanModel->getListJadwalKelas(),
            'listInstruktur' => $this->laporanModel->getListInstruktur()
        ];

        return view('laporan/progress', $data);
    }

    // Detail Progress per Pertemuan
    public function detailProgress($hash)
    {
        // Decode hash jadi ID
        $progressKursusId = decode_id($hash);
        
        if (!$progressKursusId) {
            return redirect()->to('/laporan/progress')->with('error', 'Data tidak ditemukan');
        }
        
        // Export handling
        $export = $this->request->getGet('export');
        if ($export === 'excel') {
            return $this->exportDetailProgressExcel($progressKursusId);
        } elseif ($export === 'pdf') {
            return $this->exportDetailProgressPDF($progressKursusId);
        }
        
        // Get info progress
        $infoProgress = $this->laporanModel->getInfoProgressKursus($progressKursusId);
        
        if (!$infoProgress) {
            return redirect()->to('laporan/progress')->with('error', 'Data progress tidak ditemukan');
        }
        
        // Get detail per pertemuan
        $dataPertemuan = $this->laporanModel->getDetailProgressPertemuan($progressKursusId);
        
        // Pagination
        $isMobile = $this->request->getUserAgent()->isMobile();
        $perPage = $isMobile ? 5 : 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $totalData = count($dataPertemuan);
        $totalPages = ceil($totalData / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($dataPertemuan, $offset, $perPage);
        
        $data = [
            'title' => 'Detail Progress Kursus',
            'menu_active' => 'laporan',
            'submenu_active' => 'progress',
            'infoProgress' => $infoProgress,
            'dataPertemuan' => $paginatedData,
            'pagination' => [
                'current_page' => $currentPage,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_data' => $totalData
            ],
            'progressKursusId' => $progressKursusId,
            'page_title' => 'Detail Progress - ' . $infoProgress['nama_paket'],
            'page_subtitle' => 'Detail pertemuan kelas ' . $infoProgress['nama_paket'] . ' - ' . $infoProgress['level']
        ];
        
        return view('laporan/detail_progress', $data);
    }

    // Export Excel Detail Progress
    private function exportDetailProgressExcel($progressKursusId)
    {
        $infoProgress = $this->laporanModel->getInfoProgressKursus($progressKursusId);
        $dataPertemuan = $this->laporanModel->getDetailProgressPertemuan($progressKursusId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'DETAIL PROGRESS KURSUS');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Info Kelas
        $sheet->setCellValue('A2', 'Paket: ' . $infoProgress['nama_paket'] . ' - ' . $infoProgress['level']);
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A3', 'Instruktur: ' . $infoProgress['nama_instruktur'] . ' | Progress: ' . $infoProgress['persentase_progress'] . '%');
        $sheet->mergeCells('A3:G3');

        // Header Tabel
        $sheet->setCellValue('A5', 'Pertemuan');
        $sheet->setCellValue('B5', 'Tanggal');
        $sheet->setCellValue('C5', 'Materi');
        $sheet->setCellValue('D5', 'Catatan');
        $sheet->setCellValue('E5', 'Status');
        $sheet->setCellValue('F5', 'Instruktur');
        $sheet->setCellValue('G5', 'Waktu Input');
        
        $sheet->getStyle('A5:G5')->getFont()->setBold(true);
        $sheet->getStyle('A5:G5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE0E0E0');

        // Data
        $row = 6;
        foreach ($dataPertemuan as $pertemuan) {
            $sheet->setCellValue('A' . $row, 'Pertemuan ' . $pertemuan['pertemuan_ke']);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($pertemuan['tanggal'])));
            $sheet->setCellValue('C' . $row, $pertemuan['materi'] ?? '-');
            $sheet->setCellValue('D' . $row, $pertemuan['catatan'] ?? '-');
            $sheet->setCellValue('E' . $row, ucfirst($pertemuan['status']));
            $sheet->setCellValue('F' . $row, $pertemuan['nama_instruktur'] ?? '-');
            $sheet->setCellValue('G' . $row, date('d/m/Y H:i', strtotime($pertemuan['created_at'])));
            $row++;
        }

        // Auto width
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'Detail_Progress_' . str_replace(' ', '_', $infoProgress['nama_paket']) . '_' . date('YmdHis') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Export PDF Detail Progress
    private function exportDetailProgressPDF($progressKursusId)
    {
        $infoProgress = $this->laporanModel->getInfoProgressKursus($progressKursusId);
        $dataPertemuan = $this->laporanModel->getDetailProgressPertemuan($progressKursusId);

        $html = view('laporan/pdf/detail_progress_pdf', [
            'infoProgress' => $infoProgress,
            'dataPertemuan' => $dataPertemuan
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Detail_Progress_' . str_replace(' ', '_', $infoProgress['nama_paket']) . '_' . date('YmdHis') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
}