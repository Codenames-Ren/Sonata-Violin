<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Absensi Kelas</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
    <style>
        @page {
            margin: 20mm;
            size: landscape;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
        
        .stat-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
        }
        
        .badge-hadir {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-izin {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-sakit {
            background-color: #fed7aa;
            color: #92400e;
        }
        
        .badge-alpha {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .percentage-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        
        .percentage-excellent {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .percentage-good {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .percentage-fair {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .percentage-poor {
            background-color: #fed7aa;
            color: #92400e;
        }
        
        .percentage-bad {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 4px;">DETAIL ABSENSI KELAS</h1>
        <p style="font-size: 18px; color: #6b7280;">Sonata Violin Course</p>
    </div>
    
    <!-- Info Kelas -->
    <div style="margin-bottom: 20px; font-size: 12px;">
        <table style="border: none;">
            <tr style="background: none;">
                <td style="border: none; width: 140px; padding: 4px 0;">Paket Kursus</td>
                <td style="border: none; padding: 4px 0; font-weight: 600;">: <?= $infoKelas['nama_paket'] ?> - <?= $infoKelas['level'] ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Instruktur</td>
                <td style="border: none; padding: 4px 0;">: <?= $infoKelas['nama_instruktur'] ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Ruang Kelas</td>
                <td style="border: none; padding: 4px 0;">: <?= $infoKelas['nama_ruang'] ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Jadwal</td>
                <td style="border: none; padding: 4px 0;">: <?= $infoKelas['hari'] ?>, <?= substr($infoKelas['jam_mulai'], 0, 5) ?> - <?= substr($infoKelas['jam_selesai'], 0, 5) ?> WIB</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Jumlah Pertemuan</td>
                <td style="border: none; padding: 4px 0;">: <?= $infoKelas['jumlah_pertemuan'] ?>x pertemuan</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Tanggal Cetak</td>
                <td style="border: none; padding: 4px 0;">: <?= date('d/m/Y H:i') ?> WIB</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0; font-weight: 600;">Total Siswa</td>
                <td style="border: none; padding: 4px 0; font-weight: 700; color: #2563eb;">: <?= count($dataSiswa) ?> Siswa</td>
            </tr>
        </table>
    </div>
    
    <!-- Data Table -->
    <?php if(empty($dataSiswa)): ?>
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <p style="font-weight: 600; margin-bottom: 8px;">Tidak ada data siswa</p>
            <p>Belum ada siswa terdaftar di kelas ini</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">No</th>
                    <th style="width: 20%;">Nama Siswa</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 12%;">No HP</th>
                    <th style="width: 8%; text-align: center;">Hadir</th>
                    <th style="width: 8%; text-align: center;">Izin</th>
                    <th style="width: 8%; text-align: center;">Sakit</th>
                    <th style="width: 8%; text-align: center;">Alpha</th>
                    <th style="width: 12%; text-align: center;">% Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach($dataSiswa as $siswa): 
                    // Percentage class
                    $persentase = $siswa['persentase_kehadiran'];
                    $percentageClass = 'percentage-bad';
                    if ($persentase >= 90) {
                        $percentageClass = 'percentage-excellent';
                    } elseif ($persentase >= 75) {
                        $percentageClass = 'percentage-good';
                    } elseif ($persentase >= 60) {
                        $percentageClass = 'percentage-fair';
                    } elseif ($persentase >= 40) {
                        $percentageClass = 'percentage-poor';
                    }
                ?>
                <tr>
                    <td style="text-align: center;"><?= $no++ ?></td>
                    <td style="font-weight: 600;"><?= $siswa['nama_siswa'] ?></td>
                    <td style="font-size: 10px;"><?= $siswa['email'] ?></td>
                    <td style="font-size: 10px;"><?= $siswa['no_hp'] ?></td>
                    <td style="text-align: center;">
                        <span class="stat-badge badge-hadir"><?= $siswa['total_hadir'] ?></span>
                    </td>
                    <td style="text-align: center;">
                        <span class="stat-badge badge-izin"><?= $siswa['total_izin'] ?></span>
                    </td>
                    <td style="text-align: center;">
                        <span class="stat-badge badge-sakit"><?= $siswa['total_sakit'] ?></span>
                    </td>
                    <td style="text-align: center;">
                        <span class="stat-badge badge-alpha"><?= $siswa['total_alpha'] ?></span>
                    </td>
                    <td style="text-align: center;">
                        <span class="percentage-badge <?= $percentageClass ?>">
                            <?= $persentase ?>%
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
                    <td style="border: none; padding: 4px; width: 16.66%; font-weight: 600;">
                        <strong>Total Siswa:</strong> <?= count($dataSiswa) ?> siswa
                    </td>
                    <td style="border: none; padding: 4px; width: 16.66%;">
                        <strong>Total Hadir:</strong> <?= array_sum(array_column($dataSiswa, 'total_hadir')) ?>
                    </td>
                    <td style="border: none; padding: 4px; width: 16.66%;">
                        <strong>Total Izin:</strong> <?= array_sum(array_column($dataSiswa, 'total_izin')) ?>
                    </td>
                    <td style="border: none; padding: 4px; width: 16.66%;">
                        <strong>Total Sakit:</strong> <?= array_sum(array_column($dataSiswa, 'total_sakit')) ?>
                    </td>
                    <td style="border: none; padding: 4px; width: 16.66%;">
                        <strong>Total Alpha:</strong> <?= array_sum(array_column($dataSiswa, 'total_alpha')) ?>
                    </td>
                    <td style="border: none; padding: 4px; width: 16.66%; font-weight: 600; color: #065f46;">
                        <?php
                        $totalHadir = array_sum(array_column($dataSiswa, 'total_hadir'));
                        $totalPertemuan = 0;
                        foreach($dataSiswa as $s) {
                            $totalPertemuan = max($totalPertemuan, $s['total_pertemuan_terlaksana']);
                        }
                        $rataRata = $totalPertemuan > 0 && count($dataSiswa) > 0 
                            ? round(($totalHadir / ($totalPertemuan * count($dataSiswa))) * 100, 1) 
                            : 0;
                        ?>
                        <strong>Rata-rata:</strong> <?= $rataRata ?>%
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