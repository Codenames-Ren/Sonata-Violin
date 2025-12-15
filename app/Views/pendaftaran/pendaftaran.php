<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Manajemen Pendaftaran<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Kelola pendaftaran siswa Sonata Violin<?= $this->endSection() ?>
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
        <h2 class="text-white text-2xl font-bold">Manajemen Pendaftaran</h2>
        <p class="text-white/90 text-sm mt-1">Kelola pendaftaran siswa Sonata Violin</p>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">

        <!-- SEARCH BAR -->
        <div class="w-full md:w-80">
            <div class="relative">
                <input id="searchInput" type="search"
                       class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                       placeholder="Cari no pendaftaran, nama, email dan status" autocomplete="off">
                <button id="btnClearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-gray-400 hover:text-gray-600 text-lg transition-colors">
                    ✕
                </button>
            </div>
        </div>

        <!-- ADD BUTTON -->
        <button id="btnOpenCreate"
                class="btn-hover bg-white text-primary px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
            <i class="fa fa-plus"></i> Tambah Pendaftaran
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
<div class="hidden md:block bg-white shadow-lg rounded-xl overflow-visible">
    <table class="w-full table-auto">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
        <tr>
            <th class="py-4 px-4">No. Pendaftaran</th>
            <th class="py-4 px-4">Nama</th>
            <th class="py-4 px-4">Email</th>
            <th class="py-4 px-4">Paket</th>
            <th class="py-4 px-4">Mulai</th>
            <th class="py-4 px-4">Selesai</th>
            <th class="py-4 px-4">Status</th>
            <th class="py-4 px-4">Aksi</th>
        </tr>
        </thead>

        <tbody id="tableBody" class="divide-y divide-gray-100">
        <?php if(!empty($pendaftaran)): $i=1; foreach($pendaftaran as $p): ?>
        <?php
            $status = $p['status'] ?? 'pending';
            if ($status === 'aktif') {
                $labelStatus = 'Aktif';
                $statusClass = 'bg-green-100 text-green-700';
            } elseif ($status === 'selesai') {
                $labelStatus = 'Selesai';
                $statusClass = 'bg-blue-100 text-blue-700';
            } elseif ($status === 'batal') {
                $labelStatus = 'Batal';
                $statusClass = 'bg-red-100 text-red-700';
            } elseif ($status === 'mundur') {
                $labelStatus = 'Mengundurkan Diri';
                $statusClass = 'bg-orange-100 text-orange-700';
            } else {
                $labelStatus = 'Pending';
                $statusClass = 'bg-yellow-100 text-yellow-700';
            }
        ?>
        <tr class="table-row hover:bg-gray-50 transition-colors"
            data-nama="<?= strtolower(esc($p['nama'] ?? '')) ?>"
            data-nomor-pendaftaran="<?= strtolower(esc($p['no_pendaftaran'] ?? '')) ?>"
            data-email="<?= strtolower(esc($p['email'] ?? '')) ?>"
            data-status="<?= strtolower(esc($p['status'] ?? '')) ?>"
            data-label="<?= strtolower($labelStatus) ?>">

            <td class="py-4 px-4 font-medium text-gray-600 text-xs">
                <?= esc($p['no_pendaftaran']) ?>
            </td>

            <td class="py-4 px-4 font-semibold text-gray-800 text-sm"><?= esc($p['nama'] ?? '-') ?></td>
            <td class="py-4 px-4 text-gray-600 text-sm"><?= esc($p['email'] ?? '-') ?></td>

            <td class="py-4 px-4 text-gray-600 text-sm">
                <?= esc($p['nama_paket'] ?? ('Paket - ' . ($p['paket_id'] ?? '-'))) ?>
            </td>

            <td class="py-4 px-4 text-gray-600 text-sm">
                <?= !empty($p['tanggal_mulai']) ? esc($p['tanggal_mulai']) : '-' ?>
            </td>

            <td class="py-4 px-4 text-gray-600 text-sm">
                <?= !empty($p['tanggal_selesai']) ? esc($p['tanggal_selesai']) : '-' ?>
            </td>

            <td class="py-4 px-4 text-center">
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
                    <?= $labelStatus ?>
                </span>
            </td>

            <td class="py-4 px-4 flex justify-center gap-2 flex-wrap">
                <!-- VERIFIKASI (hanya kalau pending) -->
                <?php if($status === 'pending'): ?>
                <form method="POST" action="<?= base_url('/pendaftaran/verifikasi/'.$p['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="btn-hover bg-green-100 text-green-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-green-200 transition-all">
                        Verifikasi
                    </button>
                </form>

                <form method="POST" action="<?= base_url('/pendaftaran/batal/'.$p['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="btn-hover bg-yellow-100 text-yellow-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-yellow-200 transition-all">
                        Batalkan
                    </button>
                </form>
                <?php endif; ?>

                <!-- SELESAIKAN (hanya kalau aktif) -->
                <?php if($status === 'aktif'): ?>
                <div class="relative inline-block">

                    <button type="button"
                        class="btn-hover bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-all 
                            dropdownToggle">
                        Aksi ▼
                    </button>

                    <div class="absolute hidden bg-white shadow-lg rounded-lg border border-gray-200 mt-1 w-40 z-20 dropdownMenu">

                        <form method="POST" action="<?= base_url('/pendaftaran/selesai/'.$p['id']) ?>">
                            <?= csrf_field() ?>
                            <button class="block w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                                Selesai Belajar
                            </button>
                        </form>

                        <form method="POST" action="<?= base_url('/pendaftaran/mundur/'.$p['id']) ?>">
                            <?= csrf_field() ?>
                            <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Mengundurkan Diri
                            </button>
                        </form>

                    </div>
                </div>
                <?php endif; ?>

                <!-- EDIT -->
                <?php if($status === 'pending' || $status === 'batal'): ?>
                <button class="btnEdit btn-hover bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-indigo-200 transition-all"
                    data-id="<?= $p['id'] ?>"
                    data-status="<?= $p['status'] ?>"
                    data-nama="<?= esc($p['nama'] ?? '', 'attr') ?>"
                    data-email="<?= esc($p['email'] ?? '', 'attr') ?>"
                    data-nohp="<?= esc($p['no_hp'] ?? '', 'attr') ?>"
                    data-alamat="<?= esc($p['alamat'] ?? '', 'attr') ?>"
                    data-tgl="<?= esc($p['tgl_lahir'] ?? '', 'attr') ?>"
                    data-foto="<?= esc($p['foto_profil'] ?? '', 'attr') ?>"
                    data-bukti="<?= esc($p['bukti_transaksi'] ?? '', 'attr') ?>"
                    data-status="<?= esc($p['status'] ?? '', 'attr') ?>"
                    data-paket-id="<?= esc($p['paket_id'] ?? '', 'attr') ?>"
                    data-tgl-mulai="<?= esc($p['tanggal_mulai'] ?? '', 'attr') ?>"
                    data-tgl-selesai="<?= esc($p['tanggal_selesai'] ?? '', 'attr') ?>"
                    data-nominal="<?= esc($p['nominal'] ?? '', 'attr') ?>">
                    <i class="fa fa-pen mr-1"></i>Edit
                </button>
                <?php endif; ?>

                <!-- DELETE (Only Admin) -->
                <?php if(session()->get('role') === 'admin'): ?>
                <form method="POST" action="<?= base_url('/pendaftaran/delete/'.$p['id']) ?>"
                    class="formDelete">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn-hover bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-red-200 transition-all">
                        <i class="fa fa-trash mr-1"></i>Hapus
                    </button>
                </form>
                <?php endif; ?>
            </td>

        </tr>
        <?php endforeach; else: ?>
        <tr>
            <td colspan="8" class="py-8 text-center text-gray-500">
                <i class="fa fa-file-alt text-4xl mb-3 opacity-20"></i>
                <p>Belum ada data pendaftaran.</p>
            </td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- MOBILE CARDS -->
