<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Progress Kursus<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Manajemen progress pembelajaran kelas<?= $this->endSection() ?>
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

    #modal.has-swal {
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
        <h2 class="text-white text-2xl font-bold">
            <?= $role === 'instruktur' ? 'Progress Kursus Saya' : 'Progress Kursus' ?>
        </h2>
        <p class="text-white/90 text-sm mt-1">
            <?= $role === 'instruktur' 
                ? 'Daftar progress kelas yang Anda bimbing' 
                : 'Kelola progress pembelajaran kelas' 
            ?>
        </p>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">
        <!-- SEARCH -->
        <div class="w-full md:w-80">
            <div class="relative">
                <input id="searchInput" type="search"
                       class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                       placeholder="Cari paket, instruktur, atau hari..." autocomplete="off">
                <button id="btnClearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-gray-400 hover:text-gray-600 text-lg transition-colors">
                    ✕
                </button>
            </div>
        </div>

        <!-- TAMBAH PROGRESS (ADMIN/OPERATOR) -->
        <?php if (in_array($role, ['admin','operator'])): ?>
            <button id="btnOpenCreate"
                    class="btn-hover bg-white text-primary font-bold px-5 py-2.5 rounded-lg shadow hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
                <i class="fa fa-plus"></i> Buat Progress Baru
            </button>
        <?php endif ?>
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

