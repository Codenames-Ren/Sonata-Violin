<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Manajemen Siswa<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Kelola data siswa Sonata Violin<?= $this->endSection() ?>
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
    
    .photo-preview-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        border: 2px dashed #cbd5e0;
    }
    
    @media (max-width: 768px) {
        .file-input-wrapper {
            width: 100%;
        }
        
        .file-input-wrapper input[type="file"] {
            font-size: 0.75rem;
        }
    }
</style>

<!-- HEADER -->
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6
            flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

    <div>
        <h2 class="text-white text-2xl font-bold">Manajemen Siswa</h2>
        <p class="text-white/90 text-sm mt-1">Kelola data siswa Sonata Violin</p>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">

        <!-- SEARCH BAR -->
        <div class="w-full md:w-80">
            <div class="relative">
                <input id="searchInput" type="search"
                       class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                       placeholder="Cari nama, email, atau no HP..." autocomplete="off">
                <button id="btnClearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-gray-400 hover:text-gray-600 text-lg transition-colors">
                    ✕
                </button>
            </div>
        </div>

        <!-- ADD BUTTON -->
        <button id="btnOpenCreate"
                class="btn-hover bg-white text-primary px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
            <i class="fa fa-plus"></i> Tambah Siswa
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
            <th class="py-4 px-4">NO</th>
            <th class="py-4 px-4">No Pendaftaran</th>
            <th class="py-4 px-4">Foto</th>
            <th class="py-4 px-4">Nama</th>
            <th class="py-4 px-4">Email</th>
            <th class="py-4 px-4">No HP</th>
            <th class="py-4 px-4">Alamat</th>
            <th class="py-4 px-4">Status</th>
            <th class="py-4 px-4">Aksi</th>
        </tr>
        </thead>

        <tbody id="tableBody" class="divide-y divide-gray-100">
        <?php if(!empty($siswa)): $i=1; foreach($siswa as $s): ?>
        <?php 
            $status = strtolower($s['status']);
            $linked = $s['linked'];

            // DELETE RULES
            $canDelete = ($status === 'nonaktif' && !$linked);

            // TOGGLE RULES
            $canToggle = ($status !== 'lulus');
        ?>
        <tr class="table-row hover:bg-gray-50 transition-colors"
            data-name="<?= strtolower(esc($s['nama'])) ?>"
            data-nomor-pendaftaran="<?= strtolower(esc($s['no_pendaftaran'] ?? '')) ?>"
            data-email="<?= strtolower(esc($s['email'])) ?>"
            data-nohp="<?= esc($s['no_hp']) ?>"
            data-alamat="<?= strtolower(esc($s['alamat'])) ?>">

            <td class="py-4 px-4 text-center font-medium text-gray-600"><?= $i++ ?></td>
            <td class="py-4 px-4 font-medium text-gray-600 text-xs">
                <?= esc($s['no_pendaftaran']) ?>
            </td>

            <td class="py-4 px-4 text-center">
                <img class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 mx-auto"
                     src="<?= base_url('uploads/siswa/' . ($s['foto_profil'] ?: 'default.png')) ?>">
            </td>

            <td class="py-4 px-4 font-semibold text-gray-800"><?= esc($s['nama']) ?></td>
            <td class="py-4 px-4 text-gray-600"><?= esc($s['email']) ?></td>
            <td class="py-4 px-4 text-gray-600"><?= esc($s['no_hp']) ?></td>
            <td class="py-4 px-4 text-gray-600"><?= esc($s['alamat']) ?></td>

            <td class="py-4 px-4 text-center">
                <?php 
                    if ($status === 'aktif') {
                        $label = 'Aktif';  $class = 'bg-green-100 text-green-700';
                    } elseif ($status === 'lulus') {
                        $label = 'Lulus';  $class = 'bg-yellow-100 text-yellow-700';
                    } else {
                        $label = 'Nonaktif'; $class = 'bg-red-100 text-red-700';
                    }
                ?>
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $class ?>">
                    <?= $label ?>
                </span>
            </td>

            <td class="py-4 px-4 flex justify-center gap-2">

                <!-- TOGGLE STATUS -->
                <?php if ($canToggle): ?>
                <form method="POST" action="<?= base_url('/siswa/toggle-status/'.$s['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="btn-hover px-3 py-2 rounded-lg text-sm font-semibold transition-all
                        <?= $status == 'aktif' ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
                        <?= $status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </button>
                </form>
                <?php endif; ?>

                <!-- EDIT -->
                <button class="btnEdit btn-hover bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-200 transition-all"
                        data-id="<?= $s['id'] ?>"
                        data-nama="<?= esc($s['nama'], 'attr') ?>"
                        data-email="<?= esc($s['email'], 'attr') ?>"
                        data-nohp="<?= esc($s['no_hp'], 'attr') ?>"
                        data-alamat="<?= esc($s['alamat'], 'attr') ?>"
                        data-tgl="<?= esc($s['tgl_lahir'], 'attr') ?>"
                        data-foto="<?= esc($s['foto_profil'], 'attr') ?>">
                    <i class="fa fa-pen mr-1"></i>Edit
                </button>

                <!-- DELETE -->
                <?php if ($canDelete): ?>
                <form method="POST" action="<?= base_url('/siswa/delete/'.$s['id']) ?>"
                    onsubmit="return confirm('Hapus siswa ini?')">
                    <?= csrf_field() ?>
                    <button class="btn-hover bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all">
                        <i class="fa fa-trash mr-1"></i>Hapus
                    </button>
                </form>
                <?php endif; ?>

            </td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="8" class="py-8 text-center text-gray-500">
            <i class="fa fa-users text-4xl mb-3 opacity-20"></i>
            <p>Belum ada data siswa.</p>
        </td></tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<!-- MOBILE CARDS -->
