<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Jadwal Kelas<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Manajemen jadwal kelas kursus<?= $this->endSection() ?>
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

    /* Wrapper tabel biar bisa scroll horizontal */
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Smooth scroll di mobile */
    }

    /* Atur minimum width kolom biar gak terlalu sempit */
    .table-wrapper table {
        min-width: 1280px; /* Sesuaikan dengan kebutuhan */
    }

    /* Custom scrollbar biar lebih keren */
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
        <h2 class="text-white text-2xl font-bold">Jadwal Kelas</h2>
        <p class="text-white/90 text-sm mt-1">
            Daftar jadwal kelas kursus
        </p>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-3 w-full md:w-auto">
        <!-- SEARCH -->
        <div class="w-full md:w-80">
            <div class="relative">
                <input id="searchInput" type="search"
                       class="w-full rounded-lg px-4 py-2.5 shadow-sm border border-gray-200 focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                       placeholder="Cari hari, paket, atau ruang..." autocomplete="off">
                <button id="btnClearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-gray-400 hover:text-gray-600 text-lg transition-colors">
                    ✕
                </button>
            </div>
        </div>

        <!-- TAMBAH JADWAL -->
        <?php if (in_array($role, ['admin','operator'])): ?>
            <button id="btnOpenCreate"
                    class="btn-hover bg-white text-primary font-bold px-5 py-2.5 rounded-lg shadow hover:shadow-lg transition-all w-full md:w-auto flex items-center justify-center gap-2">
                <i class="fa fa-plus"></i> Tambah Jadwal
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
                <th class="py-4 px-4">Hari</th>
                <th class="py-4 px-4">Jam</th>
                <th class="py-4 px-4">Instruktur</th>
                <th class="py-4 px-4">Paket</th>
                <th class="py-4 px-4">Periode Belajar</th>
                <th class="py-4 px-4">Ruang</th>
                <th class="py-4 px-4">Jumlah Siswa</th>
                <th class="py-4 px-4">Aksi</th>
            </tr>
            </thead>

            <tbody id="tableBody" class="divide-y divide-gray-100">
            <?php if (!empty($kelas)): $i = 1; foreach ($kelas as $k): ?>
            <tr class="table-row hover:bg-gray-50 transition-colors"
                    data-search="<?= strtolower($k['hari'].' '.$k['nama_paket'].' '.$k['nama_ruang']) ?>">
                <td class="px-4 py-4 text-center font-medium text-gray-600"><?= $i++ ?></td>
                <td class="px-4 py-4 font-semibold text-gray-800"><?= esc($k['hari']) ?></td>
                <td class="px-4 py-4 text-gray-600">
                    <?= date('H:i', strtotime($k['jam_mulai'])) ?> - <?= date('H:i', strtotime($k['jam_selesai'])) ?>
                </td>
                <td class="px-4 py-4 text-gray-600"><?= esc($k['nama_instruktur']) ?></td>
                <td class="px-4 py-4 text-gray-600"><?= esc($k['nama_paket']) ?></td>
                <td class="px-4 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                        <?php if (!empty($k['periode_mulai']) && !empty($k['periode_selesai'])): ?>
                            <?= date('d M Y', strtotime($k['periode_mulai'])) ?> - <?= date('d M Y', strtotime($k['periode_selesai'])) ?>
                        <?php else: ?>
                            Belum Ditentukan
                        <?php endif; ?>
                    </span>
                </td>
                <td class="px-4 py-4 text-gray-600"><?= esc($k['nama_ruang']) ?></td>
                <td class="px-4 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        <?= $k['jumlah_siswa'] ?? 0 ?> Siswa
                    </span>
                </td>
                <td class="px-4 py-4">
                    <div class="flex flex-wrap justify-center gap-2">
                        <a href="<?= base_url('jadwal-kelas/'.$k['id']) ?>"
                           class="btn-hover px-4 py-2 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-all whitespace-nowrap">
                            <i class="fa fa-eye mr-1"></i>Detail
                        </a>
                        <?php if (in_array($role, ['admin','operator'])): ?>
                            <button 
                                class="btnAssign btn-hover px-4 py-2 bg-green-100 text-green-700 rounded-lg text-xs font-semibold hover:bg-green-200 transition-all whitespace-nowrap"
                                data-id="<?= $k['id'] ?>"
                                data-hari="<?= esc($k['hari'], 'attr') ?>"
                                data-paket="<?= esc($k['nama_paket'], 'attr') ?>"
                                data-paketid="<?= $k['paket_id'] ?>">
                                <i class="fa fa-user-plus mr-1"></i>Assign Siswa
                            </button>
                            <button 
                                class="btnEdit btn-hover px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold hover:bg-yellow-200 transition-all whitespace-nowrap"
                                data-id="<?= $k['id'] ?>"
                                data-paket="<?= $k['paket_id'] ?>"
                                data-ruang="<?= $k['ruang_kelas_id'] ?>"
                                data-instruktur="<?= $k['instruktur_id'] ?>"
                                data-hari="<?= esc($k['hari'], 'attr') ?>"
                                data-jamMulai="<?= esc($k['jam_mulai'], 'attr') ?>"
                                data-jamSelesai="<?= esc($k['jam_selesai'], 'attr') ?>">
                                <i class="fa fa-pen mr-1"></i>Edit
                            </button>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="9" class="text-center py-8 text-gray-500">
                    <i class="fa fa-calendar-times text-4xl mb-3 opacity-20"></i>
                    <p>Belum ada jadwal kelas.</p>
                </td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
    </div>
    
    <!-- HINT SCROLL - DI SINI, DI DALAM WRAPPER DESKTOP TABLE -->
    <div class="text-center text-xs text-gray-500 p-2 bg-gray-50 border-t">
        <i class="fa fa-arrows-alt-h mr-1"></i>Geser tabel ke kanan untuk melihat lebih banyak
    </div>