<div id="mobileCards" class="md:hidden space-y-4">
    <?php if(!empty($pendaftaran)): foreach($pendaftaran as $p): 
        $status = $p['status'] ?? 'pending';
        if ($status === 'aktif') {
            $labelStatus = 'Aktif';
            $statusClass = 'bg-green-100 text-green-700';
        } elseif ($status === 'selesai') {
            $labelStatus = 'Selesai';
            $statusClass = 'bg-blue-100 text-blue-700';
        } elseif ($status === 'batal') {
            $labelStatus = 'Batal';
            $statusClass = 'bg-red-100 text-red-700';
        } elseif ($status === 'mundur') {
            $labelStatus = 'Mengundurkan Diri';
            $statusClass = 'bg-orange-100 text-orange-700';
        } else {
            $labelStatus = 'Pending';
            $statusClass = 'bg-yellow-100 text-yellow-700';
        }
    ?>
    <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
         data-nama="<?= strtolower(esc($p['nama'] ?? '')) ?>"
         data-nomor-pendaftaran="<?= strtolower(esc($p['no_pendaftaran'] ?? '')) ?>"
         data-email="<?= strtolower(esc($p['email'] ?? '')) ?>"
         data-status="<?= strtolower(esc($p['status'] ?? '')) ?>"
         data-label="<?= strtolower($labelStatus) ?>">

        <div class="flex gap-4">
            <div class="flex-1">
                <h3 class="font-bold text-gray-800 text-lg"><?= esc($p['nama'] ?? '-') ?></h3>
                <p class="text-gray-700 font-semibold text-sm">
                    <?= esc($p['no_pendaftaran']) ?>
                </p>
                <p class="text-gray-600 text-sm"><?= esc($p['email'] ?? '-') ?></p>
                <p class="text-gray-600 text-sm">
                    <?= esc($p['nama_paket'] ?? ('Paket - ' . ($p['paket_id'] ?? '-'))) ?>
                </p>
                <p class="text-gray-500 text-xs mt-1">
                    Mulai: <?= !empty($p['tanggal_mulai']) ? esc($p['tanggal_mulai']) : '-' ?><br>
                    Selesai: <?= !empty($p['tanggal_selesai']) ? esc($p['tanggal_selesai']) : '-' ?>
                </p>
                <span class="inline-block mt-2 px-2 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
                    <?= $labelStatus ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-4">
            <?php if($status === 'pending'): ?>
            <form method="POST" action="<?= base_url('/pendaftaran/verifikasi/'.$p['id']) ?>" class="col-span-1">
                <?= csrf_field() ?>
                <button class="w-full px-2 py-2 rounded-lg text-xs font-semibold transition-all bg-green-100 text-green-700 hover:bg-green-200">
                    Verifikasi
                </button>
            </form>

            <form method="POST" action="<?= base_url('/pendaftaran/batal/'.$p['id']) ?>" class="col-span-1">
                <?= csrf_field() ?>
                <button class="w-full px-2 py-2 rounded-lg text-xs font-semibold transition-all bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                    Batalkan
                </button>
            </form>
            <?php endif; ?>

            <?php if($status === 'aktif'): ?>
            <div class="col-span-2 relative">

                <button type="button"
                    class="w-full px-2 py-2 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700 hover:bg-blue-200 dropdownToggle">
                    Aksi ▼
                </button>

                <div class="absolute hidden bg-white shadow-lg rounded-lg border border-gray-200 mt-1 w-full z-20 dropdownMenu">

                    <form method="POST" action="<?= base_url('/pendaftaran/selesai/'.$p['id']) ?>">
                        <?= csrf_field() ?>
                        <button class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                            Selesai Belajar
                        </button>
                    </form>

                    <form method="POST" action="<?= base_url('/pendaftaran/mundur/'.$p['id']) ?>">
                        <?= csrf_field() ?>
                        <button class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Mengundurkan Diri
                        </button>
                    </form>

                </div>
            </div>
            <?php endif; ?>
            
            <?php if($status === 'pending' || $status === 'batal'): ?>
            <button class="btnEdit col-span-2 bg-indigo-100 text-indigo-700 px-2 py-2 rounded-lg text-xs font-semibold hover:bg-indigo-200 transition-all"
                data-id="<?= $p['id'] ?>"
                data-nama="<?= esc($p['nama'] ?? '', 'attr') ?>"
                data-email="<?= esc($p['email'] ?? '', 'attr') ?>"
                data-nohp="<?= esc($p['no_hp'] ?? '', 'attr') ?>"
                data-alamat="<?= esc($p['alamat'] ?? '', 'attr') ?>"
                data-tgl="<?= esc($p['tgl_lahir'] ?? '', 'attr') ?>"
                data-foto="<?= esc($p['foto_profil'] ?? '', 'attr') ?>"
                data-bukti="<?= esc($p['bukti_transaksi'] ?? '', 'attr') ?>"
                data-status="<?= esc($p['status'] ?? '', 'attr') ?>"
                data-paket-id="<?= esc($p['paket_id'] ?? '', 'attr') ?>"
                data-tgl-mulai="<?= esc($p['tanggal_mulai'] ?? '', 'attr') ?>"
                data-tgl-selesai="<?= esc($p['tanggal_selesai'] ?? '', 'attr') ?>"
                data-nominal="<?= esc($p['nominal'] ?? '', 'attr') ?>">
                <i class="fa fa-pen"></i>
            </button>
            <?php endif; ?>

            <?php if(session()->get('role') === 'admin'): ?>
            <form method="POST" action="<?= base_url('/pendaftaran/delete/'.$p['id']) ?>"
                class="col-span-2 formDelete">
                <?= csrf_field() ?>
                <button type="submit" class="w-full bg-red-100 text-red-700 px-2 py-2 rounded-lg text-xs font-semibold hover:bg-red-200 transition-all">
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
            <h3 id="modalTitle" class="text-white text-xl font-bold">Tambah Pendaftaran</h3>
            <button id="btnCloseModal" class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- STEP INDICATOR -->
        <div class="flex justify-between px-6 py-4 bg-gray-50 border-b-2 border-gray-200 text-sm font-semibold">
            <div class="wizard-step flex-1 text-center transition-all" data-step="1">
                <span class="inline-block px-3 py-1 rounded-full">1. Data Siswa</span>
            </div>
            <div class="wizard-step flex-1 text-center text-gray-400 transition-all" data-step="2">
                <span class="inline-block px-3 py-1 rounded-full">2. Paket Kursus</span>
            </div>
            <div class="wizard-step flex-1 text-center text-gray-400 transition-all" data-step="3">
                <span class="inline-block px-3 py-1 rounded-full">3. Pembayaran</span>
            </div>
            <div class="wizard-step flex-1 text-center text-gray-400 transition-all" data-step="4">
                <span class="inline-block px-3 py-1 rounded-full">4. Review</span>
            </div>
        </div>

        <!-- FORM -->
        <form id="modalForm" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" id="pendaftaran_id" name="id">

            <div class="p-6 space-y-6 max-h-[45vh] overflow-y-auto">

                <!-- STEP 1: DATA SISWA -->
                <div id="step1" class="wizard-page">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Nama Lengkap</label>
                            <input id="pd_nama" name="nama" required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Tanggal Lahir</label>
                            <input id="pd_tgl_lahir" name="tgl_lahir" type="date" required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Email</label>
                            <input id="pd_email" name="email" type="email" required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="email@example.com">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">No HP</label>
                            <input id="pd_nohp" name="no_hp" required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="+62xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="font-semibold text-gray-700 mb-2 block">Alamat</label>
                            <textarea id="pd_alamat" name="alamat" rows="3" required
                                      class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                      placeholder="Alamat lengkap"></textarea>
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Foto Profil</label>
                            <div class="flex flex-col items-center">
                                <img id="pd_fotoPreview"
                                     src="<?= base_url('uploads/pendaftaran/') ?>"
                                     class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200 photo-preview-empty mb-2">
                                <span class="text-xs text-gray-500 text-center mb-2">Preview Foto</span>
                                <div class="file-input-wrapper w-full">
                                    <input id="pd_fotoInput" name="foto_profil" type="file" accept="image/*"
                                           class="w-full border-2 border-gray-200 rounded-lg p-2.5 text-xs focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: PAKET KURSUS -->
                <div id="step2" class="wizard-page hidden space-y-4">

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Paket Kursus</label>

                        <select id="pd_paket_id" name="paket_id" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 bg-white 
                                focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">

                            <option value="">-- Pilih Paket --</option>

                            <?php if (!empty($paket)): foreach ($paket as $pk): ?>

                                <?php
                                    // Ambil angka durasi saja
                                    $durasiInt = preg_replace('/[^0-9]/', '', $pk['durasi']);
                                ?>

                                <option
                                    value="<?= $pk['id'] ?>"

                                    data-level="<?= ucfirst(esc($pk['level'] ?? '')) ?>"
                                    data-batch="<?= esc($pk['batch'] ?? '') ?>"

                                    data-mulai="<?= esc($pk['tanggal_mulai'] ?? '') ?>"
                                    data-selesai="<?= esc($pk['tanggal_selesai'] ?? '') ?>"

                                    data-durasi="<?= $durasiInt ?>"
                                    data-harga="<?= intval($pk['harga'] ?? 0) ?>"
                                >
                                    <?= esc($pk['nama_paket']) ?> 
                                    (<?= ucfirst($pk['level']) ?>)
                                    - Rp <?= number_format($pk['harga'], 0, ',', '.') ?>
                                </option>

                            <?php endforeach; endif; ?>
                        </select>

                        <p class="text-xs text-gray-500 mt-1">
                            * Tanggal mulai, selesai, level & batch otomatis muncul setelah memilih paket.
                        </p>
                    </div>

                    <!-- Level + Batch -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Level</label>
                            <input id="pd_level" type="text" readonly
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 bg-gray-100 text-gray-700">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Batch</label>
                            <input id="pd_batch" type="text" readonly
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 bg-gray-100 text-gray-700">
                        </div>

                    </div>

                    <!-- Tanggal Belajar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Tanggal Mulai Belajar</label>
                            <input id="pd_tanggal_mulai" name="tanggal_mulai" type="date" readonly
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 
                                    bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Tanggal Selesai Belajar</label>
                            <input id="pd_tanggal_selesai" name="tanggal_selesai" type="date" readonly
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 
                                    bg-gray-100 text-gray-700 cursor-not-allowed">
                        </div>

                    </div>

                </div>

                <!-- STEP 3: PEMBAYARAN -->
                <div id="step3" class="wizard-page hidden space-y-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Nominal Pembayaran</label>
                        <input id="pd_nominal" name="nominal" type="number" readonly
                               class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 text-gray-700">
                        <p class="text-xs text-gray-500 mt-1">
                            * Harga menyesuaikan dengan paket kursus yang dipilih
                        </p>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Upload Bukti Pembayaran</label>
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex flex-col items-center">
                                <img id="pd_buktiPreview"
                                     src="<?= base_url('uploads/bukti_pembayaran/') ?>"
                                     class="w-32 h-32 rounded-lg object-cover border-2 border-gray-200 photo-preview-empty mb-2">
                                <span class="text-xs text-gray-500 text-center">Preview Bukti</span>
                            </div>
                            <div class="file-input-wrapper flex-1">
                                <input id="pd_buktiInput" name="bukti_transaksi" type="file"
                                       accept="image/*,.pdf"
                                       class="w-full border-2 border-gray-200 rounded-lg p-2.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                <p class="text-xs text-gray-500 mt-1">
                                    Format: .jpg, dan .png
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: REVIEW -->
                <div id="step4" class="wizard-page hidden">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border-2 border-gray-200 space-y-4">

                        <h4 class="font-bold text-gray-800 mb-2">Ringkasan Data</h4>

                        <!-- DATA SISWA -->
                        <div class="space-y-2">
                            <h5 class="font-semibold text-gray-700">Data Siswa</h5>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Nama</span>
                                <span id="rev_nama" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Tanggal Lahir</span>
                                <span id="rev_tgl_lahir" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Email</span>
                                <span id="rev_email" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">No HP</span>
                                <span id="rev_nohp" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Alamat</span>
                                <span id="rev_alamat" class="font-semibold text-gray-800 text-right"></span>
                            </div>
                            <div class="mt-3">
                                <span class="text-xs text-gray-500 text-center mb-2">Preview Foto</span>
                                <img id="rev_foto_preview"
                                     src="<?= base_url('uploads/pendaftaran/') ?>"
                                     class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200 photo-preview-empty mb-2">
                            </div>
                        </div>

                        <!-- PAKET & JADWAL -->
                        <div class="space-y-2 pt-3 border-t border-gray-300">
                            <h5 class="font-semibold text-gray-700">Paket & Jadwal</h5>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Paket</span>
                                <span id="rev_paket" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Level</span>
                                <span id="rev_level" class="font-semibold text-gray-800"></span>
                            </div>

                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Batch</span>
                                <span id="rev_batch" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Tanggal Mulai</span>
                                <span id="rev_tanggal_mulai" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Tanggal Selesai</span>
                                <span id="rev_tanggal_selesai" class="font-semibold text-gray-800"></span>
                            </div>
                        </div>

                        <!-- PEMBAYARAN -->
                        <div class="space-y-2 pt-3 border-t border-gray-300">
                            <h5 class="font-semibold text-gray-700">Pembayaran</h5>
                            <div class="flex justify-between py-1 border-b border-gray-200 text-sm">
                                <span class="text-gray-600">Nominal</span>
                                <span id="rev_nominal" class="font-semibold text-gray-800"></span>
                            </div>
                            <div class="mt-3">
                                <span class="text-gray-600 block mb-1 text-sm">Bukti Pembayaran:</span>
                                <img id="rev_bukti"
                                     src="<?= base_url('uploads/bukti_pembayaran/') ?>"
                                     class="w-40 h-40 rounded-lg object-cover border-2 border-gray-300 shadow-md">
                            </div>
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

    const BASE_URL = "<?= base_url() ?>";

    //SEARCH
    const searchInput = document.getElementById("searchInput");
    const btnClearSearch = document.getElementById("btnClearSearch");

    function applySearch() {
        const keyword = searchInput.value.toLowerCase().trim();
        btnClearSearch.classList.toggle("hidden", keyword.length === 0);

        const rows = document.querySelectorAll(".table-row");
        const cards = document.querySelectorAll(".card-item");

        const filterFn = el => {
            const nama = el.dataset.nama || "";
            const noPendaftaran = el.dataset.nomorPendaftaran || "";
            const email = el.dataset.email || "";
            const status = el.dataset.status || "";
            const label  = el.dataset.label  || "";
            return nama.includes(keyword) || email.includes(keyword) || status.includes(keyword) || label.includes(keyword) || noPendaftaran.includes(keyword);
        };

        rows.forEach(row => row.style.display = filterFn(row) ? "" : "none");
        cards.forEach(card => card.style.display = filterFn(card) ? "" : "none");

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
            const noPendaftaran = item.dataset.nomorPendaftaran || '';
            const email = item.dataset.email || '';
            const status = item.dataset.status || '';
            const label = item.dataset.label || '';
            
            return nama.includes(keyword)
                || noPendaftaran.includes(keyword)
                || email.includes(keyword)
                || status.includes(keyword)
                || label.includes(keyword);
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

    //MODAL OPEN / CLOSE
    const modal = document.getElementById("modal");
    const modalBox = document.getElementById("modalBox");
    const btnOpenCreate = document.getElementById("btnOpenCreate");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const btnCancelModal = document.getElementById("btnCancelModal");

    let currentStep = 1;
    let isEditMode = false;

    btnOpenCreate.addEventListener("click", () => openModal("create"));
    btnCloseModal.addEventListener("click", closeModal);
    btnCancelModal.addEventListener("click", closeModal);

    modal.addEventListener("click", e => {
        if (e.target === modal) closeModal();
    });

    function openModal(mode) {
        currentStep = 1;
        isEditMode = (mode === "edit");

        document.getElementById("modalTitle").textContent = isEditMode ? "Edit Pendaftaran" : "Tambah Pendaftaran";

        if (!isEditMode) {
            modalForm.reset();
            document.getElementById("pendaftaran_id").value = "";

            pd_fotoPreview.src = "<?= base_url('uploads/pendaftaran/default.png') ?>";
            pd_buktiPreview.src = "<?= base_url('uploads/bukti_pembayaran/default.png') ?>";

            pd_level.value = "";
            pd_batch.value = "";
            pd_tanggal_mulai.value = "";
            pd_tanggal_selesai.value = "";
            nominal.value = "";
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

    //WIZARD STEPS
    const wizardSteps = document.querySelectorAll(".wizard-step");
    const wizardPages = document.querySelectorAll(".wizard-page");
    const btnNextStep = document.getElementById("btnNextStep");
    const btnPrevStep = document.getElementById("btnPrevStep");
    const btnSubmit = document.getElementById("btnSubmit");

    function updateStepDisplay() {
        wizardPages.forEach(p => p.classList.add("hidden"));
        document.getElementById(`step${currentStep}`).classList.remove("hidden");

        wizardSteps.forEach((step, idx) => {
            const span = step.querySelector("span");
            step.classList.remove("text-gray-400", "text-primary");
            span.classList.remove("bg-primary", "text-white");

            if (idx + 1 === currentStep) {
                step.classList.add("text-primary");
                span.classList.add("bg-primary", "text-white");
            }
        });

        btnPrevStep.classList.toggle("hidden", currentStep === 1);
        btnNextStep.classList.toggle("hidden", currentStep === 4);
        btnSubmit.classList.toggle("hidden", currentStep !== 4);

        if (currentStep === 4) updateReview();
    }

    //VALIDASI PER STEP
    btnNextStep.addEventListener("click", () => {

        if (currentStep === 1) {
            if (!pd_nama.value.trim()) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Nama wajib diisi!',
                    position: 'center', 
                    showClass: {
                        popup: 'swal2-show', 
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }

            if (!pd_tgl_lahir.value) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Tanggal lahir wajib diisi!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }
 
            if (!pd_email.value.trim()) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Email wajib diisi!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }

            if (!pd_nohp.value.trim()) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'No HP wajib diisi!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }

            if (!pd_alamat.value.trim()) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Alamat wajib diisi!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }
        }

        if (currentStep === 2) {
            if (!paketSelect.value) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Paket wajib dipilih!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }
        }

        if (currentStep === 3) {
            if (!nominal.value || nominal.value <= 0) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Nominal tidak valid!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }

            if (!pd_buktiInput.files.length && !isEditMode) {
                modal.classList.add('has-swal');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Bukti pembayaran wajib diupload!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then(() => {
                    modal.classList.remove('has-swal');
                });
                
                return;
            }
        }

        currentStep++;
        updateStepDisplay();
    });

    btnPrevStep.addEventListener("click", () => {
        if (currentStep > 1) {
            currentStep--;
            updateStepDisplay();
        }
    });

    //STEP 2 – AUTO FILL DATA PAKET
    const paketSelect = document.getElementById("pd_paket_id");
    const tglMulai = document.getElementById("pd_tanggal_mulai");
    const tglSelesai = document.getElementById("pd_tanggal_selesai");
    const nominal = document.getElementById("pd_nominal");
    const pd_level = document.getElementById("pd_level");
    const pd_batch = document.getElementById("pd_batch");

    function loadPaketData() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        if (!opt) return;

        pd_level.value = opt.dataset.level || "";
        pd_batch.value = opt.dataset.batch || "";

        if (opt.dataset.mulai) tglMulai.value = opt.dataset.mulai;
        if (opt.dataset.selesai) tglSelesai.value = opt.dataset.selesai;

        nominal.value = opt.dataset.harga || 0;
    }

    paketSelect.addEventListener("change", () => {
        loadPaketData();
    });

    //FOTO & BUKTI PREVIEW
    const pd_fotoInput = document.getElementById("pd_fotoInput");
    const pd_fotoPreview = document.getElementById("pd_fotoPreview");
    const pd_buktiInput = document.getElementById("pd_buktiInput");
    const pd_buktiPreview = document.getElementById("pd_buktiPreview");

    function previewFile(input, preview) {
        const file = input.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => preview.src = e.target.result;
        reader.readAsDataURL(file);
    }

    pd_fotoInput.addEventListener("change", () => previewFile(pd_fotoInput, pd_fotoPreview));
    pd_buktiInput.addEventListener("change", () => previewFile(pd_buktiInput, pd_buktiPreview));

    //FORM SUBMIT (CREATE / UPDATE)
    const modalForm = document.getElementById("modalForm");

    modalForm.addEventListener("submit", function () {
        const id = document.getElementById("pendaftaran_id").value;
        this.action = id
            ? "<?= base_url('/pendaftaran/update/') ?>" + id
            : "<?= base_url('/pendaftaran/create') ?>";
    });

    //EDIT MODE
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {

            if (this.dataset.status !== "pending") {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Bisa Edit!',
                    text: 'Pendaftaran yang sudah aktif tidak boleh diedit!',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                });
                
                return;
            }

            document.getElementById("pendaftaran_id").value = this.dataset.id;

            pd_nama.value = this.dataset.nama;
            pd_email.value = this.dataset.email;
            pd_nohp.value = this.dataset.nohp;
            pd_alamat.value = this.dataset.alamat;
            pd_tgl_lahir.value = this.dataset.tgl;

            paketSelect.value = this.dataset.paketId;
            loadPaketData();

            tglMulai.value = this.dataset.tglMulai;
            tglSelesai.value = this.dataset.tglSelesai;

            const opt = paketSelect.options[paketSelect.selectedIndex];
            nominal.value = opt?.dataset.harga || 0;

            pd_fotoPreview.src = this.dataset.foto
                ? "<?= base_url('uploads/pendaftaran/') ?>" + this.dataset.foto
                : "<?= base_url('uploads/pendaftaran/default.png') ?>";

            pd_buktiPreview.src = this.dataset.bukti
                ? "<?= base_url('uploads/bukti_pembayaran/') ?>" + this.dataset.bukti
                : "<?= base_url('uploads/bukti_pembayaran/default.png') ?>";

            openModal("edit");
        });
    });

    //REVIEW STEP
    function updateReview() {
        rev_nama.textContent = pd_nama.value;
        rev_tgl_lahir.textContent = pd_tgl_lahir.value;
        rev_email.textContent = pd_email.value;
        rev_nohp.textContent = pd_nohp.value;
        rev_alamat.textContent = pd_alamat.value;

        rev_foto_preview.src = pd_fotoPreview.src;

        const opt = paketSelect.options[paketSelect.selectedIndex];
        rev_paket.textContent = opt ? opt.text : "-";

        rev_level.textContent = pd_level.value || "-";
        rev_batch.textContent = pd_batch.value || "-";

        rev_tanggal_mulai.textContent = tglMulai.value || "-";
        rev_tanggal_selesai.textContent = tglSelesai.value || "-";

        rev_nominal.textContent = "Rp " + Number(nominal.value || 0).toLocaleString("id-ID");
        rev_bukti.src = pd_buktiPreview.src;
    }

    updatePagination();

    //DROPDOWN AKSI (SELESAI / MUNDUR)
    document.querySelectorAll(".dropdownToggle").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();

            const menu = this.nextElementSibling;
            menu.classList.toggle("hidden");

            document.querySelectorAll(".dropdownMenu").forEach(m => {
                if (m !== menu) m.classList.add("hidden");
            });
        });
    });

    document.addEventListener("click", () => {
        document.querySelectorAll(".dropdownMenu").forEach(m => m.classList.add("hidden"));
    });

    document.querySelectorAll('.formDelete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Yakin ingin hapus?',
                text: "Data pendaftaran ini akan diarsipkan!",
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
