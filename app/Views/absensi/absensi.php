<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Absensi Kelas<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Manajemen absensi kelas kursus<?= $this->endSection() ?>
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
    .swal2-container {
        z-index: 99999 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
    }

    .swal2-popup {
        margin: 0 !important;
        position: absolute !important;
        top: 35% !important;
        left: 56% !important;
        transform: translate(-50%, -50%) !important;
    }

    .swal2-container.swal2-backdrop-show {
        background: rgba(0, 0, 0, 0.6) !important;
    }

    #modalAbsensi.has-swal {
        backdrop-filter: none !important;
    }
    
    input[type="search"]::-webkit-search-cancel-button {
        -webkit-appearance: none;
    }

    button, input, select, textarea {
        transition: all 0.2s ease;
    }

    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
    }

    .btn-hover:active {
        transform: translateY(0);
    }

    .modal-content {
        margin-top: 2rem;
    }

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-wrapper table {
        min-width: 1280px;
    }

    .table-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #5568d3;
    }
    
</style>

<?php $role = session('role'); ?>

<!-- ================= HEADER ================= -->
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6
            flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

    <div>
        <h2 class="text-white text-2xl font-bold">Absensi Kelas</h2>
        <p class="text-white/90 text-sm mt-1">
            <?php if (in_array($role, ['admin', 'operator'])): ?>
                Daftar absensi kelas kursus
            <?php else: ?>
                Daftar absensi kelas yang sedang dibuka
            <?php endif; ?>
        </p>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">
        <!-- SEARCH -->
        <div class="w-full md:w-80">
            <div class="relative">
                <input id="searchInput" type="search"
                       class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                       placeholder="Cari hari, paket, atau tanggal..." autocomplete="off">
                <button id="btnClearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-gray-400 hover:text-gray-600 text-lg transition-colors">
                    ✕
                </button>
            </div>
        </div>

        <!-- BUKA ABSENSI BARU (Admin/Operator only) -->
        <?php if (in_array($role, ['admin','operator'])): ?>
            <button id="btnOpenCreate"
                    class="btn-hover bg-white text-primary font-bold px-5 py-2.5 rounded-lg shadow hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
                <i class="fa fa-plus"></i> Buka Absensi Baru
            </button>
        <?php endif ?>
    </div>
</div>

<!-- ================= FLASH ================= -->
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

