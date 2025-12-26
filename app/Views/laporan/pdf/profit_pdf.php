<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Profit</title>
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
    </style>
</head>
<body>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 4px;">LAPORAN PROFIT</h1>
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
                <td style="border: none; padding: 4px 0; font-weight: 600;">Total Profit</td>
                <td style="border: none; padding: 4px 0; font-weight: 700; color: #059669;">: Rp <?= number_format($totalProfit, 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>
    
    <!-- Data Table -->
    <?php if(empty($dataProfit)): ?>
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <p style="font-weight: 600; margin-bottom: 8px;">Tidak ada data profit</p>
            <p>Silakan ubah filter atau periode waktu</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 15%;">No Pendaftaran</th>
                    <th style="width: 23%;">Nama Siswa</th>
                    <th style="width: 25%;">Paket</th>
                    <th style="width: 17%; text-align: right;">Nominal</th>
                    <th style="width: 15%;">Tanggal Approve</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach($dataProfit as $profit): 
                ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td><?= $profit['no_pendaftaran'] ?></td>
                    <td><?= $profit['nama_siswa'] ?></td>
                    <td><?= $profit['nama_paket'] ?> - <?= $profit['level'] ?></td>
                    <td style="text-align: right; font-weight: 600;">Rp <?= number_format($profit['nominal'], 0, ',', '.') ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($profit['created_at'])) ?></td>
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