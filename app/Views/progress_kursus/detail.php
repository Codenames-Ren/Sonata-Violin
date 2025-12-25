<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Detail Progress Kursus<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Progress pembelajaran kelas<?= $this->endSection() ?>
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

    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-wrapper table {
        min-width: 1024px;
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

<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('progress-kursus') ?>" 
       class="btn-hover inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:border-primary hover:text-primary transition-all">
        <i class="fa fa-arrow-left"></i>
        <span>Kembali ke Daftar Progress</span>
    </a>
</div>

<!-- HEADER CARD -->
<div class="info-card p-6 rounded-xl shadow-lg mb-6 text-white">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2"><?= esc($progress['nama_paket']) ?></h2>
            <div class="flex flex-wrap gap-3 text-sm">
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-calendar mr-1"></i><?= esc($progress['hari']) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-clock mr-1"></i><?= date('H:i', strtotime($progress['jam_mulai'])) ?> - <?= date('H:i', strtotime($progress['jam_selesai'])) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-door-open mr-1"></i><?= esc($progress['nama_ruang']) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-layer-group mr-1"></i><?= ucfirst(esc($progress['level'])) ?>
                </span>
            </div>
        </div>
        
        <!-- PROGRESS SUMMARY -->
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 min-w-[200px]">
            <p class="text-white/80 text-xs mb-1 text-center">Progress Pembelajaran</p>
            <p class="text-3xl font-bold text-center mb-2">
                <?= $progress['pertemuan_terlaksana'] ?> / <?= $progress['total_pertemuan'] ?>
            </p>
            <div class="w-full bg-white/20 rounded-full h-2">
                <?php 
                    $percentage = $progress['total_pertemuan'] > 0 
                        ? ($progress['pertemuan_terlaksana'] / $progress['total_pertemuan']) * 100 
                        : 0;
                ?>
                <div class="bg-white h-2 rounded-full transition-all" 
                     style="width: <?= $percentage ?>%"></div>
            </div>
            <p class="text-center text-xs text-white/80 mt-1"><?= number_format($percentage, 0) ?>% Selesai</p>
        </div>
    </div>
</div>

<!-- INFO INSTRUKTUR -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fa fa-chalkboard-teacher text-primary"></i>
        Instruktur Pengajar
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <p class="text-xs text-gray-500 mb-1">Nama Instruktur</p>
            <p class="font-bold text-gray-800"><?= esc($progress['nama_instruktur']) ?></p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Status Progress</p>
            <?php if ($progress['status'] === 'aktif'): ?>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                    <i class="fa fa-play-circle mr-1"></i>Aktif
                </span>
            <?php elseif ($progress['status'] === 'selesai'): ?>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                    <i class="fa fa-check-circle mr-1"></i>Selesai
                </span>
            <?php else: ?>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                    <i class="fa fa-pause-circle mr-1"></i>Ditunda
                </span>
            <?php endif; ?>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Pertemuan Tersisa</p>
            <p class="font-bold text-gray-800">
                <?= max(0, $progress['total_pertemuan'] - $progress['pertemuan_terlaksana']) ?> Pertemuan
            </p>
        </div>
    </div>
</div>

<!-- DAFTAR PERTEMUAN -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa fa-clipboard-list text-primary"></i>
            Detail Pertemuan
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                <?= count($detailProgress) ?> Pertemuan Tercatat
            </span>
        </h3>

        <!-- BUTTON TAMBAH PERTEMUAN (hanya untuk instruktur) -->
        <?php if ($role === 'instruktur'): ?>
        <button id="btnTambahPertemuan"
                class="btn-hover bg-gradient-to-r from-primary to-secondary text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fa fa-plus"></i>
            Tambah Pertemuan
        </button>
        <?php endif; ?>
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden md:block">
        <div class="table-wrapper">
            <table class="w-full table-auto">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
                    <tr>
                        <th class="py-4 px-4">Pertemuan</th>
                        <th class="py-4 px-4">Tanggal</th>
                        <th class="py-4 px-4">Materi</th>
                        <th class="py-4 px-4">Catatan</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4">Diisi Oleh</th>
                        <?php if ($role === 'instruktur'): ?>
                        <th class="py-4 px-4 text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (!empty($detailProgress)): foreach ($detailProgress as $d): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-4 text-center">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold">
                                Ke-<?= $d['pertemuan_ke'] ?>
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-800 font-semibold">
                            <?= date('d M Y', strtotime($d['tanggal'])) ?>
                        </td>
                        <td class="py-4 px-4 text-gray-600">
                            <div class="max-w-xs">
                                <p class="line-clamp-2"><?= esc($d['materi']) ?></p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-gray-600">
                            <?php if (!empty($d['catatan'])): ?>
                                <div class="max-w-xs">
                                    <p class="line-clamp-2 text-sm"><?= esc($d['catatan']) ?></p>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm italic">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <?php if ($d['status'] === 'completed'): ?>
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                    <i class="fa fa-check-circle mr-1"></i>Selesai
                                </span>
                            <?php elseif ($d['status'] === 'scheduled'): ?>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                    <i class="fa fa-clock mr-1"></i>Terjadwal
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                    <i class="fa fa-times-circle mr-1"></i>Dibatalkan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4 text-gray-600 text-sm">
                            <?= esc($d['nama_instruktur']) ?>
                        </td>
                        <?php if ($role === 'instruktur'): ?>
                        <td class="py-4 px-4">
                            <div class="flex justify-center gap-2">
                                <button class="btnEditPertemuan btn-hover px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold hover:bg-yellow-200 transition-all"
                                        data-id="<?= $d['id'] ?>"
                                        data-pertemuan="<?= $d['pertemuan_ke'] ?>"
                                        data-tanggal="<?= $d['tanggal'] ?>"
                                        data-materi="<?= esc($d['materi'], 'attr') ?>"
                                        data-catatan="<?= esc($d['catatan'], 'attr') ?>"
                                        data-status="<?= $d['status'] ?>">
                                    <i class="fa fa-pen"></i>
                                </button>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="<?= $role === 'instruktur' ? '7' : '6' ?>" class="text-center py-8 text-gray-500">
                            <i class="fa fa-clipboard-list text-4xl mb-3 opacity-20"></i>
                            <p>Belum ada pertemuan tercatat.</p>
                            <?php if ($role === 'instruktur'): ?>
                                <p class="text-sm mt-2">Klik tombol "Tambah Pertemuan" untuk mulai mencatat.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- HINT SCROLL -->
        <div class="text-center text-xs text-gray-500 p-2 bg-gray-50 border-t mt-4">
            <i class="fa fa-arrows-alt-h mr-1"></i>Geser tabel ke kanan untuk melihat lebih banyak
        </div>
    </div>

    <!-- MOBILE CARDS -->
    <div class="md:hidden space-y-4">
        <?php if (!empty($detailProgress)): foreach ($detailProgress as $d): ?>
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="flex justify-between items-start mb-3">
                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold">
                    Pertemuan Ke-<?= $d['pertemuan_ke'] ?>
                </span>
                <?php if ($d['status'] === 'completed'): ?>
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        <i class="fa fa-check-circle mr-1"></i>Selesai
                    </span>
                <?php elseif ($d['status'] === 'scheduled'): ?>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                        <i class="fa fa-clock mr-1"></i>Terjadwal
                    </span>
                <?php else: ?>
                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                        <i class="fa fa-times-circle mr-1"></i>Dibatalkan
                    </span>
                <?php endif; ?>
            </div>
            
            <div class="space-y-2 text-sm mb-3">
                <p class="text-gray-600">
                    <strong>Tanggal:</strong> <?= date('d M Y', strtotime($d['tanggal'])) ?>
                </p>
                <p class="text-gray-600">
                    <strong>Materi:</strong><br>
                    <span class="text-gray-800"><?= esc($d['materi']) ?></span>
                </p>
                <?php if (!empty($d['catatan'])): ?>
                <p class="text-gray-600">
                    <strong>Catatan:</strong><br>
                    <span class="text-gray-700 text-sm"><?= esc($d['catatan']) ?></span>
                </p>
                <?php endif; ?>
                <p class="text-gray-500 text-xs">
                    <i class="fa fa-user mr-1"></i>Diisi oleh: <?= esc($d['nama_instruktur']) ?>
                </p>
            </div>
            
            <?php if ($role === 'instruktur'): ?>
            <div class="mt-3">
                <button class="btnEditPertemuan w-full px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-semibold hover:bg-yellow-200 transition-all"
                        data-id="<?= $d['id'] ?>"
                        data-pertemuan="<?= $d['pertemuan_ke'] ?>"
                        data-tanggal="<?= $d['tanggal'] ?>"
                        data-materi="<?= esc($d['materi'], 'attr') ?>"
                        data-catatan="<?= esc($d['catatan'], 'attr') ?>"
                        data-status="<?= $d['status'] ?>">
                    <i class="fa fa-pen mr-1"></i>Edit
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fa fa-clipboard-list text-5xl mb-3 opacity-20"></i>
            <p>Belum ada pertemuan tercatat.</p>
            <?php if ($role === 'instruktur'): ?>
                <p class="text-sm mt-2">Klik tombol "Tambah Pertemuan" untuk mulai mencatat.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL TAMBAH/EDIT PERTEMUAN (hanya untuk instruktur) -->
<?php if ($role === 'instruktur'): ?>
<div id="modalPertemuan" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">
    <div id="modalBox" class="modal-content bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white text-xl font-bold">Tambah Pertemuan</h3>
            <button id="btnCloseModal"
                    class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- INFO KELAS -->
        <div class="p-4 bg-blue-50 border-b-2 border-blue-200">
            <p class="text-sm text-gray-700">
                <strong>Kelas:</strong> <?= esc($progress['nama_paket']) ?> | 
                <strong>Total Pertemuan:</strong> <?= $progress['total_pertemuan'] ?> | 
                <strong>Tersisa:</strong> <?= max(0, $progress['total_pertemuan'] - $progress['pertemuan_terlaksana']) ?>
            </p>
        </div>

        <!-- FORM -->
        <form id="formPertemuan" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" id="detail_id" name="detail_id">
            <input type="hidden" name="progress_id" value="<?= $progress['id'] ?>">

            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                
                <!-- PERTEMUAN KE -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Pertemuan Ke-</label>
                    <input id="pertemuan_ke" name="pertemuan_ke" type="number" min="1" max="<?= $progress['total_pertemuan'] ?>" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Contoh: 1">
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-info-circle mr-1"></i>Maksimal pertemuan ke-<?= $progress['total_pertemuan'] ?>
                    </p>
                </div>

                <!-- TANGGAL -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Tanggal Pertemuan</label>
                    <input id="tanggal" name="tanggal" type="date" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <!-- MATERI -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Materi Pembelajaran</label>
                    <textarea id="materi" name="materi" rows="4" required
                              class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                              placeholder="Contoh: Pengenalan teknik dasar bow, latihan posisi tangan kanan..."></textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-lightbulb mr-1"></i>Jelaskan materi yang diajarkan secara detail
                    </p>
                </div>

                <!-- CATATAN -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Catatan (Opsional)</label>
                    <textarea id="catatan" name="catatan" rows="3"
                              class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                              placeholder="Catatan tambahan, kendala, atau perkembangan siswa..."></textarea>
                </div>

                <!-- STATUS -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Status Pertemuan</label>
                    <select id="status" name="status" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="scheduled">Terjadwal</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-info-circle mr-1"></i>Pilih "Selesai" jika pertemuan sudah dilaksanakan
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
                    <i class="fa fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    // ==================== MODAL PERTEMUAN (INSTRUKTUR) ====================
    <?php if ($role === 'instruktur'): ?>
    const modalPertemuan = document.getElementById("modalPertemuan");
    const modalBox = document.getElementById("modalBox");
    const modalTitle = document.getElementById("modalTitle");
    const formPertemuan = document.getElementById("formPertemuan");
    
    const btnTambahPertemuan = document.getElementById("btnTambahPertemuan");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const btnCancelModal = document.getElementById("btnCancelModal");

    const detailId = document.getElementById("detail_id");
    const inputPertemuan = document.getElementById("pertemuan_ke");
    const inputTanggal = document.getElementById("tanggal");
    const inputMateri = document.getElementById("materi");
    const inputCatatan = document.getElementById("catatan");
    const inputStatus = document.getElementById("status");

    let isEditMode = false;

    function openModalPertemuan(mode = 'create') {
        isEditMode = (mode === 'edit');
        
        modalTitle.textContent = isEditMode ? "Edit Pertemuan" : "Tambah Pertemuan";
        
        if (!isEditMode) {
            formPertemuan.reset();
            detailId.value = "";
        }
        
        modalPertemuan.classList.remove("hidden");
        
        setTimeout(() => {
            modalBox.classList.remove("opacity-0", "scale-95");
            modalBox.classList.add("opacity-100", "scale-100");
        }, 10);
    }

    function closeModalPertemuan() {
        modalBox.classList.remove("opacity-100", "scale-100");
        modalBox.classList.add("opacity-0", "scale-95");
        
        setTimeout(() => {
            modalPertemuan.classList.add("hidden");
            formPertemuan.reset();
            detailId.value = "";
        }, 300);
    }

    // Button Tambah Pertemuan
    if (btnTambahPertemuan) {
        btnTambahPertemuan.addEventListener("click", () => openModalPertemuan('create'));
    }

    // Close Modal
    btnCloseModal.addEventListener("click", closeModalPertemuan);
    btnCancelModal.addEventListener("click", closeModalPertemuan);

    // Close modal ketika klik di luar
    modalPertemuan.addEventListener("click", function(e) {
        if (e.target === modalPertemuan) {
            closeModalPertemuan();
        }
    });

    // ==================== FORM SUBMIT (CREATE/UPDATE) ====================
    formPertemuan.addEventListener("submit", function(e) {
        e.preventDefault();
        
        const progressId = <?= $progress['id'] ?>;
        const detailIdValue = detailId.value;
        
        // Validasi pertemuan_ke
        const pertemuanKe = inputPertemuan.value;
        const maxPertemuan = <?= $progress['total_pertemuan'] ?>;
        
        if (pertemuanKe < 1 || pertemuanKe > maxPertemuan) {
            Swal.fire({
                icon: 'warning',
                title: 'Pertemuan Tidak Valid!',
                text: `Pertemuan harus antara 1 sampai ${maxPertemuan}.`,
                confirmButtonColor: '#667eea'
            });
            return;
        }

        // Validasi materi minimal 10 karakter
        if (inputMateri.value.trim().length < 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Materi Terlalu Pendek!',
                text: 'Materi pembelajaran minimal 10 karakter.',
                confirmButtonColor: '#667eea'
            });
            return;
        }

        const formData = new FormData(this);
        
        // Tentukan URL berdasarkan mode
        let url;
        if (detailIdValue) {
            // Edit mode
            url = `<?= base_url('progress-kursus/detail/') ?>${progressId}/update/${detailIdValue}`;
        } else {
            // Create mode
            url = `<?= base_url('progress-kursus/detail/') ?>${progressId}/create`;
        }

        closeModalPertemuan();

        // Loading SweetAlert
        Swal.fire({
            title: 'Processing...',
            text: isEditMode ? 'Mengupdate pertemuan...' : 'Menambahkan pertemuan...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.text();
            }
        })
        .then(html => {
            if (html) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: isEditMode ? 'Pertemuan berhasil diupdate!' : 'Pertemuan berhasil ditambahkan!',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                }).then(() => {
                    location.reload();
                });
            }
        })
        .catch(error => {
            console.error('Submit error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan pertemuan.',
                confirmButtonColor: '#667eea'
            });
        });
    });

    // ==================== EDIT PERTEMUAN ====================
    document.querySelectorAll(".btnEditPertemuan").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.dataset.id;
            const pertemuan = this.dataset.pertemuan;
            const tanggal = this.dataset.tanggal;
            const materi = this.dataset.materi;
            const catatan = this.dataset.catatan;
            const status = this.dataset.status;

            // Fill form dengan data
            detailId.value = id;
            inputPertemuan.value = pertemuan;
            inputTanggal.value = tanggal;
            inputMateri.value = materi;
            inputCatatan.value = catatan || '';
            inputStatus.value = status;

            openModalPertemuan('edit');
        });
    });

    <?php endif; ?>

    // ==================== SMOOTH SCROLL ====================
    window.addEventListener('load', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>

<?= $this->endSection() ?>