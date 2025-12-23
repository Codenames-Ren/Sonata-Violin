<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Detail Absensi<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Kelola absensi siswa kelas kursus<?= $this->endSection() ?>
<?= $this->section('content') ?>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
    }

    .btn-hover:active {
        transform: translateY(0);
    }

    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .status-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 1em;
        padding-right: 2.5rem;
    }

    .status-hadir { background-color: #dcfce7; color: #16a34a; border-color: #86efac; }
    .status-izin { background-color: #fef3c7; color: #ca8a04; border-color: #fde047; }
    .status-sakit { background-color: #dbeafe; color: #2563eb; border-color: #93c5fd; }
    .status-alpa { background-color: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .status-belum { background-color: #f3f4f6; color: #6b7280; border-color: #d1d5db; }

    select:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<?php $role = session('role'); ?>
<?php $isOpen = $absensi['status'] === 'open'; ?>
<?php $isInstruktur = $role === 'instruktur'; ?>

<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('absensi') ?>" 
       class="btn-hover inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:border-primary hover:text-primary transition-all">
        <i class="fa fa-arrow-left"></i>
        <span>Kembali ke Daftar Absensi</span>
    </a>
</div>

<!-- HEADER CARD -->
<div class="info-card p-6 rounded-xl shadow-lg mb-6 text-white">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
                <h2 class="text-2xl font-bold"><?= esc($absensi['nama_paket']) ?></h2>
                <?php if ($isOpen): ?>
                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm font-semibold animate-pulse">
                        <i class="fa fa-unlock mr-1"></i>OPEN
                    </span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-red-500 text-white rounded-full text-sm font-semibold">
                        <i class="fa fa-lock mr-1"></i>CLOSED
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-calendar mr-1"></i><?= date('d M Y', strtotime($absensi['tanggal'])) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-sun mr-1"></i><?= esc($absensi['hari_absensi']) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-clock mr-1"></i><?= date('H:i', strtotime($absensi['jam_mulai'])) ?> - <?= date('H:i', strtotime($absensi['jam_selesai'])) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-door-open mr-1"></i><?= esc($absensi['nama_ruang']) ?>
                </span>
            </div>
        </div>
        
        <div class="text-right">
            <p class="text-white/80 text-sm mb-1">Instruktur</p>
            <p class="text-xl font-bold"><?= esc($absensi['nama_instruktur']) ?></p>
        </div>
    </div>
</div>

<!-- WARNING JIKA ABSENSI SUDAH DITUTUP -->
<?php if (!$isOpen): ?>
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
    <div class="flex items-center gap-3">
        <i class="fa fa-lock text-red-500 text-2xl"></i>
        <div>
            <p class="font-bold text-red-800">Absensi Sudah Ditutup</p>
            <p class="text-sm text-red-700">Data absensi tidak dapat diubah lagi.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- INFO ABSENSI (hanya untuk instruktur saat status OPEN) -->
<?php if ($isInstruktur && $isOpen): ?>
<div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
    <div class="flex items-center gap-3">
        <i class="fa fa-info-circle text-blue-500 text-2xl"></i>
        <div>
            <p class="font-bold text-blue-800">Instruksi Pengisian Absensi</p>
            <p class="text-sm text-blue-700">Pilih status kehadiran untuk setiap siswa, lalu klik <strong>"Simpan Absensi"</strong> di bawah tabel.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- FORM ABSENSI (hanya muncul kalo instruktur dan status OPEN) -->
<?php if ($isInstruktur && $isOpen): ?>
<form id="formAbsensi" method="POST" action="<?= base_url('absensi/submit') ?>" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="absensi_kelas_id" value="<?= $absensi['id'] ?>">
<?php endif; ?>

<!-- DAFTAR SISWA -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa fa-users text-primary"></i>
            Daftar Absensi Siswa
        </h3>

        <!-- STATISTIK ABSENSI -->
        <div class="flex flex-wrap gap-2" id="statistikAbsensi">
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                <i class="fa fa-check mr-1"></i>Hadir: <span id="countHadir"><?= count(array_filter($siswaAbsen, fn($s) => ($s['status_absen'] ?? '') === 'hadir')) ?></span>
            </span>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                <i class="fa fa-file-medical mr-1"></i>Izin: <span id="countIzin"><?= count(array_filter($siswaAbsen, fn($s) => ($s['status_absen'] ?? '') === 'izin')) ?></span>
            </span>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                <i class="fa fa-thermometer mr-1"></i>Sakit: <span id="countSakit"><?= count(array_filter($siswaAbsen, fn($s) => ($s['status_absen'] ?? '') === 'sakit')) ?></span>
            </span>
            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                <i class="fa fa-times mr-1"></i>Alpa: <span id="countAlpa"><?= count(array_filter($siswaAbsen, fn($s) => ($s['status_absen'] ?? '') === 'alpa')) ?></span>
            </span>
        </div>
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
                <tr>
                    <th class="py-4 px-4 text-left">No</th>
                    <th class="py-4 px-4 text-left">Nama Siswa</th>
                    <th class="py-4 px-4 text-left">Email</th>
                    <th class="py-4 px-4 text-left">No HP</th>
                    <th class="py-4 px-4 text-center w-48">Status Kehadiran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($siswaAbsen)): $no = 1; foreach ($siswaAbsen as $s): ?>
                <tr class="hover:bg-gray-50 transition-colors" data-siswa-nama="<?= esc($s['nama']) ?>">
                    <td class="py-4 px-4 font-medium text-gray-600"><?= $no++ ?></td>
                    <td class="py-4 px-4 font-semibold text-gray-800"><?= esc($s['nama']) ?></td>
                    <td class="py-4 px-4 text-gray-600"><?= esc($s['email']) ?></td>
                    <td class="py-4 px-4 text-gray-600"><?= esc($s['no_hp']) ?></td>
                    <td class="py-4 px-4">
                        <?php if ($isInstruktur && $isOpen): ?>
                            <!-- DROPDOWN ABSENSI (Editable) - REMOVED required attribute -->
                            <select name="absen[<?= $s['kelas_siswa_id'] ?>]" 
                                    class="status-select status-<?= $s['status_absen'] ?? 'belum' ?> w-full px-3 py-2 border-2 rounded-lg font-semibold text-sm focus:ring-2 focus:ring-primary/30 transition-all"
                                    data-siswa-id="<?= $s['kelas_siswa_id'] ?>">
                                <option value="">-- Pilih Status --</option>
                                <option value="hadir" <?= ($s['status_absen'] ?? '') === 'hadir' ? 'selected' : '' ?>>Hadir</option>
                                <option value="izin" <?= ($s['status_absen'] ?? '') === 'izin' ? 'selected' : '' ?>>Izin</option>
                                <option value="sakit" <?= ($s['status_absen'] ?? '') === 'sakit' ? 'selected' : '' ?>>Sakit</option>
                                <option value="alpa" <?= ($s['status_absen'] ?? '') === 'alpa' ? 'selected' : '' ?>>Alpa</option>
                            </select>
                        <?php else: ?>
                            <!-- TAMPILAN READ-ONLY (Badge) -->
                            <div class="flex justify-center">
                                <?php if (!empty($s['status_absen'])): ?>
                                    <?php 
                                    $statusColors = [
                                        'hadir' => 'bg-green-100 text-green-700',
                                        'izin' => 'bg-yellow-100 text-yellow-700',
                                        'sakit' => 'bg-blue-100 text-blue-700',
                                        'alpa' => 'bg-red-100 text-red-700'
                                    ];
                                    $statusIcons = [
                                        'hadir' => 'fa-check',
                                        'izin' => 'fa-file-medical',
                                        'sakit' => 'fa-thermometer',
                                        'alpa' => 'fa-times'
                                    ];
                                    $color = $statusColors[$s['status_absen']] ?? 'bg-gray-100 text-gray-700';
                                    $icon = $statusIcons[$s['status_absen']] ?? 'fa-question';
                                    ?>
                                    <span class="px-3 py-1 <?= $color ?> rounded-full text-xs font-semibold">
                                        <i class="fa <?= $icon ?> mr-1"></i><?= ucfirst($s['status_absen']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-semibold">
                                        <i class="fa fa-minus mr-1"></i>Belum Diabsen
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">
                        <i class="fa fa-users text-4xl mb-3 opacity-20"></i>
                        <p>Belum ada siswa terdaftar di kelas ini.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- MOBILE CARDS -->
    <div class="md:hidden space-y-4">
        <?php if (!empty($siswaAbsen)): foreach ($siswaAbsen as $s): ?>
        <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200" data-siswa-nama="<?= esc($s['nama']) ?>">
            <div class="mb-3">
                <h4 class="font-bold text-gray-800 text-lg mb-2"><?= esc($s['nama']) ?></h4>
                <div class="space-y-1 text-sm text-gray-600">
                    <p><i class="fa fa-envelope mr-2"></i><?= esc($s['email']) ?></p>
                    <p><i class="fa fa-phone mr-2"></i><?= esc($s['no_hp']) ?></p>
                </div>
            </div>

            <div class="pt-3 border-t-2 border-gray-200">
                <label class="block text-xs font-semibold text-gray-700 mb-2">Status Kehadiran:</label>
                
                <?php if ($isInstruktur && $isOpen): ?>
                    <!-- DROPDOWN ABSENSI (Editable) - REMOVED required attribute -->
                    <select name="absen[<?= $s['kelas_siswa_id'] ?>]" 
                            class="status-select status-<?= $s['status_absen'] ?? 'belum' ?> w-full px-3 py-2 border-2 rounded-lg font-semibold text-sm focus:ring-2 focus:ring-primary/30 transition-all"
                            data-siswa-id="<?= $s['kelas_siswa_id'] ?>">
                        <option value="">-- Pilih Status --</option>
                        <option value="hadir" <?= ($s['status_absen'] ?? '') === 'hadir' ? 'selected' : '' ?>>Hadir</option>
                        <option value="izin" <?= ($s['status_absen'] ?? '') === 'izin' ? 'selected' : '' ?>>Izin</option>
                        <option value="sakit" <?= ($s['status_absen'] ?? '') === 'sakit' ? 'selected' : '' ?>>Sakit</option>
                        <option value="alpa" <?= ($s['status_absen'] ?? '') === 'alpa' ? 'selected' : '' ?>>Alpa</option>
                    </select>
                <?php else: ?>
                    <!-- TAMPILAN READ-ONLY (Badge) -->
                    <?php if (!empty($s['status_absen'])): ?>
                        <?php 
                        $statusColors = [
                            'hadir' => 'bg-green-100 text-green-700',
                            'izin' => 'bg-yellow-100 text-yellow-700',
                            'sakit' => 'bg-blue-100 text-blue-700',
                            'alpa' => 'bg-red-100 text-red-700'
                        ];
                        $statusIcons = [
                            'hadir' => 'fa-check',
                            'izin' => 'fa-file-medical',
                            'sakit' => 'fa-thermometer',
                            'alpa' => 'fa-times'
                        ];
                        $color = $statusColors[$s['status_absen']] ?? 'bg-gray-100 text-gray-700';
                        $icon = $statusIcons[$s['status_absen']] ?? 'fa-question';
                        ?>
                        <div class="px-3 py-2 <?= $color ?> rounded-lg text-sm font-semibold text-center">
                            <i class="fa <?= $icon ?> mr-1"></i><?= ucfirst($s['status_absen']) ?>
                        </div>
                    <?php else: ?>
                        <div class="px-3 py-2 bg-gray-100 text-gray-500 rounded-lg text-sm font-semibold text-center">
                            <i class="fa fa-minus mr-1"></i>Belum Diabsen
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fa fa-users text-5xl mb-3 opacity-20"></i>
            <p>Belum ada siswa terdaftar di kelas ini.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- BUTTON SIMPAN ABSENSI (hanya untuk instruktur saat status OPEN) -->
<?php if ($isInstruktur && $isOpen): ?>
    </form>
    
    <div class="mt-6 flex justify-end">
        <button type="button" id="btnSimpanAbsensi"
                class="btn-hover bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-lg font-bold shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
            <i class="fa fa-save"></i>
            Simpan Absensi
        </button>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== DYNAMIC STATUS STYLING ====================
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(select => {
        updateSelectStyling(select);
        
        select.addEventListener('change', function() {
            updateSelectStyling(this);
            updateStatistik();
        });
    });
    
    function updateSelectStyling(select) {
        const value = select.value;
        
        // Hapus semua class status
        select.classList.remove('status-hadir', 'status-izin', 'status-sakit', 'status-alpa', 'status-belum');
        
        // Tambahkan class sesuai value
        if (value === 'hadir') {
            select.classList.add('status-hadir');
        } else if (value === 'izin') {
            select.classList.add('status-izin');
        } else if (value === 'sakit') {
            select.classList.add('status-sakit');
        } else if (value === 'alpa') {
            select.classList.add('status-alpa');
        } else {
            select.classList.add('status-belum');
        }
    }
    
    // ==================== UPDATE STATISTIK REAL-TIME ====================
    function updateStatistik() {
        const selects = document.querySelectorAll('.status-select');
        const stats = {
            hadir: 0,
            izin: 0,
            sakit: 0,
            alpa: 0
        };
        
        selects.forEach(select => {
            // SKIP kalau hidden
            if (select.offsetParent === null) return;
            
            const value = select.value;
            if (stats.hasOwnProperty(value)) {
                stats[value]++;
            }
        });
        
        // Update counter
        document.getElementById('countHadir').textContent = stats.hadir;
        document.getElementById('countIzin').textContent = stats.izin;
        document.getElementById('countSakit').textContent = stats.sakit;
        document.getElementById('countAlpa').textContent = stats.alpa;
    }
    
    // ==================== FORM VALIDATION & SUBMIT - FIXED ====================
    const btnSimpan = document.getElementById('btnSimpanAbsensi');
    const formAbsensi = document.getElementById('formAbsensi');

    if (btnSimpan && formAbsensi) {
        btnSimpan.addEventListener('click', function(e) {
            e.preventDefault();
            
            const selects = formAbsensi.querySelectorAll('.status-select');
            let allFilled = true;
            let emptyCount = 0;
            let emptyNames = [];
            
            console.log('=== DEBUG VALIDATION ===');
            console.log('Total selects:', selects.length);
            
            // Validasi manual - FIXED: hanya ambil select yang visible
            selects.forEach((select, index) => {
                // SKIP kalau select tidak visible (hidden di mobile/desktop)
                if (select.offsetParent === null) {
                    console.log(`Select ${index + 1}: SKIPPED (hidden)`);
                    return;
                }
                
                const value = select.value.trim();
                console.log(`Select ${index + 1}:`, {
                    value: value,
                    isEmpty: (!value || value === ''),
                    siswaId: select.getAttribute('data-siswa-id')
                });
                
                // Cek apakah kosong atau belum dipilih
                if (!value || value === '') {
                    allFilled = false;
                    emptyCount++;
                    
                    // Ambil nama siswa dari row
                    const row = select.closest('tr') || select.closest('[data-siswa-nama]');
                    const nama = row ? row.getAttribute('data-siswa-nama') : 'Unknown';
                    emptyNames.push(nama);
                    
                    // Highlight error
                    select.classList.add('border-red-500');
                    select.style.borderWidth = '3px';
                } else {
                    select.classList.remove('border-red-500');
                    select.style.borderWidth = '2px';
                }
            });
            
            console.log('All filled:', allFilled);
            console.log('Empty count:', emptyCount);
            console.log('Empty names:', emptyNames);
            
            if (!allFilled) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Absensi Belum Lengkap!',
                    html: `
                        <div class="text-left">
                            <p class="mb-3">Masih ada <strong class="text-red-600">${emptyCount} siswa</strong> yang belum dipilih status kehadirannya:</p>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 max-h-60 overflow-y-auto">
                                ${emptyNames.map((name, idx) => `
                                    <p class="text-sm text-red-800 mb-1">
                                        <i class="fa fa-user mr-2"></i>${idx + 1}. ${name}
                                    </p>
                                `).join('')}
                            </div>
                            <p class="mt-3 text-sm text-gray-600">Silakan lengkapi data absensi terlebih dahulu.</p>
                        </div>
                    `,
                    confirmButtonText: 'OK, Saya Mengerti',
                    confirmButtonColor: '#667eea',
                    customClass: {
                        popup: 'text-left'
                    }
                });
                
                // Scroll ke select pertama yang kosong
                const firstEmpty = formAbsensi.querySelector('.status-select.border-red-500');
                if (firstEmpty) {
                    firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => firstEmpty.focus(), 500);
                }
                
                return false;
            }
            
            // Hitung statistik untuk konfirmasi - FIXED: skip hidden
            const stats = {
                hadir: 0,
                izin: 0,
                sakit: 0,
                alpa: 0
            };
            
            selects.forEach(select => {
                // SKIP kalau hidden
                if (select.offsetParent === null) return;
                
                const value = select.value.trim();
                if (stats.hasOwnProperty(value)) {
                    stats[value]++;
                }
            });
            
            console.log('Stats:', stats);
            
            // Konfirmasi sebelum submit
            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Penyimpanan Absensi',
                html: `
                    <div class="text-left mb-4">
                        <p class="mb-3 text-gray-700">Anda akan menyimpan data absensi dengan rincian:</p>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg space-y-3 border-2 border-gray-200">
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-green-700 font-semibold"><i class="fa fa-check mr-2"></i>Hadir</span>
                                <span class="font-bold text-green-700 text-lg">${stats.hadir} siswa</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-yellow-700 font-semibold"><i class="fa fa-file-medical mr-2"></i>Izin</span>
                                <span class="font-bold text-yellow-700 text-lg">${stats.izin} siswa</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-blue-700 font-semibold"><i class="fa fa-thermometer mr-2"></i>Sakit</span>
                                <span class="font-bold text-blue-700 text-lg">${stats.sakit} siswa</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-red-700 font-semibold"><i class="fa fa-times mr-2"></i>Alpa</span>
                                <span class="font-bold text-red-700 text-lg">${stats.alpa} siswa</span>
                            </div>
                        </div>
                        <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                            <p class="text-sm text-yellow-800">
                                <i class="fa fa-exclamation-triangle mr-2"></i>
                                <strong>Perhatian:</strong> Data yang sudah disimpan tidak dapat diubah lagi.
                            </p>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-save mr-2"></i>Ya, Simpan Sekarang',
                cancelButtonText: '<i class="fa fa-times mr-2"></i>Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: {
                    popup: 'text-left'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menyimpan Data Absensi...',
                        html: '<i class="fa fa-spinner fa-spin text-4xl text-primary"></i><br><br>Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit via AJAX - FIXED: hanya kirim data yang visible
                    const visibleSelects = Array.from(selects).filter(select => select.offsetParent !== null);

                    // Buat FormData baru dengan data yang visible aja
                    const cleanFormData = new FormData();

                    // Tambahkan csrf token
                    const csrfInput = formAbsensi.querySelector('input[name="csrf_test_name"]');
                    if (csrfInput) {
                        cleanFormData.append('csrf_test_name', csrfInput.value);
                    }

                    // Tambahkan absensi_kelas_id
                    const absensiKelasId = formAbsensi.querySelector('input[name="absensi_kelas_id"]');
                    if (absensiKelasId) {
                        cleanFormData.append('absensi_kelas_id', absensiKelasId.value);
                    }

                    // Tambahkan hanya dropdown yang visible
                    visibleSelects.forEach(select => {
                        const name = select.name;
                        const value = select.value;
                        cleanFormData.append(name, value);
                    });

                    // Debug: Log form data
                    console.log('=== FORM DATA (CLEAN) ===');
                    for (let pair of cleanFormData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                    
                    fetch(formAbsensi.action, {
                        method: 'POST',
                        body: cleanFormData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Response:', data);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Data absensi berhasil disimpan.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                // Reload halaman untuk menampilkan perubahan
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: data.message || 'Terjadi kesalahan saat menyimpan data absensi.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#dc2626'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat menghubungi server. Silakan coba lagi.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc2626'
                        });
                    });
                }
            });
        });
    }
});
</script>

<?= $this->endSection() ?>