<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Laporan<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Pilih jenis laporan yang ingin dilihat<?= $this->endSection() ?>
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
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }
    
    .icon-wrapper {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
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
    <div>
        <h2 class="text-white text-2xl font-bold">Laporan Sistem</h2>
        <p class="text-white/90 text-sm mt-1">
            Pilih jenis laporan yang ingin Anda lihat atau unduh
        </p>
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

<!-- ================= INFO BOX ================= -->
<div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6 shadow-sm">
    <div class="flex items-start">
        <i class="fa fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
        <div>
            <h3 class="font-bold text-blue-800 mb-1">Informasi Laporan</h3>
            <p class="text-sm text-blue-700">
                Semua laporan dapat difilter berdasarkan tanggal, paket, atau kriteria lainnya. 
                Anda juga dapat mengunduh laporan dalam format <strong>Excel</strong> atau <strong>PDF</strong>.
            </p>
        </div>
    </div>
</div>

<!-- ================= MENU GRID LAPORAN ================= -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- LAPORAN PROFIT -->
    <a href="<?= base_url('laporan/profit') ?>" 
       class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden group">
        <div class="p-6">
            <!-- Icon -->
            <div class="icon-wrapper w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                <i class="fa fa-chart-line text-white text-2xl"></i>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-primary transition-colors">
                Laporan Profit
            </h3>
            
            <!-- Description -->
            <p class="text-sm text-gray-600 mb-4">
                Lihat total pemasukan dari pembayaran kursus yang telah disetujui
            </p>
            
            <!-- Features List -->
            <ul class="space-y-2 text-xs text-gray-500">
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-green-500 mr-2"></i>
                    Filter berdasarkan periode
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-green-500 mr-2"></i>
                    Total profit terakumulasi
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-green-500 mr-2"></i>
                    Export Excel & PDF
                </li>
            </ul>
            
            <!-- Button -->
            <div class="mt-6 flex items-center text-primary font-semibold text-sm group-hover:translate-x-2 transition-transform">
                Lihat Laporan <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </div>
    </a>

    <!-- LAPORAN PENDAFTARAN -->
    <a href="<?= base_url('laporan/pendaftaran') ?>" 
       class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden group">
        <div class="p-6">
            <div class="icon-wrapper w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                <i class="fa fa-user-plus text-white text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-primary transition-colors">
                Laporan Pendaftaran
            </h3>
            
            <p class="text-sm text-gray-600 mb-4">
                Data pendaftaran siswa baru per periode batch kursus
            </p>
            
            <ul class="space-y-2 text-xs text-gray-500">
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-blue-500 mr-2"></i>
                    Jumlah pendaftar per batch
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-blue-500 mr-2"></i>
                    Statistik bulanan
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-blue-500 mr-2"></i>
                    Filter by status & paket
                </li>
            </ul>
            
            <div class="mt-6 flex items-center text-primary font-semibold text-sm group-hover:translate-x-2 transition-transform">
                Lihat Laporan <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </div>
    </a>

    <!-- LAPORAN ABSENSI -->
    <a href="<?= base_url('laporan/absensi') ?>" 
       class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden group">
        <div class="p-6">
            <div class="icon-wrapper w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                <i class="fa fa-clipboard-check text-white text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-primary transition-colors">
                Laporan Absensi Kelas
            </h3>
            
            <p class="text-sm text-gray-600 mb-4">
                Rekap kehadiran siswa di setiap kelas dan pertemuan
            </p>
            
            <ul class="space-y-2 text-xs text-gray-500">
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-purple-500 mr-2"></i>
                    Detail hadir, izin, sakit, alpha
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-purple-500 mr-2"></i>
                    Filter by kelas & instruktur
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-purple-500 mr-2"></i>
                    Persentase kehadiran
                </li>
            </ul>
            
            <div class="mt-6 flex items-center text-primary font-semibold text-sm group-hover:translate-x-2 transition-transform">
                Lihat Laporan <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </div>
    </a>

    <!-- LAPORAN PROGRESS KURSUS -->
    <a href="<?= base_url('laporan/progress') ?>" 
       class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden group">
        <div class="p-6">
            <div class="icon-wrapper w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                <i class="fa fa-tasks text-white text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-primary transition-colors">
                Laporan Progress Kursus
            </h3>
            
            <p class="text-sm text-gray-600 mb-4">
                Tracking progress pembelajaran setiap kelas kursus
            </p>
            
            <ul class="space-y-2 text-xs text-gray-500">
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-orange-500 mr-2"></i>
                    Persentase penyelesaian
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-orange-500 mr-2"></i>
                    Total pertemuan terlaksana
                </li>
                <li class="flex items-center">
                    <i class="fa fa-check-circle text-orange-500 mr-2"></i>
                    Status kelas (aktif/selesai)
                </li>
            </ul>
            
            <div class="mt-6 flex items-center text-primary font-semibold text-sm group-hover:translate-x-2 transition-transform">
                Lihat Laporan <i class="fa fa-arrow-right ml-2"></i>
            </div>
        </div>
    </a>

</div>

<!-- ================= STATISTIK CEPAT (OPTIONAL) ================= -->
<div class="mt-8 bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
        <i class="fa fa-chart-bar text-primary mr-2"></i>
        Ringkasan Cepat
    </h3>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Profit -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500 stat-card">
            <p class="text-xs text-gray-600 mb-1">Total Profit Bulan Ini</p>
            <p id="statProfit" class="text-2xl font-bold text-green-700">
                <i class="fa fa-spinner fa-spin"></i>
            </p>
            <p class="text-xs text-gray-500 mt-1">
                <i class="fa fa-arrow-up text-green-600"></i> 
                <span id="statProfitPercent" class="font-semibold">0%</span>
            </p>
        </div>
        
        <!-- Total Pendaftaran -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500 stat-card">
            <p class="text-xs text-gray-600 mb-1">Pendaftaran Bulan Ini</p>
            <p id="statPendaftaran" class="text-2xl font-bold text-blue-700">
                <i class="fa fa-spinner fa-spin"></i>
            </p>
            <p class="text-xs text-gray-500 mt-1">Siswa baru</p>
        </div>
        
        <!-- Kelas Aktif -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border-l-4 border-purple-500 stat-card">
            <p class="text-xs text-gray-600 mb-1">Kelas Aktif</p>
            <p id="statKelas" class="text-2xl font-bold text-purple-700">
                <i class="fa fa-spinner fa-spin"></i>
            </p>
            <p class="text-xs text-gray-500 mt-1">Sedang berjalan</p>
        </div>
        
        <!-- Sertifikat Belum Cetak -->
        <div class="bg-gradient-to-br from-pink-50 to-pink-100 p-4 rounded-lg border-l-4 border-pink-500 stat-card">
            <p class="text-xs text-gray-600 mb-1">Sertifikat Pending</p>
            <p id="statSertifikat" class="text-2xl font-bold text-pink-700">
                <i class="fa fa-spinner fa-spin"></i>
            </p>
            <p class="text-xs text-gray-500 mt-1">Belum dicetak</p>
        </div>
    </div>
</div>

<script>
    // ==================== ANIMATE ON SCROLL ====================
    document.addEventListener('DOMContentLoaded', function() {
        
        // Animate cards on load
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 50);
            }, index * 100);
        });
        
        // Animate stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                card.style.transition = 'all 0.4s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 50);
            }, 600 + (index * 100));
        });
        
        loadStatistik();
    });
    
    // ==================== LOAD STATISTIK VIA AJAX ====================
    function loadStatistik() {
        fetch('<?= base_url('laporan/get-statistik') ?>')
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                document.getElementById('statProfit').innerHTML = 
                    'Rp ' + formatRupiah(data.totalProfit || 0);
                
                const profitPercent = data.profitPercent || 0;
                const profitPercentEl = document.getElementById('statProfitPercent');
                const profitIcon = profitPercentEl.previousElementSibling;
                
                profitPercentEl.textContent = Math.abs(profitPercent) + '%';
                
                // Ganti icon & warna berdasarkan growth
                if (profitPercent > 0) {
                    profitIcon.className = 'fa fa-arrow-up text-green-600';
                    profitPercentEl.className = 'font-semibold text-green-600';
                } else if (profitPercent < 0) {
                    profitIcon.className = 'fa fa-arrow-down text-red-600';
                    profitPercentEl.className = 'font-semibold text-red-600';
                } else {
                    profitIcon.className = 'fa fa-minus text-gray-600';
                    profitPercentEl.className = 'font-semibold text-gray-600';
                }
                
                document.getElementById('statPendaftaran').textContent = 
                    data.totalPendaftaran || 0;
                
                document.getElementById('statKelas').textContent = 
                    data.totalKelas || 0;
                
                document.getElementById('statSertifikat').textContent = 
                    data.totalSertifikat || 0;
                    
                // Animate
                animateValue('statPendaftaran', 0, data.totalPendaftaran || 0, 800);
                animateValue('statKelas', 0, data.totalKelas || 0, 800);
                animateValue('statSertifikat', 0, data.totalSertifikat || 0, 800);
            })
            .catch(error => {
                console.warn('AJAX failed, using fallback data from PHP:', error);
                
                const totalProfit = <?= $totalProfitBulanIni ?>;
                const totalPendaftaran = <?= $totalPendaftaranBulanIni ?>;
                const totalKelas = <?= $totalKelasAktif ?>;
                const totalSertifikat = <?= $totalSertifikatPending ?>;
                
                document.getElementById('statProfit').innerHTML = 
                    'Rp ' + formatRupiah(totalProfit);
                document.getElementById('statProfitPercent').textContent = '0%';
                document.getElementById('statPendaftaran').textContent = totalPendaftaran;
                document.getElementById('statKelas').textContent = totalKelas;
                document.getElementById('statSertifikat').textContent = totalSertifikat;
                
                animateValue('statPendaftaran', 0, totalPendaftaran, 800);
                animateValue('statKelas', 0, totalKelas, 800);
                animateValue('statSertifikat', 0, totalSertifikat, 800);
            });
    }

    // ==================== ANIMATE NUMBER COUNT UP ====================
    function animateValue(id, start, end, duration) {
        const obj = document.getElementById(id);
        if (!obj) return;
        
        const range = end - start;
        if (range === 0) {
            obj.textContent = end;
            return;
        }
        
        const increment = end > start ? 1 : -1;
        const stepTime = Math.abs(Math.floor(duration / range));
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            obj.textContent = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }

    // ==================== FORMAT RUPIAH ====================
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // ==================== SMOOTH SCROLL ====================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ==================== CARD CLICK ANIMATION ====================
    const cardLinks = document.querySelectorAll('.card-hover');
    cardLinks.forEach(card => {
        card.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(102, 126, 234, 0.3)';
            ripple.style.width = '100px';
            ripple.style.height = '100px';
            ripple.style.left = e.offsetX - 50 + 'px';
            ripple.style.top = e.offsetY - 50 + 'px';
            ripple.style.animation = 'ripple 0.6s ease-out';
            ripple.style.pointerEvents = 'none';
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // ==================== FLASH MESSAGE AUTO HIDE ====================
    const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-yellow-50');
    flashMessages.forEach(msg => {
        if (msg.classList.contains('border-green-500') || 
            msg.classList.contains('border-red-500') || 
            msg.classList.contains('border-yellow-500')) {
            
            setTimeout(() => {
                msg.style.transition = 'all 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateX(100%)';
                
                setTimeout(() => {
                    msg.remove();
                }, 500);
            }, 5000);
        }
    });
    
    // ==================== KEYBOARD NAVIGATION ====================
    document.addEventListener('keydown', function(e) {
        // Press 1-5 untuk quick access ke menu
        const shortcuts = {
            '1': '<?= base_url('laporan/profit') ?>',
            '2': '<?= base_url('laporan/pendaftaran') ?>',
            '3': '<?= base_url('laporan/absensi') ?>',
            '4': '<?= base_url('laporan/progress') ?>',
            '5': '<?= base_url('sertifikat') ?>'
        };
        
        if (e.altKey && shortcuts[e.key]) {
            window.location.href = shortcuts[e.key];
        }
    });
    
    // ==================== HOVER SOUND EFFECT (OPTIONAL) ====================
    // Uncomment kalo mau ada sound effect pas hover
    /*
    const hoverSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBDCP2/LTgDQICmS56+yiTgwOV6vo7LRgGAg+mtv0xnMpBSuBzvLaizsIHGS36+aRPgkQYrPt7ahU');
    
    cardLinks.forEach(card => {
        card.addEventListener('mouseenter', () => {
            hoverSound.currentTime = 0;
            hoverSound.play().catch(e => {}); // Ignore if autoplay blocked
        });
    });
    */
    
    console.log('%c🚀 Laporan System Ready!', 'color: #667eea; font-size: 16px; font-weight: bold;');
    console.log('%cKeyboard Shortcuts:', 'color: #764ba2; font-weight: bold;');
    console.log('Alt+1: Laporan Profit');
    console.log('Alt+2: Laporan Pendaftaran');
    console.log('Alt+3: Laporan Absensi');
    console.log('Alt+4: Laporan Progress');
    console.log('Alt+5: Kelola Sertifikat');
</script>

<style>
    @keyframes ripple {
        0% {
            transform: scale(0);
            opacity: 1;
        }
        100% {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .stat-card {
        cursor: default;
    }
</style>

<?= $this->endSection() ?>