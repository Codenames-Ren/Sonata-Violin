<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendaftaran</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
    <style>
        @page {
            margin: 20mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background-color: #f3f4f6;
            font-weight: 600;
            text-align: left;
            padding: 10px 8px;
            border: 1px solid #d1d5db;
        }
        
        td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-aktif {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-batal {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-selesai {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-mundur {
            background-color: #fed7aa;
            color: #92400e;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 4px;">LAPORAN PENDAFTARAN</h1>
        <p style="font-size: 18px; color: #6b7280;">Sonata Violin Course</p>
    </div>
    
    <!-- Info -->
    <div style="margin-bottom: 20px; font-size: 12px;">
        <table style="border: none;">
            <tr style="background: none;">
                <td style="border: none; width: 120px; padding: 4px 0;">Periode</td>
                <td style="border: none; padding: 4px 0;">: <?= $periode ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Tanggal Cetak</td>
                <td style="border: none; padding: 4px 0;">: <?= date('d/m/Y H:i') ?> WIB</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0; font-weight: 600;">Total Pendaftaran</td>
                <td style="border: none; padding: 4px 0; font-weight: 700; color: #2563eb;">: <?= count($dataPendaftaran) ?> Siswa</td>
            </tr>
        </table>
    </div>
    
    <!-- Data Table -->
    <?php if(empty($dataPendaftaran)): ?>
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <p style="font-weight: 600; margin-bottom: 8px;">Tidak ada data pendaftaran</p>
            <p>Silakan ubah filter atau periode waktu</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">No</th>
                    <th style="width: 12%;">No Pendaftaran</th>
                    <th style="width: 18%;">Nama</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 12%;">No HP</th>
                    <th style="width: 18%;">Paket</th>
                    <th style="width: 10%;">Tgl Daftar</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach($dataPendaftaran as $daftar): 
                    // Status badge class
                    $statusClass = '';
                    switch($daftar['status']) {
                        case 'pending':
                            $statusClass = 'status-pending';
                            break;
                        case 'aktif':
                            $statusClass = 'status-aktif';
                            break;
                        case 'batal':
                            $statusClass = 'status-batal';
                            break;
                        case 'selesai':
                            $statusClass = 'status-selesai';
                            break;
                        case 'mundur':
                            $statusClass = 'status-mundur';
                            break;
                    }
                ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="font-family: monospace; font-size: 11px;"><?= $daftar['no_pendaftaran'] ?></td>
                    <td style="font-weight: 600;"><?= $daftar['nama'] ?></td>
                    <td style="font-size: 11px;"><?= $daftar['email'] ?></td>
                    <td style="font-size: 11px;"><?= $daftar['no_hp'] ?></td>
                    <td>
                        <div style="font-weight: 600; margin-bottom: 2px;"><?= $daftar['nama_paket'] ?></div>
                        <div style="font-size: 10px; color: #6b7280;"><?= $daftar['level'] ?></div>
                    </td>
                    <td><?= date('d/m/Y', strtotime($daftar['tanggal_daftar'])) ?></td>
                    <td style="text-align: center;">
                        <span class="status-badge <?= $statusClass ?>">
                            <?= ucfirst($daftar['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Summary Statistics -->
        <div style="margin-top: 20px; padding: 12px; background-color: #f3f4f6; border-radius: 8px;">
            <table style="border: none; font-size: 12px;">
                <tr style="background: none;">
                    <td style="border: none; padding: 4px; width: 25%;">
                        <strong>Total:</strong> <?= count($dataPendaftaran) ?> siswa
                    </td>
                    <td style="border: none; padding: 4px; width: 25%;">
                        <strong>Pending:</strong> <?= count(array_filter($dataPendaftaran, fn($d) => $d['status'] == 'pending')) ?>
                    </td>
                    <td style="border: none; padding: 4px; width: 25%;">
                        <strong>Aktif:</strong> <?= count(array_filter($dataPendaftaran, fn($d) => $d['status'] == 'aktif')) ?>
                    </td>
                    <td style="border: none; padding: 4px; width: 25%;">
                        <strong>Batal:</strong> <?= count(array_filter($dataPendaftaran, fn($d) => $d['status'] == 'batal')) ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
    
    <!-- Footer -->
    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #9ca3af;">
        <p>Dokumen ini dicetak otomatis oleh sistem - <?= date('Y') ?> Sonata Violin Course</p>
    </div>
</body>
</html>