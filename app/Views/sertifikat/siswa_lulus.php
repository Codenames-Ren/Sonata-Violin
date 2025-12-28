<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Siswa Lulus<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Generate sertifikat untuk siswa yang sudah lulus<?= $this->endSection() ?>
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
            <div class="flex items-center gap-2 mb-2">
                <a href="<?= base_url('sertifikat') ?>" class="text-white/80 hover:text-white transition-colors">
                    <i class="text-2xl fa fa-arrow-left"></i>
                </a>
                <h2 class="text-white text-2xl font-bold">Siswa Lulus</h2>
            </div>
            <p class="text-white/90 text-sm">
                Siswa yang sudah lulus dan belum memiliki sertifikat
            </p>
        </div>
        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-3 text-white">
            <p class="text-xs opacity-90">Total Siswa</p>
            <p class="text-2xl font-bold"><?= count($siswaLulus) ?></p>
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

<!-- ================= BATCH GENERATE FORM ================= -->
<?php if(!empty($siswaLulus)): ?>
<form method="POST" action="<?= base_url('sertifikat/generate-batch') ?>" id="batchForm">
    <?= csrf_field() ?>
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <input type="checkbox" id="selectAll" class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary" form="none">
            <label for="selectAll" class="font-semibold text-gray-700 cursor-pointer">Pilih Semua</label>
        </div>
        <button type="submit" 
                id="btnGenerateBatch"
                disabled
                class="w-full sm:w-auto px-6 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <i class="fa fa-magic"></i>
            <span>Generate Sertifikat Batch</span>
        </button>
    </div>
<?php endif; ?>

