<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Laporan Profit<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Data pemasukan dari pembayaran kursus yang telah disetujui<?= $this->endSection() ?>
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
            <div class="flex items-center gap-2 mb-2">
                <a href="<?= base_url('laporan') ?>" class="text-white/80 hover:text-white transition-colors">
                    <i class="text-2xl fa fa-arrow-left"></i>
                </a>
                <h2 class="text-white text-2xl font-bold">Laporan Profit</h2>
            </div>
            <p class="text-white/90 text-sm">
                Total pemasukan dari pembayaran kursus yang telah diverifikasi
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3 text-white">
                <p class="text-xs opacity-90">Total Profit</p>
                <p class="text-2xl font-bold">Rp <?= number_format($totalProfit, 0, ',', '.') ?></p>
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

<!-- ================= FILTER SECTION ================= -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <form method="GET" action="<?= base_url('laporan/profit') ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            
            <!-- Tanggal Start -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-calendar text-primary mr-1"></i> Tanggal Mulai
                </label>
                <input type="date" 
                       name="tanggal_start" 
                       value="<?= $filters['tanggal_start'] ?? '' ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
            </div>
            
            <!-- Tanggal End -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa fa-calendar text-primary mr-1"></i> Tanggal Selesai
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
                            <?= $paket['nama_paket'] ?> - <?= $paket['level'] ?>
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
                       placeholder="Nama / No Pendaftaran"
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
            
            <a href="<?= base_url('laporan/profit') ?>" 
               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                <i class="fa fa-times"></i>
                <span>Reset Filter</span>
            </a>

            <div class="flex-1"></div>

            <a href="<?= base_url('laporan/profit?export=excel' . (isset($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '') . (isset($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '') . (isset($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '')) ?>" 
               class="px-6 py-2.5 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-all flex items-center justify-center gap-2">
                <i class="fa fa-file-excel"></i>
                <span>Export Excel</span>
            </a>
            
            <a href="<?= base_url('laporan/profit?export=pdf' . (isset($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '') . (isset($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '') . (isset($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '')) ?>" 
               class="px-6 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                <i class="fa fa-file-pdf"></i>
                <span>Export PDF</span>
            </a>
        </div>
    </form>
    
    <!-- Active Filters Info -->
    <?php if(!empty($filters['tanggal_start']) || !empty($filters['tanggal_end']) || !empty($filters['paket_id']) || !empty($filters['search'])): ?>
    <div class="mt-4 pt-4 border-t border-gray-200">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm text-gray-600 font-semibold">Filter Aktif:</span>
            
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

<!-- ================= DATA INFO ================= -->
<div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6 shadow-sm">
    <div class="flex items-start">
        <i class="fa fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
        <div>
            <p class="text-sm text-blue-700">
                Menampilkan <strong><?= count($dataProfit) ?></strong> dari <strong><?= $pagination['total_data'] ?></strong> data profit.
                <?php if($pagination['total_data'] > 0): ?>
                    Halaman <strong><?= $pagination['current_page'] ?></strong> dari <strong><?= $pagination['total_pages'] ?></strong>.
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

