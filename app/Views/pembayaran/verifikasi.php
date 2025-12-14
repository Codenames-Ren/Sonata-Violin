<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Verifikasi Pembayaran<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Manajemen verifikasi pembayaran pendaftaran<?= $this->endSection() ?>
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
</style>

<!-- HEADER -->
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6
            flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

    <div>
        <h2 class="text-white text-2xl font-bold">Verifikasi Pembayaran</h2>
        <p class="text-white/90 text-sm mt-1">
            Verifikasi bukti pembayaran pendaftaran siswa
        </p>
    </div>

    <!-- SEARCH BAR -->
    <div class="w-full md:w-80">
        <div class="relative">
            <input id="searchInput" type="search"
                   class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200
                          focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                   placeholder="Cari No. Pendaftaran..." autocomplete="off">
            <button id="btnClearSearch"
                    class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-gray-400 hover:text-gray-600 text-lg transition-colors">
                ✕
            </button>
        </div>
    </div>
</div>

<!-- FLASH MESSAGE -->
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

<!-- DESKTOP TABLE -->
<div class="hidden md:block bg-white shadow-lg rounded-xl overflow-hidden">
    <table class="w-full table-auto">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
        <tr>
            <th class="py-4 px-4">No. Pendaftaran</th>
            <th class="py-4 px-4">Nama</th>
            <th class="py-4 px-4">Nominal</th>
            <th class="py-4 px-4">Tanggal Upload</th>
            <th class="py-4 px-4">Status</th>
            <th class="py-4 px-4">Aksi</th>
        </tr>
        </thead>

        <tbody id="tableBody" class="divide-y divide-gray-100">
        <?php if(!empty($pembayaran)): foreach($pembayaran as $p): ?>
        <tr class="table-row hover:bg-gray-50 transition-colors"
            data-nama="<?= strtolower(esc($p['nama'] ?? '')) ?>"
            data-nopendaftaran="<?= strtolower($p['no_pendaftaran']) ?>">

            <td class="py-4 px-4 font-semibold text-gray-800">
                <?= esc($p['no_pendaftaran']) ?>
            </td>

            <td class="py-4 px-4 text-gray-600">
                <?= esc($p['nama'] ?? '-') ?>
            </td>

            <td class="py-4 px-4 text-gray-600">
                Rp <?= number_format($p['nominal'], 0, ',', '.') ?>
            </td>

            <td class="py-4 px-4 text-gray-600 text-sm">
                <?= esc($p['tanggal_upload']) ?>
            </td>

            <td class="py-4 px-4 text-center">
                <?php if($p['status'] === 'pending'): ?>
                    <span class="px-3 py-1 text-xs rounded-full font-semibold bg-yellow-100 text-yellow-700">Pending</span>
                <?php elseif($p['status'] === 'verified'): ?>
                    <span class="px-3 py-1 text-xs rounded-full font-semibold bg-green-100 text-green-700">Verified</span>
                <?php else: ?>
                    <span class="px-3 py-1 text-xs rounded-full font-semibold bg-red-100 text-red-700">Rejected</span>
                <?php endif ?>
            </td>

            <td class="py-4 px-4">
                <div class="flex justify-center gap-2">
                    <a href="<?= base_url('uploads/bukti_pembayaran/'.$p['bukti_transaksi']) ?>"
                       target="_blank"
                       class="btn-hover px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-200 transition-all">
                        <i class="fa fa-eye mr-1"></i>Lihat Bukti
                    </a>

                    <?php if($p['status'] === 'pending'): ?>
                    <form method="POST" action="<?= base_url('/pembayaran/verify/'.$p['id']) ?>">
                        <?= csrf_field() ?>
                        <button class="btn-hover px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-semibold hover:bg-green-200 transition-all">
                            <i class="fa fa-check mr-1"></i>Terima
                        </button>
                    </form>

                    <form method="POST" action="<?= base_url('/pembayaran/reject/'.$p['id']) ?>">
                        <?= csrf_field() ?>
                        <button class="btn-hover px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all"
                                data-id="<?= $p['id'] ?>">
                            <i class="fa fa-times mr-1"></i>Tolak
                        </button>
                    </form>
                    <?php elseif($p['status'] === 'rejected'): ?>
                    <form method="POST"
                        action="<?= base_url('/pembayaran/resubmit/'.$p['id']) ?>"
                        enctype="multipart/form-data">

                        <?= csrf_field() ?>

                        <label class="btn-hover px-4 py-2 bg-orange-100 text-orange-700 rounded-lg
                                    text-sm font-semibold hover:bg-orange-200 cursor-pointer inline-block">
                            <i class="fa fa-upload mr-1"></i>Upload Ulang
                            <input type="file"
                                name="bukti_transaksi"
                                accept="image/*,.pdf"
                                class="hidden"
                                onchange="this.form.submit()">
                        </label>
                    </form>
                    <?php endif ?>
                </div>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="6" class="py-8 text-center text-gray-500">
                <i class="fa fa-file-invoice text-4xl mb-3 opacity-20"></i>
                <p>Belum ada data pembayaran.</p>
            </td>
        </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<!-- MOBILE CARDS -->
