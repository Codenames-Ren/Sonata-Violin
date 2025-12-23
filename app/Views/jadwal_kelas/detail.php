<?= $this->extend('layout/template') ?>
<?= $this->section('title') ?>Detail Kelas<?= $this->endSection() ?>
<?= $this->section('subtitle') ?>Informasi lengkap jadwal kelas<?= $this->endSection() ?>
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
    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
    }

    .btn-hover:active {
        transform: translateY(0);
    }

    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>

<?php $role = session('role'); ?>

<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('jadwal-kelas') ?>" 
       class="btn-hover inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-gray-300 rounded-lg font-semibold text-gray-700 hover:border-primary hover:text-primary transition-all">
        <i class="fa fa-arrow-left"></i>
        <span>Kembali ke Daftar Jadwal</span>
    </a>
</div>

<!-- HEADER CARD -->
<div class="info-card p-6 rounded-xl shadow-lg mb-6 text-white">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-2"><?= esc($jadwal['nama_paket']) ?></h2>
            <div class="flex flex-wrap gap-3 text-sm">
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-calendar mr-1"></i><?= esc($jadwal['hari']) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-clock mr-1"></i><?= date('H:i', strtotime($jadwal['jam_mulai'])) ?> - <?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full backdrop-blur-sm">
                    <i class="fa fa-door-open mr-1"></i><?= esc($jadwal['nama_ruang']) ?>
                </span>
            </div>
        </div>
        <div class="text-right">
            <p class="text-white/80 text-sm mb-1">Tingkat</p>
            <p class="text-2xl font-bold"><?= ucfirst(esc($jadwal['tingkat'])) ?></p>
        </div>
    </div>
</div>

<!-- INFO INSTRUKTUR -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fa fa-chalkboard-teacher text-primary"></i>
        Instruktur Pengajar
    </h3>
    
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <!-- Avatar -->
        <?php if (!empty($jadwal['foto_instruktur'])): ?>
            <img src="<?= base_url('uploads/instruktur/' . $jadwal['foto_instruktur']) ?>" 
                alt="<?= esc($jadwal['nama_instruktur']) ?>"
                class="w-16 h-16 rounded-full object-cover border-2 border-primary flex-shrink-0">
        <?php else: ?>
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                <?= strtoupper(substr($jadwal['nama_instruktur'], 0, 2)) ?>
            </div>
        <?php endif; ?>
        
        <!-- Info Instruktur -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Nama</p>
                <p class="font-bold text-gray-800"><?= esc($jadwal['nama_instruktur']) ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Email</p>
                <p class="text-gray-700 text-sm"><?= esc($jadwal['email_instruktur']) ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Keahlian</p>
                <p class="text-gray-700 text-sm"><?= esc($jadwal['keahlian_instruktur']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- DAFTAR SISWA -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa fa-users text-primary"></i>
            Daftar Siswa Terdaftar
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                <?= count($siswa) ?> / <?= $jadwal['kapasitas_ruang'] ?>
            </span>
        </h3>

        <!-- BUTTON TAMBAH SISWA (hanya untuk admin/operator) -->
        <?php if (in_array($role, ['admin', 'operator'])): ?>
        <button id="btnTambahSiswa"
                class="btn-hover bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-2.5 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fa fa-user-plus"></i>
            Tambah Siswa
        </button>
        <?php endif; ?>
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-sm font-semibold border-b-2 border-gray-200">
                <tr>
                    <th class="py-4 px-4 text-left">No</th>
                    <th class="py-4 px-4 text-left">Nama Siswa</th>
                    <th class="py-4 px-4 text-left">Email</th>
                    <th class="py-4 px-4 text-left">No HP</th>
                    <th class="py-4 px-4 text-center">Status</th>
                    <?php if (in_array($role, ['admin', 'operator'])): ?>
                    <th class="py-4 px-4 text-center">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($siswa)): $no = 1; foreach ($siswa as $s): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-4 font-medium text-gray-600"><?= $no++ ?></td>
                    <td class="py-4 px-4 font-semibold text-gray-800"><?= esc($s['nama']) ?></td>
                    <td class="py-4 px-4 text-gray-600"><?= esc($s['email']) ?></td>
                    <td class="py-4 px-4 text-gray-600"><?= esc($s['no_hp']) ?></td>
                    <td class="py-4 px-4 text-center">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            <?= ucfirst(esc($s['status'])) ?>
                        </span>
                    </td>
                    <?php if (in_array($role, ['admin', 'operator'])): ?>
                    <td class="py-4 px-4 text-center">
                        <button class="btnHapusSiswa btn-hover px-4 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-all"
                                data-id="<?= $s['id'] ?>"
                                data-nama="<?= esc($s['nama'], 'attr') ?>">
                            <i class="fa fa-trash mr-1"></i>Hapus
                        </button>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="<?= in_array($role, ['admin', 'operator']) ? '6' : '5' ?>" class="text-center py-8 text-gray-500">
                        <i class="fa fa-users text-4xl mb-3 opacity-20"></i>
                        <p>Belum ada siswa terdaftar di kelas ini.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- MOBILE CARDS -->
    <div class="md:hidden space-y-4">
        <?php if (!empty($siswa)): foreach ($siswa as $s): ?>
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="flex justify-between items-start mb-3">
                <h4 class="font-bold text-gray-800 text-lg"><?= esc($s['nama']) ?></h4>
                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                    <?= ucfirst(esc($s['status'])) ?>
                </span>
            </div>
            <div class="space-y-1 text-sm mb-3">
                <p class="text-gray-600"><i class="fa fa-envelope mr-2"></i><?= esc($s['email']) ?></p>
                <p class="text-gray-600"><i class="fa fa-phone mr-2"></i><?= esc($s['no_hp']) ?></p>
            </div>
            <?php if (in_array($role, ['admin', 'operator'])): ?>
            <button class="btnHapusSiswa w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-all"
                    data-id="<?= $s['id'] ?>"
                    data-nama="<?= esc($s['nama'], 'attr') ?>">
                <i class="fa fa-trash mr-1"></i>Hapus dari Kelas
            </button>
            <?php endif; ?>
        </div>
        <?php endforeach; else: ?>
        <div class="text-center py-8 text-gray-500">
            <i class="fa fa-users text-5xl mb-3 opacity-20"></i>
            <p>Belum ada siswa terdaftar di kelas ini.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL TAMBAH SISWA (hanya untuk admin/operator) -->