<div id="mobileCards" class="md:hidden space-y-4">
<?php if(!empty($siswa)): foreach($siswa as $s): 
    $status = strtolower($s['status']);
    $linked = $s['linked'];

    $canDelete = ($status === 'nonaktif' && !$linked);
    $canToggle = ($status !== 'lulus');

    if ($status === 'aktif') {
        $label = 'Aktif';  $class = 'bg-green-100 text-green-700';
    } elseif ($status === 'lulus') {
        $label = 'Lulus';  $class = 'bg-yellow-100 text-yellow-700';
    } else {
        $label = 'Nonaktif'; $class = 'bg-red-100 text-red-700';
    }
?>
    <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
         data-name="<?= strtolower(esc($s['nama'])) ?>"
         data-nomor-pendaftaran="<?= strtolower(esc($s['no_pendaftaran'] ?? '')) ?>"
         data-email="<?= strtolower(esc($s['email'])) ?>"
         data-nohp="<?= esc($s['no_hp']) ?>"
         data-alamat="<?= strtolower(esc($s['alamat'])) ?>">

        <div class="flex gap-4">
            <img class="w-16 h-16 rounded-lg border-2 border-gray-200 object-cover"
                 src="<?= base_url('uploads/siswa/'.($s['foto_profil'] ?: 'default.png')) ?>">

            <div class="flex-1">
                <h3 class="font-bold text-gray-800 text-lg"><?= esc($s['nama']) ?></h3>
                <p class="text-gray-700 font-semibold text-sm">
                    <?= esc($s['no_pendaftaran']) ?>
                </p>
                <p class="text-gray-600 text-sm"><?= esc($s['email']) ?></p>
                <p class="text-gray-600 text-sm"><?= esc($s['no_hp']) ?></p>
                <p class="text-gray-600 text-sm"><?= esc($s['alamat']) ?></p>

                <span class="inline-block mt-1 px-2 py-1 rounded-full text-xs font-semibold <?= $class ?>">
                    <?= $label ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 mt-4">

            <?php if ($canToggle): ?>
            <form method="POST" action="<?= base_url('/siswa/toggle-status/'.$s['id']) ?>" class="col-span-3">
                <?= csrf_field() ?>
                <button class="w-full px-2 py-2 rounded-lg text-xs font-semibold transition-all
                    <?= $status=='aktif' ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' ?>">
                    <?= $status=='aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                </button>
            </form>
            <?php endif; ?>

            <!-- EDIT -->
            <button class="btnEdit col-span-3 bg-indigo-100 text-indigo-700 px-2 py-2 rounded-lg text-xs font-semibold hover:bg-indigo-200 transition-all"
                    data-id="<?= $s['id'] ?>"
                    data-nama="<?= esc($s['nama'], 'attr') ?>"
                    data-email="<?= esc($s['email'], 'attr') ?>"
                    data-nohp="<?= esc($s['no_hp'], 'attr') ?>"
                    data-alamat="<?= esc($s['alamat'], 'attr') ?>"
                    data-tgl="<?= esc($s['tgl_lahir'], 'attr') ?>"
                    data-foto="<?= esc($s['foto_profil'], 'attr') ?>">
                <i class="fa fa-pen"></i>
            </button>

            <!-- DELETE -->
            <?php if ($canDelete): ?>
            <form method="POST" action="<?= base_url('/siswa/delete/'.$s['id']) ?>" class="col-span-2"
                onsubmit="return confirm('Hapus siswa ini?')">
                <?= csrf_field() ?>
                <button class="w-full bg-red-100 text-red-700 px-2 py-2 rounded-lg text-xs font-semibold hover:bg-red-200 transition-all">
                    <i class="fa fa-trash mr-1"></i>Hapus
                </button>
            </form>
            <?php endif; ?>

        </div>

    </div>
