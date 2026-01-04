<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Preview Sertifikat<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Lihat preview sertifikat sebelum dicetak<?= $this->endSection() ?>
<?= $this->section('content') ?>

<script src="https://cdn.tailwindcss.com"></script>

<style>
    .certificate-preview {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        aspect-ratio: 297 / 210;
        background: linear-gradient(to bottom, #1e2332 0%, #16191f 100%);
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
        border-radius: 8px;
    }
    
    /* Mobile: Bikin landscape & scrollable */
    @media (max-width: 1023px) {
        .certificate-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .certificate-preview {
            min-width: 800px;
            max-width: none;
        }
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        .certificate-preview, .certificate-preview * {
            visibility: visible;
        }
        .certificate-preview {
            position: absolute;
            left: 0;
            top: 0;
            width: 297mm;
            height: 210mm;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<!-- BACK & ACTION BUTTONS -->
<div class="no-print mb-6 flex flex-col md:flex-row gap-3 justify-between items-center">
    <a href="<?= base_url('sertifikat') ?>" 
       class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:border-blue-500 hover:text-blue-500 transition-all">
        <i class="fa fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
    
    <div class="w-full md:w-auto flex flex-col md:flex-row gap-3">
        <a href="<?= base_url('sertifikat/cetak/' . encode_id($sertifikat['id'])) ?>" 
        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
            <i class="fa fa-download"></i>
            <span>Download Sertifikat</span>
        </a>
    </div>
</div>

<!-- INFO CARD -->
<div class="no-print bg-white rounded-xl shadow-lg p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <p class="text-xs text-gray-500 mb-1">Nomor Sertifikat</p>
            <p class="font-bold text-gray-800"><?= $sertifikat['no_sertifikat'] ?></p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Nama Siswa</p>
            <p class="font-bold text-gray-800"><?= $sertifikat['nama_siswa'] ?></p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Tanggal Lulus</p>
            <p class="font-bold text-gray-800"><?= date('d F Y', strtotime($sertifikat['tanggal_lulus'])) ?></p>
        </div>
    </div>
</div>

<!-- MOBILE HINT -->
<div class="no-print lg:hidden bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-4 text-sm text-blue-700">
    <i class="fa fa-info-circle mr-2"></i>
    <span>Geser ke samping untuk melihat seluruh sertifikat</span>
</div>

<!-- CERTIFICATE PREVIEW -->
<div class="bg-gray-100 rounded-xl shadow-2xl p-4 md:p-8 mb-6 certificate-container">
    <div class="certificate-preview">
        <!-- Corner Decorations -->
        <div style="position: absolute; top: 12mm; left: 12mm; width: 50px; height: 50px; border: 2px solid #b8b8b8; border-right: none; border-bottom: none;"></div>
        <div style="position: absolute; top: 12mm; right: 12mm; width: 50px; height: 50px; border: 2px solid #b8b8b8; border-left: none; border-bottom: none;"></div>
        <div style="position: absolute; bottom: 12mm; left: 12mm; width: 50px; height: 50px; border: 2px solid #b8b8b8; border-right: none; border-top: none;"></div>
        <div style="position: absolute; bottom: 12mm; right: 12mm; width: 50px; height: 50px; border: 2px solid #b8b8b8; border-left: none; border-top: none;"></div>
        
        <!-- Ornamental Border -->
        <div style="position: absolute; top: 15mm; left: 15mm; right: 15mm; bottom: 15mm; border: 3px solid #b8b8b8; pointer-events: none;">
            <div style="position: absolute; top: 5px; left: 5px; right: 5px; bottom: 5px; border: 1px solid #6d7080;"></div>
        </div>

        <!-- Certificate Content -->
        <div style="position: relative; z-index: 1; padding: 8% 10% 4% 10%; text-align: center;">
            
            <!-- Academy Name -->
            <h1 style="font-family: Georgia, serif; font-size: 32px; font-weight: 700; color: #e8e8e8; letter-spacing: 3px; margin-bottom: 6px; text-transform: uppercase;">
                SONATA VIOLIN
            </h1>
            
            <!-- Decorative Line -->
            <div style="width: 70px; height: 2px; background: #b8b8b8; margin: 10px auto;"></div>
            
            <!-- Subtitle -->
            <p style="font-family: Georgia, serif; font-size: 10px; color: #a8a8a8; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 18px;">
                Academy of Musical Excellence
            </p>

            <!-- Certificate Title -->
            <h2 style="font-family: Georgia, serif; font-size: 34px; font-weight: 600; color: #b8b8b8; margin: 14px 0; letter-spacing: 2px;">
                Certificate of Completion
            </h2>

            <!-- Certificate Number -->
            <p style="font-size: 9px; color: #888; margin-bottom: 14px;">
                Certificate No: <?= $sertifikat['no_sertifikat'] ?>
            </p>

            <!-- Awarded Text -->
            <p style="font-size: 11px; color: #d0d0d0; margin-bottom: 16px; letter-spacing: 1px;">
                This certificate is proudly presented to
            </p>

            <!-- Recipient Name -->
            <div style="font-family: Georgia, serif; font-size: 26px; font-weight: 700; color: #f0f0f0; border-bottom: 2px solid #b8b8b8; padding-bottom: 8px; margin: 0 auto 16px; min-width: 280px; display: inline-block;">
                <?= $sertifikat['nama_siswa'] ?>
            </div>

            <!-- Completion Text -->
            <p style="font-size: 11px; color: #c8c8c8; line-height: 1.5; max-width: 480px; margin: 0 auto 14px;">
                For successfully completing the violin course and demonstrating exceptional
                dedication, skill, and passion in the art of violin performance
            </p>

            <!-- Course Info -->
            <p style="font-size: 12px; color: #b8b8b8; margin-bottom: 18px; font-weight: 800;">
               Class <?= $sertifikat['nama_paket'] ?>  Level <?= $sertifikat['level'] ?>
            </p>

            <!-- Signature Section - FIXED: Naik & Responsive -->
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 18px; padding: 0 12%; gap: 20px;">
                <!-- Date (Left) -->
                <div style="flex: 1; text-align: center;">
                    <div style="border-bottom: 2px solid #b8b8b8; margin: 0 auto 6px; padding-bottom: 4px; min-height: 26px; display: flex; align-items: flex-end; justify-content: center;">
                        <p style="font-size: 10px; font-weight: 600; color: #e8e8e8; white-space: nowrap;">
                            <?= date('F d, Y', strtotime($sertifikat['tanggal_lulus'])) ?>
                        </p>
                    </div>
                    <p style="font-size: 10px; font-weight: 600; color: #e8e8e8; margin-top: 4px;">Date</p>
                </div>

                <!-- Signature (Right) -->
                <div style="flex: 1; text-align: center;">
                    <div style="border-bottom: 2px solid #b8b8b8; margin: 0 auto 6px; padding-bottom: 4px; min-height: 26px; display: flex; align-items: flex-end; justify-content: center;">
                        <p style="font-size: 10px; font-weight: 600; color: #e8e8e8; white-space: nowrap;">Ren Kyrielight</p>
                    </div>
                    <p style="font-size: 9px; font-weight: 600; color: #e8e8e8; margin-top: 4px; line-height: 1.2;">Director of Sonata<br>Violin Academy</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HINT -->
<div class="no-print text-center text-sm text-gray-500 mb-8">
    <i class="fa fa-info-circle mr-2"></i>
    <span>Klik "Download PDF" untuk menyimpan sertifikat dalam format PDF berkualitas tinggi</span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    const certificate = document.querySelector('.certificate-preview');
    if (certificate) {
        certificate.style.opacity = '0';
        certificate.style.transform = 'scale(0.95)';
        certificate.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            certificate.style.opacity = '1';
            certificate.style.transform = 'scale(1)';
        }, 100);
    }
});
</script>

<?= $this->endSection() ?>