<!-- ================= DESKTOP TABLE ================= -->
<div class="hidden md:block bg-white shadow-lg rounded-xl overflow-hidden">
    <div class="table-wrapper">
        <table class="w-full table-auto">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
            <tr>
                <th class="py-4 px-4">NO</th>
                <th class="py-4 px-4">Tanggal</th>
                <th class="py-4 px-4">Hari</th>
                <th class="py-4 px-4">Jam</th>
                <th class="py-4 px-4">Instruktur</th>
                <th class="py-4 px-4">Paket</th>
                <th class="py-4 px-4">Ruang</th>
                <th class="py-4 px-4">Jumlah Siswa</th>
                <th class="py-4 px-4">Status</th>
                <th class="py-4 px-4">Aksi</th>
            </tr>
            </thead>

            <tbody id="tableBody" class="divide-y divide-gray-100">
            <?php if (!empty($absensi)): $i = 1; foreach ($absensi as $abs): ?>
            <tr class="table-row hover:bg-gray-50 transition-colors"
                data-search="<?= strtolower($abs['hari'].' '.$abs['nama_paket'].' '.$abs['tanggal']) ?>">
                <td class="px-4 py-4 text-center font-medium text-gray-600"><?= $i++ ?></td>
                <td class="px-4 py-4 text-gray-600"><?= date('d M Y', strtotime($abs['tanggal'])) ?></td>
                <td class="px-4 py-4 font-semibold text-gray-800"><?= esc($abs['hari_absensi']) ?></td>
                <td class="px-4 py-4 text-gray-600">
                    <?= date('H:i', strtotime($abs['jam_mulai'])) ?> - <?= date('H:i', strtotime($abs['jam_selesai'])) ?>
                </td>
                <td class="px-4 py-4 text-gray-600"><?= esc($abs['nama_instruktur']) ?></td>
                <td class="px-4 py-4 text-gray-600"><?= esc($abs['nama_paket']) ?></td>
                <td class="px-4 py-4 text-gray-600"><?= esc($abs['nama_ruang']) ?></td>
                <td class="px-4 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        <?php if (isset($abs['jumlah_hadir'])): ?>
                            <?= $abs['jumlah_hadir'] ?? 0 ?> / <?= $abs['jumlah_siswa'] ?? 0 ?>
                        <?php else: ?>
                            <?= $abs['jumlah_siswa'] ?? 0 ?> Siswa
                        <?php endif; ?>
                    </span>
                </td>
                <td class="px-4 py-4 text-center">
                    <?php if ($abs['status_absensi'] === 'open'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <i class="fa fa-unlock mr-1"></i>OPEN
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <i class="fa fa-lock mr-1"></i>CLOSE
                        </span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-4">
                    <div class="flex flex-wrap justify-center gap-2">
                        <a href="<?= base_url('absensi/detail/'.$abs['absensi_id']) ?>"
                           class="btn-hover px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-all whitespace-nowrap">
                            <i class="fa fa-eye mr-1"></i>Lihat Detail
                        </a>
                        
                        <?php if (in_array($role, ['admin','operator'])): ?>
                            <?php if ($abs['status_absensi'] === 'open'): ?>
                                <form method="POST" action="<?= base_url('absensi/close/'.$abs['absensi_id']) ?>" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            class="btnClose btn-hover px-4 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-all whitespace-nowrap">
                                        <i class="fa fa-lock mr-1"></i>Close Absensi
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr id="noDataRow">
                <td colspan="10" class="text-center py-8 text-gray-500">
                    <i class="fa fa-clipboard-list text-4xl mb-3 opacity-20"></i>
                    <p>
                        <?php if ($role === 'instruktur'): ?>
                            Belum ada absensi yang dibuka untuk kelas Anda.
                        <?php else: ?>
                            Belum ada absensi yang dibuka.
                        <?php endif; ?>
                    </p>
                </td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
    </div>
    
    <!-- HINT SCROLL -->
    <div class="text-center text-xs text-gray-500 p-2 bg-gray-50 border-t">
        <i class="fa fa-arrows-alt-h mr-1"></i>Geser tabel ke kanan untuk melihat lebih banyak
    </div>
</div>

<!-- ================= MOBILE CARDS ================= -->
<div id="mobileCards" class="block md:hidden space-y-4">
    <?php if (!empty($absensi)): ?>
        <?php foreach ($absensi as $abs): ?>
        <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
             data-search="<?= strtolower($abs['hari'].' '.$abs['nama_paket'].' '.$abs['tanggal']) ?>">
            
            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg"><?= esc($abs['hari_absensi']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">
                            <?= date('d M Y', strtotime($abs['tanggal'])) ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <?php if ($abs['status_absensi'] === 'open'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <i class="fa fa-unlock mr-1"></i>OPEN
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <i class="fa fa-lock mr-1"></i>CLOSE
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="space-y-1 text-sm">
                    <p class="text-gray-600"><strong>Jam:</strong> <?= date('H:i', strtotime($abs['jam_mulai'])) ?> - <?= date('H:i', strtotime($abs['jam_selesai'])) ?></p>
                    <p class="text-gray-600"><strong>Instruktur:</strong> <?= esc($abs['nama_instruktur']) ?></p>
                    <p class="text-gray-600"><strong>Paket:</strong> <?= esc($abs['nama_paket']) ?></p>
                    <p class="text-gray-600"><strong>Ruang:</strong> <?= esc($abs['nama_ruang']) ?></p>
                    <p class="text-gray-600">
                        <strong>Siswa:</strong> 
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            <?php if (isset($abs['jumlah_hadir'])): ?>
                                <?= $abs['jumlah_hadir'] ?? 0 ?> / <?= $abs['jumlah_siswa'] ?? 0 ?>
                            <?php else: ?>
                                <?= $abs['jumlah_siswa'] ?? 0 ?> Siswa
                            <?php endif; ?>
                        </span>
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-2 mt-4">
                    <a href="<?= base_url('absensi/detail/'.$abs['absensi_id']) ?>"
                       class="text-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-200 transition-all">
                        <i class="fa fa-eye mr-1"></i>Lihat Detail
                    </a>

                    <?php if (in_array($role, ['admin','operator']) && $abs['status_absensi'] === 'open'): ?>
                        <form method="POST" action="<?= base_url('absensi/close/'.$abs['absensi_id']) ?>" class="w-full">
                            <?= csrf_field() ?>
                            <button type="submit"
                                    class="btnClose w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all">
                                <i class="fa fa-lock mr-1"></i>Close Absensi
                            </button>
                        </form>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div id="noDataCard" class="bg-white shadow-lg rounded-xl p-8 text-center">
            <i class="fa fa-clipboard-list text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-medium">
                <?php if ($role === 'instruktur'): ?>
                    Belum ada absensi yang dibuka untuk kelas Anda
                <?php else: ?>
                    Belum ada absensi yang dibuka
                <?php endif; ?>
            </p>
        </div>
    <?php endif ?>
</div>

<!-- ================= PAGINATION ================= -->
<div class="mt-6 flex justify-center items-center gap-4">
    <button id="btnPrev" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:border-gray-300 disabled:hover:text-gray-800">
        <i class="fa fa-chevron-left mr-1"></i>Prev
    </button>
    <div class="text-sm text-gray-600 font-medium">
        Halaman <span id="currentPage" class="font-bold text-primary">1</span> dari <span id="totalPages" class="font-bold">1</span>
    </div>
    <button id="btnNext" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:border-gray-300 disabled:hover:text-gray-800">
        Next<i class="fa fa-chevron-right ml-1"></i>
    </button>
</div>

<!-- ================= MODAL BUKA ABSENSI ================= -->
<?php if (in_array($role, ['admin', 'operator'])): ?>
<div id="modalAbsensi" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">
    <div id="modalBox" class="modal-content bg-white w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 class="text-white text-xl font-bold">Buka Absensi Baru</h3>
            <button id="btnCloseModal"
                    class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- INFO -->
        <div class="p-4 bg-blue-50 border-b-2 border-blue-200">
            <p class="text-sm text-gray-700">
                <i class="fa fa-info-circle mr-1"></i>
                <strong>Tanggal Hari Ini : </strong> <?= date('d F Y') ?>
            </p>
        </div>

        <!-- LIST JADWAL -->
        <div class="p-6 max-h-[60vh] overflow-y-auto">
            <?php if (!empty($jadwal)): ?>
                <div class="space-y-3">
                    <?php foreach ($jadwal as $j): ?>
                    <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-primary hover:shadow-md transition-all">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-lg mb-2">
                                    <?= esc($j['hari']) ?> | <?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?>
                                </h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    <p><strong>Paket:</strong> <?= esc($j['nama_paket']) ?> (<?= esc($j['level']) ?>)</p>
                                    <p><strong>Instruktur:</strong> <?= esc($j['nama_instruktur']) ?></p>
                                    <p><strong>Ruang:</strong> <?= esc($j['nama_ruang']) ?></p>
                                    <p>
                                        <strong>Siswa:</strong> 
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            <?= $j['jumlah_siswa'] ?? 0 ?> Siswa
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <form method="POST" action="<?= base_url('absensi/open/'.$j['jadwal_id']) ?>" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            class="btn-hover px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all whitespace-nowrap">
                                        <i class="fa fa-unlock mr-1"></i>Buka Absensi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fa fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-medium">Tidak ada jadwal kelas aktif untuk hari ini</p>
                    <p class="text-sm text-gray-400 mt-2">Pastikan ada jadwal kelas yang aktif di hari <?= $hariIni ?? '' ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- FOOTER -->
        <div class="p-5 bg-gray-50 border-t-2 border-gray-200 flex justify-end">
            <button type="button" id="btnCancelModal"
                    class="btn-hover px-5 py-2.5 bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ITEMS_PER_PAGE_DESKTOP = 8;
        const ITEMS_PER_PAGE_MOBILE = 3;
        
        let currentPage = 1;
        let filteredItems = [];
        let allDesktopRows = [];
        let allMobileCards = [];
        
        // ==================== ELEMENTS ====================
        const searchInput = document.getElementById('searchInput');
        const btnClearSearch = document.getElementById('btnClearSearch');
        const tableBody = document.getElementById('tableBody');
        const mobileCards = document.getElementById('mobileCards');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const currentPageSpan = document.getElementById('currentPage');
        const totalPagesSpan = document.getElementById('totalPages');
        const btnCloseAll = document.querySelectorAll('.btnClose');
        
        const modalAbsensi = document.getElementById('modalAbsensi');
        const btnOpenCreate = document.getElementById('btnOpenCreate');
        const btnCloseModal = document.getElementById('btnCloseModal');
        const btnCancelModal = document.getElementById('btnCancelModal');
        const modalBox = document.getElementById('modalBox');
        
        // ==================== INITIALIZE ====================
        function init() {
            allDesktopRows = Array.from(tableBody.querySelectorAll('.table-row'));
            allMobileCards = Array.from(mobileCards.querySelectorAll('.card-item'));
            
            filteredItems = allDesktopRows.map((row, index) => ({
                desktopRow: row,
                mobileCard: allMobileCards[index],
                searchText: row.getAttribute('data-search')
            }));
            
            renderPage();
        }
        
        // ==================== SEARCH ====================
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            if (query) {
                btnClearSearch.classList.remove('hidden');
            } else {
                btnClearSearch.classList.add('hidden');
            }
            
            // Filter items
            if (query === '') {
                filteredItems = allDesktopRows.map((row, index) => ({
                    desktopRow: row,
                    mobileCard: allMobileCards[index],
                    searchText: row.getAttribute('data-search')
                }));
            } else {
                filteredItems = allDesktopRows
                    .map((row, index) => ({
                        desktopRow: row,
                        mobileCard: allMobileCards[index],
                        searchText: row.getAttribute('data-search')
                    }))
                    .filter(item => item.searchText && item.searchText.includes(query));
            }
            
            currentPage = 1;
            renderPage();
        });
        
        btnClearSearch.addEventListener('click', function() {
            searchInput.value = '';
            btnClearSearch.classList.add('hidden');
            
            filteredItems = allDesktopRows.map((row, index) => ({
                desktopRow: row,
                mobileCard: allMobileCards[index],
                searchText: row.getAttribute('data-search')
            }));
            
            currentPage = 1;
            renderPage();
        });
        
        // ==================== PAGINATION ====================
        function renderPage() {
            const isMobile = window.innerWidth < 768;
            const itemsPerPage = isMobile ? ITEMS_PER_PAGE_MOBILE : ITEMS_PER_PAGE_DESKTOP;
            
            const totalPages = Math.max(1, Math.ceil(filteredItems.length / itemsPerPage));
            
            currentPageSpan.textContent = filteredItems.length === 0 ? 0 : currentPage;
            totalPagesSpan.textContent = filteredItems.length === 0 ? 0 : totalPages;
            
            // Enable/disable buttons
            btnPrev.disabled = currentPage <= 1 || filteredItems.length === 0;
            btnNext.disabled = currentPage >= totalPages || filteredItems.length === 0;
            
            allDesktopRows.forEach(row => row.style.display = 'none');
            allMobileCards.forEach(card => card.style.display = 'none');
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            
            const pageItems = filteredItems.slice(startIndex, endIndex);
            
            pageItems.forEach(item => {
                if (isMobile) {
                    if (item.mobileCard) item.mobileCard.style.display = 'block';
                } else {
                    if (item.desktopRow) item.desktopRow.style.display = '';
                }
            });
            
            const noDataRow = document.getElementById('noDataRow');
            const noDataCard = document.getElementById('noDataCard');
            
            if (filteredItems.length === 0) {
                if (!isMobile && noDataRow) {
                    noDataRow.style.display = '';
                }
                if (isMobile && noDataCard) {
                    noDataCard.style.display = 'block';
                }
            } else {
                if (noDataRow) noDataRow.style.display = 'none';
                if (noDataCard) noDataCard.style.display = 'none';
            }
        }
        
        // Prev button
        btnPrev.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderPage();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
        
        // Next button
        btnNext.addEventListener('click', function() {
            const isMobile = window.innerWidth < 768;
            const itemsPerPage = isMobile ? ITEMS_PER_PAGE_MOBILE : ITEMS_PER_PAGE_DESKTOP;
            const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
            
            if (currentPage < totalPages) {
                currentPage++;
                renderPage();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
        
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                currentPage = 1;
                renderPage();
            }, 250);
        });
        
        // ==================== MODAL FUNCTIONS ====================
        function openModal() {
            if (modalAbsensi && modalBox) {
                modalAbsensi.classList.remove('hidden');
                setTimeout(() => {
                    modalBox.classList.remove('opacity-0', 'scale-95');
                    modalBox.classList.add('opacity-100', 'scale-100');
                }, 10);
            }
        }
        
        function closeModal() {
            if (modalAbsensi && modalBox) {
                modalBox.classList.remove('opacity-100', 'scale-100');
                modalBox.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    modalAbsensi.classList.add('hidden');
                }, 300);
            }
        }
        
        // Modal event listeners
        if (btnOpenCreate) {
            btnOpenCreate.addEventListener('click', openModal);
        }
        
        if (btnCloseModal) {
            btnCloseModal.addEventListener('click', closeModal);
        }
        
        if (btnCancelModal) {
            btnCancelModal.addEventListener('click', closeModal);
        }
        
        if (modalAbsensi) {
            modalAbsensi.addEventListener('click', function(e) {
                if (e.target === modalAbsensi) {
                    closeModal();
                }
            });
        }
        
        // ESC key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalAbsensi && !modalAbsensi.classList.contains('hidden')) {
                closeModal();
            }
        });
        
        // ==================== CLOSE ABSENSI CONFIRMATION ====================
        btnCloseAll.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                // Add has-swal class to modal if it exists
                if (modalAbsensi) {
                    modalAbsensi.classList.add('has-swal');
                }
                
                Swal.fire({
                    title: 'Konfirmasi Close Absensi',
                    text: 'Yakin ingin menutup absensi ini? Siswa tidak akan bisa absen lagi setelah ditutup.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#667eea',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Tutup!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (modalAbsensi) {
                        modalAbsensi.classList.remove('has-swal');
                    }
                    
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        
        init();
    });
</script>

<?= $this->endSection() ?>