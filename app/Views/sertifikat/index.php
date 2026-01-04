<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Kelola Sertifikat<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Manajemen sertifikat siswa yang telah lulus<?= $this->endSection() ?>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
    }
    
    .gradient-border {
        position: relative;
    }
    
    .gradient-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #667eea, #764ba2);
        border-radius: 12px 12px 0 0;
    }
</style>

<!-- ================= HEADER ================= -->
<div class="bg-gradient-to-r from-primary to-secondary p-6 rounded-xl shadow-lg mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-white text-2xl font-bold mb-2">Kelola Sertifikat</h2>
            <p class="text-white/90 text-sm">
                Manajemen sertifikat siswa yang telah menyelesaikan kursus
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3 text-white">
                <p class="text-xs opacity-90">Sudah Cetak</p>
                <p class="text-2xl font-bold"><?= $totalSudahCetak ?></p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3 text-white">
                <p class="text-xs opacity-90">Belum Cetak</p>
                <p class="text-2xl font-bold"><?= $totalBelumCetak ?></p>
            </div>
        </div>
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

<?php if(session()->getFlashdata('warning')): ?>
<div class="p-4 rounded-lg bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 mb-4 shadow-sm">
    <i class="fa fa-exclamation-triangle mr-2"></i><?= session()->getFlashdata('warning') ?>
</div>
<?php endif ?>

<!-- ================= QUICK ACTIONS ================= -->
<div class="mb-6">
    <a href="<?= base_url('sertifikat/siswa-lulus') ?>" 
       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all">
        <i class="fa fa-graduation-cap"></i>
        <span>Generate Sertifikat Siswa Lulus</span>
    </a>
</div>