<div id="mobileCards" class="md:hidden space-y-4">
<?php if(!empty($pembayaran)): foreach($pembayaran as $p): ?>
    <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
         data-nama="<?= strtolower(esc($p['nama'] ?? '')) ?>"
         data-nopendaftaran="<?= strtolower($p['no_pendaftaran']) ?>">

        <h3 class="font-bold text-gray-800 text-lg"><?= esc($p['no_pendaftaran']) ?></h3>
        <p class="text-gray-600 text-sm"><?= esc($p['nama'] ?? '-') ?></p>
        <p class="text-gray-600 text-sm">
            Rp <?= number_format($p['nominal'], 0, ',', '.') ?>
        </p>
        <p class="text-gray-500 text-xs mt-1"><?= esc($p['tanggal_upload']) ?></p>

        <span class="inline-block mt-2 px-2 py-1 rounded-full text-xs font-semibold
            <?= $p['status']==='pending'?'bg-yellow-100 text-yellow-700':
                ($p['status']==='verified'?'bg-green-100 text-green-700':'bg-red-100 text-red-700') ?>">
            <?= ucfirst($p['status']) ?>
        </span>

        <div class="flex flex-col gap-2 mt-4">
            <a href="<?= base_url('uploads/bukti_pembayaran/'.$p['bukti_transaksi']) ?>"
               target="_blank"
               class="text-center bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-blue-200 transition-all">
                <i class="fa fa-eye mr-1"></i>Lihat Bukti
            </a>

            <?php if($p['status'] === 'pending'): ?>
            <div class="grid grid-cols-2 gap-2">
                <form method="POST" action="<?= base_url('/pembayaran/verify/'.$p['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="w-full bg-green-100 text-green-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-green-200 transition-all">
                        <i class="fa fa-check mr-1"></i>Terima
                    </button>
                </form>

                <form method="POST" action="<?= base_url('/pembayaran/reject/'.$p['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="w-full bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all"
                            data-id="<?= $p['id'] ?>">
                        <i class="fa fa-times mr-1"></i>Tolak
                    </button>
                </form>
            </div>
            <?php elseif($p['status'] === 'rejected'): ?>
            <form method="POST"
                action="<?= base_url('/pembayaran/resubmit/'.$p['id']) ?>"
                enctype="multipart/form-data">

                <?= csrf_field() ?>

                <label class="block w-full text-center bg-orange-100 text-orange-700
                            px-3 py-2 rounded-lg text-sm font-semibold
                            hover:bg-orange-200 cursor-pointer transition-all">
                    <i class="fa fa-upload mr-1"></i>Upload Ulang Bukti
                    <input type="file"
                        name="bukti_transaksi"
                        accept="image/*,.pdf"
                        class="hidden"
                        onchange="this.form.submit()">
                </label>
            </form>
            <?php endif ?>
        </div>
    </div>
<?php endforeach; endif ?>
</div>