<?php if (in_array($role, ['admin', 'operator'])): ?>
<div id="modalTambahSiswa" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-start justify-center p-4 z-50">
    <div id="modalBox" class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95 mt-20">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-5 flex justify-between items-center">
            <h3 class="text-white text-xl font-bold">Tambah Siswa ke Kelas</h3>
            <button id="btnCloseModal"
                    class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                &times;
            </button>
        </div>

        <!-- INFO KELAS -->
        <div class="p-4 bg-green-50 border-b-2 border-green-200">
            <p class="text-sm text-gray-700">
                <strong>Kelas:</strong> <?= esc($jadwal['nama_paket']) ?> | 
                <strong>Hari:</strong> <?= esc($jadwal['hari']) ?> | 
                <strong>Jam:</strong> <?= date('H:i', strtotime($jadwal['jam_mulai'])) ?> - <?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>
            </p>
        </div>

        <!-- FORM -->
        <form id="formTambahSiswa" method="POST" action="<?= base_url('jadwal-kelas/assign-siswa') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="jadwal_kelas_id" value="<?= $jadwal['id'] ?>">

            <div class="p-6">
                <label class="font-semibold text-gray-700 mb-2 block">Pilih Siswa</label>
                <select name="pendaftaran_id" required
                        class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach ($siswaAvailable as $sa): ?>
                        <option value="<?= $sa['id'] ?>">
                            <?= esc($sa['nama']) ?> - <?= esc($sa['nama_paket']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fa fa-info-circle mr-1"></i>Hanya siswa dengan paket yang sesuai yang dapat ditambahkan
                </p>
            </div>

            <!-- FOOTER -->
            <div class="p-5 bg-gray-50 border-t-2 border-gray-200 flex justify-end gap-3">
                <button type="button" id="btnCancelModal"
                        class="btn-hover px-5 py-2.5 bg-white border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="btn-hover px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                    <i class="fa fa-user-plus mr-1"></i>Tambah Siswa
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    // ==================== SWAL STYLING ====================
    const swalStyle = document.createElement('style');
    swalStyle.textContent = `
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
    `;
    document.head.appendChild(swalStyle);

    // ==================== MODAL TAMBAH SISWA ====================
    <?php if (in_array($role, ['admin', 'operator'])): ?>
    const modal = document.getElementById("modalTambahSiswa");
    const modalBox = document.getElementById("modalBox");
    const btnTambahSiswa = document.getElementById("btnTambahSiswa");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const btnCancelModal = document.getElementById("btnCancelModal");

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
        
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    }

    // Event Listeners untuk Modal
    if (btnTambahSiswa) {
        btnTambahSiswa.addEventListener("click", openModal);
    }

    if (btnCloseModal) {
        btnCloseModal.addEventListener("click", closeModal);
    }

    if (btnCancelModal) {
        btnCancelModal.addEventListener("click", closeModal);
    }

    // Close modal ketika klik di luar
    if (modal) {
        modal.addEventListener("click", function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
    <?php endif; ?>

    // ==================== HAPUS SISWA ====================
    <?php if (in_array($role, ['admin', 'operator'])): ?>
    document.querySelectorAll(".btnHapusSiswa").forEach(btn => {
        btn.addEventListener("click", function() {
            const siswaId = this.dataset.id;
            const namaSiswa = this.dataset.nama;
            const jadwalId = <?= $jadwal['id'] ?>;

            Swal.fire({
                title: 'Hapus Siswa?',
                html: `Apakah Anda yakin ingin menghapus <strong>${namaSiswa}</strong> dari kelas ini?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= base_url('jadwal-kelas/remove-siswa/') ?>${jadwalId}/${siswaId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Siswa berhasil dihapus dari kelas.',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Gagal menghapus siswa.',
                                confirmButtonColor: '#667eea'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus siswa.',
                            confirmButtonColor: '#667eea'
                        });
                    });
                }
            });
        });
    });
    <?php endif; ?>

    // ==================== FORM SUBMIT TAMBAH SISWA ====================
    <?php if (in_array($role, ['admin', 'operator'])): ?>
    const formTambahSiswa = document.getElementById("formTambahSiswa");

    if (formTambahSiswa) {
        formTambahSiswa.addEventListener("submit", function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            closeModal();
            
            setTimeout(() => {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Menambahkan siswa ke kelas...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(this.action, {
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
                            text: 'Siswa berhasil ditambahkan ke kelas.',
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
                        text: 'Terjadi kesalahan saat menambahkan siswa.',
                        confirmButtonColor: '#667eea'
                    });
                });
            }, 300);
        });
    }
    <?php endif; ?>

    // ==================== SMOOTH SCROLL BACK BUTTON ====================
    window.addEventListener('load', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

</script>

<?= $this->endSection() ?>