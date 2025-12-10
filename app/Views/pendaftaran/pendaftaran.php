<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Manajemen Pendaftaran<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Kelola pendaftaran siswa Sonata Violin<?= $this->endSection() ?>
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
        <h2 class="text-white text-2xl font-bold">Manajemen Pendaftaran</h2>
        <p class="text-white/90 text-sm mt-1">Kelola pendaftaran siswa Sonata Violin</p>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">

        <!-- SEARCH BAR -->
        <div class="w-full md:w-80">
            <div class="relative">
                <input id="searchInput" type="search"
                       class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                       placeholder="Cari nama, email, atau status..." autocomplete="off">
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
<div class="hidden md:block bg-white shadow-lg rounded-xl overflow-hidden">
    <table class="w-full table-auto">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
        <tr>
            <th class="py-4 px-4">#</th>
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
            } else {
                $labelStatus = 'Pending';
                $statusClass = 'bg-yellow-100 text-yellow-700';
            }
        ?>
        <tr class="table-row hover:bg-gray-50 transition-colors"
            data-nama="<?= strtolower(esc($p['nama'] ?? '')) ?>"
            data-email="<?= strtolower(esc($p['email'] ?? '')) ?>"
            data-status="<?= strtolower(esc($p['status'] ?? '')) ?>">

            <td class="py-4 px-4 text-center font-medium text-gray-600"><?= $i++ ?></td>

            <td class="py-4 px-4 font-semibold text-gray-800"><?= esc($p['nama'] ?? '-') ?></td>
            <td class="py-4 px-4 text-gray-600"><?= esc($p['email'] ?? '-') ?></td>

            <td class="py-4 px-4 text-gray-600">
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
                <form method="POST" action="<?= base_url('/pendaftaran/selesai/'.$p['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="btn-hover bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-all">
                        Tandai Selesai
                    </button>
                </form>
                <?php endif; ?>

                <!-- EDIT -->
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

                <!-- DELETE (Only Admin) -->
                <?php if(session()->get('role') === 'admin'): ?>
                <form method="POST" action="<?= base_url('/pendaftaran/delete/'.$p['id']) ?>"
                      onsubmit="return confirm('Yakin ingin menghapus (arsip) data pendaftaran ini?')">
                    <?= csrf_field() ?>
                    <button class="btn-hover bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-red-200 transition-all">
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
        } else {
            $labelStatus = 'Pending';
            $statusClass = 'bg-yellow-100 text-yellow-700';
        }
    ?>
    <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
         data-nama="<?= strtolower(esc($p['nama'] ?? '')) ?>"
         data-email="<?= strtolower(esc($p['email'] ?? '')) ?>"
         data-status="<?= strtolower(esc($p['status'] ?? '')) ?>">

        <div class="flex gap-4">
            <div class="flex-1">
                <h3 class="font-bold text-gray-800 text-lg"><?= esc($p['nama'] ?? '-') ?></h3>
                <p class="text-gray-600 text-sm"><?= esc($p['email'] ?? '-') ?></p>
                <p class="text-gray-600 text-sm">
                    <?= esc($p['paket_nama'] ?? ('Paket #' . ($p['paket_id'] ?? '-'))) ?>
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
            <form method="POST" action="<?= base_url('/pendaftaran/selesai/'.$p['id']) ?>" class="col-span-2">
                <?= csrf_field() ?>
                <button class="w-full px-2 py-2 rounded-lg text-xs font-semibold transition-all bg-blue-100 text-blue-700 hover:bg-blue-200">
                    Tandai Selesai
                </button>
            </form>
            <?php endif; ?>

            <button class="btnEdit col-span-1 bg-indigo-100 text-indigo-700 px-2 py-2 rounded-lg text-xs font-semibold hover:bg-indigo-200 transition-all"
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

            <?php if(session()->get('role') === 'admin'): ?>
            <form method="POST" action="<?= base_url('/pendaftaran/delete/'.$p['id']) ?>"
                  class="col-span-1"
                  onsubmit="return confirm('Yakin ingin menghapus (arsip) data pendaftaran ini?')">
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