<!-- ================= DESKTOP TABLE VIEW ================= -->
<div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary to-secondary text-white">
                <tr>
                    <?php if(!empty($siswaLulus)): ?>
                    <th class="px-4 py-4 text-center text-sm font-bold w-12">
                        <input type="checkbox" 
                            class="selectAllCheckbox w-5 h-5 rounded border-white/30"
                            form="none"
                            id="selectAllTable">
                    </th>
                    <?php endif; ?>
                    <th class="px-4 py-4 text-left text-sm font-bold">No</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Nama Siswa</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Kontak</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Paket</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Instruktur</th>
                    <th class="px-4 py-4 text-left text-sm font-bold">Jadwal</th>
                    <th class="px-4 py-4 text-center text-sm font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if(empty($siswaLulus)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fa fa-graduation-cap text-4xl mb-3 block text-gray-300"></i>
                        <p class="font-semibold">Tidak ada siswa lulus yang belum punya sertifikat</p>
                        <p class="text-sm mt-1">Semua siswa lulus sudah memiliki sertifikat</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php 
                    $no = 1;
                    foreach($siswaLulus as $siswa): 
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" 
                                   name="kelas_siswa_ids[]" 
                                   value="<?= $siswa['kelas_siswa_id'] ?>"
                                   class="checkItem w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700"><?= $no++ ?></td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= $siswa['nama_siswa'] ?></p>
                                <p class="text-xs text-gray-500"><?= $siswa['email'] ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $siswa['no_hp'] ?></td>
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm"><?= $siswa['nama_paket'] ?></p>
                                <p class="text-xs text-gray-500"><?= $siswa['level'] ?></p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?= $siswa['nama_instruktur'] ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            <?= $siswa['hari'] ?><br>
                            <span class="text-xs text-gray-500">
                                <?= date('H:i', strtotime($siswa['jam_mulai'])) ?> - <?= date('H:i', strtotime($siswa['jam_selesai'])) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="<?= base_url('sertifikat/generate/'.$siswa['kelas_siswa_id']) ?>"
                                  class="inline-block">
                                <?= csrf_field() ?>
                                <button type="submit"
                                   class="inline-flex items-center gap-1 px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                    <i class="fa fa-magic"></i>
                                    <span>Generate</span>
                                </button>
                            </form>
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
    <?php if(empty($siswaLulus)): ?>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center">
        <i class="fa fa-graduation-cap text-6xl text-gray-300 mb-4"></i>
        <p class="font-semibold text-gray-700 text-lg mb-2">Tidak ada siswa lulus</p>
        <p class="text-sm text-gray-500">Semua siswa lulus sudah memiliki sertifikat</p>
    </div>
    <?php else: ?>
        <?php 
        $no = 1;
        foreach($siswaLulus as $siswa): 
        ?>
        <div class="card-hover gradient-border bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-5">
                <!-- Checkbox & Number -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" 
                               name="kelas_siswa_ids[]" 
                               value="<?= $siswa['kelas_siswa_id'] ?>"
                               class="checkItem w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                        <span class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full font-mono">
                            #<?= $no++ ?>
                        </span>
                    </div>
                </div>
                
                <!-- Siswa Info -->
                <div class="mb-4">
                    <p class="font-bold text-gray-800 text-lg"><?= $siswa['nama_siswa'] ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?= $siswa['email'] ?></p>
                    <p class="text-xs text-gray-500"><?= $siswa['no_hp'] ?></p>
                </div>
                
                <!-- Paket Info -->
                <div class="bg-purple-50 rounded-lg p-3 mb-4">
                    <p class="text-xs text-purple-600 mb-1">Paket Kursus</p>
                    <p class="text-sm font-bold text-purple-800"><?= $siswa['nama_paket'] ?></p>
                    <p class="text-xs text-purple-600 mt-1"><?= $siswa['level'] ?></p>
                </div>
                
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Instruktur</p>
                        <p class="text-sm font-semibold text-gray-800"><?= $siswa['nama_instruktur'] ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Jadwal</p>
                        <p class="text-sm font-semibold text-gray-800"><?= $siswa['hari'] ?></p>
                        <p class="text-xs text-gray-500 mt-1">
                            <?= date('H:i', strtotime($siswa['jam_mulai'])) ?> - <?= date('H:i', strtotime($siswa['jam_selesai'])) ?>
                        </p>
                    </div>
                </div>
                
                <!-- Action Button -->
                <form method="POST" action="<?= base_url('sertifikat/generate/'.$siswa['kelas_siswa_id']) ?>">
                    <?= csrf_field() ?>
                    <button type="submit"
                       class="w-full block text-center px-4 py-3 bg-green-500 text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fa fa-magic mr-2"></i>
                        <span>Generate Sertifikat</span>
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if(!empty($siswaLulus)): ?>
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
    
        // ========== CHECKBOX SELECT ALL LOGIC ==========
        const selectAllMain = document.getElementById('selectAll');
        const selectAllTable = document.querySelector('.selectAllCheckbox');
        const btnGenerateBatch = document.getElementById('btnGenerateBatch');
        const batchForm = document.getElementById('batchForm');
        
        // Function to get ACTIVE checkboxes only (desktop OR mobile, not both)
        function getActiveCheckboxes() {
            const isMobile = window.innerWidth < 1024;
            if (isMobile) {
                return document.querySelectorAll('.lg\\:hidden input[name="kelas_siswa_ids[]"]');
            } else {
                return document.querySelectorAll('.hidden.lg\\:block input[name="kelas_siswa_ids[]"]');
            }
        }
        
        // Function update button state
        function updateSelectionState() {
            const checkItems = getActiveCheckboxes();
            const checkedCount = Array.from(checkItems).filter(item => item.checked).length;
            
            // Enable/disable button
            if (btnGenerateBatch) {
                if (checkedCount > 0) {
                    btnGenerateBatch.disabled = false;
                    btnGenerateBatch.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btnGenerateBatch.disabled = true;
                    btnGenerateBatch.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
            
            // Update select all checkbox state
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
        const allCheckboxes = document.querySelectorAll('input[name="kelas_siswa_ids[]"]');
        allCheckboxes.forEach(item => {
            item.addEventListener('change', updateSelectionState);
        });
        
        // Initialize state
        updateSelectionState();
        
        // ========== FORM SUBMIT CONFIRMATION (BATCH GENERATE) - UBAH KE SWAL ==========
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
                        text: 'Pilih minimal 1 siswa untuk generate sertifikat!',
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
                    title: 'Konfirmasi Generate Batch',
                    html: `
                        <p class="mb-2">Generate sertifikat untuk <strong>${uniqueValues.length} siswa</strong> yang dipilih?</p>
                        <p class="text-sm text-gray-500">Proses ini mungkin memakan waktu beberapa saat.</p>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fa fa-magic mr-2"></i>Ya, Generate!',
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
                        btnGenerateBatch.disabled = true;
                        btnGenerateBatch.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i><span>Memproses...</span>';
                        
                        // Submit form
                        batchForm.submit();
                    }
                });
            });
        }
        
        // ========== SINGLE GENERATE BUTTON - UBAH KE SWAL ==========
        const singleGenerateForms = document.querySelectorAll('form[action*="generate/"]');
        singleGenerateForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const siswaName = this.querySelector('button').textContent.includes('Generate') 
                    ? 'siswa ini' 
                    : 'siswa ini';
                
                // Extract nama siswa dari action URL atau data attribute jika ada
                const actionUrl = this.getAttribute('action');
                
                Swal.fire({
                    icon: 'question',
                    title: 'Konfirmasi Generate',
                    html: `
                        <p class="mb-2">Generate sertifikat untuk siswa ini?</p>
                        <p class="text-sm text-gray-500">Sertifikat akan dibuat dan tersimpan di database.</p>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fa fa-magic mr-2"></i>Ya, Generate!',
                    cancelButtonText: 'Batal',
                    position: 'center',
                    showClass: {
                        popup: 'swal2-show',
                        backdrop: 'swal2-backdrop-show'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = this.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i><span>Loading...</span>';
                            btn.disabled = true;
                            btn.style.opacity = '0.7';
                        }
                        this.submit();
                    }
                });
            });
        });
        
        // ========== AUTO HIDE FLASH MESSAGES ==========
        const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-yellow-50');
        flashMessages.forEach(msg => {
            setTimeout(() => {
                msg.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-10px)';
                setTimeout(() => msg.remove(), 500);
            }, 5000);
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
        
        // ========== PAGINATION (Desktop: 10, Mobile: 5) ==========
        let currentPage = 1;
        let totalPages = 1;

        function getVisibleItems() {
            const isMobile = window.innerWidth < 1024;
            const rows = Array.from(document.querySelectorAll('.hidden.lg\\:block.bg-white tbody tr'));
            const cards = Array.from(document.querySelectorAll('.lg\\:hidden.space-y-4 > .card-hover'));
            
            return isMobile ? cards : rows;
        }

        function updatePaginationView() {
            const isMobile = window.innerWidth < 1024;
            const allRows = Array.from(document.querySelectorAll('.hidden.lg\\:block.bg-white tbody tr'));
            const allCards = Array.from(document.querySelectorAll('.lg\\:hidden.space-y-4 > .card-hover'));
            
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

            const currentPageEl = document.getElementById("currentPage");
            const totalPagesEl = document.getElementById("totalPages");
            const btnPrev = document.getElementById("btnPrev");
            const btnNext = document.getElementById("btnNext");

            if (currentPageEl) currentPageEl.textContent = currentPage;
            if (totalPagesEl) totalPagesEl.textContent = totalPages;

            if (btnPrev) btnPrev.disabled = currentPage === 1;
            if (btnNext) btnNext.disabled = currentPage === totalPages;
        }

        const btnPrev = document.getElementById("btnPrev");
        const btnNext = document.getElementById("btnNext");

        if (btnPrev) {
            btnPrev.addEventListener("click", () => {
                if (currentPage > 1) { 
                    currentPage--; 
                    updatePaginationView(); 
                }
            });
        }

        if (btnNext) {
            btnNext.addEventListener("click", () => {
                if (currentPage < totalPages) { 
                    currentPage++; 
                    updatePaginationView(); 
                }
            });
        }

        window.addEventListener("resize", () => {
            currentPage = 1;
            updatePaginationView();
            updateSelectionState();
        });

        // Initialize pagination
        updatePaginationView();
        
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
        const actionBtns = document.querySelectorAll('button[type="submit"]');
        actionBtns.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                if (!this.disabled) {
                    this.style.transform = 'scale(1.05)';
                    this.style.transition = 'transform 0.2s ease';
                }
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
        
        // ========== NUMBER ANIMATION FOR COUNTER ==========
        const counterElement = document.querySelector('.bg-white\\/20 .text-2xl.font-bold');
        if (counterElement) {
            const finalValue = parseInt(counterElement.textContent);
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
                    counterElement.textContent = currentValue;
                }, stepTime);
            }
        }
    });
</script>

<?= $this->endSection() ?>