<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Manajemen Ruang Kelas<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Kelola data ruang kelas Sonata Violin<?= $this->endSection() ?>
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

.modal-content {
    margin-top: 2rem;
}
</style>

<!-- HEADER -->
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6
            flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

    <div>
        <h2 class="text-white text-2xl font-bold">Manajemen Ruang Kelas</h2>
        <p class="text-white/90 text-sm mt-1">Kelola data ruang kelas Sonata Violin</p>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">

        <!-- SEARCH BAR -->
        <div class="w-full md:w-80">
            <div class="relative">
                <input id="searchInput" type="search"
                       class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                       placeholder="Cari nama ruang atau fasilitas..." autocomplete="off">
                <button id="btnClearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-gray-400 hover:text-gray-600 text-lg transition-colors">
                    ✕
                </button>
            </div>
        </div>

        <!-- ADD BUTTON -->
        <button id="btnOpenCreate"
                class="btn-hover bg-white text-primary px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
            <i class="fa fa-plus"></i> Tambah Ruang
        </button>
    </div>
</div>

<!-- FLASH -->
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
            <th class="py-4 px-4">#</th>
            <th class="py-4 px-4">Nama Ruang</th>
            <th class="py-4 px-4">Kapasitas</th>
            <th class="py-4 px-4">Fasilitas</th>
            <th class="py-4 px-4">Status</th>
            <th class="py-4 px-4">Aksi</th>
        </tr>
        </thead>

        <tbody id="tableBody" class="divide-y divide-gray-100">
        <?php if(!empty($ruang_kelas)): $i=1; foreach($ruang_kelas as $r): ?>
        <tr class="table-row hover:bg-gray-50 transition-colors"
            data-nama="<?= strtolower(esc($r['nama_ruang'])) ?>"
            data-fasilitas="<?= strtolower(esc($r['fasilitas'])) ?>">

            <td class="py-4 px-4 text-center font-medium text-gray-600"><?= $i++ ?></td>
            <td class="py-4 px-4 font-semibold text-gray-800"><?= esc($r['nama_ruang']) ?></td>
            <td class="py-4 px-4 text-gray-600"><?= esc($r['kapasitas']) ?></td>
            <td class="py-4 px-4 text-gray-600"><?= esc($r['fasilitas']) ?></td>

            <td class="py-4 px-4 text-center">
                <?php if($r['status'] === 'aktif'): ?>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                <?php else: ?>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                <?php endif ?>
            </td>

            <td class="py-4 px-4 flex justify-center gap-2">

                <!-- TOGGLE STATUS -->
                <form method="POST" action="<?= base_url('/ruang-kelas/toggle-status/'.$r['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="btn-hover px-3 py-2 rounded-lg text-sm font-semibold transition-all
                        <?= $r['status'] === 'aktif'
                            ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                            : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
                        <?= $r['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </button>
                </form>

                <!-- EDIT -->
                <button class="btnEdit btn-hover bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-200 transition-all"
                        data-id="<?= $r['id'] ?>"
                        data-nama="<?= esc($r['nama_ruang'],'attr') ?>"
                        data-kapasitas="<?= esc($r['kapasitas'],'attr') ?>"
                        data-fasilitas="<?= esc($r['fasilitas'],'attr') ?>">
                    <i class="fa fa-pen mr-1"></i>Edit
                </button>

                <!-- DELETE -->
                <form method="POST" action="<?= base_url('/ruang-kelas/delete/'.$r['id']) ?>"
                      onsubmit="return confirm('Hapus ruang kelas ini?')">
                    <?= csrf_field() ?>
                    <button class="btn-hover bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all">
                        <i class="fa fa-trash mr-1"></i>Hapus
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="6" class="py-8 text-center text-gray-500">
                <i class="fa fa-door-open text-4xl mb-3 opacity-20"></i>
                <p>Belum ada data ruang kelas.</p>
            </td>
        </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<!-- MOBILE CARDS -->
<div id="mobileCards" class="md:hidden space-y-4">
<?php if(!empty($ruang_kelas)): foreach($ruang_kelas as $r): ?>
    <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
         data-nama="<?= strtolower(esc($r['nama_ruang'])) ?>"
         data-fasilitas="<?= strtolower(esc($r['fasilitas'])) ?>">

        <h3 class="font-bold text-gray-800 text-lg"><?= esc($r['nama_ruang']) ?></h3>
        <p class="text-gray-600 text-sm">Kapasitas: <?= esc($r['kapasitas']) ?></p>
        <p class="text-gray-600 text-sm">Fasilitas: <?= esc($r['fasilitas']) ?></p>

        <span class="inline-block mt-2 px-2 py-1 rounded-full text-xs font-semibold
            <?= $r['status'] === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= ucfirst($r['status']) ?>
        </span>

        <div class="flex gap-2 mt-4">
            <form method="POST" action="<?= base_url('/ruang-kelas/toggle-status/'.$r['id']) ?>" class="flex-1">
                <?= csrf_field() ?>
                <button class="w-full px-3 py-2 rounded-lg text-sm font-semibold transition-all
                    <?= $r['status'] === 'aktif' 
                        ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' 
                        : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
                    <?= $r['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                </button>
            </form>

            <button class="btnEdit flex-1 bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-200 transition-all"
                data-id="<?= $r['id'] ?>"
                data-nama="<?= esc($r['nama_ruang'],'attr') ?>"
                data-kapasitas="<?= esc($r['kapasitas'],'attr') ?>"
                data-fasilitas="<?= esc($r['fasilitas'],'attr') ?>">
                <i class="fa fa-pen mr-1"></i>Edit
            </button>

            <form method="POST" action="<?= base_url('/ruang-kelas/delete/'.$r['id']) ?>"
                  class="flex-1" onsubmit="return confirm('Hapus ruang kelas ini?')">
                <?= csrf_field() ?>
                <button class="w-full bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all">
                    <i class="fa fa-trash mr-1"></i>Hapus
                </button>
            </form>
        </div>
    </div>
<?php endforeach; endif ?>
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

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">
    <div id="modalBox" class="modal-content bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden transform
               transition-all duration-300 opacity-0 scale-95">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white text-xl font-bold">Tambah Ruang Kelas</h3>
            <button id="btnCloseModal"
                    class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- FORM -->
        <form id="modalForm" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" id="ruang_id" name="id">

            <div class="p-6 space-y-4">
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Nama Ruang</label>
                    <input id="nama_ruang" name="nama_ruang" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Masukkan nama ruang">
                </div>

                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Kapasitas</label>
                    <input id="kapasitas" name="kapasitas" type="number" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Masukkan kapasitas">
                </div>

                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Fasilitas</label>
                    <textarea id="fasilitas" name="fasilitas"
                              class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                              rows="3"
                              placeholder="Masukkan fasilitas ruang"></textarea>
                </div>
            </div>

            <!-- FOOTER BUTTONS -->
            <div class="p-4 md:p-5 bg-gray-50 border-t-2 border-gray-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">

                <div class="flex gap-2 order-2 sm:order-1">
                    <button type="button" id="btnCancelModal"
                            class="btn-hover flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                        Batal
                    </button>
                </div>

                <div class="flex gap-2 order-1 sm:order-2">
                    <button type="submit"
                            class="btn-hover flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                        <i class="fa fa-save mr-1"></i>Simpan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    //SEARCH BAR
    const searchInput = document.getElementById("searchInput");
    const btnClearSearch = document.getElementById("btnClearSearch");

    function applySearch() {
        const keyword = searchInput.value.toLowerCase().trim();
        btnClearSearch.classList.toggle("hidden", keyword.length === 0);

        const rows = document.querySelectorAll(".table-row");
        const cards = document.querySelectorAll(".card-item");

        rows.forEach(row => {
            const nama = row.dataset.nama;
            const fasilitas = row.dataset.fasilitas;
            const match = nama.includes(keyword) || fasilitas.includes(keyword);
            row.style.display = match ? "" : "none";
        });

        cards.forEach(card => {
            const nama = card.dataset.nama;
            const fasilitas = card.dataset.fasilitas;
            const match = nama.includes(keyword) || fasilitas.includes(keyword);
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
        
        if (keyword === '') {
            return allItems;
        }
        
        return allItems.filter(item => {
            const nama = item.dataset.nama || '';
            const fasilitas = item.dataset.fasilitas || '';
            
            return nama.includes(keyword) || fasilitas.includes(keyword);
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
        if (currentPage > 1) { currentPage--; updatePagination(); }
    });

    document.getElementById("btnNext").addEventListener("click", () => {
        if (currentPage < totalPages) { currentPage++; updatePagination(); }
    });

    window.addEventListener("resize", () => {
        currentPage = 1;
        updatePagination();
    });

    updatePagination();

    //MODAL CONTROL
    const modal = document.getElementById("modal");
    const modalBox = document.getElementById("modalBox");
    const modalTitle = document.getElementById("modalTitle");
    const modalForm = document.getElementById("modalForm");

    const btnOpenCreate = document.getElementById("btnOpenCreate");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const btnCancelModal = document.getElementById("btnCancelModal");

    const inputId = document.getElementById("ruang_id");
    const inputNama = document.getElementById("nama_ruang");
    const inputKapasitas = document.getElementById("kapasitas");
    const inputFasilitas = document.getElementById("fasilitas");

    let isEditMode = false;

    function openModal(mode = "create") {
        isEditMode = (mode === "edit");

        if (!isEditMode) {
            modalForm.reset();
            inputId.value = "";
            modalTitle.textContent = "Tambah Ruang Kelas";
            modalForm.action = "<?= base_url('/ruang-kelas/create') ?>";
        } else {
            modalTitle.textContent = "Edit Ruang Kelas";
        }

        modal.classList.remove("hidden");

        setTimeout(() => {
            modalBox.classList.remove("opacity-0", "scale-95");
            modalBox.classList.add("opacity-100", "scale-100");
        }, 10);
    }

    function closeModal() {
        modalBox.classList.remove("opacity-100", "scale-100");
        modalBox.classList.add("opacity-0", "scale-95");

        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    btnOpenCreate.addEventListener("click", () => openModal("create"));
    btnCloseModal.addEventListener("click", closeModal);
    btnCancelModal.addEventListener("click", closeModal);

    modal.addEventListener("click", e => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener("keydown", e => {
        if (e.key === "Escape") closeModal();
    });

    //EDIT HANDLER
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function() {

            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const kapasitas = this.dataset.kapasitas;
            const fasilitas = this.dataset.fasilitas;

            inputId.value = id;
            inputNama.value = nama;
            inputKapasitas.value = kapasitas;
            inputFasilitas.value = fasilitas;

            modalForm.action = "<?= base_url('/ruang-kelas/update/') ?>" + id;
            openModal("edit");
        });
    });

    updatePagination();
</script>

<?= $this->endSection() ?>