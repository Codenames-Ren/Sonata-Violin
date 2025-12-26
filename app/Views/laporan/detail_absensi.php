<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Detail Absensi Kelas<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Rekap kehadiran siswa per kelas<?= $this->endSection() ?>
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
</style>

<!-- ================= HEADER ================= -->
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="<?= base_url('laporan/absensi') ?>" class="text-white/80 hover:text-white transition-colors">
                    <i class="text-2xl fa fa-arrow-left"></i>
                </a>
                <h2 class="text-white text-2xl font-bold">Detail Absensi Kelas</h2>
            </div>
            <p class="text-white/90 text-sm">
                <?= $infoKelas['nama_paket'] ?> - <?= $infoKelas['level'] ?> | <?= $infoKelas['nama_instruktur'] ?>
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3 text-white">
                <p class="text-xs opacity-90">Total Siswa</p>
                <p class="text-2xl font-bold"><?= $statistik['total_siswa'] ?> Siswa</p>
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

<!-- ================= INFO KELAS CARD ================= -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fa fa-info-circle text-primary"></i>
        Informasi Kelas
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-xs text-purple-600 font-semibold mb-1">Paket Kursus</p>
            <p class="text-lg font-bold text-purple-800"><?= $infoKelas['nama_paket'] ?></p>
            <p class="text-sm text-purple-600"><?= $infoKelas['level'] ?></p>
        </div>
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-xs text-blue-600 font-semibold mb-1">Instruktur & Ruang</p>
            <p class="text-lg font-bold text-blue-800"><?= $infoKelas['nama_instruktur'] ?></p>
            <p class="text-sm text-blue-600">Ruang <?= $infoKelas['nama_ruang'] ?></p>
        </div>
        <div class="bg-orange-50 rounded-lg p-4">
            <p class="text-xs text-orange-600 font-semibold mb-1">Jadwal</p>
            <p class="text-lg font-bold text-orange-800"><?= $infoKelas['hari'] ?></p>
            <p class="text-sm text-orange-600">
                <?= substr($infoKelas['jam_mulai'], 0, 5) ?> - <?= substr($infoKelas['jam_selesai'], 0, 5) ?>
            </p>
        </div>
    </div>
</div>

<!-- ================= STATISTIK CARDS ================= -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-lg p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Siswa</p>
                <p class="text-2xl font-bold text-gray-800"><?= $statistik['total_siswa'] ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fa fa-users text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Pertemuan</p>
                <p class="text-2xl font-bold text-purple-600"><?= $statistik['pertemuan_terlaksana'] ?></p>
                <p class="text-xs text-gray-500">/ <?= $statistik['total_pertemuan'] ?>x</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fa fa-calendar-check text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Hadir</p>
                <p class="text-2xl font-bold text-green-600"><?= $statistik['total_hadir'] ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fa fa-check-circle text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Izin</p>
                <p class="text-2xl font-bold text-yellow-600"><?= $statistik['total_izin'] ?></p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fa fa-envelope text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Sakit</p>
                <p class="text-2xl font-bold text-orange-600"><?= $statistik['total_sakit'] ?></p>
            </div>
            <div class="bg-orange-100 p-3 rounded-lg">
                <i class="fa fa-heartbeat text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-5 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Alpha</p>
                <p class="text-2xl font-bold text-red-600"><?= $statistik['total_alpha'] ?></p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fa fa-times-circle text-red-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- ================= RATA-RATA KEHADIRAN CARD ================= -->
<div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm opacity-90 mb-1">Rata-rata Kehadiran Kelas</p>
            <p class="text-4xl font-bold"><?= $statistik['rata_rata_kehadiran'] ?>%</p>
            <p class="text-xs opacity-80 mt-2">
                Dari <?= $statistik['pertemuan_terlaksana'] ?> pertemuan yang telah terlaksana
            </p>
        </div>
        <div class="bg-white/20 p-4 rounded-lg">
            <i class="fa fa-chart-line text-5xl"></i>
        </div>
    </div>
</div>

