<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Laporan Absensi<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Rekap kehadiran siswa per kelas dan pertemuan<?= $this->endSection() ?>
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
                <a href="<?= base_url('laporan') ?>" class="text-white/80 hover:text-white transition-colors">
                    <i class="text-2xl fa fa-arrow-left"></i>
                </a>
                <h2 class="text-white text-2xl font-bold">Laporan Absensi Kelas</h2>
            </div>
            <p class="text-white/90 text-sm">
                Rekap kehadiran siswa per kelas dan pertemuan
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3 text-white">
                <p class="text-xs opacity-90">Total Kelas Aktif</p>
                <p class="text-2xl font-bold"><?= count($dataAbsensi) ?> Kelas</p>
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

<!-- Statistik Cards dihapus karena tidak diperlukan di halaman list kelas -->

<!-- ================= FILTER SECTION ================= -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <form method="GET" action="<?= base_url('laporan/absensi') ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            
            <!-- Tanggal Start -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-calendar text-primary mr-1"></i> Mulai Dari
                </label>
                <input type="date" 
                       name="tanggal_start" 
                       value="<?= $filters['tanggal_start'] ?? '' ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            
            <!-- Tanggal End -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-calendar text-primary mr-1"></i> Sampai Dengan
                </label>
                <input type="date" 
                       name="tanggal_end" 
                       value="<?= $filters['tanggal_end'] ?? '' ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            
            <!-- Jadwal Kelas -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-calendar-alt text-primary mr-1"></i> Jadwal Kelas
                </label>
                <select name="jadwal_kelas_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    <option value="">Semua Jadwal</option>
                    <?php foreach($listJadwal as $jadwal): ?>
                        <option value="<?= $jadwal['id'] ?>" <?= ($filters['jadwal_kelas_id'] ?? '') == $jadwal['id'] ? 'selected' : '' ?>>
                            <?= $jadwal['nama_paket'] ?> - <?= $jadwal['level'] ?> (<?= $jadwal['hari'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Instruktur -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-chalkboard-teacher text-primary mr-1"></i> Instruktur
                </label>
                <select name="instruktur_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    <option value="">Semua Instruktur</option>
                    <?php foreach($listInstruktur as $instruktur): ?>
                        <option value="<?= $instruktur['id'] ?>" <?= ($filters['instruktur_id'] ?? '') == $instruktur['id'] ? 'selected' : '' ?>>
                            <?= $instruktur['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Search -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-search text-primary mr-1"></i> Cari
                </label>
                <input type="text" 
                       name="search" 
                       value="<?= $filters['search'] ?? '' ?>"
                       placeholder="Cari paket / instruktur"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-lg hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fa fa-filter"></i>
                <span>Terapkan Filter</span>
            </button>
            
            <a href="<?= base_url('laporan/absensi') ?>" 
               class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                <i class="fa fa-times"></i>
                <span>Reset Filter</span>
            </a>
        </div>
    </form>
    
    <!-- Active Filters Info -->
    <?php if(!empty($filters['tanggal_start']) || !empty($filters['tanggal_end']) || !empty($filters['jadwal_kelas_id']) || !empty($filters['instruktur_id']) || !empty($filters['search'])): ?>
    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm text-gray-600 font-semibold">Filter Aktif:</span>
            
            <?php if(!empty($filters['tanggal_start']) || !empty($filters['tanggal_end'])): ?>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                <i class="fa fa-calendar mr-1"></i>
                <?= !empty($filters['tanggal_start']) ? date('d/m/Y', strtotime($filters['tanggal_start'])) : '...' ?>
                - 
                <?= !empty($filters['tanggal_end']) ? date('d/m/Y', strtotime($filters['tanggal_end'])) : '...' ?>
            </span>
            <?php endif; ?>
            
            <?php if(!empty($filters['jadwal_kelas_id'])): ?>
            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">
                <i class="fa fa-calendar-alt mr-1"></i>
                <?php 
                    $selectedJadwal = array_filter($listJadwal, fn($j) => $j['id'] == $filters['jadwal_kelas_id']);
                    $selectedJadwal = reset($selectedJadwal);
                    echo $selectedJadwal['nama_paket'] ?? 'Jadwal';
                ?>
            </span>
            <?php endif; ?>
            
            <?php if(!empty($filters['instruktur_id'])): ?>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                <i class="fa fa-chalkboard-teacher mr-1"></i>
                <?php 
                    $selectedInstruktur = array_filter($listInstruktur, fn($i) => $i['id'] == $filters['instruktur_id']);
                    $selectedInstruktur = reset($selectedInstruktur);
                    echo $selectedInstruktur['nama'] ?? 'Instruktur';
                ?>
            </span>
            <?php endif; ?>
            
            <?php if(!empty($filters['search'])): ?>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                <i class="fa fa-search mr-1"></i>
                "<?= esc($filters['search']) ?>"
            </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- ================= DESKTOP TABLE VIEW ================= -->
<div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary to-secondary text-white">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-bold">No</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Tanggal Mulai</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Paket</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Jumlah Pertemuan</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Instruktur</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Ruang</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Jam</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Jumlah Siswa</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($dataAbsensi)): ?>
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa fa-inbox text-4xl mb-3 block text-gray-300"></i>
                        <p class="font-semibold">Tidak ada data kelas</p>
                        <p class="text-sm mt-1">Coba ubah filter atau tambahkan kelas baru</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php 
                    $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                    foreach($dataAbsensi as $absen): 
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700"><?= $no++ ?></td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-800 text-sm">
                                <?= date('d/m/Y', strtotime($absen['tanggal_mulai_kelas'])) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= $absen['nama_paket'] ?></p>
                                <p class="text-xs text-gray-500"><?= $absen['level'] ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="font-bold text-primary text-lg"><?= $absen['jumlah_pertemuan'] ?>x</span>
                                <span class="text-xs text-gray-500"><?= $absen['pertemuan_terlaksana'] ?> terlaksana</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $absen['nama_instruktur'] ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $absen['nama_ruang'] ?></td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800"><?= $absen['hari'] ?></p>
                                <p class="text-xs text-gray-500">
                                    <?= substr($absen['jam_mulai'], 0, 5) ?> - <?= substr($absen['jam_selesai'], 0, 5) ?>
                                </p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-700 rounded-full font-bold text-sm">
                                <?= $absen['jumlah_siswa'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="<?= base_url('laporan/detailAbsensi/' . $absen['jadwal_id']) ?>" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white text-sm font-semibold rounded-lg hover:shadow-lg transition-all">
                                <i class="fa fa-eye"></i>
                                <span>Detail</span>
                            </a>
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
    <?php if(empty($dataAbsensi)): ?>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <i class="fa fa-inbox text-6xl text-gray-300 mb-4"></i>
        <p class="font-semibold text-gray-700 text-lg mb-2">Tidak ada data kelas</p>
        <p class="text-sm text-gray-500">Coba ubah filter atau tambahkan kelas baru</p>
    </div>
    <?php else: ?>
        <?php 
        $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
        foreach($dataAbsensi as $absen): 
        ?>
        <div class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-5">
                <!-- Header Card -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-bold text-gray-800 text-lg"><?= $absen['nama_paket'] ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= $absen['level'] ?></p>
                    </div>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-mono">
                        #<?= $no++ ?>
                    </span>
                </div>
                
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                        <p class="text-sm font-semibold text-gray-800"><?= date('d/m/Y', strtotime($absen['tanggal_mulai_kelas'])) ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Jumlah Pertemuan</p>
                        <p class="text-sm font-bold text-primary"><?= $absen['jumlah_pertemuan'] ?>x Pertemuan</p>
                        <p class="text-xs text-gray-500 mt-1"><?= $absen['pertemuan_terlaksana'] ?> terlaksana</p>
                    </div>
                </div>
                
                <!-- Instruktur & Ruang -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-purple-50 rounded-lg p-3">
                        <p class="text-xs text-purple-600 mb-1">Instruktur</p>
                        <p class="text-sm font-bold text-purple-800"><?= $absen['nama_instruktur'] ?></p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-xs text-blue-600 mb-1">Ruang</p>
                        <p class="text-sm font-bold text-blue-800"><?= $absen['nama_ruang'] ?></p>
                    </div>
                </div>
                
                <!-- Jadwal & Jumlah Siswa -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-orange-50 rounded-lg p-3">
                        <p class="text-xs text-orange-600 mb-1">Jadwal</p>
                        <p class="text-sm font-bold text-orange-800"><?= $absen['hari'] ?></p>
                        <p class="text-xs text-orange-600 mt-1">
                            <?= substr($absen['jam_mulai'], 0, 5) ?> - <?= substr($absen['jam_selesai'], 0, 5) ?>
                        </p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3">
                        <p class="text-xs text-green-600 mb-1">Jumlah Siswa</p>
                        <p class="text-2xl font-bold text-green-800"><?= $absen['jumlah_siswa'] ?></p>
                    </div>
                </div>
                
                <!-- Button Detail -->
                <a href="<?= base_url('laporan/detail/' . $absen['jadwal_id']) ?>" 
                   class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                    <i class="fa fa-eye"></i>
                    <span>Lihat Detail Absensi</span>
                </a>
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
        
        // ========== FILTER ==========
        const filterForm = document.querySelector('form[method="GET"]');
        const submitBtn = filterForm.querySelector('button[type="submit"]');
        const tableContainer = document.querySelector('.hidden.lg\\:block.bg-white');
        const mobileContainer = document.querySelector('.lg\\:hidden.space-y-4');
        
        // Function untuk fetch data via AJAX
        async function applyFilterAJAX(formData) {
            try {
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i><span>Memuat...</span>';
                submitBtn.disabled = true;
                
                const params = new URLSearchParams(formData);
                const url = `<?= base_url('laporan/absensi') ?>?${params.toString()}&ajax=1`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if(!response.ok) throw new Error('Network error');
                
                const data = await response.json();
                
                updateTableData(data.dataAbsensi, data.pagination);
                updateMobileCards(data.dataAbsensi, data.pagination);
                updatePaginationView();
                
                window.history.pushState({}, '', `?${params.toString()}`);        
                submitBtn.innerHTML = '<i class="fa fa-filter"></i><span>Terapkan Filter</span>';
                submitBtn.disabled = false;
                
                showNotification('Filter berhasil diterapkan!', 'success');
                
            } catch(error) {
                console.error('Filter error:', error);
                submitBtn.innerHTML = '<i class="fa fa-filter"></i><span>Terapkan Filter</span>';
                submitBtn.disabled = false;
                showNotification('Gagal memuat data. Silakan coba lagi.', 'error');
            }
        }
        
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.set('page', '1');
            applyFilterAJAX(formData);
        });
        
        // ========== RESET FILTER ==========
        const resetBtn = document.querySelector('a[href*="laporan/absensi"]:not([href*="export"])');
        if(resetBtn && resetBtn.textContent.includes('Reset')) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Clear all inputs
                filterForm.querySelectorAll('input, select').forEach(input => {
                    if(input.type === 'text' || input.type === 'date') {
                        input.value = '';
                    } else if(input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    }
                });
                
                // Apply empty filter
                applyFilterAJAX(new FormData(filterForm));
                
                window.history.pushState({}, '', window.location.pathname);
            });
        }
        
        // Function update table
        function updateTableData(dataAbsensi, pagination) {
            const tbody = tableContainer.querySelector('tbody');
            if(!tbody) return;
            
            if(dataAbsensi.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa fa-inbox text-4xl mb-3 block text-gray-300"></i>
                            <p class="font-semibold">Tidak ada data kelas</p>
                            <p class="text-sm mt-1">Coba ubah filter atau tambahkan kelas baru</p>
                        </td>
                    </tr>
                `;
                return;
            }
            
            let html = '';
            let no = (pagination.current_page - 1) * pagination.per_page + 1;
            
            dataAbsensi.forEach(absen => {
                html += `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700">${no++}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-800 text-sm">
                                ${formatDate(absen.tanggal_mulai_kelas)}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">${absen.nama_paket}</p>
                                <p class="text-xs text-gray-500">${absen.level}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="font-bold text-primary text-lg">${absen.jumlah_pertemuan}x</span>
                                <span class="text-xs text-gray-500">${absen.pertemuan_terlaksana} terlaksana</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">${absen.nama_instruktur}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">${absen.nama_ruang}</td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">${absen.hari}</p>
                                <p class="text-xs text-gray-500">
                                    ${absen.jam_mulai.substring(0, 5)} - ${absen.jam_selesai.substring(0, 5)}
                                </p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-700 rounded-full font-bold text-sm">
                                ${absen.jumlah_siswa}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="<?= base_url('laporan/detailAbsensi/') ?>${absen.jadwal_id}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white text-sm font-semibold rounded-lg hover:shadow-lg transition-all">
                                <i class="fa fa-eye"></i>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        }
        
        // Function update mobile cards
        function updateMobileCards(dataAbsensi, pagination) {
            if(!mobileContainer) return;
            
            if(dataAbsensi.length === 0) {
                mobileContainer.innerHTML = `
                    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                        <i class="fa fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <p class="font-semibold text-gray-700 text-lg mb-2">Tidak ada data kelas</p>
                        <p class="text-sm text-gray-500">Coba ubah filter atau tambahkan kelas baru</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            let no = (pagination.current_page - 1) * pagination.per_page + 1;
            
            dataAbsensi.forEach(absen => {
                html += `
                    <div class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <p class="font-bold text-gray-800 text-lg">${absen.nama_paket}</p>
                                    <p class="text-xs text-gray-500 mt-1">${absen.level}</p>
                                </div>
                                <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-mono">
                                    #${no++}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                                    <p class="text-sm font-semibold text-gray-800">${formatDate(absen.tanggal_mulai_kelas)}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 mb-1">Jumlah Pertemuan</p>
                                    <p class="text-sm font-bold text-primary">${absen.jumlah_pertemuan}x Pertemuan</p>
                                    <p class="text-xs text-gray-500 mt-1">${absen.pertemuan_terlaksana} terlaksana</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-purple-50 rounded-lg p-3">
                                    <p class="text-xs text-purple-600 mb-1">Instruktur</p>
                                    <p class="text-sm font-bold text-purple-800">${absen.nama_instruktur}</p>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <p class="text-xs text-blue-600 mb-1">Ruang</p>
                                    <p class="text-sm font-bold text-blue-800">${absen.nama_ruang}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-orange-50 rounded-lg p-3">
                                    <p class="text-xs text-orange-600 mb-1">Jadwal</p>
                                    <p class="text-sm font-bold text-orange-800">${absen.hari}</p>
                                    <p class="text-xs text-orange-600 mt-1">
                                        ${absen.jam_mulai.substring(0, 5)} - ${absen.jam_selesai.substring(0, 5)}
                                    </p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-xs text-green-600 mb-1">Jumlah Siswa</p>
                                    <p class="text-2xl font-bold text-green-800">${absen.jumlah_siswa}</p>
                                </div>
                            </div>
                            
                            <a href="<?= base_url('laporan/detailAbsensi/') ?>${absen.jadwal_id}" 
                               class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-lg hover:shadow-lg transition-all">
                                <i class="fa fa-eye"></i>
                                <span>Lihat Detail Absensi</span>
                            </a>
                        </div>
                    </div>
                `;
            });
            
            mobileContainer.innerHTML = html;
        }
        
        // Helper functions
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
        
        // Notification helper
        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-red-50 border-red-500 text-red-700';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            const notification = document.createElement('div');
            notification.className = `p-4 rounded-lg ${bgColor} border-l-4 mb-4 shadow-sm fixed top-4 right-4 z-50 max-w-md`;
            notification.innerHTML = `<i class="fa ${icon} mr-2"></i>${message}`;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100px)';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }
        
        // AUTO HIDE FLASH MESSAGES
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
        
        // TABLE ROW ANIMATION
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
            }
        });

        document.getElementById("btnNext").addEventListener("click", () => {
            if (currentPage < totalPages) { 
                currentPage++; 
                updatePaginationView(); 
            }
        });

        window.addEventListener("resize", () => {
            currentPage = 1;
            updatePaginationView();
        });

        // Initialize pagination
        updatePaginationView();
        
        // NUMBER ANIMATION FOR HEADER TOTAL
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
                headerTotal.textContent = Math.floor(currentValue) + ' Kelas';
            }, stepTime);
        }
    });
</script>

<?= $this->endSection() ?>