<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Detail Progress Kursus<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Detail pertemuan dan materi kelas per pertemuan<?= $this->endSection() ?>
<?= $this->section('content') ?>

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#667eea',
                'primary-dark': '#5568d3',
                secondary: '#764ba2',
            }
        }
    }
}
</script>

<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
    }
    
    .gradient-border {
        position: relative;
    }
    
    .gradient-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #667eea, #764ba2);
        border-radius: 12px 12px 0 0;
    }
    
    .progress-bar {
        height: 8px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(to right, #667eea, #764ba2);
        transition: width 0.3s ease;
    }
</style>

<!-- ================= HEADER WITH INFO KELAS ================= -->
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6">
    <div class="flex flex-col gap-4">
        <!-- Back Button & Title -->
        <div class="flex items-center gap-3 mb-2">
            <a href="<?= base_url('laporan/progress') ?>" class="text-white/80 hover:text-white transition-colors">
                <i class="text-2xl fa fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-white text-2xl font-bold"><?= $infoProgress['nama_paket'] ?> - <?= $infoProgress['level'] ?></h2>
                <p class="text-white/90 text-sm mt-1">Detail progress pertemuan kelas</p>
            </div>
        </div>
        
        <!-- Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Instruktur -->
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3">
                <p class="text-xs text-white/80 mb-1">Instruktur</p>
                <p class="text-white font-bold"><?= $infoProgress['nama_instruktur'] ?></p>
            </div>
            
            <!-- Jadwal -->
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3">
                <p class="text-xs text-white/80 mb-1">Jadwal</p>
                <p class="text-white font-bold"><?= $infoProgress['hari'] ?></p>
                <p class="text-xs text-white/90"><?= substr($infoProgress['jam_mulai'], 0, 5) ?> - <?= substr($infoProgress['jam_selesai'], 0, 5) ?></p>
            </div>
            
            <!-- Ruang -->
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3">
                <p class="text-xs text-white/80 mb-1">Ruang Kelas</p>
                <p class="text-white font-bold"><?= $infoProgress['nama_ruang'] ?></p>
            </div>
            
            <!-- Progress -->
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3">
                <p class="text-xs text-white/80 mb-1">Total Progress</p>
                <p class="text-white font-bold text-2xl"><?= $infoProgress['persentase_progress'] ?>%</p>
                <p class="text-xs text-white/90"><?= $infoProgress['pertemuan_terlaksana'] ?>/<?= $infoProgress['total_pertemuan'] ?> Pertemuan</p>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="mt-2">
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $infoProgress['persentase_progress'] ?>%"></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= FLASH MESSAGES ================= -->
<?php if(session()->getFlashdata('success')): ?>
<div class="p-4 rounded-lg bg-green-50 border-l-4 border-green-500 text-green-700 mb-4 shadow-sm">
    <i class="fa fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
</div>
<?php endif ?>

<?php if(session()->getFlashdata('error')): ?>
<div class="p-4 rounded-lg bg-red-50 border-l-4 border-red-500 text-red-700 mb-4 shadow-sm">
    <i class="fa fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
</div>
<?php endif ?>

<!-- ================= STATISTIK CARDS ================= -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Pertemuan -->
    <div class="gradient-border bg-white rounded-xl shadow-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-calendar-check text-2xl text-blue-600"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase">Target</span>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1"><?= $infoProgress['total_pertemuan'] ?></p>
        <p class="text-sm text-gray-500">Total Pertemuan</p>
    </div>
    
    <!-- Terlaksana -->
    <div class="gradient-border bg-white rounded-xl shadow-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-check-double text-2xl text-green-600"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase">Selesai</span>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1"><?= $infoProgress['pertemuan_terlaksana'] ?></p>
        <p class="text-sm text-gray-500">Pertemuan Terlaksana</p>
    </div>
    
    <!-- Sisa -->
    <div class="gradient-border bg-white rounded-xl shadow-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-clock text-2xl text-orange-600"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase">Tersisa</span>
        </div>
        <p class="text-3xl font-bold text-gray-800 mb-1"><?= $infoProgress['total_pertemuan'] - $infoProgress['pertemuan_terlaksana'] ?></p>
        <p class="text-sm text-gray-500">Pertemuan Lagi</p>
    </div>
    
    <!-- Status -->
    <div class="gradient-border bg-white rounded-xl shadow-lg p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fa fa-info-circle text-2xl text-purple-600"></i>
            </div>
            <span class="text-xs font-semibold text-gray-500 uppercase">Status</span>
        </div>
        <p class="text-2xl font-bold mb-1 <?= $infoProgress['status'] == 'aktif' ? 'text-green-600' : ($infoProgress['status'] == 'selesai' ? 'text-blue-600' : 'text-gray-600') ?>">
            <?= ucfirst($infoProgress['status']) ?>
        </p>
        <p class="text-sm text-gray-500">Status Kelas</p>
    </div>
</div>

<!-- ================= EXPORT BUTTONS ================= -->
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <a href="<?= base_url('laporan/detailProgress/' . $progressKursusId . '?export=excel') ?>" 
       class="flex-1 flex items-center justify-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl">
        <i class="fa fa-file-excel text-xl"></i>
        <span>Export Excel</span>
    </a>
    
    <a href="<?= base_url('laporan/detailProgress/' . $progressKursusId . '?export=pdf') ?>" 
       class="flex-1 flex items-center justify-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-lg hover:shadow-xl">
        <i class="fa fa-file-pdf text-xl"></i>
        <span>Export PDF</span>
    </a>
</div>

<!-- ================= DESKTOP TABLE VIEW ================= -->
<div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary to-secondary text-white">
                <tr>
                    <th class="px-4 py-4 text-center text-sm font-bold">Pertemuan</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Tanggal</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Materi</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Catatan</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Status</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Input Oleh</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Waktu Input</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($dataPertemuan)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa fa-inbox text-4xl mb-3 block text-gray-300"></i>
                        <p class="font-semibold">Belum ada data pertemuan</p>
                        <p class="text-sm mt-1">Progress pertemuan akan muncul di sini</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($dataPertemuan as $pertemuan): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-primary to-secondary text-white rounded-full font-bold">
                                <?= $pertemuan['pertemuan_ke'] ?>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-semibold text-gray-800"><?= date('d/m/Y', strtotime($pertemuan['tanggal'])) ?></p>
                                <p class="text-xs text-gray-500"><?= date('l', strtotime($pertemuan['tanggal'])) ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-sm text-gray-700 font-medium"><?= $pertemuan['materi'] ?: '-' ?></p>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-sm text-gray-600 max-w-xs truncate"><?= $pertemuan['catatan'] ?: '-' ?></p>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <?php 
                            $statusClass = '';
                            $statusIcon = '';
                            switch($pertemuan['status']) {
                                case 'selesai':
                                    $statusClass = 'bg-green-100 text-green-700';
                                    $statusIcon = 'fa-check-circle';
                                    break;
                                case 'belum':
                                    $statusClass = 'bg-yellow-100 text-yellow-700';
                                    $statusIcon = 'fa-clock';
                                    break;
                                case 'dibatalkan':
                                    $statusClass = 'bg-red-100 text-red-700';
                                    $statusIcon = 'fa-times-circle';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-700';
                                    $statusIcon = 'fa-question-circle';
                            }
                            ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 <?= $statusClass ?> rounded-full text-xs font-semibold">
                                <i class="fa <?= $statusIcon ?>"></i>
                                <?= ucfirst($pertemuan['status']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <?= $pertemuan['nama_instruktur'] ?: '-' ?>
                        </td>
                        <td class="px-4 py-4">
                            <div>
                                <p class="text-sm font-medium text-gray-800"><?= date('d/m/Y', strtotime($pertemuan['created_at'])) ?></p>
                                <p class="text-xs text-gray-500"><?= date('H:i', strtotime($pertemuan['created_at'])) ?> WIB</p>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MOBILE CARD VIEW ================= -->
<div class="lg:hidden space-y-4 mb-6">
    <?php if(empty($dataPertemuan)): ?>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <i class="fa fa-inbox text-6xl text-gray-300 mb-4"></i>
        <p class="font-semibold text-gray-700 text-lg mb-2">Belum ada data pertemuan</p>
        <p class="text-sm text-gray-500">Progress pertemuan akan muncul di sini</p>
    </div>
    <?php else: ?>
        <?php foreach($dataPertemuan as $pertemuan): ?>
        <div class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-5">
                <!-- Header Card -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-xl">
                            <?= $pertemuan['pertemuan_ke'] ?>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-lg">Pertemuan <?= $pertemuan['pertemuan_ke'] ?></p>
                            <p class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($pertemuan['tanggal'])) ?></p>
                        </div>
                    </div>
                    <?php 
                    $statusClass = '';
                    $statusIcon = '';
                    switch($pertemuan['status']) {
                        case 'selesai':
                            $statusClass = 'bg-green-100 text-green-700';
                            $statusIcon = 'fa-check-circle';
                            break;
                        case 'belum':
                            $statusClass = 'bg-yellow-100 text-yellow-700';
                            $statusIcon = 'fa-clock';
                            break;
                        case 'dibatalkan':
                            $statusClass = 'bg-red-100 text-red-700';
                            $statusIcon = 'fa-times-circle';
                            break;
                        default:
                            $statusClass = 'bg-gray-100 text-gray-700';
                            $statusIcon = 'fa-question-circle';
                    }
                    ?>
                    <span class="inline-flex items-center gap-1 px-3 py-1.5 <?= $statusClass ?> rounded-full text-xs font-semibold">
                        <i class="fa <?= $statusIcon ?>"></i>
                        <?= ucfirst($pertemuan['status']) ?>
                    </span>
                </div>
                
                <!-- Materi -->
                <div class="bg-blue-50 rounded-lg p-3 mb-3">
                    <p class="text-xs text-blue-600 mb-1 font-semibold">Materi Pembelajaran</p>
                    <p class="text-sm text-blue-900 font-medium"><?= $pertemuan['materi'] ?: 'Belum ada materi' ?></p>
                </div>
                
                <!-- Catatan -->
                <div class="bg-purple-50 rounded-lg p-3 mb-3">
                    <p class="text-xs text-purple-600 mb-1 font-semibold">Catatan Instruktur</p>
                    <p class="text-sm text-purple-900"><?= $pertemuan['catatan'] ?: 'Tidak ada catatan' ?></p>
                </div>
                
                <!-- Info Bottom -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Input Oleh</p>
                        <p class="text-sm font-semibold text-gray-800"><?= $pertemuan['nama_instruktur'] ?: '-' ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Waktu Input</p>
                        <p class="text-sm font-semibold text-gray-800"><?= date('d/m/Y H:i', strtotime($pertemuan['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ================= PAGINATION ================= -->
<div class="mt-6 flex justify-center items-center gap-4">
    <button id="btnPrev" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all disabled:opacity-50 disabled:cursor-not-allowed">
        <i class="fa fa-chevron-left mr-1"></i>Prev
    </button>
    <div class="text-sm text-gray-600 font-medium">
        Halaman <span id="currentPage" class="font-bold text-primary">1</span> dari <span id="totalPages" class="font-bold">1</span>
    </div>
    <button id="btnNext" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all disabled:opacity-50 disabled:cursor-not-allowed">
        Next<i class="fa fa-chevron-right ml-1"></i>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== AUTO HIDE FLASH MESSAGES ====================
    const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
    flashMessages.forEach(msg => {
        if(!msg.classList.contains('fixed')) {
            setTimeout(() => {
                msg.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-10px)';
                setTimeout(() => msg.remove(), 500);
            }, 5000);
        }
    });
    
    // ==================== TABLE ROW ANIMATION ====================
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(10px)';
        setTimeout(() => {
            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });
    
    // ==================== MOBILE CARDS ANIMATION ====================
    const mobileCards = document.querySelectorAll('.lg\\:hidden.space-y-4 > div');
    mobileCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // ==================== STATISTIK CARDS ANIMATION ====================
    const statCards = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-4 > div');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        }, index * 80);
    });
    
    // ==================== NUMBER COUNTER ANIMATION ====================
    function animateNumber(element) {
        const finalValue = parseInt(element.textContent) || 0;
        let currentValue = 0;
        const increment = Math.max(1, Math.ceil(finalValue / 30));
        const duration = 1000;
        const stepTime = duration / (finalValue / increment);
        
        const counter = setInterval(() => {
            currentValue += increment;
            if(currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(counter);
            }
            element.textContent = Math.floor(currentValue);
        }, stepTime);
    }
    
    // Animate statistik numbers (target, terlaksana, sisa)
    const statNumbers = document.querySelectorAll('.text-3xl.font-bold.text-gray-800');
    statNumbers.forEach(num => {
        if(/^\d+$/.test(num.textContent.trim())) {
            animateNumber(num);
        }
    });
    
    // Animate progress percentage di header
    const progressPercentage = document.querySelector('.bg-white\\/20.backdrop-blur-sm .text-white.font-bold.text-2xl');
    if(progressPercentage) {
        const text = progressPercentage.textContent;
        const finalValue = parseFloat(text.replace(/[^0-9.]/g, '')) || 0;
        let currentValue = 0;
        const increment = finalValue / 50;
        const duration = 1200;
        const stepTime = duration / 50;
        
        const counter = setInterval(() => {
            currentValue += increment;
            if(currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(counter);
            }
            progressPercentage.textContent = currentValue.toFixed(0) + '%';
        }, stepTime);
    }
    
    // ==================== PROGRESS BAR ANIMATION ====================
    const progressBar = document.querySelector('.progress-fill');
    if(progressBar) {
        const targetWidth = progressBar.style.width;
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.transition = 'width 1.5s ease-out';
            progressBar.style.width = targetWidth;
        }, 500);
    }
    
    // ==================== PAGINATION ====================
    let currentPage = 1;
    let totalPages = 1;

    function getVisibleItems() {
        const isMobile = window.innerWidth < 1024;
        const rows = Array.from(document.querySelectorAll('.hidden.lg\\:block.bg-white tbody tr'));
        const cards = Array.from(document.querySelectorAll('.lg\\:hidden.space-y-4 > div'));
        
        return isMobile ? cards : rows;
    }

    function updatePaginationView() {
        const isMobile = window.innerWidth < 1024;
        const allRows = Array.from(document.querySelectorAll('.hidden.lg\\:block.bg-white tbody tr'));
        const allCards = Array.from(document.querySelectorAll('.lg\\:hidden.space-y-4 > div'));
        
        allRows.forEach(row => row.style.display = "none");
        allCards.forEach(card => card.style.display = "none");
        
        const visibleItems = getVisibleItems();
        const perPage = isMobile ? 5 : 10;

        totalPages = Math.max(1, Math.ceil(visibleItems.length / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        const startIndex = (currentPage - 1) * perPage;
        const endIndex = startIndex + perPage;
        
        visibleItems.slice(startIndex, endIndex).forEach(item => {
            item.style.display = "";
        });

        document.getElementById("currentPage").textContent = currentPage;
        document.getElementById("totalPages").textContent = totalPages;

        document.getElementById("btnPrev").disabled = currentPage === 1;
        document.getElementById("btnNext").disabled = currentPage === totalPages;
    }

    document.getElementById("btnPrev").addEventListener("click", () => {
        if (currentPage > 1) { 
            currentPage--; 
            updatePaginationView();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    document.getElementById("btnNext").addEventListener("click", () => {
        if (currentPage < totalPages) { 
            currentPage++; 
            updatePaginationView();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    window.addEventListener("resize", () => {
        currentPage = 1;
        updatePaginationView();
    });

    // Initialize pagination
    updatePaginationView();
    
    // ==================== EXPORT BUTTON ANIMATION ====================
    const exportButtons = document.querySelectorAll('a[href*="export"]');
    exportButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const btnText = this.querySelector('span');
            const btnIcon = this.querySelector('i');
            const originalText = btnText.textContent;
            
            btnIcon.classList.remove('fa-file-excel', 'fa-file-pdf');
            btnIcon.classList.add('fa-spinner', 'fa-spin');
            btnText.textContent = 'Mengunduh...';
            
            setTimeout(() => {
                btnIcon.classList.remove('fa-spinner', 'fa-spin');
                btnIcon.classList.add(originalText.includes('Excel') ? 'fa-file-excel' : 'fa-file-pdf');
                btnText.textContent = originalText;
            }, 2000);
        });
    });
    
    // Back button biarkan default behavior aja, ga usah ribet
    
    // ==================== STATUS BADGE PULSE ANIMATION ====================
    const statusBadges = document.querySelectorAll('tbody td span[class*="bg-"], .lg\\:hidden span[class*="bg-"]');
    statusBadges.forEach(badge => {
        const badgeText = badge.textContent.trim().toLowerCase();
        
        if(badgeText === 'selesai') {
            badge.style.animation = 'pulse-green 2s infinite';
        } else if(badgeText === 'belum') {
            badge.style.animation = 'pulse-yellow 2s infinite';
        } else if(badgeText === 'dibatalkan') {
            badge.style.animation = 'pulse-red 2s infinite';
        }
    });
    
    // ==================== HEADER INFO CARDS HOVER EFFECT ====================
    const headerInfoCards = document.querySelectorAll('.bg-white\\/20.backdrop-blur-sm');
    headerInfoCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.3s ease';
            this.style.backgroundColor = 'rgba(255, 255, 255, 0.3)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
        });
    });
    
    // ==================== TOOLTIP FOR TRUNCATED TEXT ====================
    const truncatedTexts = document.querySelectorAll('.truncate');
    truncatedTexts.forEach(text => {
        if(text.scrollWidth > text.clientWidth) {
            text.title = text.textContent;
            text.style.cursor = 'help';
        }
    });
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
        }
        @keyframes pulse-yellow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(234, 179, 8, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(234, 179, 8, 0); }
        }
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(102, 126, 234, 0.1), 
                        0 10px 10px -5px rgba(102, 126, 234, 0.04);
        }
    `;
    document.head.appendChild(style);
    
    console.log('✅ Detail Progress Kursus JavaScript initialized successfully!');
});
</script>

<?= $this->endSection() ?>