<!-- ================= EXPORT BUTTONS ================= -->
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <a href="<?= base_url('laporan/detailAbsensi/' . $jadwalKelasId . '?export=excel') ?>" 
       class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
        <i class="fa fa-file-excel"></i>
        <span>Export Excel</span>
    </a>
    
    <a href="<?= base_url('laporan/detailAbsensi/' . $jadwalKelasId . '?export=pdf') ?>" 
       class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all flex items-center justify-center gap-2">
        <i class="fa fa-file-pdf"></i>
        <span>Export PDF</span>
    </a>
</div>

<!-- ================= DESKTOP TABLE VIEW ================= -->
<div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary to-secondary text-white">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-bold">No</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Nama Siswa</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Email</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">No HP</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Hadir</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Izin</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Sakit</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Alpha</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">% Kehadiran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($dataSiswa)): ?>
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa fa-inbox text-4xl mb-3 block text-gray-300"></i>
                        <p class="font-semibold">Tidak ada data siswa</p>
                        <p class="text-sm mt-1">Belum ada siswa terdaftar di kelas ini</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php 
                    $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                    foreach($dataSiswa as $siswa): 
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700"><?= $no++ ?></td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-gray-800 text-sm"><?= $siswa['nama_siswa'] ?></p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $siswa['email'] ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $siswa['no_hp'] ?></td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-green-100 text-green-700 rounded-full font-bold text-sm">
                                <?= $siswa['total_hadir'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 text-yellow-700 rounded-full font-bold text-sm">
                                <?= $siswa['total_izin'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 text-orange-700 rounded-full font-bold text-sm">
                                <?= $siswa['total_sakit'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-12 bg-red-100 text-red-700 rounded-full font-bold text-sm">
                                <?= $siswa['total_alpha'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <?php
                            $persentase = $siswa['persentase_kehadiran'];
                            $badgeClass = 'bg-gray-100 text-gray-700';
                            if ($persentase >= 90) {
                                $badgeClass = 'bg-green-100 text-green-700';
                            } elseif ($persentase >= 75) {
                                $badgeClass = 'bg-blue-100 text-blue-700';
                            } elseif ($persentase >= 60) {
                                $badgeClass = 'bg-yellow-100 text-yellow-700';
                            } elseif ($persentase >= 40) {
                                $badgeClass = 'bg-orange-100 text-orange-700';
                            } else {
                                $badgeClass = 'bg-red-100 text-red-700';
                            }
                            ?>
                            <span class="inline-flex items-center justify-center px-4 py-2 <?= $badgeClass ?> rounded-full font-bold text-sm">
                                <?= $persentase ?>%
                            </span>
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
    <?php if(empty($dataSiswa)): ?>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <i class="fa fa-inbox text-6xl text-gray-300 mb-4"></i>
        <p class="font-semibold text-gray-700 text-lg mb-2">Tidak ada data siswa</p>
        <p class="text-sm text-gray-500">Belum ada siswa terdaftar di kelas ini</p>
    </div>
    <?php else: ?>
        <?php 
        $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
        foreach($dataSiswa as $siswa): 
        ?>
        <div class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-5">
                <!-- Header Card -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-bold text-gray-800 text-lg"><?= $siswa['nama_siswa'] ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= $siswa['email'] ?></p>
                        <p class="text-xs text-gray-500"><?= $siswa['no_hp'] ?></p>
                    </div>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-mono">
                        #<?= $no++ ?>
                    </span>
                </div>
                
                <!-- Statistik Kehadiran -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-xs text-gray-600 font-semibold mb-3">Statistik Kehadiran</p>
                    <div class="grid grid-cols-4 gap-3">
                        <div class="text-center">
                            <div class="w-14 h-14 mx-auto bg-green-100 text-green-700 rounded-lg flex items-center justify-center font-bold mb-1">
                                <?= $siswa['total_hadir'] ?>
                            </div>
                            <p class="text-xs text-gray-600">Hadir</p>
                        </div>
                        <div class="text-center">
                            <div class="w-14 h-14 mx-auto bg-yellow-100 text-yellow-700 rounded-lg flex items-center justify-center font-bold mb-1">
                                <?= $siswa['total_izin'] ?>
                            </div>
                            <p class="text-xs text-gray-600">Izin</p>
                        </div>
                        <div class="text-center">
                            <div class="w-14 h-14 mx-auto bg-orange-100 text-orange-700 rounded-lg flex items-center justify-center font-bold mb-1">
                                <?= $siswa['total_sakit'] ?>
                            </div>
                            <p class="text-xs text-gray-600">Sakit</p>
                        </div>
                        <div class="text-center">
                            <div class="w-14 h-14 mx-auto bg-red-100 text-red-700 rounded-lg flex items-center justify-center font-bold mb-1">
                                <?= $siswa['total_alpha'] ?>
                            </div>
                            <p class="text-xs text-gray-600">Alpha</p>
                        </div>
                    </div>
                </div>
                
                <!-- Persentase Kehadiran -->
                <?php
                $persentase = $siswa['persentase_kehadiran'];
                $badgeClass = 'bg-gray-100 text-gray-700';
                $bgClass = 'bg-gray-50';
                if ($persentase >= 90) {
                    $badgeClass = 'bg-green-100 text-green-700';
                    $bgClass = 'bg-green-50';
                } elseif ($persentase >= 75) {
                    $badgeClass = 'bg-blue-100 text-blue-700';
                    $bgClass = 'bg-blue-50';
                } elseif ($persentase >= 60) {
                    $badgeClass = 'bg-yellow-100 text-yellow-700';
                    $bgClass = 'bg-yellow-50';
                } elseif ($persentase >= 40) {
                    $badgeClass = 'bg-orange-100 text-orange-700';
                    $bgClass = 'bg-orange-50';
                } else {
                    $badgeClass = 'bg-red-100 text-red-700';
                    $bgClass = 'bg-red-50';
                }
                ?>
                <div class="<?= $bgClass ?> rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-600 font-semibold mb-2">Persentase Kehadiran</p>
                    <span class="inline-flex items-center justify-center px-6 py-3 <?= $badgeClass ?> rounded-full font-bold text-xl">
                        <?= $persentase ?>%
                    </span>
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
    const statCards = document.querySelectorAll('.grid.grid-cols-2 > div, .grid.grid-cols-6 > div');
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
    
    // Animate statistik numbers
    const statNumbers = document.querySelectorAll('.text-2xl.font-bold:not(.text-white)');
    statNumbers.forEach(num => {
        if(/^\d+$/.test(num.textContent.trim())) {
            animateNumber(num);
        }
    });
    
    // Animate header total siswa
    const headerTotal = document.querySelector('.bg-white\\/20 .text-2xl.font-bold');
    if(headerTotal) {
        const text = headerTotal.textContent;
        const finalValue = parseInt(text.replace(/[^0-9]/g, '')) || 0;
        let currentValue = 0;
        const increment = Math.max(1, Math.ceil(finalValue / 30));
        const duration = 800;
        const stepTime = duration / (finalValue / increment);
        
        const counter = setInterval(() => {
            currentValue += increment;
            if(currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(counter);
            }
            headerTotal.textContent = Math.floor(currentValue) + ' Siswa';
        }, stepTime);
    }
    
    // Animate rata-rata kehadiran percentage
    const rataRataCard = document.querySelector('.bg-gradient-to-r.from-green-500 .text-4xl.font-bold');
    if(rataRataCard) {
        const text = rataRataCard.textContent;
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
            rataRataCard.textContent = currentValue.toFixed(1) + '%';
        }, stepTime);
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
    
    // ==================== SMOOTH SCROLL FOR BACK BUTTON ====================
    const backButton = document.querySelector('a[href*="laporan/absensi"]:not([href*="export"])');
    if(backButton && backButton.querySelector('.fa-arrow-left')) {
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    }
    
    // ==================== PERCENTAGE BADGE COLOR PULSE ====================
    const percentageBadges = document.querySelectorAll('tbody td:last-child span, .lg\\:hidden.space-y-4 .text-xl');
    percentageBadges.forEach(badge => {
        if(badge.textContent.includes('%')) {
            const percentage = parseFloat(badge.textContent);
            if(percentage >= 90) {
                badge.style.animation = 'pulse-green 2s infinite';
            } else if(percentage < 60) {
                badge.style.animation = 'pulse-red 2s infinite';
            }
        }
    });
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
        }
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        }
    `;
    document.head.appendChild(style);
});
</script>

<?= $this->endSection() ?>