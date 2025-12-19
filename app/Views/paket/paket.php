<?= $this->extend('layout/template') ?>
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
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="text-white text-2xl font-bold">Paket Kursus</h2>
        <p class="text-white/90 text-sm mt-1">Kelola daftar paket kursus Sonata Violin</p>
    </div>
    
    <button id="btnOpenCreate" class="btn-hover bg-white text-primary px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
        <i class="fa fa-plus"></i> Tambah Paket
    </button>
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
    <!-- Wrapper untuk horizontal scroll -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
            <tr>
                <th class="py-4 px-4 whitespace-nowrap">NO</th>
                <th class="py-4 px-4 whitespace-nowrap">Nama Paket</th>
                <th class="py-4 px-4 whitespace-nowrap">Level</th>
                <th class="py-4 px-4 whitespace-nowrap">Durasi</th>
                <th class="py-4 px-4 whitespace-nowrap">Pertemuan</th>
                <th class="py-4 px-4 whitespace-nowrap">Periode Daftar</th>
                <th class="py-4 px-4 whitespace-nowrap">Periode Belajar</th>
                <th class="py-4 px-4 whitespace-nowrap">Batch</th>
                <th class="py-4 px-4 whitespace-nowrap">Harga</th>
                <th class="py-4 px-4 whitespace-nowrap">Status</th>
                <th class="py-4 px-4 whitespace-nowrap min-w-[280px]">Aksi</th>
            </tr>
            </thead>

            <tbody id="tableBody" class="divide-y divide-gray-100">
            <?php if(!empty($paket)): $i=1; foreach($paket as $p): ?>
            <tr class="table-row hover:bg-gray-50 transition-colors" data-index="<?= $i-1 ?>">

                <td class="py-4 px-4 text-center font-medium text-gray-600"><?= $i++ ?></td>

                <td class="py-4 px-4 font-semibold text-gray-800 whitespace-nowrap"><?= esc($p['nama_paket']) ?></td>

                <td class="py-4 px-4 text-center">
                <?php 
                    $levelColors = [
                        'beginner' => 'bg-green-100 text-green-700',
                        'intermediate' => 'bg-orange-100 text-orange-700',
                        'advanced' => 'bg-blue-100 text-blue-700'
                    ];
                    $colorClass = $levelColors[$p['level']] ?? 'bg-gray-100 text-gray-700';
                ?>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $colorClass ?>">
                    <?= esc(ucfirst($p['level'])) ?>
                </span>
                </td>

                <td class="py-4 px-4 text-center text-gray-600 whitespace-nowrap"><?= esc($p['durasi']) ?></td>

                <td class="py-4 px-4 text-center text-gray-600"><?= esc($p['jumlah_pertemuan']) ?>x</td>

                <td class="py-4 px-4 text-center text-gray-600 whitespace-nowrap">
                    <?= esc($p['periode_mulai']) ?> - <?= esc($p['periode_selesai']) ?>
                </td>

                <td class="py-4 px-4 text-center text-gray-600 whitespace-nowrap">
                    <?= esc($p['tanggal_mulai']) ?> - <?= esc($p['tanggal_selesai']) ?>
                </td>

                <td class="py-4 px-4 text-center text-gray-600">
                    <?= esc($p['batch'] ?: '-') ?>
                </td>

                <td class="py-4 px-4 text-center font-semibold text-primary whitespace-nowrap">
                    Rp <?= number_format($p['harga'],0,',','.') ?>
                </td>

                <td class="py-4 px-4 text-center">
                    <?php if($p['status'] === 'aktif'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                    <?php endif; ?>
                </td>

                <!-- BAGIAN BUTTON YANG DIPERBAIKI -->
                <td class="py-4 px-4">
                    <div class="flex flex-wrap justify-center gap-2 min-w-[280px]">

                        <!-- Switch -->
                        <form method="POST" action="<?= base_url('/paket/status/'.$p['id']) ?>" class="w-auto">
                            <?= csrf_field() ?>
                            <button class="btn-hover px-3 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap <?= $p['status']==='aktif' ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
                                <?= $p['status']==='aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                            </button>
                        </form>

                        <!-- Edit -->
                        <button
                            class="btnEdit btn-hover bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-200 transition-all whitespace-nowrap"
                            data-id="<?= $p['id'] ?>"
                            data-nama="<?= esc($p['nama_paket'], 'attr') ?>"
                            data-level="<?= esc($p['level'], 'attr') ?>"
                            data-durasi="<?= esc($p['durasi'], 'attr') ?>"
                            data-pertemuan="<?= esc($p['jumlah_pertemuan'], 'attr') ?>"
                            data-harga="<?= esc($p['harga'], 'attr') ?>"
                            data-deskripsi="<?= esc($p['deskripsi'], 'attr') ?>"
                            data-permulai="<?= esc($p['periode_mulai'], 'attr') ?>"
                            data-perselesai="<?= esc($p['periode_selesai'], 'attr') ?>"
                            data-mulai="<?= esc($p['tanggal_mulai'], 'attr') ?>"
                            data-selesai="<?= esc($p['tanggal_selesai'], 'attr') ?>"
                            data-batch="<?= esc($p['batch'], 'attr') ?>"
                            data-status="<?= esc($p['status'], 'attr') ?>"
                        >
                            <i class="fa fa-pen mr-1"></i>Edit
                        </button>

                        <!-- Delete -->
                        <form method="POST" action="<?= base_url('/paket/delete/'.$p['id']) ?>" class="formDeletePaket w-auto">
                            <?= csrf_field() ?>
                            <button class="btn-hover bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all whitespace-nowrap">
                                <i class="fa fa-trash mr-1"></i>Hapus
                            </button>
                        </form>

                    </div>
                </td>
                <!-- END BAGIAN BUTTON YANG DIPERBAIKI -->

            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="11" class="py-8 text-center text-gray-500">
                <i class="fa fa-box-open text-4xl mb-3 opacity-20"></i>
                <p>Belum ada paket kursus.</p>
            </td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MOBILE CARDS -->
<div id="mobileCards" class="md:hidden space-y-4">
<?php if(!empty($paket)): $i=0; foreach($paket as $p): ?>
    <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow" data-index="<?= $i++ ?>">

        <div class="flex justify-between items-start mb-3 pb-3 border-b border-gray-100">
            <h3 class="font-bold text-gray-800 text-lg"><?= esc($p['nama_paket']) ?></h3>

            <?php 
                $colorClass = [
                    'beginner' => 'bg-green-100 text-green-700',
                    'intermediate' => 'bg-orange-100 text-orange-700',
                    'advanced' => 'bg-blue-100 text-blue-700'
                ][$p['level']] ?? 'bg-gray-100 text-gray-700';
            ?>

            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $colorClass ?>">
                <?= esc(ucfirst($p['level'])) ?>
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3 text-sm">

            <div>
                <p class="text-gray-500 text-xs mb-1">Durasi</p>
                <p class="font-semibold text-gray-800"><?= esc($p['durasi']) ?></p>
            </div>

            <div>
                <p class="text-gray-500 text-xs mb-1">Pertemuan</p>
                <p class="font-semibold text-gray-800"><?= esc($p['jumlah_pertemuan']) ?>x</p>
            </div>

            <div class="col-span-2">
                <p class="text-gray-500 text-xs mb-1">Periode Daftar</p>
                <p class="font-semibold text-gray-800">
                    <?= esc($p['periode_mulai']) ?> – <?= esc($p['periode_selesai']) ?>
                </p>
            </div>

            <div class="col-span-2">
                <p class="text-gray-500 text-xs mb-1">Periode Belajar</p>
                <p class="font-semibold text-gray-800">
                    <?= esc($p['tanggal_mulai']) ?> – <?= esc($p['tanggal_selesai']) ?>
                </p>
            </div>

            <div class="col-span-2">
                <p class="text-gray-500 text-xs mb-1">Batch</p>
                <p class="font-semibold text-gray-800"><?= esc($p['batch'] ?: '-') ?></p>
            </div>

            <div class="col-span-2">
                <p class="text-gray-500 text-xs mb-1">Status</p>
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $p['status']==='aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= ucfirst($p['status']) ?>
                </span>
            </div>

            <div class="col-span-2">
                <p class="text-gray-500 text-xs mb-1">Harga</p>
                <p class="font-bold text-primary text-lg">Rp <?= number_format($p['harga'],0,',','.') ?></p>
            </div>
        </div>

        <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100">

            <form method="POST" action="<?= base_url('/paket/status/'.$p['id']) ?>" class="flex-1">
                <?= csrf_field() ?>
                <button class="w-full <?= $p['status']==='aktif' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' ?> px-3 py-2 rounded-lg text-sm font-semibold">
                    <?= $p['status']==='aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                </button>
            </form>

            <button
                class="btnEdit flex-1 bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-200 transition-all"
                data-id="<?= $p['id'] ?>"
                data-nama="<?= esc($p['nama_paket'], 'attr') ?>"
                data-level="<?= esc($p['level'], 'attr') ?>"
                data-durasi="<?= esc($p['durasi'], 'attr') ?>"
                data-pertemuan="<?= esc($p['jumlah_pertemuan'], 'attr') ?>"
                data-harga="<?= esc($p['harga'], 'attr') ?>"
                data-deskripsi="<?= esc($p['deskripsi'], 'attr') ?>"

                data-permulai="<?= esc($p['periode_mulai'], 'attr') ?>"
                data-perselesai="<?= esc($p['periode_selesai'], 'attr') ?>"

                data-mulai="<?= esc($p['tanggal_mulai'], 'attr') ?>"
                data-selesai="<?= esc($p['tanggal_selesai'], 'attr') ?>"

                data-batch="<?= esc($p['batch'], 'attr') ?>"
                data-status="<?= esc($p['status'], 'attr') ?>"
            >
                <i class="fa fa-pen mr-1"></i>Edit
            </button>

        </div>

        <form method="POST" action="<?= base_url('/paket/delete/'.$p['id']) ?>" class="mt-2 formDeletePaket">
            <?= csrf_field() ?>
            <button class="w-full bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-red-200">
                <i class="fa fa-trash mr-1"></i>Hapus
            </button>
        </form>

    </div>
<?php endforeach; endif ?>
</div>

<!-- PAGINATION -->
<?php if(!empty($paket)): ?>
<div class="mt-6 flex justify-center items-center gap-4">
    <button id="btnPrev"
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary disabled:opacity-50">
        <i class="fa fa-chevron-left mr-1"></i>Prev
    </button>

    <div class="text-sm text-gray-600 font-medium">
        Halaman <span id="currentPage" class="font-bold text-primary">1</span> dari <span id="totalPages" class="font-bold">1</span>
    </div>

    <button id="btnNext"
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary disabled:opacity-50">
        Next <i class="fa fa-chevron-right ml-1"></i>
    </button>
</div>
<?php endif ?>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start md:items-start justify-center p-4 z-50 pt-8">
    <div id="modalBox" class="modal-content bg-white w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95">
        
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white text-xl font-bold">Tambah Paket Kursus</h3>
            <button id="btnCloseModal" class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center">×</button>
        </div>

        <form id="modalForm" method="POST" action="<?= base_url('/paket/create') ?>">
            <?= csrf_field() ?>

            <input type="hidden" id="paket_id" name="id">

            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">

                <!-- Nama + Level -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Nama Paket</label>
                        <input id="paket_nama" name="nama_paket" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Level</label>
                        <select id="paket_level" name="level" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                </div>

                <!-- Durasi + Pertemuan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Durasi</label>
                        <input id="paket_durasi" name="durasi" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Jumlah Pertemuan</label>
                        <input id="paket_pertemuan" name="jumlah_pertemuan" type="number" min="1" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>
                </div>

                <!-- Harga + Deskripsi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Harga (Rp)</label>
                        <input id="paket_harga" name="harga" type="number" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Deskripsi</label>
                        <textarea id="paket_deskripsi" name="deskripsi" rows="2" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5"></textarea>
                    </div>
                </div>

                <!-- PERIODE DAFTAR -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Periode Daftar Mulai</label>
                        <input id="paket_per_mulai" name="periode_mulai" type="date" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Periode Daftar Selesai</label>
                        <input id="paket_per_selesai" name="periode_selesai" type="date" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>
                </div>

                <!-- PERIODE BELAJAR (AUTO END DATE) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Tanggal Mulai Belajar</label>
                        <input id="paket_mulai" name="tanggal_mulai" type="date" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Tanggal Selesai Belajar</label>
                        <input id="paket_selesai" name="tanggal_selesai" type="date" readonly class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 bg-gray-100 cursor-not-allowed">
                    </div>
                </div>

                <!-- BATCH + STATUS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Batch</label>
                        <input id="paket_batch" name="batch" placeholder="contoh: Januari 2025" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Status</label>
                        <select id="paket_status" name="status" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="p-4 md:p-5 bg-gray-50 border-t-2 border-gray-200 flex flex-col sm:flex-row justify-between gap-3">

                <button id="btnCancelModal" type="button" class="btn-hover px-4 py-2 bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50">
                    Batal
                </button>

                <button type="submit" class="btn-hover px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md">
                    <i class="fa fa-save mr-1"></i>Simpan Data
                </button>

            </div>

        </form>
    </div>
</div>

<script>
    // PAGINATION
    let currentPage = 1;
    let totalPages = 1;

    function getVisibleItems() {
        const isMobile = window.innerWidth < 768;
        const rows = Array.from(document.querySelectorAll(".table-row"));
        const cards = Array.from(document.querySelectorAll(".card-item"));
        return isMobile ? cards : rows;
    }

    function updatePagination() {
        const items = getVisibleItems();
        const perPage = window.innerWidth < 768 ? 3 : 8;

        totalPages = Math.max(1, Math.ceil(items.length / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        items.forEach((item, index) => {
            const page = Math.floor(index / perPage) + 1;
            item.style.display = (page === currentPage) ? "" : "none";
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

    //   MODAL & FORM
    const modal = document.getElementById("modal");
    const modalBox = document.getElementById("modalBox");
    const modalForm = document.getElementById("modalForm");
    const modalTitle = document.getElementById("modalTitle");

    function openModal() {
        modal.classList.remove("hidden");
        setTimeout(() => {
            modalBox.classList.remove("opacity-0", "scale-95");
            modalBox.classList.add("opacity-100", "scale-100");
        }, 10);
    }

    function closeModal() {
        modalBox.classList.remove("opacity-100", "scale-100");
        modalBox.classList.add("opacity-0", "scale-95");
        setTimeout(() => modal.classList.add("hidden"), 300);
    }

    document.getElementById("btnCloseModal").addEventListener("click", closeModal);
    document.getElementById("btnCancelModal").addEventListener("click", closeModal);

    modal.addEventListener("click", e => { if (e.target === modal) closeModal(); });
    document.addEventListener("keydown", e => { if (e.key === "Escape") closeModal(); });

    //FIELD ELEMENTS
    const paketDurasi      = document.getElementById("paket_durasi");
    const paketMulaiBelajar = document.getElementById("paket_mulai");     // tanggal_mulai
    const paketSelesaiBelajar = document.getElementById("paket_selesai"); // tanggal_selesai

    const paketPerMulai = document.getElementById("paket_per_mulai");     // periode_mulai
    const paketPerSelesai = document.getElementById("paket_per_selesai"); // periode_selesai


    //HELPER TANGGAL
    function formatDateInput(date) {
        const pad = n => String(n).padStart(2, "0");
        return date.getFullYear() + "-" + pad(date.getMonth() + 1) + "-" + pad(date.getDate());
    }

    function parseDurasiToMonths(str) {
        if (!str) return null;
        const m = str.match(/\d+/);
        if (!m) return null;
        return parseInt(m[0], 10);
    }

    //HITUNG TANGGAL SELESAI BELAJAR
    function hitungTanggalSelesaiBelajar() {
        const durasiStr = paketDurasi.value.trim();
        const mulaiStr  = paketMulaiBelajar.value;

        const months = parseDurasiToMonths(durasiStr);

        if (!months || !mulaiStr) {
            paketSelesaiBelajar.value = "";
            return;
        }

        const mulaiDate = new Date(mulaiStr + "T00:00:00");
        if (isNaN(mulaiDate.getTime())) {
            paketSelesaiBelajar.value = "";
            return;
        }

        mulaiDate.setMonth(mulaiDate.getMonth() + months);

        paketSelesaiBelajar.value = formatDateInput(mulaiDate);
    }

    if (paketDurasi && paketMulaiBelajar && paketSelesaiBelajar) {
        paketDurasi.addEventListener("input", hitungTanggalSelesaiBelajar);
        paketMulaiBelajar.addEventListener("change", hitungTanggalSelesaiBelajar);
    }

    //     OPEN CREATE MODAL
    document.getElementById("btnOpenCreate").addEventListener("click", () => {
        modalForm.reset();
        document.getElementById("paket_id").value = "";
        document.getElementById("paket_status").value = "aktif";

        modalTitle.textContent = "Tambah Paket Kursus";
        modalForm.action = "<?= base_url('/paket/create') ?>";

        paketSelesaiBelajar.value = "";
        openModal();
    });

    //     EDIT MODAL HANDLER
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;

            document.getElementById("paket_id").value        = id;
            document.getElementById("paket_nama").value      = this.dataset.nama;
            document.getElementById("paket_level").value     = this.dataset.level;
            document.getElementById("paket_durasi").value    = this.dataset.durasi;
            document.getElementById("paket_pertemuan").value = this.dataset.pertemuan;
            document.getElementById("paket_harga").value     = this.dataset.harga;
            document.getElementById("paket_deskripsi").value = this.dataset.deskripsi || "";

            paketPerMulai.value   = this.dataset.permulai   || "";
            paketPerSelesai.value = this.dataset.perselesai || "";

            paketMulaiBelajar.value   = this.dataset.mulai   || "";
            paketSelesaiBelajar.value = this.dataset.selesai || "";

            document.getElementById("paket_batch").value  = this.dataset.batch   || "";
            document.getElementById("paket_status").value = this.dataset.status  || "aktif";

            modalTitle.textContent = "Edit Paket Kursus";
            modalForm.action = "<?= base_url('/paket/update/') ?>" + id;

            openModal();
        });
    });

    // FORM VALIDATION
    modalForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        const nama = document.getElementById("paket_nama").value.trim();
        const durasi = document.getElementById("paket_durasi").value.trim();
        const pertemuan = document.getElementById("paket_pertemuan").value.trim();
        const harga = document.getElementById("paket_harga").value.trim();
        
        // Validasi Nama Paket
        if (!nama) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Nama paket harus diisi!',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            });
            document.getElementById("paket_nama").focus();
            return;
        }
        
        // Validasi Durasi
        if (!durasi) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Durasi harus diisi!',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            });
            document.getElementById("paket_durasi").focus();
            return;
        }
        
        // Validasi Jumlah Pertemuan
        if (!pertemuan) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Jumlah pertemuan harus diisi!',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            });
            document.getElementById("paket_pertemuan").focus();
            return;
        }
        
        // Validasi Jumlah Pertemuan harus > 0
        if (parseInt(pertemuan) <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Jumlah pertemuan harus lebih dari 0!',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            });
            document.getElementById("paket_pertemuan").focus();
            return;
        }
        
        // Validasi Harga
        if (!harga) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Harga harus diisi!',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            });
            document.getElementById("paket_harga").focus();
            return;
        }
        
        // Validasi Harga harus > 0
        if (parseInt(harga) <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Harga harus lebih dari 0!',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            });
            document.getElementById("paket_harga").focus();
            return;
        }
        
        this.submit();
    });

    // DELETE CONFIRMATION
    document.querySelectorAll('.formDeletePaket').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Yakin ingin hapus?',
                text: "Data paket kursus ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>