<!-- MODAL MULTI STEP (4 STEP) -->
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
                                   placeholder="08xxxxxxxxxx">
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
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="">-- Pilih Paket --</option>

                            <?php if(!empty($paket)): foreach($paket as $pk): ?>

                                <?php  
                                    // Ekstrak angka durasi (contoh: "3 bulan", "2 Minggu", "6 Bulan") → 3 / 2 / 6
                                    $durasiInt = 0;
                                    if (!empty($pk['durasi'])) {
                                        preg_match('/\d+/', $pk['durasi'], $m);
                                        $durasiInt = intval($m[0] ?? 0);
                                    }
                                ?>

                                <option 
                                    value="<?= $pk['id'] ?>"
                                    data-durasi="<?= $durasiInt ?>" 
                                    data-harga="<?= intval($pk['harga'] ?? 0) ?>">
                                    
                                    <?= esc($pk['nama_paket'] ?? $pk['nama']) ?>

                                    <?php if(isset($pk['durasi'])): ?>
                                        (Durasi: <?= esc($pk['durasi']) ?>)
                                    <?php endif; ?>

                                    <?php if(isset($pk['harga'])): ?>
                                        - Rp <?= number_format($pk['harga'], 0, ',', '.') ?>
                                    <?php endif; ?>
                                </option>

                            <?php endforeach; endif; ?>
                        </select>

                        <p class="text-xs text-gray-500 mt-1">
                            * Durasi paket akan digunakan untuk menghitung tanggal selesai otomatis.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Tanggal Mulai</label>
                            <input id="pd_tanggal_mulai" name="tanggal_mulai" type="date" required
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700 mb-2 block">Tanggal Selesai</label>
                            <input id="pd_tanggal_selesai" name="tanggal_selesai" type="date" readonly
                                class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 text-gray-600">
                            <p class="text-xs text-gray-500 mt-1">
                                * Otomatis dihitung dari tanggal mulai + durasi paket.
                            </p>
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
                                    Format: .jpg, .png, dan .pdf
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
                            class="flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                        Batal
                    </button>

                    <button id="btnPrevStep" type="button"
                            class="hidden flex-1 sm:flex-none px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
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
            const email = el.dataset.email || "";
            const status = el.dataset.status || "";
            return nama.includes(keyword) || email.includes(keyword) || status.includes(keyword);
        };

        rows.forEach(row => row.style.display = filterFn(row) ? "" : "none");
        cards.forEach(card => card.style.display = filterFn(card) ? "" : "none");

        updatePagination();
    }

    searchInput.addEventListener("input", applySearch);
    btnClearSearch.addEventListener("click", () => {
        searchInput.value = "";
        btnClearSearch.classList.add("hidden");
        applySearch();
    });

    //PAGINATION
    let currentPage = 1;
    let totalPages = 1;

    function getItemsPerPage() {
        return window.innerWidth < 768 ? 3 : 8;
    }

    function getVisibleItems() {
        const isMobile = window.innerWidth < 768;
        const rows = Array.from(document.querySelectorAll(".table-row")).filter(r => r.style.display !== "none");
        const cards = Array.from(document.querySelectorAll(".card-item")).filter(c => c.style.display !== "none");
        return isMobile ? cards : rows;
    }

    function updatePagination() {
        const items = getVisibleItems();
        const perPage = getItemsPerPage();
        totalPages = Math.ceil(items.length / perPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;

        items.forEach((item, index) => {
            const pageNum = Math.floor(index / perPage) + 1;
            item.style.display = pageNum === currentPage ? "" : "none";
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

    window.addEventListener("resize", updatePagination);

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

    //WIZARD STEP DISPLAY
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

    //VALIDASI TIAP STEP
    btnNextStep.addEventListener("click", () => {

        if (currentStep === 1) {
            if (!pd_nama.value.trim()) return alert("Nama wajib diisi!");
            if (!pd_tgl_lahir.value) return alert("Tanggal lahir wajib diisi!");
            if (!pd_email.value.trim()) return alert("Email wajib diisi!");
            if (!pd_nohp.value.trim()) return alert("No HP wajib diisi!");
            if (!pd_alamat.value.trim()) return alert("Alamat wajib diisi!");
        }

        if (currentStep === 2) {
            if (!paketSelect.value) return alert("Paket wajib dipilih!");
            if (!tglMulai.value) return alert("Tanggal mulai wajib diisi!");
        }

        if (currentStep === 3) {
            if (!nominal.value || nominal.value <= 0) return alert("Nominal tidak valid!");
            if (!pd_buktiInput.files.length && !isEditMode)
                return alert("Bukti pembayaran wajib diupload!");
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

    //AUTO HITUNG TANGGAL SELESAI
    const paketSelect = document.getElementById("pd_paket_id");
    const tglMulai = document.getElementById("pd_tanggal_mulai");
    const tglSelesai = document.getElementById("pd_tanggal_selesai");
    const nominal = document.getElementById("pd_nominal");

    function formatDateInput(date) {
        const pad = (n) => String(n).padStart(2, "0");
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
    }

    function hitungTanggalSelesai() {
        const opt = paketSelect.options[paketSelect.selectedIndex];
        if (!opt || !tglMulai.value) return;

        const durasi = Number(opt.dataset.durasi);
        const mulai = new Date(tglMulai.value + "T00:00:00");
        mulai.setMonth(mulai.getMonth() + durasi);

        tglSelesai.value = formatDateInput(mulai);
    }

    paketSelect.addEventListener("change", () => {
        const harga = paketSelect.options[paketSelect.selectedIndex]?.dataset.harga;
        if (harga) nominal.value = harga;
        hitungTanggalSelesai();
    });

    tglMulai.addEventListener("change", hitungTanggalSelesai);

    //FOTO + BUKTI PREVIEW
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

    //FORM ACTION
    const modalForm = document.getElementById("modalForm");

    modalForm.addEventListener("submit", function () {
        const id = document.getElementById("pendaftaran_id").value;
        this.action = id
            ? "<?= base_url('/pendaftaran/update/') ?>" + id
            : "<?= base_url('/pendaftaran/create') ?>";
    });


    //EDIT BUTTON (FIX FOTO + BUKTI + STATUS PENDING ONLY)
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {

            if (this.dataset.status !== "pending") {
                alert("Pendaftaran yang sudah aktif tidak boleh diedit!");
                return;
            }

            document.getElementById("pendaftaran_id").value = this.dataset.id;

            pd_nama.value = this.dataset.nama;
            pd_email.value = this.dataset.email;
            pd_nohp.value = this.dataset.nohp;
            pd_alamat.value = this.dataset.alamat;
            pd_tgl_lahir.value = this.dataset.tgl;
            paketSelect.value = this.dataset.paketId;
            tglMulai.value = this.dataset.tglMulai;
            tglSelesai.value = this.dataset.tglSelesai;
            nominal.value = this.dataset.nominal;

            pd_fotoPreview.src = this.dataset.foto
                ? "<?= base_url('uploads/pendaftaran/') ?>" + this.dataset.foto
                : "<?= base_url('uploads/pendaftaran/default.png') ?>";

            pd_buktiPreview.src = this.dataset.bukti
                ? "<?= base_url('uploads/bukti_pembayaran/') ?>" + this.dataset.bukti
                : "<?= base_url('uploads/bukti_pembayaran/default.png') ?>";

            const opt = paketSelect.options[paketSelect.selectedIndex];
                if (opt && opt.dataset.harga) {
                    nominal.value = opt.dataset.harga;
                }

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
        rev_paket.textContent = paketSelect.options[paketSelect.selectedIndex]?.text || "-";
        rev_tanggal_mulai.textContent = tglMulai.value;
        rev_tanggal_selesai.textContent = tglSelesai.value;
        rev_nominal.textContent = "Rp " + Number(nominal.value || 0).toLocaleString("id-ID");
        rev_bukti.src = pd_buktiPreview.src;
    }

    updatePagination();
</script>

<?= $this->endSection() ?>
