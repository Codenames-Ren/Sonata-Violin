<?= $this->extend('layout/template') ?>
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
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="text-white text-2xl font-bold">Management Operator</h2>
        <p class="text-white/90 text-sm mt-1">Kelola akun operator & admin dengan mudah</p>
    </div>
    
    <button id="btnOpenCreate" class="btn-hover bg-white text-primary px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
        <i class="fa fa-plus"></i> Tambah Operator
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
    <table class="w-full table-auto">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
        <tr>
            <th class="py-4 px-4">NO</th>
            <th class="py-4 px-4">Username</th>
            <th class="py-4 px-4">Nama Lengkap</th>
            <th class="py-4 px-4">Role</th>
            <th class="py-4 px-4">Status</th>
            <th class="py-4 px-4">Dibuat</th>
            <th class="py-4 px-4">Aksi</th>
        </tr>
        </thead>

        <tbody id="tableBody" class="divide-y divide-gray-100">
        <?php if(!empty($operators)): $i=0; foreach($operators as $op): ?>
        <tr class="table-row hover:bg-gray-50 transition-colors" data-index="<?= $i++ ?>">
            <td class="py-4 px-4 text-center font-medium text-gray-600"><?= $i ?></td>
            <td class="py-4 px-4 font-semibold text-gray-800"><?= esc($op['username']) ?></td>
            <td class="py-4 px-4 text-gray-600"><?= esc($op['nama_lengkap']) ?></td>
            <td class="py-4 px-4 text-center">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $op['role'] === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' ?>">
                <?= ucfirst(esc($op['role'])) ?>
            </span>
            </td>
            <td class="py-4 px-4 text-center">
                <?php if(($op['status'] ?? 'aktif') === 'aktif'): ?>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                <?php else: ?>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Nonaktif</span>
                <?php endif; ?>
            </td>
            <td class="py-4 px-4 text-center text-gray-600"><?= date('d M Y', strtotime($op['created_at'])) ?></td>
            
            <td class="py-4 px-4">
                <div class="flex justify-center gap-2 flex-wrap">
                    <button class="btnEdit bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-indigo-200 transition-all btn-hover"
                            data-id="<?= $op['id'] ?>"
                            data-username="<?= esc($op['username'], 'attr') ?>"
                            data-nama="<?= esc($op['nama_lengkap'], 'attr') ?>"
                            data-role="<?= esc($op['role'], 'attr') ?>">
                        <i class="fa fa-pen mr-1"></i>Edit
                    </button>

                    <?php if($op['role'] !== 'admin'): ?>
                        <form method="POST" action="<?= base_url('/settings/operators/toggle-status/'.$op['id']) ?>" class="inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="<?= ($op['status'] === 'aktif') ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?> px-3 py-2 rounded-lg text-xs font-semibold transition-all btn-hover">
                                <i class="fa fa-<?= ($op['status'] === 'aktif') ? 'pause' : 'play' ?> mr-1"></i><?= ($op['status'] === 'aktif') ? 'Nonaktif' : 'Aktifkan' ?>
                            </button>
                        </form>

                        <form method="POST" action="<?= base_url('/settings/operators/delete/'.$op['id']) ?>" 
                              class="inline" onsubmit="return confirm('Hapus operator ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-red-200 transition-all btn-hover">
                                <i class="fa fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="text-gray-400 text-xs italic px-3 py-2">🔒 Protected</span>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="7" class="py-8 text-center text-gray-500">
            <i class="fa fa-users text-4xl mb-3 opacity-20"></i>
            <p>Belum ada operator yang terdaftar.</p>
        </td></tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<!-- MOBILE CARDS -->
<div id="mobileCards" class="md:hidden space-y-4">
    <?php if(!empty($operators)): $i=0; foreach($operators as $op): ?>
    <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow" data-index="<?= $i++ ?>">
        <div class="flex justify-between items-start mb-3 pb-3 border-b border-gray-100">
            <div>
                <h3 class="font-bold text-gray-800 text-lg"><?= esc($op['username']) ?></h3>
                <p class="text-gray-600 text-sm mt-1"><?= esc($op['nama_lengkap']) ?></p>
            </div>
            <div class="flex flex-col items-end gap-2">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $op['role'] === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' ?>">
                <?= ucfirst(esc($op['role'])) ?>
            </span>
                <?php if(($op['status'] ?? 'aktif') === 'aktif'): ?>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                <?php else: ?>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Nonaktif</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="mb-3">
            <p class="text-gray-500 text-xs mb-1">Dibuat</p>
            <p class="font-semibold text-gray-800"><?= date('d M Y', strtotime($op['created_at'])) ?></p>
        </div>

        <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-gray-100">
    <button class="btnEdit col-span-3 bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-200 transition-all"
            data-id="<?= $op['id'] ?>"
            data-username="<?= esc($op['username'], 'attr') ?>"
            data-nama="<?= esc($op['nama_lengkap'], 'attr') ?>"
            data-role="<?= esc($op['role'], 'attr') ?>">
        <i class="fa fa-pen mr-1"></i>Edit
    </button>
    <?php if($op['role'] !== 'admin'): ?>
        <form method="POST" action="<?= base_url('/settings/operators/toggle-status/'.$op['id']) ?>" class="col-span-3">
            <?= csrf_field() ?>
            <button type="submit" class="w-full <?= ($op['status'] === 'aktif') ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?> px-3 py-2 rounded-lg text-sm font-semibold transition-all">
                <i class="fa fa-<?= ($op['status'] === 'aktif') ? 'pause' : 'play' ?> mr-1"></i><?= ($op['status'] === 'aktif') ? 'Nonaktif' : 'Aktif' ?>
            </button>
        </form>
        <form method="POST" action="<?= base_url('/settings/operators/delete/'.$op['id']) ?>" 
              class="col-span-3" onsubmit="return confirm('Hapus operator ini?')">
            <?= csrf_field() ?>
            <button type="submit" class="w-full bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all">
                <i class="fa fa-trash mr-1"></i>Hapus
            </button>
        </form>
    <?php else: ?>
        <span class="col-span-3 text-center text-gray-400 text-xs italic px-3 py-2">🔒 Protected</span>
    <?php endif; ?>
