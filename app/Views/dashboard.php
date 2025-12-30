<?php echo $this->extend('layout/template'); ?>

<?php echo $this->section('content'); ?>

<!-- Welcome Section -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800 mb-2">Dashboard Overview</h1>
    <p class="text-slate-500">Selamat datang kembali! Berikut ringkasan aktivitas kursus biola hari ini.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Siswa Aktif -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-lg">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Siswa Aktif</p>
                <h3 class="text-4xl font-bold"><?= $totalSiswaAktif ?></h3>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="bg-white/20 px-2 py-1 rounded-md font-medium">
                <?= $siswaBulanIni > 0 ? '+' : '' ?><?= $siswaBulanIni ?> bulan ini
            </span>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full"></div>
    </div>

    <!-- Instruktur -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-green-600 p-6 text-white shadow-lg">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Instruktur</p>
                <h3 class="text-4xl font-bold"><?= $totalInstruktur ?></h3>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-chalkboard-user text-2xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="bg-white/20 px-2 py-1 rounded-md font-medium"><?= $instrukturMengajar ?> aktif mengajar</span>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full"></div>
    </div>

    <!-- Kelas Hari Ini -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white shadow-lg">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Kelas Hari Ini</p>
                <h3 class="text-4xl font-bold"><?= $kelasHariIni ?></h3>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-calendar-days text-2xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="bg-white/20 px-2 py-1 rounded-md font-medium"><?= $kelasBerlangsung ?> sedang berlangsung</span>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full"></div>
    </div>

    <!-- Pembayaran Pending -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white shadow-lg">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-orange-100 text-sm font-medium mb-1">Pembayaran Pending</p>
                <h3 class="text-4xl font-bold"><?= $pembayaranPending ?></h3>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="bg-white/20 px-2 py-1 rounded-md font-medium">
                Rp <?= number_format($nominalPending / 1000000, 1) ?>jt total
            </span>
        </div>
        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full"></div>
    </div>

</div>

<!-- Second Row Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <!-- Pendaftaran Bulan Ini -->
    <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-2xl p-6 border border-cyan-200">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-700">Pendaftaran Baru</h4>
            <i class="fas fa-user-plus text-cyan-600 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-cyan-600 mb-2"><?= $pendaftaranBulanIni ?></p>
        <p class="text-sm text-slate-600">
            Bulan ini (<?= $selisihPendaftaran >= 0 ? '+' : '' ?><?= $selisihPendaftaran ?> dari bulan lalu)
        </p>
    </div>

    <!-- Income Bulan Ini -->
    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-6 border border-emerald-200">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-700">Income Bulan Ini</h4>
            <i class="fas fa-money-bill-wave text-emerald-600 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-emerald-600 mb-2">
            Rp <?= number_format($incomeBulanIni / 1000000, 1) ?>jt
        </p>
        <p class="text-sm text-slate-600">
            Target: Rp <?= number_format($targetIncome / 1000000) ?>jt (<?= $persenIncome ?>%)
        </p>
    </div>

    <!-- Ruangan Tersedia -->
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-6 border border-indigo-200">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-700">Ruangan</h4>
            <i class="fas fa-door-open text-indigo-600 text-xl"></i>
        </div>
        <p class="text-3xl font-bold text-indigo-600 mb-2"><?= $ruangAktif ?> / <?= $totalRuang ?></p>
        <p class="text-sm text-slate-600">
            <?= ($totalRuang - $ruangAktif) ?> ruangan <?= ($totalRuang - $ruangAktif) > 0 ? 'dalam perbaikan' : 'semua aktif' ?>
        </p>
    </div>

</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Jadwal Kelas Terdekat -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-lg text-slate-800">Jadwal Kelas Terdekat</h3>
            <a href="<?= base_url('/jadwal-kelas'); ?>" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">Lihat Semua →</a>
        </div>
        <div class="p-6">
            <?php if (empty($jadwalTerdekat)): ?>
                <div class="text-center py-8 text-slate-400">
                    <i class="fas fa-calendar-xmark text-4xl mb-3"></i>
                    <p>Belum ada jadwal kelas terdekat</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php 
                    $colors = ['blue', 'purple', 'green', 'orange'];
                    $i = 0;
                    foreach ($jadwalTerdekat as $kelas): 
                        $color = $colors[$i % count($colors)];
                        $label = strtoupper($kelas['kategori'] === 'hari_ini' ? 'HARI INI' : 'BESOK');
                    ?>
                    
                    <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-<?= $color ?>-50 to-transparent rounded-xl border border-<?= $color ?>-100 hover:shadow-md transition-all">
                        <div class="flex-shrink-0 w-16 text-center">
                            <p class="text-xs text-slate-500 font-medium"><?= $label ?></p>
                            <p class="text-2xl font-bold text-<?= $color ?>-600"><?= date('H:i', strtotime($kelas['jam_mulai'])) ?></p>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800 mb-1"><?= esc($kelas['nama_paket']) ?></h4>
                            <div class="flex items-center gap-4 text-sm text-slate-600">
                                <span><i class="fas fa-user text-<?= $color ?>-500 mr-1"></i> <?= esc($kelas['nama_instruktur']) ?></span>
                                <span><i class="fas fa-users text-<?= $color ?>-500 mr-1"></i> <?= $kelas['jumlah_siswa'] ?> siswa</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="px-3 py-1 bg-<?= $color ?>-500 text-white text-xs font-medium rounded-full"><?= esc($kelas['nama_ruang']) ?></span>
                        </div>
                    </div>

                    <?php 
                    $i++;
                    endforeach; 
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-6">
        
        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="font-bold text-lg text-slate-800">Quick Actions</h3>
            </div>
            <div class="p-4">
                <div class="space-y-2">
                    <a href="<?= base_url('/pendaftaran'); ?>" class="flex items-center gap-3 p-3 bg-white hover:bg-cyan-50 rounded-xl border border-slate-200 hover:border-cyan-300 transition-all">
                        <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-plus text-cyan-600"></i>
                        </div>
                        <span class="font-medium text-slate-700">Daftar Siswa Baru</span>
                    </a>

                    <a href="<?= base_url('/jadwal-kelas'); ?>" class="flex items-center gap-3 p-3 bg-white hover:bg-purple-50 rounded-xl border border-slate-200 hover:border-purple-300 transition-all">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-purple-600"></i>
                        </div>
                        <span class="font-medium text-slate-700">Kelola Jadwal</span>
                    </a>

                    <a href="<?= base_url('/pembayaran'); ?>" class="flex items-center gap-3 p-3 bg-white hover:bg-orange-50 rounded-xl border border-slate-200 hover:border-orange-300 transition-all">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-check-dollar text-orange-600"></i>
                        </div>
                        <span class="font-medium text-slate-700">Cek Pembayaran</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if ($paketHampirHabis > 0): ?>
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-lg border border-amber-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-10 h-10 bg-amber-400 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-900 mb-1">Perlu Perhatian</h4>
                        <p class="text-sm text-amber-800">Ada <?= $paketHampirHabis ?> siswa yang paketnya akan habis minggu ini</p>
                    </div>
                </div>
                <a href="<?= base_url('/siswa'); ?>" class="block w-full text-center py-2 bg-amber-400 hover:bg-amber-500 text-amber-900 font-medium rounded-lg transition-all">
                    Lihat Detail
                </a>
            </div>
        </div>
        <?php endif; ?>

    </div>

</div>

<?php echo $this->endSection(); ?>