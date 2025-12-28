<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion - Sonata Violin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: #1a1f2e;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            background: linear-gradient(to bottom, #1e2332 0%, #16191f 100%);
            position: relative;
            overflow: hidden;
        }

        .ornamental-border {
            position: absolute;
            top: 15mm;
            left: 15mm;
            right: 15mm;
            bottom: 15mm;
            border: 3px solid #b8b8b8;
        }

        .ornamental-border-inner {
            position: absolute;
            top: 20mm;
            left: 20mm;
            right: 20mm;
            bottom: 20mm;
            border: 1px solid #6d7080;
        }

        .corner-decoration {
            position: absolute;
            width: 50px;
            height: 50px;
            border: 2px solid #b8b8b8;
        }

        .corner-tl {
            top: 12mm;
            left: 12mm;
            border-right: none;
            border-bottom: none;
        }

        .corner-tr {
            top: 12mm;
            right: 12mm;
            border-left: none;
            border-bottom: none;
        }

        .corner-bl {
            bottom: 12mm;
            left: 12mm;
            border-right: none;
            border-top: none;
        }

        .corner-br {
            bottom: 12mm;
            right: 12mm;
            border-left: none;
            border-top: none;
        }

        .certificate-content {
            position: relative;
            padding: 125px 80px 25px 80px;
            text-align: center;
        }

        .academy-name {
            font-size: 38px;
            font-weight: 700;
            color: #e8e8e8;
            letter-spacing: 4px;
            margin-top: 0;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .decorative-line {
            width: 80px;
            height: 2px;
            background: #b8b8b8;
            margin: 12px auto;
        }

        .subtitle {
            font-size: 12px;
            color: #a8a8a8;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 25px;
        }

        .certificate-title {
            font-size: 42px;
            font-weight: 600;
            color: #b8b8b8;
            margin: 20px 0;
            letter-spacing: 3px;
        }

        .cert-number {
            font-size: 20px;
            color: #888;
            margin-bottom: 20px;
        }

        .awarded-text {
            font-size: 13px;
            color: #d0d0d0;
            margin-bottom: 22px;
            letter-spacing: 1px;
        }

        .recipient-name {
            font-size: 36px;
            font-weight: 700;
            color: #f0f0f0;
            border-bottom: 2px solid #b8b8b8;
            padding-bottom: 10px;
            margin: 0 auto 22px;
            display: inline-block;
            min-width: 350px;
        }

        .completion-text {
            font-size: 14px;
            color: #c8c8c8;
            line-height: 1.7;
            max-width: 550px;
            margin: 0 auto 20px;
        }

        .course-info {
            font-size: 15px;
            color: #b8b8b8;
            margin-bottom: 30px;
            font-weight: 800;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
        }

        .signature-row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .signature-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .signature-line {
            width: 180px;
            border-bottom: 2px solid #b8b8b8;
            margin: 0 auto 8px;
            height: 35px;
            text-align: center;
            padding-top: 18px;
            font-size: 15px;
            color: #b8b8b8;
        }

        .signature-label {
            font-size: 14px;
            font-weight: 600;
            color: #e8e8e8;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 10px;
            color: #a8a8a8;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="corner-decoration corner-tl"></div>
        <div class="corner-decoration corner-tr"></div>
        <div class="corner-decoration corner-bl"></div>
        <div class="corner-decoration corner-br"></div>
        <div class="ornamental-border"></div>
        <div class="ornamental-border-inner"></div>

        <div class="certificate-content">
            <h1 class="academy-name">SONATA VIOLIN</h1>
            <div class="decorative-line"></div>
            <p class="subtitle">Academy of Musical Excellence</p>

            <h2 class="certificate-title">Certificate of Completion</h2>

            <p class="cert-number">Certificate No: <?= esc($sertifikat['no_sertifikat']) ?></p>

            <p class="awarded-text">This certificate is proudly presented to</p>

            <div class="recipient-name">
                <?= esc($sertifikat['nama_siswa']) ?>
            </div>

            <p class="completion-text">
                For successfully completing the violin course and demonstrating exceptional
                dedication, skill, and passion in the art of violin performance
            </p>

            <p class="course-info">
                Class <?= esc($sertifikat['nama_paket']) ?> Level <?= esc($sertifikat['level']) ?>
            </p>

            <div class="signature-section">
                <div class="signature-row">
                    <div class="signature-cell">
                        <div class="signature-line">
                            <?php
                            $tanggal = date_create($sertifikat['tanggal_lulus']);
                            echo date_format($tanggal, 'F d, Y');
                            ?>
                        </div>
                        <p class="signature-label">Date</p>
                    </div>

                    <div class="signature-cell">
                        <div class="signature-line">Ren Kyrielight</div>
                        <p class="signature-label">Director of Sonata Violin Academy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>