</div>
</div>
    <?php endforeach; endif ?>
</div>

<!-- PAGINATION -->
<?php if(!empty($operators)): ?>
<div class="mt-6 flex justify-center items-center gap-4">
    <button id="btnPrev" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:border-gray-300 disabled:hover:text-gray-800 disabled:hover:translate-y-0 disabled:hover:shadow-none">
        <i class="fa fa-chevron-left mr-1"></i>Prev
    </button>
    <div class="text-sm text-gray-600 font-medium">
        Halaman <span id="currentPage" class="font-bold text-primary">1</span> dari <span id="totalPages" class="font-bold">1</span>
    </div>
    <button id="btnNext" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:border-gray-300 disabled:hover:text-gray-800 disabled:hover:translate-y-0 disabled:hover:shadow-none">
        Next<i class="fa fa-chevron-right ml-1"></i>
    </button>
</div>
<?php endif ?>

<!-- MODAL -->
<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-start md:items-center justify-center p-4 z-50">
    <div id="modalBox" class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95 mt-4 md:mt-0">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white text-xl font-bold">Tambah Operator</h3>
            <button id="btnCloseModal" class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- FORM -->
        <form id="modalForm" method="POST" action="<?= base_url('/settings/operators/create') ?>">
            <?= csrf_field() ?>
            <input type="hidden" id="op_id" name="id">

            <div class="p-6 space-y-4 max-h-[65vh] overflow-y-auto">
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Username</label>
                    <input id="op_username" name="username" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Masukkan username">
                </div>

                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Nama Lengkap</label>
                    <input id="op_nama" name="nama_lengkap" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Masukkan nama lengkap">
                </div>

                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Role</label>
                    <select id="op_role" name="role"
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="operator">Operator</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Password</label>
                    <input id="op_password" name="password" type="password"
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                           placeholder="Masukkan password">
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-info-circle"></i> Kosongkan jika tidak ingin mengubah password
                    </p>
                </div>
            </div>

            <!-- FOOTER BUTTONS -->
            <div class="p-4 md:p-5 bg-gray-50 border-t-2 border-gray-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">

                <div class="flex gap-2 order-2 sm:order-1">
                    <button id="btnCancelModal" type="button"
                            class="flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all hover:-translate-y-0.5 hover:shadow-lg">
                        Batal
                    </button>
                </div>

                <div class="flex gap-2 order-1 sm:order-2">
                    <button type="submit"
                            class="btn-hover flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                        <i class="fa fa-save mr-1"></i>Simpan Data
                    </button>
                </div>

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

        const btnPrev = document.getElementById("btnPrev");
        const btnNext = document.getElementById("btnNext");
        
        btnPrev.disabled = currentPage === 1;
        btnNext.disabled = currentPage === totalPages;
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

    // MODAL CONTROL + ANIMATION
    const modal = document.getElementById("modal");
    const modalBox = document.getElementById("modalBox");

    function openModal() {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        setTimeout(() => {
            modalBox.classList.remove("opacity-0", "scale-95");
            modalBox.classList.add("opacity-100", "scale-100");
        }, 10);
    }

    function closeModal() {
        modalBox.classList.remove("opacity-100", "scale-100");
        modalBox.classList.add("opacity-0", "scale-95");

        setTimeout(() => {
            modal.classList.remove("flex");
            modal.classList.add("hidden");
        }, 300);
    }

    document.getElementById("btnCloseModal").addEventListener("click", closeModal);
    document.getElementById("btnCancelModal").addEventListener("click", closeModal);

    modal.addEventListener("click", e => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener("keydown", e => {
        if (e.key === "Escape") closeModal();
    });

    // OPEN CREATE MODAL
    document.getElementById("btnOpenCreate").addEventListener("click", () => {
        document.getElementById("modalForm").reset();
        document.getElementById("op_id").value = "";
        document.getElementById("modalTitle").textContent = "Tambah Operator Baru";
        document.getElementById("modalForm").action = "<?= base_url('/settings/operators/create') ?>";
        document.getElementById("op_role").removeAttribute("disabled");
        openModal();
    });

    // EDIT BUTTON HANDLER
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.dataset.id;
            
            document.getElementById("op_id").value = id;
            document.getElementById("op_username").value = this.dataset.username;
            document.getElementById("op_nama").value = this.dataset.nama;
            document.getElementById("op_role").value = this.dataset.role;
            document.getElementById("op_password").value = "";
            
            if (this.dataset.role === 'admin') {
                document.getElementById("op_role").setAttribute("disabled", true);
            } else {
                document.getElementById("op_role").removeAttribute("disabled");
            }
            
            document.getElementById("modalTitle").textContent = "Edit Operator";
            document.getElementById("modalForm").action = "<?= base_url('/settings/operators/update/') ?>" + id;
            
            openModal();
        });
    });
</script>

<?= $this->endSection() ?>