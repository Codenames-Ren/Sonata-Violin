<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Progress Kursus</title>
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
        

        
        .progress-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        
        .progress-excellent {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .progress-good {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .progress-fair {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .progress-poor {
            background-color: #fed7aa;
            color: #92400e;
        }
        
        .progress-bad {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 4px;">DETAIL PROGRESS KURSUS</h1>
        <p style="font-size: 18px; color: #6b7280;">Sonata Violin Course</p>
    </div>
    
    <!-- Info Kelas -->
    <div style="margin-bottom: 20px; font-size: 12px;">
        <table style="border: none;">
            <tr style="background: none;">
                <td style="border: none; width: 140px; padding: 4px 0;">Paket Kursus</td>
                <td style="border: none; padding: 4px 0; font-weight: 600;">: <?= $infoProgress['nama_paket'] ?> - <?= $infoProgress['level'] ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Instruktur</td>
                <td style="border: none; padding: 4px 0;">: <?= $infoProgress['nama_instruktur'] ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Ruang Kelas</td>
                <td style="border: none; padding: 4px 0;">: <?= $infoProgress['nama_ruang'] ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Jadwal</td>
                <td style="border: none; padding: 4px 0;">: <?= $infoProgress['hari'] ?>, <?= substr($infoProgress['jam_mulai'], 0, 5) ?> - <?= substr($infoProgress['jam_selesai'], 0, 5) ?> WIB</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Status Kelas</td>
                <td style="border: none; padding: 4px 0;">: <?= ucfirst($infoProgress['status']) ?></td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0;">Tanggal Cetak</td>
                <td style="border: none; padding: 4px 0;">: <?= date('d/m/Y H:i') ?> WIB</td>
            </tr>
            <tr style="background: none;">
                <td style="border: none; padding: 4px 0; font-weight: 600;">Progress Kelas</td>
                <td style="border: none; padding: 4px 0; font-weight: 700; color: #2563eb;">
                    : <?= $infoProgress['pertemuan_terlaksana'] ?>/<?= $infoProgress['total_pertemuan'] ?> Pertemuan 
                    (<?= $infoProgress['persentase_progress'] ?>%)
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Summary Statistics -->
    <div style="margin-bottom: 20px; padding: 12px; background-color: #f3f4f6; border-radius: 8px;">
        <table style="border: none; font-size: 12px;">
            <tr style="background: none;">
                <td style="border: none; padding: 4px; width: 25%; font-weight: 600;">
                    <strong>Total Pertemuan:</strong> <?= $infoProgress['total_pertemuan'] ?> pertemuan
                </td>
                <td style="border: none; padding: 4px; width: 25%; font-weight: 600;">
                    <strong>Terlaksana:</strong> <?= $infoProgress['pertemuan_terlaksana'] ?> pertemuan
                </td>
                <td style="border: none; padding: 4px; width: 25%; font-weight: 600;">
                    <strong>Sisa:</strong> <?= $infoProgress['total_pertemuan'] - $infoProgress['pertemuan_terlaksana'] ?> pertemuan
                </td>
                <td style="border: none; padding: 4px; width: 25%; font-weight: 600;">
                    <strong>Progress:</strong> <?= $infoProgress['persentase_progress'] ?>%
                </td>
            </tr>
        </table>
    </div>
    
    <!-- Data Table -->
    <?php if(empty($dataPertemuan)): ?>
        <div style="text-align: center; padding: 40px; color: #9ca3af;">
            <p style="font-weight: 600; margin-bottom: 8px;">Tidak ada data pertemuan</p>
            <p>Belum ada pertemuan yang tercatat untuk kelas ini</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 6%; text-align: center;">Pertemuan</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 25%;">Materi</th>
                    <th style="width: 30%;">Catatan</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    <th style="width: 12%;">Input Oleh</th>
                    <th style="width: 15%;">Waktu Input</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($dataPertemuan as $pertemuan): ?>
                <tr>
                    <td style="text-align: center; font-weight: 700; font-size: 14px;">
                        <?= $pertemuan['pertemuan_ke'] ?>
                    </td>
                    <td style="font-weight: 600;">
                        <?= date('d/m/Y', strtotime($pertemuan['tanggal'])) ?>
                        <br>
                        <span style="font-size: 10px; color: #6b7280;">
                            <?= date('l', strtotime($pertemuan['tanggal'])) ?>
                        </span>
                    </td>
                    <td style="font-size: 11px;">
                        <?= $pertemuan['materi'] ?: '-' ?>
                    </td>
                    <td style="font-size: 10px; color: #4b5563;">
                        <?= $pertemuan['catatan'] ?: '-' ?>
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                        <?= ucfirst($pertemuan['status']) ?>
                    </td>
                    <td style="font-size: 10px;">
                        <?= $pertemuan['nama_instruktur'] ?: '-' ?>
                    </td>
                    <td style="font-size: 10px;">
                        <?= date('d/m/Y', strtotime($pertemuan['created_at'])) ?>
                        <br>
                        <span style="color: #6b7280;">
                            <?= date('H:i', strtotime($pertemuan['created_at'])) ?> WIB
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Bottom Summary -->
        <div style="margin-top: 20px; padding: 12px; background-color: #f3f4f6; border-radius: 8px;">
            <table style="border: none; font-size: 12px;">
                <tr style="background: none;">
                    <td style="border: none; padding: 4px; width: 50%; font-weight: 600;">
                        <strong>Catatan:</strong> Dokumen ini menampilkan detail <?= count($dataPertemuan) ?> pertemuan yang sudah dijadwalkan dari total <?= $infoProgress['total_pertemuan'] ?> pertemuan.
                    </td>
                    <td style="border: none; padding: 4px; width: 50%; font-weight: 600; text-align: right;">
                        <strong>Status Progress:</strong> <?= $infoProgress['pertemuan_terlaksana'] ?> pertemuan terlaksana, <?= $infoProgress['total_pertemuan'] - $infoProgress['pertemuan_terlaksana'] ?> pertemuan tersisa
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