</div>

<!-- ================= MOBILE CARDS ================= -->
<div id="mobileCards" class="block md:hidden space-y-4">
    <?php if (!empty($kelas)): ?>
        <?php foreach ($kelas as $k): ?>
        <div class="card-item bg-white shadow-lg rounded-xl p-4 border border-gray-100 hover:shadow-xl transition-shadow"
             data-search="<?= strtolower($k['hari'].' '.$k['nama_paket'].' '.$k['nama_ruang']) ?>">
            
            <div class="space-y-3">
                <div class="flex justify-between items-start">
                    <h3 class="font-bold text-gray-800 text-lg"><?= esc($k['hari']) ?></h3>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        <?= $k['jumlah_siswa'] ?? 0 ?> Siswa
                    </span>
                </div>

                <div class="space-y-1 text-sm">
                    <p class="text-gray-600"><strong>Jam:</strong> <?= date('H:i', strtotime($k['jam_mulai'])) ?> - <?= date('H:i', strtotime($k['jam_selesai'])) ?></p>
                    <p class="text-gray-600"><strong>Instruktur:</strong> <?= esc($k['nama_instruktur']) ?></p>
                    <p class="text-gray-600"><strong>Paket:</strong> <?= esc($k['nama_paket']) ?></p>
                    <p class="text-gray-600">
                        <strong>Periode:</strong> 
                        <?php if (!empty($k['periode_mulai']) && !empty($k['periode_selesai'])): ?>
                            <?= date('d M Y', strtotime($k['periode_mulai'])) ?> - <?= date('d M Y', strtotime($k['periode_selesai'])) ?>
                        <?php else: ?>
                            Belum Ditentukan
                        <?php endif; ?>
                    </p>
                    <p class="text-gray-600"><strong>Ruang:</strong> <?= esc($k['nama_ruang']) ?></p>
                </div>

                <div class="grid grid-cols-1 gap-2 mt-4">
                    <a href="<?= base_url('jadwal-kelas/'.$k['id']) ?>"
                       class="text-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-200 transition-all">
                        <i class="fa fa-eye mr-1"></i>Detail Kelas
                    </a>

                    <?php if (in_array($role, ['admin','operator'])): ?>
                        <button 
                            class="btnAssign btn-hover px-4 py-2 bg-green-100 text-green-700 rounded-lg text-xs font-semibold hover:bg-green-200 transition-all whitespace-nowrap"
                            data-id="<?= $k['id'] ?>"
                            data-hari="<?= esc($k['hari'], 'attr') ?>"
                            data-paket="<?= esc($k['nama_paket'], 'attr') ?>"
                            data-paketid="<?= $k['paket_id'] ?>">
                            <i class="fa fa-user-plus mr-1"></i>Assign Siswa
                        </button>

                        <button 
                            class="btnEdit px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-semibold hover:bg-yellow-200 transition-all"
                            data-id="<?= $k['id'] ?>"
                            data-paket="<?= $k['paket_id'] ?>"
                            data-ruang="<?= $k['ruang_kelas_id'] ?>"
                            data-instruktur="<?= $k['instruktur_id'] ?>"
                            data-hari="<?= esc($k['hari'], 'attr') ?>"
                            data-jamMulai="<?= esc($k['jam_mulai'], 'attr') ?>"
                            data-jamSelesai="<?= esc($k['jam_selesai'], 'attr') ?>">
                            <i class="fa fa-pen mr-1"></i>Edit Jadwal
                        </button>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-white shadow-lg rounded-xl p-8 text-center">
            <i class="fa fa-calendar-times text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-medium">Belum ada jadwal kelas</p>
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