<!-- ================= DESKTOP TABLE VIEW ================= -->
<div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary to-secondary text-white">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-bold">No</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">No Pendaftaran</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Nama Siswa</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Email</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Paket</th>
                    <th class="px-4 py-4 text-right text-sm font-bold">Nominal</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Tgl Upload</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Tgl Approve</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($dataProfit)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa fa-inbox text-4xl mb-3 block text-gray-300"></i>
                        <p class="font-semibold">Tidak ada data profit</p>
                        <p class="text-sm mt-1">Coba ubah filter atau tambahkan data baru</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php 
                    $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                    foreach($dataProfit as $profit): 
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-700"><?= $no++ ?></td>
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                <?= $profit['no_pendaftaran'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= $profit['nama_siswa'] ?></p>
                                <p class="text-xs text-gray-500"><?= $profit['no_hp'] ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $profit['email'] ?></td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= $profit['nama_paket'] ?></p>
                                <p class="text-xs text-gray-500"><?= $profit['level'] ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-bold text-green-600 text-sm">
                                Rp <?= number_format($profit['nominal'], 0, ',', '.') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <?= date('d/m/Y H:i', strtotime($profit['tanggal_upload'])) ?>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <?= date('d/m/Y H:i', strtotime($profit['created_at'])) ?>
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
    <?php if(empty($dataProfit)): ?>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <i class="fa fa-inbox text-6xl text-gray-300 mb-4"></i>
        <p class="font-semibold text-gray-700 text-lg mb-2">Tidak ada data profit</p>
        <p class="text-sm text-gray-500">Coba ubah filter atau tambahkan data baru</p>
    </div>
    <?php else: ?>
        <?php 
        $no = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
        foreach($dataProfit as $profit): 
        ?>
        <div class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-5">
                <!-- Header Card -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-bold text-gray-800 text-lg"><?= $profit['nama_siswa'] ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= $profit['no_hp'] ?></p>
                    </div>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-mono">
                        #<?= $no++ ?>
                    </span>
                </div>
                
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">No Pendaftaran</p>
                        <p class="text-sm font-semibold text-gray-800"><?= $profit['no_pendaftaran'] ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm font-semibold text-gray-800 truncate"><?= $profit['email'] ?></p>
                    </div>
                </div>
                
                <!-- Paket Info -->
                <div class="bg-purple-50 rounded-lg p-3 mb-4">
                    <p class="text-xs text-purple-600 mb-1">Paket Kursus</p>
                    <p class="text-sm font-bold text-purple-800"><?= $profit['nama_paket'] ?></p>
                    <p class="text-xs text-purple-600 mt-1"><?= $profit['level'] ?></p>
                </div>
                
                <!-- Nominal -->
                <div class="bg-green-50 rounded-lg p-4 mb-4 text-center">
                    <p class="text-xs text-green-600 mb-1">Nominal Profit</p>
                    <p class="text-2xl font-bold text-green-700">
                        Rp <?= number_format($profit['nominal'], 0, ',', '.') ?>
                    </p>
                </div>
                
                <!-- Tanggal Info -->
                <div class="grid grid-cols-2 gap-3 text-xs text-gray-600">
                    <div>
                        <p class="text-gray-500 mb-1">
                            <i class="fa fa-upload mr-1"></i> Upload
                        </p>
                        <p class="font-semibold"><?= date('d/m/Y', strtotime($profit['tanggal_upload'])) ?></p>
                        <p class="text-gray-400"><?= date('H:i', strtotime($profit['tanggal_upload'])) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">
                            <i class="fa fa-check-circle mr-1"></i> Approve
                        </p>
                        <p class="font-semibold"><?= date('d/m/Y', strtotime($profit['created_at'])) ?></p>
                        <p class="text-gray-400"><?= date('H:i', strtotime($profit['created_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ================= PAGINATION DESKTOP ================= -->
<?php if($pagination['total_pages'] > 1): ?>
<div class="hidden lg:flex justify-center items-center gap-2 mb-6">
    
    <!-- Previous Button -->
    <?php if($pagination['current_page'] > 1): ?>
    <a href="?page=<?= $pagination['current_page'] - 1 ?><?= !empty($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '' ?><?= !empty($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '' ?><?= !empty($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '' ?><?= !empty($filters['search']) ? '&search='.$filters['search'] : '' ?>" 
       class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
        <i class="fa fa-chevron-left"></i>
    </a>
    <?php else: ?>
    <span class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-400 cursor-not-allowed">
        <i class="fa fa-chevron-left"></i>
    </span>
    <?php endif; ?>
    
    <!-- Page Numbers -->
    <?php
    $start = max(1, $pagination['current_page'] - 2);
    $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
    
    if($start > 1): ?>
        <a href="?page=1<?= !empty($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '' ?><?= !empty($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '' ?><?= !empty($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '' ?><?= !empty($filters['search']) ? '&search='.$filters['search'] : '' ?>" 
           class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            1
        </a>
        <?php if($start > 2): ?>
        <span class="px-2 text-gray-400">...</span>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php for($i = $start; $i <= $end; $i++): ?>
        <?php if($i == $pagination['current_page']): ?>
        <span class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white font-bold rounded-lg shadow-lg">
            <?= $i ?>
        </span>
        <?php else: ?>
        <a href="?page=<?= $i ?><?= !empty($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '' ?><?= !empty($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '' ?><?= !empty($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '' ?><?= !empty($filters['search']) ? '&search='.$filters['search'] : '' ?>" 
           class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <?= $i ?>
        </a>
        <?php endif; ?>
    <?php endfor; ?>
    
    <?php if($end < $pagination['total_pages']): ?>
        <?php if($end < $pagination['total_pages'] - 1): ?>
        <span class="px-2 text-gray-400">...</span>
        <?php endif; ?>
        <a href="?page=<?= $pagination['total_pages'] ?><?= !empty($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '' ?><?= !empty($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '' ?><?= !empty($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '' ?><?= !empty($filters['search']) ? '&search='.$filters['search'] : '' ?>" 
           class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <?= $pagination['total_pages'] ?>
        </a>
    <?php endif; ?>
    
    <!-- Next Button -->
    <?php if($pagination['current_page'] < $pagination['total_pages']): ?>
    <a href="?page=<?= $pagination['current_page'] + 1 ?><?= !empty($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '' ?><?= !empty($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '' ?><?= !empty($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '' ?><?= !empty($filters['search']) ? '&search='.$filters['search'] : '' ?>" 
       class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
        <i class="fa fa-chevron-right"></i>
    </a>
    <?php else: ?>
    <span class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-400 cursor-not-allowed">
        <i class="fa fa-chevron-right"></i>
    </span>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- ================= PAGINATION MOBILE ================= -->
<?php if($pagination['total_pages'] > 1): ?>
<div class="lg:hidden bg-white rounded-xl shadow-lg p-4 mb-6">
    <div class="flex items-center justify-between mb-3">
        <span class="text-sm text-gray-600">
            Halaman <strong><?= $pagination['current_page'] ?></strong> dari <strong><?= $pagination['total_pages'] ?></strong>
        </span>
        <span class="text-xs text-gray-500">
            Total: <?= $pagination['total_data'] ?> data
        </span>
    </div>
    
    <div class="flex gap-2">
        <!-- Previous -->
        <?php if($pagination['current_page'] > 1): ?>
        <a href="?page=<?= $pagination['current_page'] - 1 ?><?= !empty($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '' ?><?= !empty($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '' ?><?= !empty($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '' ?><?= !empty($filters['search']) ? '&search='.$filters['search'] : '' ?>" 
           class="flex-1 px-4 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-lg text-center hover:shadow-lg transition-all">
            <i class="fa fa-chevron-left mr-2"></i> Sebelumnya
        </a>
        <?php else: ?>
        <span class="flex-1 px-4 py-3 bg-gray-100 text-gray-400 font-semibold rounded-lg text-center cursor-not-allowed">
            <i class="fa fa-chevron-left mr-2"></i> Sebelumnya
        </span>
        <?php endif; ?>
        
        <!-- Next -->
        <?php if($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="?page=<?= $pagination['current_page'] + 1 ?><?= !empty($filters['tanggal_start']) ? '&tanggal_start='.$filters['tanggal_start'] : '' ?><?= !empty($filters['tanggal_end']) ? '&tanggal_end='.$filters['tanggal_end'] : '' ?><?= !empty($filters['paket_id']) ? '&paket_id='.$filters['paket_id'] : '' ?><?= !empty($filters['search']) ? '&search='.$filters['search'] : '' ?>" 
           class="flex-1 px-4 py-3 bg-gradient-to-r from-primary to-secondary text-white font-semibold rounded-lg text-center hover:shadow-lg transition-all">
            Selanjutnya <i class="fa fa-chevron-right ml-2"></i>
        </a>
        <?php else: ?>
        <span class="flex-1 px-4 py-3 bg-gray-100 text-gray-400 font-semibold rounded-lg text-center cursor-not-allowed">
            Selanjutnya <i class="fa fa-chevron-right ml-2"></i>
        </span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>