<?php endforeach; endif; ?>
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

<!-- MODAL MULTI STEP -->
<div id="modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">

    <div id="modalBox"
         class="modal-content bg-white w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden transform
                transition-all duration-300 opacity-0 scale-95">

        <!-- HEADER -->
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white text-xl font-bold">Tambah Siswa</h3>
            <button id="btnCloseModal" class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- STEP INDICATOR -->
        <div class="flex justify-between px-6 py-4 bg-gray-50 border-b-2 border-gray-200 text-sm font-semibold">
            <div class="wizard-step flex-1 text-center transition-all" data-step="1">
                <span class="inline-block px-3 py-1 rounded-full">1. Data Diri</span>
            </div>
            <div class="wizard-step flex-1 text-center text-gray-400 transition-all" data-step="2">
                <span class="inline-block px-3 py-1 rounded-full">2. Kontak</span>
            </div>
            <div class="wizard-step flex-1 text-center text-gray-400 transition-all" data-step="3">
                <span class="inline-block px-3 py-1 rounded-full">3. Review</span>
            </div>
        </div>

        <!-- FORM -->
        <form id="modalForm" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" id="siswa_id" name="id">

            <div class="p-6 space-y-6 max-h-[60vh] overflow-y-auto">

                <!-- STEP 1 -->
                <div id="step1" class="wizard-page">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Nama Lengkap</label>
                            <input id="siswa_nama" name="nama" required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Tanggal Lahir</label>
                            <input id="siswa_tgl" name="tgl_lahir" type="date" required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="font-semibold text-gray-700 mb-2 block">Foto Profil</label>
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex flex-col items-center">
                                <img id="fotoPreview"
                                     src="<?= base_url('uploads/default.png') ?>"
                                     class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200 photo-preview-empty mb-2">
                                <span class="text-xs text-gray-500 text-center">Preview Foto</span>
                            </div>
                            <div class="file-input-wrapper flex-1">
                                <input id="fotoInput" name="foto_profil" type="file"
                                       accept="image/*" 
                                       class="w-full border-2 border-gray-200 rounded-lg p-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div id="step2" class="wizard-page hidden space-y-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Email</label>
                        <input id="siswa_email" name="email" type="email"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="email@example.com" required>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">No HP</label>
                        <input id="siswa_nohp" name="no_hp"
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="08xxxxxxxxxx" required>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Alamat</label>
                        <textarea id="siswa_alamat" name="alamat"
                                  class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                  rows="3"
                                  placeholder="Alamat lengkap" required></textarea>
                    </div>
                </div>

                <!-- STEP 3 (REVIEW) -->
                <div id="step3" class="wizard-page hidden">

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border-2 border-gray-200 space-y-3">

                        <div class="flex justify-between py-2 border-b border-gray-300">
                            <strong class="text-gray-600">Nama:</strong> 
                            <span id="rev_nama" class="font-semibold text-gray-800"></span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-300">
                            <strong class="text-gray-600">Tanggal Lahir:</strong> 
                            <span id="rev_tgl" class="font-semibold text-gray-800"></span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-300">
                            <strong class="text-gray-600">Email:</strong> 
                            <span id="rev_email" class="font-semibold text-gray-800"></span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-300">
                            <strong class="text-gray-600">No HP:</strong> 
                            <span id="rev_nohp" class="font-semibold text-gray-800"></span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-300">
                            <strong class="text-gray-600">Alamat:</strong> 
                            <span id="rev_alamat" class="font-semibold text-gray-800"></span>
                        </div>

                        <div class="mt-4 pt-4 border-t-2 border-gray-300">
                            <strong class="text-gray-600 block mb-2">Foto Profil:</strong>
                            <img id="rev_foto"
                                 src="<?= base_url('uploads/default.png') ?>"
                                 class="w-32 h-32 rounded-lg object-cover border-2 border-gray-300 shadow-md">
                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER BUTTONS -->
            <div class="p-4 md:p-5 bg-gray-50 border-t-2 border-gray-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">

                <div class="flex gap-2 order-2 sm:order-1">
                    <button type="button" id="btnCancelModal"
                            class="btn-hover flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                        Batal
                    </button>

                    <button id="btnPrevStep" type="button"
                            class="btn-hover hidden flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                        <i class="fa fa-chevron-left mr-1"></i><span class="hidden sm:inline">Sebelumnya</span><span class="sm:hidden">Prev</span>
                    </button>
                </div>

                <div class="flex gap-2 order-1 sm:order-2">
                    <button id="btnNextStep" type="button"
                            class="btn-hover flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-gradient-to-r from-primary to-secondary text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                        <span class="hidden sm:inline">Lanjut</span><span class="sm:hidden">Next</span><i class="fa fa-chevron-right ml-1"></i>
                    </button>

                    <button id="btnSubmit" type="submit"
                            class="btn-hover hidden flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
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

        rows.forEach(row => row.style.display = "");
        cards.forEach(card => card.style.display = "");

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
            const name = item.dataset.name || '';
            const email = item.dataset.email || '';
            const nohp = item.dataset.nohp || '';
            const alamat = item.dataset.alamat || '';
            const noPendaftaran = item.dataset.nomorPendaftaran || '';
            
            return name.includes(keyword)
                || email.includes(keyword)
                || nohp.includes(keyword)
                || alamat.includes(keyword)
                || noPendaftaran.includes(keyword);
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

    //MODAL MULTI STEP
    const modal = document.getElementById("modal");
    const modalBox = document.getElementById("modalBox");
    const modalTitle = document.getElementById("modalTitle");
    const modalForm = document.getElementById("modalForm");

    const btnOpenCreate = document.getElementById("btnOpenCreate");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const btnCancelModal = document.getElementById("btnCancelModal");

    const btnNextStep = document.getElementById("btnNextStep");
    const btnPrevStep = document.getElementById("btnPrevStep");
    const btnSubmit = document.getElementById("btnSubmit");

    const wizardSteps = document.querySelectorAll(".wizard-step");
    const wizardPages = document.querySelectorAll(".wizard-page");

    let currentStep = 1;
    let isEditMode = false;

    function openModal(mode = 'create') {
        isEditMode = (mode === 'edit');
        currentStep = 1;

        modalTitle.textContent = isEditMode ? "Edit Siswa" : "Tambah Siswa";
        
        if (!isEditMode) {
            modalForm.reset();
            document.getElementById("siswa_id").value = "";
            document.getElementById("fotoPreview").src = "<?= base_url('uploads/default.png') ?>";
        }

        updateStepDisplay();
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

    function updateStepDisplay() {
        wizardPages.forEach(page => page.classList.add("hidden"));
        document.getElementById(`step${currentStep}`).classList.remove("hidden");

        wizardSteps.forEach((step, index) => {
            const stepNum = index + 1;
            const span = step.querySelector("span");
            
            step.classList.remove("text-gray-400", "text-primary", "text-green-600");
            span.classList.remove("bg-primary", "text-white", "bg-green-100", "text-green-700");
            
            if (stepNum === currentStep) {
                step.classList.add("text-primary");
                span.classList.add("bg-primary", "text-white");
            } else if (stepNum < currentStep) {
                step.classList.add("text-primary");
                span.classList.add("bg-primary", "text-white");
            } else {
                step.classList.add("text-gray-400");
            }
        });

        btnPrevStep.classList.toggle("hidden", currentStep === 1);
        btnNextStep.classList.toggle("hidden", currentStep === 3);
        btnSubmit.classList.toggle("hidden", currentStep !== 3);

        if (currentStep === 3) {
            updateReview();
        }
    }

    function updateReview() {
        document.getElementById("rev_nama").textContent = 
            document.getElementById("siswa_nama").value || "-";
        
        document.getElementById("rev_tgl").textContent = 
            document.getElementById("siswa_tgl").value || "-";
        
        document.getElementById("rev_email").textContent = 
            document.getElementById("siswa_email").value || "-";
        
        document.getElementById("rev_nohp").textContent = 
            document.getElementById("siswa_nohp").value || "-";
        
        document.getElementById("rev_alamat").textContent = 
            document.getElementById("siswa_alamat").value || "-";
        
        document.getElementById("rev_foto").src = 
            document.getElementById("fotoPreview").src;
    }

    btnOpenCreate.addEventListener("click", () => openModal('create'));
    btnCloseModal.addEventListener("click", closeModal);
    btnCancelModal.addEventListener("click", closeModal);

    btnNextStep.addEventListener("click", () => {
        if (currentStep < 3) {
            // Validasi Step 1
            if (currentStep === 1) {
                const nama = document.getElementById("siswa_nama").value.trim();
                const tgl = document.getElementById("siswa_tgl").value;
                
                if (!nama) {
                    alert("Nama lengkap harus diisi!");
                    document.getElementById("siswa_nama").focus();
                    return;
                }
                
                if (!tgl) {
                    alert("Tanggal lahir harus diisi!");
                    document.getElementById("siswa_tgl").focus();
                    return;
                }
            }
            
            // Validasi Step 2
            if (currentStep === 2) {
                const email = document.getElementById("siswa_email").value.trim();
                const nohp = document.getElementById("siswa_nohp").value.trim();
                const alamat = document.getElementById("siswa_alamat").value.trim();
                
                if (!email) {
                    alert("Email harus diisi!");
                    document.getElementById("siswa_email").focus();
                    return;
                }
                
                // Validasi format email
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    alert("Format email tidak valid!");
                    document.getElementById("siswa_email").focus();
                    return;
                }
                
                if (!nohp) {
                    alert("No HP harus diisi!");
                    document.getElementById("siswa_nohp").focus();
                    return;
                }
                
                if (!alamat) {
                    alert("Alamat harus diisi!");
                    document.getElementById("siswa_alamat").focus();
                    return;
                }
            }
            
            currentStep++;
            updateStepDisplay();
        }
    });

    btnPrevStep.addEventListener("click", () => {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    });

    modalForm.addEventListener("submit", function(e) {
        const id = document.getElementById("siswa_id").value;
        
        if (id) {
            this.action = "<?= base_url('/siswa/update/') ?>" + id;
        } else {
            this.action = "<?= base_url('/siswa/create') ?>";
        }
    });

    //FOTO PREVIEW
    const fotoInput = document.getElementById("fotoInput");
    const fotoPreview = document.getElementById("fotoPreview");

    fotoInput.addEventListener("change", function() {
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                fotoPreview.src = e.target.result;
                fotoPreview.classList.remove("photo-preview-empty");
            };
            
            reader.readAsDataURL(file);
        }
    });

    //EDIT BUTTON
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const email = this.dataset.email;
            const nohp = this.dataset.nohp;
            const alamat = this.dataset.alamat;
            const tgl = this.dataset.tgl;
            const foto = this.dataset.foto;

            document.getElementById("siswa_id").value = id;
            document.getElementById("siswa_nama").value = nama;
            document.getElementById("siswa_email").value = email;
            document.getElementById("siswa_nohp").value = nohp;
            document.getElementById("siswa_alamat").value = alamat;
            document.getElementById("siswa_tgl").value = tgl;
            
            // Set foto preview dengan base_url
            const fotoUrl = foto ? "<?= base_url('uploads/siswa/') ?>" + foto : "<?= base_url('uploads/default.png') ?>";
            document.getElementById("fotoPreview").src = fotoUrl;

            openModal('edit');
        });
    });

    modal.addEventListener("click", function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    updatePagination();
</script>

<?= $this->endSection() ?>
