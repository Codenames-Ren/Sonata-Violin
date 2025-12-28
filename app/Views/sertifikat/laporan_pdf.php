<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Sertifikat</title>
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

        .badge {
            /* padding: 4px 8px;
            border-radius: 4px; */
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-green {
            /* background-color: #d1fae5; */
            color: #065f46;
        }

        .badge-yellow {
            /* background-color: #fef3c7; */
            color: #92400e;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 4px;">LAPORAN SERTIFIKAT</h1>
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
                <td style="border: none; width: 120px; padding: 4px 0;">Status</td>
                <td style="border: none; padding: 4px 0;">: <?= $statusFilter ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Tanggal Cetak</td>
                <td style="border: none; padding: 4px 0;">: <?= date('d/m/Y H:i') ?> WIB</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0; font-weight: 600;">Total Sertifikat</td>
                <td style="border: none; padding: 4px 0; font-weight: 700; color: #059669;">: <?= $totalSertifikat ?> sertifikat</td>
            </tr>
        </table>
    </div>
    
    <!-- Data Table -->
    <?php if(empty($dataSertifikat)): ?>
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <p style="font-weight: 600; margin-bottom: 8px;">Tidak ada data sertifikat</p>
            <p>Silakan ubah filter atau periode waktu</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">No</th>
                    <th style="width: 14%;">No Sertifikat</th>
                    <th style="width: 20%;">Nama Siswa</th>
                    <th style="width: 18%;">Paket</th>
                    <th style="width: 16%;">Instruktur</th>
                    <th style="width: 12%;">Tgl Lulus</th>
                    <th style="width: 16%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach($dataSertifikat as $sertifikat): 
                ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="font-size: 10px; font-family: monospace;"><?= $sertifikat['no_sertifikat'] ?></td>
                    <td>
                        <div style="font-weight: 600; margin-bottom: 2px;"><?= $sertifikat['nama_siswa'] ?></div>
                        <div style="font-size: 10px; color: #6b7280;"><?= $sertifikat['email'] ?></div>
                    </td>
                    <td>
                        <div style="font-weight: 600; margin-bottom: 2px;"><?= $sertifikat['nama_paket'] ?></div>
                        <div style="font-size: 10px; color: #6b7280;"><?= $sertifikat['level'] ?></div>
                    </td>
                    <td><?= $sertifikat['nama_instruktur'] ?></td>
                    <td><?= date('d/m/Y', strtotime($sertifikat['tanggal_lulus'])) ?></td>
                    <td style="text-align: center;">
                        <?php if($sertifikat['status'] == 'sudah_cetak'): ?>
                            <span class="badge badge-green">Sudah Cetak</span>
                        <?php else: ?>
                            <span class="badge badge-yellow">Belum Cetak</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <!-- Footer -->
    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #9ca3af;">
        <p>Dokumen ini dicetak otomatis oleh sistem - <?= date('Y') ?> Sonata Violin Course</p>
    </div>
</body>
</html>