<!-- ================= MODAL CREATE/EDIT JADWAL ================= -->
<?php if (in_array($role, ['admin', 'operator'])): ?>
<div id="modalJadwal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">
    <div id="modalBox" class="modal-content bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center">
            <h3 id="modalTitle" class="text-white text-xl font-bold">Tambah Jadwal Kelas</h3>
            <button id="btnCloseModal"
                    class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- FORM -->
        <form id="modalForm" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" id="jadwal_id" name="id">

            <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                
                <!-- PAKET -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Paket Kursus</label>
                    <select id="jadwal_paket" name="paket_id" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="">-- Pilih Paket --</option>
                        <?php foreach ($paket as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= esc($p['nama_paket']) ?> - <?= esc($p['level']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- RUANG KELAS -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Ruang Kelas</label>
                    <select id="jadwal_ruang" name="ruang_kelas_id" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="">-- Pilih Ruang --</option>
                        <?php foreach ($ruang as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= esc($r['nama_ruang']) ?> (Kapasitas: <?= $r['kapasitas'] ?>)</option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- INSTRUKTUR -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Instruktur</label>
                    <select id="jadwal_instruktur" name="instruktur_id" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="">-- Pilih Instruktur --</option>
                        <?php foreach ($instruktur as $ins): ?>
                            <option value="<?= $ins['id'] ?>"><?= esc($ins['nama']) ?> - <?= esc($ins['keahlian']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- HARI -->
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Hari Kelas</label>
                    <div class="border-2 border-gray-200 rounded-lg p-4 space-y-2 max-h-48 overflow-y-auto">
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer transition-all">
                            <input type="checkbox" name="hari[]" value="Senin" class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary/20 rounded">
                            <span class="text-gray-700">Senin</span>
                        </label>
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer transition-all">
                            <input type="checkbox" name="hari[]" value="Selasa" class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary/20 rounded">
                            <span class="text-gray-700">Selasa</span>
                        </label>
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer transition-all">
                            <input type="checkbox" name="hari[]" value="Rabu" class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary/20 rounded">
                            <span class="text-gray-700">Rabu</span>
                        </label>
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer transition-all">
                            <input type="checkbox" name="hari[]" value="Kamis" class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary/20 rounded">
                            <span class="text-gray-700">Kamis</span>
                        </label>
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer transition-all">
                            <input type="checkbox" name="hari[]" value="Jumat" class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary/20 rounded">
                            <span class="text-gray-700">Jumat</span>
                        </label>
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer transition-all">
                            <input type="checkbox" name="hari[]" value="Sabtu" class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary/20 rounded">
                            <span class="text-gray-700">Sabtu</span>
                        </label>
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded cursor-pointer transition-all">
                            <input type="checkbox" name="hari[]" value="Minggu" class="w-4 h-4 text-primary focus:ring-2 focus:ring-primary/20 rounded">
                            <span class="text-gray-700">Minggu</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-info-circle mr-1"></i>Centang beberapa hari untuk jadwal kelas seminggu lebih dari 1x pertemuan
                    </p>
                </div>

                <!-- JAM MULAI & SELESAI -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Jam Mulai</label>
                        <input id="jadwal_jam_mulai" name="jam_mulai" type="time" step="60" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Jam Selesai</label>
                        <input id="jadwal_jam_selesai" name="jam_selesai" type="time" step="60" required
                           class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
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

<!-- ================= MODAL ASSIGN SISWA ================= -->
<div id="modalAssign" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">
    <div id="modalAssignBox" class="modal-content bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-5 flex justify-between items-center">
            <h3 id="modalAssignTitle" class="text-white text-xl font-bold">Assign Siswa ke Kelas</h3>
            <button id="btnCloseAssign"
                    class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- INFO KELAS -->
        <div class="p-4 bg-green-50 border-b-2 border-green-200">
            <p class="text-sm text-gray-700">
                <strong>Kelas:</strong> <span id="infoKelasHari"></span> | 
                <strong>Paket:</strong> <span id="infoKelasPaket"></span>
            </p>
        </div>

        <!-- FORM -->
        <form id="formAssign" method="POST" action="<?= base_url('jadwal-kelas/assign-siswa') ?>">
            <?= csrf_field() ?>
            <input type="hidden" id="assign_jadwal_id" name="jadwal_kelas_id">

            <div class="p-6 space-y-4 max-h-[50vh] overflow-y-auto">
                <div>
                    <label class="font-semibold text-gray-700 mb-2 block">Pilih Siswa</label>
                    <select id="assign_siswa" name="pendaftaran_id" required
                            class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach ($pendaftaran as $daftar): ?>
                            <option value="<?= $daftar['id'] ?>" data-paket="<?= $daftar['paket_id'] ?>">
                                <?= esc($daftar['nama']) ?> - <?= esc($daftar['nama_paket']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fa fa-info-circle mr-1"></i>Hanya siswa dengan paket yang sesuai yang bisa di-assign
                    </p>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="p-5 bg-gray-50 border-t-2 border-gray-200 flex justify-end gap-3">
                <button type="button" id="btnCancelAssign"
                        class="btn-hover px-5 py-2.5 bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="btn-hover px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                    <i class="fa fa-user-plus mr-1"></i>Assign Siswa
                </button>
            </div>
        </form>
    </div>
</div>

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

// ==================== MODAL JADWAL KELAS ====================
<?php if (in_array($role, ['admin', 'operator'])): ?>
const modalJadwal = document.getElementById("modalJadwal");
const modalBox = document.getElementById("modalBox");
const modalTitle = document.getElementById("modalTitle");
const modalForm = document.getElementById("modalForm");

const btnOpenCreate = document.getElementById("btnOpenCreate");
const btnCloseModal = document.getElementById("btnCloseModal");
const btnCancelModal = document.getElementById("btnCancelModal");

let isEditMode = false;

function openModalJadwal(mode = 'create') {
    isEditMode = (mode === 'edit');
    
    modalTitle.textContent = isEditMode ? "Edit Jadwal Kelas" : "Tambah Jadwal Kelas";
    
    if (!isEditMode) {
        modalForm.reset();
        document.getElementById("jadwal_id").value = "";
    }
    
    modalJadwal.classList.remove("hidden");
    
    setTimeout(() => {
        modalBox.classList.remove("opacity-0", "scale-95");
        modalBox.classList.add("opacity-100", "scale-100");
    }, 10);
}

function closeModalJadwal() {
    modalBox.classList.remove("opacity-100", "scale-100");
    modalBox.classList.add("opacity-0", "scale-95");
    
    setTimeout(() => {
        modalJadwal.classList.add("hidden");
    }, 300);
}

if (btnOpenCreate) {
    btnOpenCreate.addEventListener("click", () => openModalJadwal('create'));
}

btnCloseModal.addEventListener("click", closeModalJadwal);
btnCancelModal.addEventListener("click", closeModalJadwal);

modalForm.addEventListener("submit", function(e) {
    const id = document.getElementById("jadwal_id").value;
    
    const checkedDays = document.querySelectorAll('input[name="hari[]"]:checked');
    if (checkedDays.length === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Minimal 1 Hari!',
            text: 'Anda harus memilih minimal 1 hari untuk jadwal kelas.',
            confirmButtonColor: '#667eea'
        });
        return;
    }
    
    if (id) {
        this.action = "<?= base_url('jadwal-kelas/update/') ?>" + id;
    } else {
        this.action = "<?= base_url('jadwal-kelas/create') ?>";
    }
});

document.querySelectorAll(".btnEdit").forEach(btn => {
    btn.addEventListener("click", function() {
        const id = this.dataset.id;
        const paket = this.dataset.paket;
        const ruang = this.dataset.ruang;
        const instruktur = this.dataset.instruktur;
        const hari = this.dataset.hari;
        const jamMulai = this.dataset.jammulai;
        const jamSelesai = this.dataset.jamselesai;

        document.getElementById("jadwal_id").value = id;
        document.getElementById("jadwal_paket").value = paket;
        document.getElementById("jadwal_ruang").value = ruang;
        document.getElementById("jadwal_instruktur").value = instruktur;
        
        document.querySelectorAll('input[name="hari[]"]').forEach(cb => cb.checked = false);

        const hariArray = hari.split(',').map(h => h.trim());
        document.querySelectorAll('input[name="hari[]"]').forEach(cb => {
            if (hariArray.includes(cb.value)) {
                cb.checked = true;
            }
        });
        document.getElementById("jadwal_jam_mulai").value = jamMulai;
        document.getElementById("jadwal_jam_selesai").value = jamSelesai;

        openModalJadwal('edit');
    });
});

modalJadwal.addEventListener("click", function(e) {
    if (e.target === modalJadwal) {
        closeModalJadwal();
    }
});
<?php endif; ?>

// ==================== MODAL ASSIGN SISWA ====================
const modalAssign = document.getElementById("modalAssign");
const modalAssignBox = document.getElementById("modalAssignBox");
const btnCloseAssign = document.getElementById("btnCloseAssign");
const btnCancelAssign = document.getElementById("btnCancelAssign");

function openModalAssign() {
    modalAssign.classList.remove("hidden");
    
    setTimeout(() => {
        modalAssignBox.classList.remove("opacity-0", "scale-95");
        modalAssignBox.classList.add("opacity-100", "scale-100");
    }, 10);
}

function closeModalAssign() {
    modalAssignBox.classList.remove("opacity-100", "scale-100");
    modalAssignBox.classList.add("opacity-0", "scale-95");
    
    setTimeout(() => {
        modalAssign.classList.add("hidden");
    }, 300);
}

btnCloseAssign.addEventListener("click", closeModalAssign);
btnCancelAssign.addEventListener("click", closeModalAssign);

document.querySelectorAll(".btnAssign").forEach(btn => {
    btn.addEventListener("click", function() {
        const id = this.dataset.id;
        const hari = this.dataset.hari;
        const paket = this.dataset.paket;
        const paketId = this.dataset.paketid; 

        document.getElementById("assign_jadwal_id").value = id;
        document.getElementById("infoKelasHari").textContent = hari;
        document.getElementById("infoKelasPaket").textContent = paket;

        const selectSiswa = document.getElementById("assign_siswa");
        const allOptions = selectSiswa.querySelectorAll("option");
        
        allOptions.forEach(option => {
            if (option.value === "") {
                option.style.display = ""; 
                return;
            }
            
            const optionPaketId = option.dataset.paket;
            if (optionPaketId === paketId) {
                option.style.display = ""; 
            } else {
                option.style.display = "none";
            }
        });

        openModalAssign();
    });
});

modalAssign.addEventListener("click", function(e) {
    if (e.target === modalAssign) {
        closeModalAssign();
    }
});

// ==================== INITIALIZE ====================
updatePagination();
</script>

<?= $this->endSection() ?>