<!-- ================= DESKTOP TABLE ================= -->
<div class="hidden md:block bg-white shadow-lg rounded-xl overflow-hidden">
    <div class="table-wrapper">
        <table class="w-full table-auto">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
            <tr>
                <th class="py-4 px-4">NO</th>
                <th class="py-4 px-4">Paket Kursus</th>
                <th class="py-4 px-4">Hari</th>
                <th class="py-4 px-4">Jam</th>
                <th class="py-4 px-4">Instruktur</th>
                <th class="py-4 px-4">Ruang</th>
                <th class="py-4 px-4">Progress</th>
                <th class="py-4 px-4">Status</th>
                <th class="py-4 px-4">Aksi</th>
            </tr>
            </thead>

            <tbody id="tableBody" class="divide-y divide-gray-100">
            <?php if (!empty($progress)): $i = 1; foreach ($progress as $p): ?>
            <tr class="table-row hover:bg-gray-50 transition-colors"
                data-search="<?= strtolower($p['nama_paket'].' '.$p['nama_instruktur'].' '.$p['hari'].' '.$p['nama_ruang']) ?>">
                
                <td class="px-4 py-4 text-center font-medium text-gray-600"><?= $i++ ?></td>
                
                <td class="px-4 py-4">
                    <div>
                        <p class="font-semibold text-gray-800"><?= esc($p['nama_paket']) ?></p>
                        <p class="text-xs text-gray-500"><?= esc($p['level']) ?></p>
                    </div>
                </td>
                
                <td class="px-4 py-4 font-semibold text-gray-800"><?= esc($p['hari']) ?></td>
                
                <td class="px-4 py-4 text-gray-600">
                    <?= date('H:i', strtotime($p['jam_mulai'])) ?> - <?= date('H:i', strtotime($p['jam_selesai'])) ?>
                </td>
                
                <td class="px-4 py-4 text-gray-600"><?= esc($p['nama_instruktur']) ?></td>
                
                <td class="px-4 py-4 text-gray-600"><?= esc($p['nama_ruang']) ?></td>
                
                <td class="px-4 py-4 text-center">
                    <div class="flex flex-col items-center gap-1">
                        <span class="text-sm font-semibold text-gray-800">
                            <?= $p['pertemuan_terlaksana'] ?> / <?= $p['total_pertemuan'] ?>
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <?php 
                                $percentage = $p['total_pertemuan'] > 0 
                                    ? ($p['pertemuan_terlaksana'] / $p['total_pertemuan']) * 100 
                                    : 0;
                            ?>
                            <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full transition-all" 
                                 style="width: <?= $percentage ?>%"></div>
                        </div>
                        <span class="text-xs text-gray-500"><?= number_format($percentage, 0) ?>%</span>
                    </div>
                </td>
                
                <td class="px-4 py-4 text-center">
                    <?php if ($p['status'] === 'aktif'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Aktif
                        </span>
                    <?php elseif ($p['status'] === 'selesai'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Selesai
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            Ditunda
                        </span>
                    <?php endif; ?>
                </td>
                
                <td class="px-4 py-4">
                    <div class="flex justify-center">
                        <a href="<?= base_url('progress-kursus/detail/'.encode_id($p['id'])) ?>"
                           class="btn-hover px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-all whitespace-nowrap">
                            <i class="fa fa-eye mr-1"></i>Detail
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="9" class="text-center py-8 text-gray-500">
                    <i class="fa fa-clipboard-list text-4xl mb-3 opacity-20"></i>
                    <p><?= $role === 'instruktur' ? 'Belum ada progress kelas Anda.' : 'Belum ada progress kursus.' ?></p>
                    <?php if (in_array($role, ['admin','operator'])): ?>
                        <p class="text-sm mt-2">Klik tombol "Buat Progress Baru" untuk memulai.</p>
                    <?php endif; ?>
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
    <?php if (!empty($progress)): ?>
        <?php foreach ($progress as $p): ?>
        <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
             data-search="<?= strtolower($p['nama_paket'].' '.$p['nama_instruktur'].' '.$p['hari'].' '.$p['nama_ruang']) ?>">
            
            <div class="space-y-3">
                <!-- HEADER CARD -->
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg"><?= esc($p['nama_paket']) ?></h3>
                        <p class="text-xs text-gray-500"><?= esc($p['level']) ?></p>
                    </div>
                    <?php if ($p['status'] === 'aktif'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Aktif
                        </span>
                    <?php elseif ($p['status'] === 'selesai'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Selesai
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            Ditunda
                        </span>
                    <?php endif; ?>
                </div>

                <!-- INFO DETAIL -->
                <div class="space-y-1 text-sm">
                    <p class="text-gray-600"><strong>Hari:</strong> <?= esc($p['hari']) ?></p>
                    <p class="text-gray-600"><strong>Jam:</strong> <?= date('H:i', strtotime($p['jam_mulai'])) ?> - <?= date('H:i', strtotime($p['jam_selesai'])) ?></p>
                    <p class="text-gray-600"><strong>Instruktur:</strong> <?= esc($p['nama_instruktur']) ?></p>
                    <p class="text-gray-600"><strong>Ruang:</strong> <?= esc($p['nama_ruang']) ?></p>
                </div>

                <!-- PROGRESS BAR -->
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-semibold text-gray-600">Progress Pembelajaran</span>
                        <span class="text-xs font-bold text-gray-800">
                            <?= $p['pertemuan_terlaksana'] ?> / <?= $p['total_pertemuan'] ?> Pertemuan
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <?php 
                            $percentage = $p['total_pertemuan'] > 0 
                                ? ($p['pertemuan_terlaksana'] / $p['total_pertemuan']) * 100 
                                : 0;
                        ?>
                        <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full transition-all" 
                             style="width: <?= $percentage ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-center"><?= number_format($percentage, 0) ?>% Selesai</p>
                </div>

                <!-- BUTTON DETAIL -->
                <div class="mt-4">
                    <a href="<?= base_url('progress-kursus/detail/'.encode_id($p['id'])) ?>"
                       class="block text-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-200 transition-all">
                        <i class="fa fa-eye mr-1"></i>Lihat Detail Progress
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-white shadow-lg rounded-xl p-8 text-center">
            <i class="fa fa-clipboard-list text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-medium">
                <?= $role === 'instruktur' ? 'Belum ada progress kelas Anda' : 'Belum ada progress kursus' ?>
            </p>
            <?php if (in_array($role, ['admin','operator'])): ?>
                <p class="text-sm text-gray-400 mt-2">Klik tombol "Buat Progress Baru" untuk memulai</p>
            <?php endif; ?>
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

<!-- ================= MODAL BUAT PROGRESS BARU (ADMIN/OPERATOR) ================= -->
<?php if (in_array($role, ['admin', 'operator'])): ?>
<div id="modalProgress" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">
    <div id="modalBox" class="modal-content bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 class="text-white text-xl font-bold">Buat Progress Kursus Baru</h3>
            <button id="btnCloseModal"
                    class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- INFO -->
        <div class="p-4 bg-blue-50 border-b-2 border-blue-200">
            <p class="text-sm text-gray-700">
                <i class="fa fa-info-circle mr-1 text-blue-600"></i>
                Pilih kelas yang <strong>belum memiliki progress</strong> untuk memulai tracking pembelajaran.
            </p>
        </div>

        <!-- FORM -->
        <form id="formProgress" method="POST" action="<?= base_url('progress-kursus/create') ?>">
            <?= csrf_field() ?>

            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                
                <!-- PILIH KELAS -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Pilih Kelas</label>
                    <select id="progress_kelas" name="jadwal_kelas_id" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="">-- Pilih Kelas --</option>
                        <?php if (!empty($kelasAvailable)): ?>
                            <?php foreach ($kelasAvailable as $k): ?>
                                <option value="<?= $k['id'] ?>" data-pertemuan="<?= $k['jumlah_pertemuan'] ?>">
                                    <?= esc($k['nama_paket']) ?> - 
                                    <?= esc($k['hari']) ?> 
                                    (<?= date('H:i', strtotime($k['jam_mulai'])) ?>) - 
                                    <?= esc($k['nama_instruktur']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Semua kelas sudah memiliki progress</option>
                        <?php endif; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-lightbulb mr-1"></i>Hanya menampilkan kelas yang belum memiliki progress
                    </p>
                </div>

                <!-- TOTAL PERTEMUAN -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Total Pertemuan</label>
                    <input id="progress_total" name="total_pertemuan" type="number" min="1" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Contoh: 12">
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-calendar-check mr-1"></i>
                        <span id="infoPertemuan">Jumlah pertemuan akan terisi otomatis dari paket kursus</span>
                    </p>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="p-5 bg-gray-50 border-t-2 border-gray-200 flex justify-end gap-3">
                <button type="button" id="btnCancelModal"
                        class="btn-hover px-5 py-2.5 bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="btn-hover px-5 py-2.5 bg-gradient-to-r from-primary to-secondary text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                    <i class="fa fa-save mr-1"></i>Buat Progress
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    // ==================== SEARCH FUNCTIONALITY ====================
    const searchInput = document.getElementById("searchInput");
    const btnClearSearch = document.getElementById("btnClearSearch");

    function applySearch() {
        const keyword = searchInput.value.toLowerCase().trim();
        btnClearSearch.classList.toggle("hidden", keyword.length === 0);

        const rows = document.querySelectorAll(".table-row");
        const cards = document.querySelectorAll(".card-item");

        rows.forEach(row => {
            const searchData = row.dataset.search || "";
            row.style.display = searchData.includes(keyword) ? "" : "none";
        });

        cards.forEach(card => {
            const searchData = card.dataset.search || "";
            card.style.display = searchData.includes(keyword) ? "" : "none";
        });

        currentPage = 1;
        updatePagination();
    }

    searchInput.addEventListener("input", applySearch);

    btnClearSearch.addEventListener("click", () => {
        searchInput.value = "";
        btnClearSearch.classList.add("hidden");
        applySearch();
    });

    // ==================== PAGINATION ====================
    let currentPage = 1;
    let totalPages = 1;

    function getVisibleItems() {
        const isMobile = window.innerWidth < 768;
        const rows = Array.from(document.querySelectorAll(".table-row"));
        const cards = Array.from(document.querySelectorAll(".card-item"));
        
        const allItems = isMobile ? cards : rows;
        const keyword = searchInput.value.toLowerCase().trim();
        
        if (keyword === '') {
            return allItems;
        }
        
        return allItems.filter(item => {
            const searchData = item.dataset.search || '';
            return searchData.includes(keyword);
        });
    }

    function updatePagination() {
        const isMobile = window.innerWidth < 768;
        const allRows = Array.from(document.querySelectorAll(".table-row"));
        const allCards = Array.from(document.querySelectorAll(".card-item"));
        
        allRows.forEach(row => row.style.display = "none");
        allCards.forEach(card => card.style.display = "none");
        
        const visibleItems = getVisibleItems();
        const perPage = isMobile ? 3 : 8;

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
            updatePagination(); 
        }
    });

    document.getElementById("btnNext").addEventListener("click", () => {
        if (currentPage < totalPages) { 
            currentPage++; 
            updatePagination(); 
        }
    });

    window.addEventListener("resize", () => {
        currentPage = 1;
        updatePagination();
    });

    // ==================== MODAL PROGRESS (ADMIN/OPERATOR) ====================
    <?php if (in_array($role, ['admin', 'operator'])): ?>
    const modalProgress = document.getElementById("modalProgress");
    const modalBox = document.getElementById("modalBox");
    const formProgress = document.getElementById("formProgress");
    const btnOpenCreate = document.getElementById("btnOpenCreate");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const btnCancelModal = document.getElementById("btnCancelModal");
    
    const selectKelas = document.getElementById("progress_kelas");
    const inputTotal = document.getElementById("progress_total");
    const infoPertemuan = document.getElementById("infoPertemuan");

    function openModalProgress() {
        modalProgress.classList.remove("hidden");
        
        setTimeout(() => {
            modalBox.classList.remove("opacity-0", "scale-95");
            modalBox.classList.add("opacity-100", "scale-100");
        }, 10);
    }

    function closeModalProgress() {
        modalBox.classList.remove("opacity-100", "scale-100");
        modalBox.classList.add("opacity-0", "scale-95");
        
        setTimeout(() => {
            modalProgress.classList.add("hidden");
            formProgress.reset();
            infoPertemuan.textContent = "Jumlah pertemuan akan terisi otomatis dari paket kursus";
        }, 300);
    }

    if (btnOpenCreate) {
        btnOpenCreate.addEventListener("click", openModalProgress);
    }

    btnCloseModal.addEventListener("click", closeModalProgress);
    btnCancelModal.addEventListener("click", closeModalProgress);

    // Auto-fill total pertemuan dari paket kursus
    selectKelas.addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        const jumlahPertemuan = selectedOption.dataset.pertemuan;
        
        if (jumlahPertemuan && jumlahPertemuan !== '') {
            inputTotal.value = jumlahPertemuan;
            infoPertemuan.innerHTML = `<i class="fa fa-check-circle mr-1 text-green-600"></i>Paket ini memiliki ${jumlahPertemuan} pertemuan`;
        } else {
            inputTotal.value = '';
            infoPertemuan.innerHTML = `<i class="fa fa-exclamation-triangle mr-1 text-yellow-600"></i>Silakan isi jumlah pertemuan secara manual`;
        }
    });

    // Validasi form sebelum submit
    formProgress.addEventListener("submit", function(e) {
        const kelasId = selectKelas.value;
        const totalPertemuan = inputTotal.value;
        
        if (!kelasId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Kelas!',
                text: 'Anda harus memilih kelas terlebih dahulu.',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        if (!totalPertemuan || totalPertemuan < 1) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Total Pertemuan Tidak Valid!',
                text: 'Total pertemuan harus lebih dari 0.',
                confirmButtonColor: '#667eea'
            });
            return;
        }
    });

    // Close modal ketika klik di luar
    modalProgress.addEventListener("click", function(e) {
        if (e.target === modalProgress) {
            closeModalProgress();
        }
    });
    <?php endif; ?>

    // ==================== INITIALIZE ====================
    updatePagination();
</script>

<?= $this->endSection() ?>