<!-- ================= FILTER SECTION ================= -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <form method="GET" action="<?= base_url('sertifikat') ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-flag text-primary mr-1"></i> Status Cetak
                </label>
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    <option value="">Semua Status</option>
                    <option value="belum_cetak" <?= ($filters['status'] ?? '') == 'belum_cetak' ? 'selected' : '' ?>>Belum Cetak</option>
                    <option value="sudah_cetak" <?= ($filters['status'] ?? '') == 'sudah_cetak' ? 'selected' : '' ?>>Sudah Cetak</option>
                </select>
            </div>
            
            <!-- Tanggal Start -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-calendar text-primary mr-1"></i> Tgl Lulus Mulai
                </label>
                <input type="date" 
                       name="tanggal_start" 
                       value="<?= $filters['tanggal_start'] ?? '' ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            
            <!-- Tanggal End -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-calendar text-primary mr-1"></i> Tgl Lulus Selesai
                </label>
                <input type="date" 
                       name="tanggal_end" 
                       value="<?= $filters['tanggal_end'] ?? '' ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            
            <!-- Paket -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-box text-primary mr-1"></i> Paket Kursus
                </label>
                <select name="paket_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    <option value="">Semua Paket</option>
                    <?php foreach($listPaket as $paket): ?>
                        <option value="<?= $paket['id'] ?>" <?= ($filters['paket_id'] ?? '') == $paket['id'] ? 'selected' : '' ?>>
                            <?= $paket['nama_paket'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Search -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-search text-primary mr-1"></i> Cari
                </label>
                <input type="text" 
                       name="search" 
                       value="<?= $filters['search'] ?? '' ?>"
                       placeholder="Nama / No Sertifikat"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-lg hover:shadow-lg transition-all flex items-center justify-center gap-2">
                <i class="fa fa-filter"></i>
                <span>Terapkan Filter</span>
            </button>
            
            <a href="<?= base_url('sertifikat') ?>" 
               class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                <i class="fa fa-times"></i>
                <span>Reset Filter</span>
            </a>

            <div class="flex-1"></div>

            <a href="<?= base_url('sertifikat?export=excel' . (isset($filters['status']) ? '&status='.$filters['status'] : '') . (isset($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '') . (isset($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '') . (isset($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '')) ?>" 
               class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                <i class="fa fa-file-excel"></i>
                <span>Export Excel</span>
            </a>

            <a href="<?= base_url('sertifikat/?export=pdf' . (isset($filters['status']) ? '&status='.$filters['status'] : '') . (isset($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '') . (isset($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '') . (isset($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '')) ?>"
               class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                <i class="fa fa-file-pdf"></i>
                <span>Export PDF</span>
            </a>
        </div>
    </form>
    
    <!-- Active Filters Info -->
    <?php if(!empty($filters['status']) || !empty($filters['tanggal_start']) || !empty($filters['tanggal_end']) || !empty($filters['paket_id']) || !empty($filters['search'])): ?>
    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm text-gray-600 font-semibold">Filter Aktif:</span>
            
            <?php if(!empty($filters['status'])): ?>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                <i class="fa fa-flag mr-1"></i>
                <?= ucwords(str_replace('_', ' ', $filters['status'])) ?>
            </span>
            <?php endif; ?>
            
            <?php if(!empty($filters['tanggal_start']) || !empty($filters['tanggal_end'])): ?>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                <i class="fa fa-calendar mr-1"></i>
                <?= !empty($filters['tanggal_start']) ? date('d/m/Y', strtotime($filters['tanggal_start'])) : '...' ?>
                - 
                <?= !empty($filters['tanggal_end']) ? date('d/m/Y', strtotime($filters['tanggal_end'])) : '...' ?>
            </span>
            <?php endif; ?>
            
            <?php if(!empty($filters['paket_id'])): ?>
            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">
                <i class="fa fa-box mr-1"></i>
                <?php 
                    $selectedPaket = array_filter($listPaket, fn($p) => $p['id'] == $filters['paket_id']);
                    $selectedPaket = reset($selectedPaket);
                    echo $selectedPaket['nama_paket'] ?? 'Paket';
                ?>
            </span>
            <?php endif; ?>
            
            <?php if(!empty($filters['search'])): ?>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                <i class="fa fa-search mr-1"></i>
                "<?= esc($filters['search']) ?>"
            </span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- ================= BATCH ACTIONS FORM ================= -->
<?php if(!empty($dataSertifikat)): ?>
<form method="POST" action="<?= base_url('sertifikat/cetak-batch') ?>" id="batchForm">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <input type="checkbox" id="selectAll" class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
            <label for="selectAll" class="font-semibold text-gray-700 cursor-pointer">Pilih Semua</label>
            <span class="text-sm text-gray-600" id="selectedCount">0 dipilih</span>
        </div>
        <button type="submit" 
                id="btnCetakBatch"
                disabled
                class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
            <i class="fa fa-print"></i>
            <span>Cetak Batch (ZIP)</span>
        </button>
    </div>
<?php endif; ?>

<!-- ================= DESKTOP TABLE VIEW ================= -->
<div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary to-secondary text-white">
                <tr>
                    <?php if(!empty($dataSertifikat)): ?>
                    <th class="px-4 py-4 text-center text-sm font-bold w-12">
                        <input type="checkbox" class="selectAllCheckbox w-5 h-5 rounded border-white/30">
                    </th>
                    <?php endif; ?>
                    <th class="px-4 py-4 text-left text-sm font-bold">No</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">No Sertifikat</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Nama Siswa</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Paket</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Instruktur</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Tgl Lulus</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Status</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($dataSertifikat)): ?>
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa fa-certificate text-4xl mb-3 block text-gray-300"></i>
                        <p class="font-semibold">Tidak ada data sertifikat</p>
                        <p class="text-sm mt-1">Generate sertifikat untuk siswa yang sudah lulus</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php 
                    $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                    foreach($dataSertifikat as $sertifikat): 
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" 
                                   name="sertifikat_ids[]" 
                                   value="<?= $sertifikat['id'] ?>"
                                   class="checkItem w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700"><?= $no++ ?></td>
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded font-semibold">
                                <?= $sertifikat['no_sertifikat'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= $sertifikat['nama_siswa'] ?></p>
                                <p class="text-xs text-gray-500"><?= $sertifikat['email'] ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= $sertifikat['nama_paket'] ?></p>
                                <p class="text-xs text-gray-500"><?= $sertifikat['level'] ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $sertifikat['nama_instruktur'] ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <?= date('d/m/Y', strtotime($sertifikat['tanggal_lulus'])) ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($sertifikat['status'] == 'sudah_cetak'): ?>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                    <i class="fa fa-check-circle mr-1"></i>Sudah Cetak
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                    <i class="fa fa-clock mr-1"></i>Belum Cetak
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?= base_url('sertifikat/preview/'.encode_id($sertifikat['id'])) ?>"
                                   class="px-3 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded hover:bg-blue-600 transition-colors"
                                   title="Preview">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="<?= base_url('sertifikat/cetak/'.encode_id($sertifikat['id'])) ?>" 
                                   class="px-3 py-1.5 bg-red-500 text-white text-xs font-semibold rounded hover:bg-red-600 transition-colors"
                                   title="Cetak PDF">
                                    <i class="fa fa-print"></i>
                                </a>
                                <a href="<?= base_url('sertifikat/delete/'.$sertifikat['id']) ?>" 
                                   class="px-3 py-1.5 bg-gray-500 text-white text-xs font-semibold rounded hover:bg-gray-600 transition-colors"
                                   onclick="confirmDelete(event, '<?= base_url('sertifikat/delete/'.$sertifikat['id']) ?>')"
                                   title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MOBILE CARD VIEW ================= -->
<div class="lg:hidden space-y-4 mb-6">
    <?php if(empty($dataSertifikat)): ?>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <i class="fa fa-certificate text-6xl text-gray-300 mb-4"></i>
        <p class="font-semibold text-gray-700 text-lg mb-2">Tidak ada data sertifikat</p>
        <p class="text-sm text-gray-500">Generate sertifikat untuk siswa yang sudah lulus</p>
    </div>
    <?php else: ?>
        <?php 
        $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
        foreach($dataSertifikat as $sertifikat): 
        ?>
        <div class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-5">
                <!-- Checkbox & Number -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               name="sertifikat_ids[]" 
                               value="<?= $sertifikat['id'] ?>"
                               class="checkItem w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full font-mono">
                            #<?= $no++ ?>
                        </span>
                    </div>
                    <?php if($sertifikat['status'] == 'sudah_cetak'): ?>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                            <i class="fa fa-check-circle mr-1"></i>Sudah Cetak
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                            <i class="fa fa-clock mr-1"></i>Belum Cetak
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- No Sertifikat -->
                <div class="bg-purple-50 rounded-lg p-3 mb-4">
                    <p class="text-xs text-purple-600 mb-1">Nomor Sertifikat</p>
                    <p class="text-sm font-bold text-purple-800 font-mono"><?= $sertifikat['no_sertifikat'] ?></p>
                </div>
                
                <!-- Siswa Info -->
                <div class="mb-4">
                    <p class="font-bold text-gray-800 text-lg"><?= $sertifikat['nama_siswa'] ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?= $sertifikat['email'] ?></p>
                </div>
                
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Paket Kursus</p>
                        <p class="text-sm font-semibold text-gray-800"><?= $sertifikat['nama_paket'] ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= $sertifikat['level'] ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Instruktur</p>
                        <p class="text-sm font-semibold text-gray-800"><?= $sertifikat['nama_instruktur'] ?></p>
                    </div>
                </div>
                
                <!-- Tanggal Lulus -->
                <div class="bg-blue-50 rounded-lg p-3 mb-4">
                    <p class="text-xs text-blue-600 mb-1">
                        <i class="fa fa-calendar mr-1"></i> Tanggal Lulus
                    </p>
                    <p class="text-sm font-semibold text-blue-800">
                        <?= date('d F Y', strtotime($sertifikat['tanggal_lulus'])) ?>
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-2">
                    <a href="<?= base_url('sertifikat/preview/'.encode_id($sertifikat['id'])) ?>" 
                       class="px-4 py-2.5 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 transition-colors text-center">
                        <i class="fa fa-eye"></i>
                        <span class="block text-xs mt-1">Preview</span>
                    </a>
                    <a href="<?= base_url('sertifikat/cetak/'.encode_id($sertifikat['id'])) ?>" 
                       class="px-4 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition-colors text-center">
                        <i class="fa fa-print"></i>
                        <span class="block text-xs mt-1">Cetak</span>
                    </a>
                    <a href="<?= base_url('sertifikat/delete/'.$sertifikat['id']) ?>" 
                       class="px-4 py-2.5 bg-gray-500 text-white text-sm font-semibold rounded-lg hover:bg-gray-600 transition-colors text-center"
                       onclick="confirmDelete(event, '<?= base_url('sertifikat/delete/'.$sertifikat['id']) ?>')">
                        <i class="fa fa-trash"></i>
                        <span class="block text-xs mt-1">Hapus</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if(!empty($dataSertifikat)): ?>
</form>
<?php endif; ?>

<!-- ================= PAGINATION ================= -->
<div class="mt-6 flex justify-center items-center gap-4">
    <button id="btnPrev" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all disabled:opacity-50 disabled:cursor-not-allowed">
        <i class="fa fa-chevron-left mr-1"></i>Prev
    </button>
    <div class="text-sm text-gray-600 font-medium">
        Halaman <span id="currentPage" class="font-bold text-primary">1</span> dari <span id="totalPages" class="font-bold">1</span>
    </div>
    <button id="btnNext" 
            class="px-5 py-2.5 border-2 border-gray-300 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all disabled:opacity-50 disabled:cursor-not-allowed">
        Next<i class="fa fa-chevron-right ml-1"></i>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    
    // ========== CHECKBOX SELECT ALL LOGIC (FIXED: No Double Count) ==========
    const selectAllMain = document.getElementById('selectAll');
    const selectAllTable = document.querySelector('.selectAllCheckbox');
    const selectedCount = document.getElementById('selectedCount');
    const btnCetakBatch = document.getElementById('btnCetakBatch');
    const batchForm = document.getElementById('batchForm');
    
    // Function to get ACTIVE checkboxes only (desktop OR mobile, not both)
    function getActiveCheckboxes() {
        const isMobile = window.innerWidth < 1024;
        if (isMobile) {
            return document.querySelectorAll('.lg\\:hidden input[name="sertifikat_ids[]"]');
        } else {
            return document.querySelectorAll('.hidden.lg\\:block input[name="sertifikat_ids[]"]');
        }
    }
    
    // Function update counter & button state
    function updateSelectionState() {
        const checkItems = getActiveCheckboxes();
        const checkedCount = Array.from(checkItems).filter(item => item.checked).length;
        
        if (selectedCount) {
            selectedCount.textContent = `${checkedCount} dipilih`;
        }
        
        if (btnCetakBatch) {
            if (checkedCount > 0) {
                btnCetakBatch.disabled = false;
                btnCetakBatch.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btnCetakBatch.disabled = true;
                btnCetakBatch.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
        
        const allChecked = checkItems.length > 0 && checkedCount === checkItems.length;
        const someChecked = checkedCount > 0 && checkedCount < checkItems.length;
        
        if (selectAllMain) {
            selectAllMain.checked = allChecked;
            selectAllMain.indeterminate = someChecked;
        }
        if (selectAllTable) {
            selectAllTable.checked = allChecked;
            selectAllTable.indeterminate = someChecked;
        }
    }
    
    // Select All - Main (atas)
    if (selectAllMain) {
        selectAllMain.addEventListener('change', function() {
            const isChecked = this.checked;
            const checkItems = getActiveCheckboxes();
            checkItems.forEach(item => item.checked = isChecked);
            if (selectAllTable) selectAllTable.checked = isChecked;
            updateSelectionState();
        });
    }
    
    // Select All - Table Header (desktop)
    if (selectAllTable) {
        selectAllTable.addEventListener('change', function() {
            const isChecked = this.checked;
            const checkItems = getActiveCheckboxes();
            checkItems.forEach(item => item.checked = isChecked);
            if (selectAllMain) selectAllMain.checked = isChecked;
            updateSelectionState();
        });
    }
    
    // Individual checkboxes - attach to ALL checkboxes
    const allCheckboxes = document.querySelectorAll('input[name="sertifikat_ids[]"]');
    allCheckboxes.forEach(item => {
        item.addEventListener('change', updateSelectionState);
    });
    
    updateSelectionState();
    
    // ========== FORM SUBMIT CONFIRMATION (CETAK BATCH) - UBAH KE SWAL ==========
    if (batchForm) {
        batchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get only ACTIVE checkboxes based on viewport
            const checkItems = getActiveCheckboxes();
            const checkedBoxes = Array.from(checkItems).filter(item => item.checked);
            const uniqueValues = [...new Set(checkedBoxes.map(box => box.value))];
            
            if (uniqueValues.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Pilih minimal 1 sertifikat untuk dicetak!',
                    confirmButtonColor: '#667eea',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                });
                return false;
            }
            
            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Cetak Batch',
                html: `
                    <p class="mb-2">Cetak <strong>${uniqueValues.length} sertifikat</strong> dalam format ZIP?</p>
                    <p class="text-sm text-gray-500">Proses ini mungkin memakan waktu beberapa saat.</p>
                `,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa fa-print mr-2"></i>Ya, Cetak!',
                cancelButtonText: 'Batal',
                position: 'center',
                showClass: {
                    popup: 'swal2-show',
                    backdrop: 'swal2-backdrop-show'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable semua checkbox yang ga dipilih
                    allCheckboxes.forEach(box => {
                        if (!box.checked) {
                            box.disabled = true;
                        }
                    });
                    
                    // Show loading
                    btnCetakBatch.disabled = true;
                    btnCetakBatch.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i><span>Memproses...</span>';
                    
                    // Submit form
                    batchForm.submit();
                    
                    // Reset button after download starts
                    setTimeout(() => {
                        btnCetakBatch.innerHTML = '<i class="fa fa-print"></i><span>Cetak Batch (ZIP)</span>';
                        btnCetakBatch.disabled = false;
                        allCheckboxes.forEach(box => box.disabled = false);
                        updateSelectionState();
                    }, 3000);
                }
            });
        });
    }
    
    // ========== SINGLE CETAK BUTTON  ==========
    const cetakButtons = document.querySelectorAll('a[href*="/sertifikat/cetak/"]');
    cetakButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const originalHTML = this.innerHTML;
            const originalOpacity = this.style.opacity;
            const originalPointer = this.style.pointerEvents;
            
            // Show loading
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            this.style.pointerEvents = 'none';
            this.style.opacity = '0.7';
            
            // Reset after 3 seconds (download should start by then)
            setTimeout(() => {
                this.innerHTML = originalHTML;
                this.style.pointerEvents = originalPointer || 'auto';
                this.style.opacity = originalOpacity || '1';
            }, 3000);
        });
    });
    
    // ========== PREVIEW & DELETE BUTTONS ==========
    const previewButtons = document.querySelectorAll('a[href*="/sertifikat/preview/"]');
    previewButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            setTimeout(() => {
                this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                this.style.pointerEvents = 'none';
                this.style.opacity = '0.7';
            }, 100);
        });
    });
    
    // ========== AUTO HIDE FLASH MESSAGES ==========
    const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-yellow-50');
    flashMessages.forEach(msg => {
        if(!msg.classList.contains('fixed')) {
            setTimeout(() => {
                msg.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-10px)';
                setTimeout(() => msg.remove(), 500);
            }, 5000);
        }
    });
    
    // ========== TABLE ROW ANIMATION ==========
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        if (!row.querySelector('td[colspan]')) {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            setTimeout(() => {
                row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        }
    });
    
    // ========== MOBILE CARD ANIMATION ==========
    const mobileCards = document.querySelectorAll('.lg\\:hidden .card-hover');
    mobileCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // ========== EXPORT BUTTON LOADING STATE ==========
    const exportButtons = document.querySelectorAll('a[href*="export="]');
    exportButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i><span>Mengunduh...</span>';
            this.style.pointerEvents = 'none';
            this.style.opacity = '0.7';
            
            setTimeout(() => {
                this.innerHTML = originalHTML;
                this.style.pointerEvents = 'auto';
                this.style.opacity = '1';
            }, 3000);
        });
    });
    
    // ========== KEYBOARD SHORTCUTS ==========
    document.addEventListener('keydown', function(e) {
        // Ctrl+A or Cmd+A = Select All
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && getActiveCheckboxes().length > 0) {
            e.preventDefault();
            if (selectAllMain) {
                selectAllMain.checked = !selectAllMain.checked;
                selectAllMain.dispatchEvent(new Event('change'));
            }
        }
        
        // Escape = Deselect All
        if (e.key === 'Escape') {
            if (selectAllMain) selectAllMain.checked = false;
            if (selectAllTable) selectAllTable.checked = false;
            allCheckboxes.forEach(item => item.checked = false);
            updateSelectionState();
        }
    });
    
    // ========== PAGINATION ==========
    let currentPage = 1;
    let totalPages = 1;

    function getVisibleItems() {
        const isMobile = window.innerWidth < 1024;
        const rows = Array.from(document.querySelectorAll('.hidden.lg\\:block.bg-white tbody tr'));
        const cards = Array.from(document.querySelectorAll('.lg\\:hidden.space-y-4 > div'));
        
        return isMobile ? cards : rows;
    }

    function updatePaginationView() {
        const isMobile = window.innerWidth < 1024;
        const allRows = Array.from(document.querySelectorAll('.hidden.lg\\:block.bg-white tbody tr'));
        const allCards = Array.from(document.querySelectorAll('.lg\\:hidden.space-y-4 > div'));
        
        // Hide semua
        allRows.forEach(row => row.style.display = "none");
        allCards.forEach(card => card.style.display = "none");
        
        const visibleItems = getVisibleItems();
        const perPage = isMobile ? 5 : 10;

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
            updatePaginationView(); 
        }
    });

    document.getElementById("btnNext").addEventListener("click", () => {
        if (currentPage < totalPages) { 
            currentPage++; 
            updatePaginationView(); 
        }
    });

    window.addEventListener("resize", () => {
        currentPage = 1;
        updatePaginationView();
        updateSelectionState();
    });

    // Initialize pagination
    updatePaginationView();
    
    // ========== NUMBER ANIMATION FOR COUNTERS ==========
    const counterElements = document.querySelectorAll('.bg-white\\/20 .text-2xl.font-bold');
    counterElements.forEach(el => {
        const finalValue = parseInt(el.textContent);
        if (finalValue > 0 && finalValue <= 100) {
            let currentValue = 0;
            const increment = Math.ceil(finalValue / 20);
            const duration = 800;
            const stepTime = duration / 20;
            
            const counter = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(counter);
                }
                el.textContent = currentValue;
            }, stepTime);
        }
    });
    
    // ========== STATUS BADGE HOVER EFFECT ==========
    const statusBadges = document.querySelectorAll('.px-3.py-1.rounded-full');
    statusBadges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.2s ease';
        });
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // ========== HOVER EFFECT FOR ACTION BUTTONS ==========
    const actionBtns = document.querySelectorAll('a[href*="preview"], a[href*="cetak"], a[href*="delete"]');
    actionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.2s ease';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// ========== FUNCTION CONFIRM DELETE (UBAH KE SWAL) ==========
// Taruh di luar DOMContentLoaded agar bisa dipanggil dari inline onclick
function confirmDelete(e, url) {
    e.preventDefault();
    
    Swal.fire({
        icon: 'warning',
        title: 'Konfirmasi Hapus',
        text: 'Yakin hapus sertifikat ini? Data tidak bisa dikembalikan!',
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
            window.location.href = url;
        }
    });
}
</script>

<?= $this->endSection() ?>