<!-- MODAL REJECT PEMBAYARAN -->
<div id="rejectModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50">

    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-5">
            <h3 class="text-white text-lg font-bold">Tolak Pembayaran</h3>
            <p class="text-white/90 text-sm mt-1">
                Masukkan alasan penolakan pembayaran
            </p>
        </div>

        <!-- FORM -->
        <form id="rejectForm" method="POST">
            <?= csrf_field() ?>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">
                        Catatan Admin <span class="text-red-500">*</span>
                    </label>
                    <textarea name="catatan"
                              required
                              rows="4"
                              class="w-full border-2 border-gray-200 rounded-lg px-4 py-2
                                     focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all"
                              placeholder="Contoh: Nominal tidak sesuai, bukti transfer tidak jelas..."></textarea>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="bg-gray-50 border-t px-6 py-4 flex justify-end gap-2">
                <button type="button"
                        id="btnCancelReject"
                        class="px-4 py-2 rounded-lg border border-gray-300 font-semibold hover:bg-gray-100">
                    Batal
                </button>

                <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">
                    Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<!-- PAGINATION -->
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

<script>
    // SEARCH BAR
    const searchInput = document.getElementById("searchInput");
    const btnClearSearch = document.getElementById("btnClearSearch");

    function applySearch() {
        const keyword = searchInput.value.toLowerCase().trim();
        btnClearSearch.classList.toggle("hidden", keyword.length === 0);

        const rows = document.querySelectorAll(".table-row");
        const cards = document.querySelectorAll(".card-item");

        rows.forEach(row => {
            const noPendaftaran = row.dataset.nopendaftaran || '';
            const nama = row.dataset.nama || ''; 
            const match = noPendaftaran.includes(keyword) || nama.includes(keyword);  
            row.style.display = match ? "" : "none";
        });

        cards.forEach(card => {
            const noPendaftaran = card.dataset.nopendaftaran || '';
            const nama = card.dataset.nama || '';
            const match = noPendaftaran.includes(keyword) || nama.includes(keyword); 
            card.style.display = match ? "" : "none";
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

    // PAGINATION
    let currentPage = 1;
    let totalPages = 1;

    function getVisibleItems() {
        const isMobile = window.innerWidth < 768;
        const rows = Array.from(document.querySelectorAll(".table-row"));
        const cards = Array.from(document.querySelectorAll(".card-item"));
        const allItems = isMobile ? cards : rows;

        const keyword = searchInput.value.toLowerCase().trim();

        if (keyword === '') return allItems;

        return allItems.filter(item => {
            const noPendaftaran = item.dataset.nopendaftaran || '';
            const nama = item.dataset.nama || '';
            return noPendaftaran.includes(keyword) || nama.includes(keyword);
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

    updatePagination();

    // MODAL REJECT CONTROL
    const rejectModal = document.getElementById("rejectModal");
    const rejectForm = document.getElementById("rejectForm");
    const btnCancelReject = document.getElementById("btnCancelReject");

    function openRejectModal(paymentId) {
        rejectForm.reset();
        rejectForm.action = "<?= base_url('/pembayaran/reject/') ?>" + paymentId;
        
        rejectModal.classList.remove("hidden");
        rejectModal.classList.add("flex");
    }

    function closeRejectModal() {
        rejectModal.classList.remove("flex");
        rejectModal.classList.add("hidden");
    }

    // Event listener untuk tombol Cancel
    btnCancelReject.addEventListener("click", closeRejectModal);

    // Close modal ketika klik di backdrop
    rejectModal.addEventListener("click", e => {
        if (e.target === rejectModal) closeRejectModal();
    });

    // Close modal dengan tombol ESC
    document.addEventListener("keydown", e => {
        if (e.key === "Escape" && !rejectModal.classList.contains("hidden")) {
            closeRejectModal();
        }
    });

    // Event listener untuk semua tombol Tolak (Desktop + Mobile)
    function attachRejectButtons() {
        document.querySelectorAll("button[data-id]").forEach(btn => {
            btn.removeEventListener("click", handleRejectClick); // Hapus listener lama
            btn.addEventListener("click", handleRejectClick);
        });
    }

    function handleRejectClick(e) {
        e.preventDefault();
        const paymentId = this.dataset.id;
        openRejectModal(paymentId);
    }

    // Jalankan saat pertama load
    attachRejectButtons();
</script>

<?= $this->endSection() ?>