<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
* {
  transition: all 0.15s ease;
}

.header-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 2rem;
  border-radius: 1rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.2);
}

.header-section h2 {
  color: white;
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
}

.header-section p {
  color: rgba(255,255,255,0.9);
  margin-top: .25rem;
}

.btn-primary {
  background: white;
  color: #667eea;
  padding: .625rem 1.5rem;
  border-radius: .5rem;
  font-weight: 600;
  border: none;
  box-shadow: 0 2px 4px rgba(0,0,0,.1);
  cursor: pointer;
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0,0,0,.15);
}

.alert {
  padding: 1rem 1.25rem;
  border-radius: .75rem;
  margin-bottom: 1.5rem;
  border-left: 4px solid;
  font-weight: 500;
}

.alert-success {
  background: #ecfdf5;
  border-color: #10b981;
  color: #065f46;
}

.alert-error {
  background: #fef2f2;
  border-color: #ef4444;
  color: #991b1b;
}

.table-container {
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.1);
}

.table-container table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

.table-container thead {
  background: #f8fafc;
  border-bottom: 2px solid #e2e8f0;
}

.table-container thead th {
  padding: 1rem 0.75rem;
  font-weight: 600;
  color: #475569;
  text-transform: uppercase;
  font-size: .75rem;
  letter-spacing: .05em;
  text-align: center;
}

.table-container tbody td {
  padding: 1rem 0.75rem;
  color: #334155;
  text-align: center;
}

.table-container tbody tr {
  border-bottom: 1px solid #f1f5f9;
}

.table-container tbody tr:hover {
  background: #fafbfc;
}

.table-container thead th:nth-child(1),
.table-container tbody td:nth-child(1) {
  width: 60px;
}

.table-container thead th:nth-child(3),
.table-container tbody td:nth-child(3) {
  width: 120px;
}

.table-container thead th:nth-child(4),
.table-container tbody td:nth-child(4) {
  width: 110px;
}

.table-container thead th:nth-child(5),
.table-container tbody td:nth-child(5) {
  width: 110px;
}

.table-container thead th:nth-child(6),
.table-container tbody td:nth-child(6) {
  width: 140px;
}

.table-container thead th:nth-child(7),
.table-container tbody td:nth-child(7) {
  width: 180px;
}

.btn-action {
  padding: .35rem .55rem;
  border-radius: .375rem;
  font-size: .75rem;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: .25rem;
  cursor: pointer;
  border: none;
}

.btn-edit {
  background: #e0e7ff;
  color: #4338ca;
}

.btn-edit:hover {
  background: #c7d2fe;
}

.btn-delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-delete:hover {
  background: #fecaca;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
  display: none;
  background: rgba(0, 0, 0, 0);
  backdrop-filter: blur(0px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active {
  display: block;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(2px);
}

.modal-box {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) translateY(-20px) scale(0.96);
  background: white;
  border-radius: 1rem;
  width: 90%;
  max-width: 42rem;
  max-height: 90vh;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  opacity: 0;
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  margin-top: 4rem;
}

.modal-overlay.active .modal-box {
  opacity: 1;
  transform: translate(-50%, -50%) translateY(0) scale(1);
}

.modal-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1rem 1.25rem;
  border-radius: 1rem 1rem 0 0;
  position: relative;
  flex-shrink: 0;
}

.modal-header h3 {
  color: white;
  font-size: 1.05rem;
  margin: 0;
  font-weight: 700;
}

.modal-close {
  position: absolute;
  right: 1.25rem;
  top: 1.25rem;
  background: rgba(255,255,255,.2);
  border: none;
  width: 1.75rem;
  height: 1.75rem;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: .4rem;
  cursor: pointer;
  color: white;
}

.modal-close:hover {
  background: rgba(255,255,255,.3);
}

.modal-body {
  padding: 1.25rem 1.5rem;
  overflow-y: auto;
  flex: 1;
  min-height: 0;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 0.875rem;
}

.form-row-full {
  display: grid;
  grid-template-columns: 1fr;
  margin-bottom: 0.875rem;
}

.modal-body::-webkit-scrollbar {
  width: 6px;
}

.modal-body::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.modal-footer {
  padding: .875rem 1.25rem;
  background: #f8fafc;
  border-radius: 0 0 1rem 1rem;
  display: flex;
  justify-content: flex-end;
  gap: .5rem;
  flex-shrink: 0;
}

.form-group {
  margin-bottom: 0;
}

.form-label {
  display: block;
  font-size: .875rem;
  font-weight: 600;
  margin-bottom: .4rem;
  color: #374151;
}

.form-input, .form-select, .form-textarea {
  width: 100%;
  padding: .55rem .75rem;
  border-radius: .5rem;
  border: 2px solid #e5e7eb;
  font-size: .875rem;
  color: #1f2937;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-textarea {
  min-height: 60px;
  resize: vertical;
}

.btn-secondary {
  background: white;
  border: 2px solid #e5e7eb;
  padding: .5rem 1rem;
  font-weight: 600;
  border-radius: .375rem;
  font-size: .875rem;
  color: #6b7280;
  cursor: pointer;
}

.btn-secondary:hover {
  background: #f9fafb;
  border-color: #d1d5db;
}

.btn-submit {
  background: linear-gradient(135deg,#667eea,#764ba2);
  color: white;
  padding: .5rem 1.25rem;
  border: none;
  border-radius: .375rem;
  font-weight: 600;
  font-size: .875rem;
  cursor: pointer;
}

.btn-submit:hover {
  box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.3);
}

.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #9ca3af;
}

@media (max-width: 768px) {
  .header-section {
    padding: 1.25rem;
  }

  .header-section > div {
    flex-direction: column !important;
    gap: 1rem;
  }

  .btn-primary {
    width: 100%;
    justify-content: center;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .table-container {
    overflow-x: auto;
  }

  .table-container table {
    min-width: 800px;
  }

  .table-container thead th {
    padding: 0.75rem 0.5rem;
  }

  .table-container tbody td {
    padding: 0.75rem 0.5rem;
  }

  .modal-box {
    width: 95%;
    max-width: 95%;
    max-height: 85vh;
  }

  .form-row {
    grid-template-columns: 1fr;
    gap: 0.875rem;
  }

  .modal-header {
    padding: 1rem;
  }

  .modal-header h3 {
    font-size: 1rem;
    padding-right: 2rem;
  }

  .modal-close {
    top: 1rem;
    right: 1rem;
    width: 1.5rem;
    height: 1.5rem;
  }

  .modal-body {
    padding: 1rem;
  }

  .modal-footer {
    padding: 0.75rem 1rem;
    flex-direction: column;
  }

  .btn-secondary, .btn-submit {
    width: 100%;
    justify-content: center;
  }

  .btn-action {
    font-size: 0.7rem;
    padding: 0.3rem 0.5rem;
  }
}

@media (max-width: 480px) {
  .header-section h2 {
    font-size: 1.25rem;
  }

  .header-section p {
    font-size: 0.8rem;
  }

  .table-container thead th {
    font-size: 0.7rem;
    padding: 0.75rem 0.5rem;
  }

  .table-container tbody td {
    font-size: 0.8rem;
    padding: 0.75rem 0.5rem;
  }

  .alert {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
  }
}
</style>

<!-- HEADER -->
<div class="header-section">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <div>
      <h2>Paket Kursus</h2>
      <p>Kelola daftar paket kursus Sonata Violin</p>
    </div>
    <button id="btnOpenCreate" class="btn-primary">
      <i class="fa fa-plus"></i> Tambah Paket
    </button>
  </div>
</div>

<!-- FLASH -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success">
    <i class="fa fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-error">
    <i class="fa fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

<!-- TABLE -->
<div class="table-container">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nama Paket</th>
        <th>Level</th>
        <th>Durasi</th>
        <th>Pertemuan</th>
        <th>Harga</th>
        <th>Aksi</th>
      </tr>
    </thead>

    <tbody>
      <?php if (!empty($paket)): $i=1; foreach ($paket as $p): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><strong><?= esc($p['nama_paket']) ?></strong></td>
        <td><?= esc(ucfirst($p['level'])) ?></td>
        <td><?= esc($p['durasi']) ?></td>
        <td><?= esc($p['jumlah_pertemuan']) ?>x</td>
        <td>Rp <?= number_format($p['harga'],0,',','.') ?></td>

        <td>
          <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
            <button class="btn-action btn-edit btnEdit"
              data-id="<?= $p['id'] ?>"
              data-nama="<?= esc($p['nama_paket'], 'attr') ?>"
              data-level="<?= esc($p['level'], 'attr') ?>"
              data-durasi="<?= esc($p['durasi'], 'attr') ?>"
              data-pertemuan="<?= esc($p['jumlah_pertemuan'], 'attr') ?>"
              data-harga="<?= esc($p['harga'], 'attr') ?>"
              data-deskripsi="<?= esc($p['deskripsi'], 'attr') ?>">
              <i class="fa fa-pen"></i> Edit
            </button>

            <form method="POST" action="<?= base_url('/paket/delete/'.$p['id']) ?>" onsubmit="return confirm('Hapus paket ini?')" style="display: inline;">
              <?= csrf_field() ?>
              <button type="submit" class="btn-action btn-delete">
                <i class="fa fa-trash"></i> Hapus
              </button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <td colspan="7">
          <div class="empty-state">
            <i class="fa fa-box-open" style="font-size:3rem; opacity:.3; margin-bottom: 1rem;"></i>
            <p>Belum ada paket kursus</p>
          </div>
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- MODAL -->
<div id="modal" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <h3 id="modalTitle">Tambah Paket Kursus</h3>
      <button type="button" class="modal-close" id="btnCloseModalX">
        <i class="fa fa-times"></i>
      </button>
    </div>

    <form id="modalForm" method="POST" action="<?= base_url('/paket/create') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="paket_id">

      <div class="modal-body">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nama Paket</label>
            <input type="text" class="form-input" name="nama_paket" id="paket_nama" required placeholder="Masukkan nama paket">
          </div>

          <div class="form-group">
            <label class="form-label">Level</label>
            <select name="level" id="paket_level" class="form-select">
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Durasi</label>
            <input type="text" class="form-input" name="durasi" id="paket_durasi" required placeholder="contoh: 3 Bulan">
            <p style="font-size: 0.7rem; color: #6b7280; margin-top: 0.25rem;">
              <i class="fa fa-info-circle"></i> Contoh: 3 Bulan, 6 Minggu
            </p>
          </div>

          <div class="form-group">
            <label class="form-label">Jumlah Pertemuan</label>
            <input type="number" min="1" class="form-input" name="jumlah_pertemuan" id="paket_pertemuan" required placeholder="Jumlah pertemuan">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" class="form-input" name="harga" id="paket_harga" required placeholder="Masukkan harga">
          </div>

          <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-textarea" name="deskripsi" id="paket_deskripsi" placeholder="Deskripsi paket (opsional)" style="min-height: 60px;"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="btnCloseModal" class="btn-secondary">Batal</button>
        <button type="submit" class="btn-submit">
          <i class="fa fa-save"></i> Simpan Data
        </button>
      </div>
    </form>
  </div>
</div>

<script>
const modal = document.getElementById('modal');

function closeModal() {
  modal.classList.remove('active');
}

function openModal() {
  modal.style.display = 'block';
  void modal.offsetWidth;
  requestAnimationFrame(() => {
    modal.classList.add('active');
  });
}

document.getElementById('btnOpenCreate').addEventListener('click', () => {
  document.getElementById('modalTitle').textContent = 'Tambah Paket Kursus';
  document.getElementById('modalForm').action = "<?= base_url('/paket/create') ?>";

  document.getElementById('paket_id').value = '';
  document.getElementById('paket_nama').value = '';
  document.getElementById('paket_level').value = 'beginner';
  document.getElementById('paket_durasi').value = '';
  document.getElementById('paket_pertemuan').value = '';
  document.getElementById('paket_harga').value = '';
  document.getElementById('paket_deskripsi').value = '';

  openModal();
});

document.querySelectorAll('.btnEdit').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Edit Paket Kursus';
    
    const id = btn.dataset.id;
    document.getElementById('modalForm').action = "<?= base_url('/paket/update/') ?>" + id;

    document.getElementById('paket_id').value = id;
    document.getElementById('paket_nama').value = btn.dataset.nama;
    document.getElementById('paket_level').value = btn.dataset.level;
    document.getElementById('paket_durasi').value = btn.dataset.durasi;
    document.getElementById('paket_pertemuan').value = btn.dataset.pertemuan;
    document.getElementById('paket_harga').value = btn.dataset.harga;
    document.getElementById('paket_deskripsi').value = btn.dataset.deskripsi;

    openModal();
  });
});

document.getElementById('btnCloseModal').addEventListener('click', closeModal);
document.getElementById('btnCloseModalX').addEventListener('click', closeModal);

document.getElementById('modal').addEventListener('click', (e) => {
  if (e.target.id === 'modal') {
    closeModal();
  }
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modal.classList.contains('active')) {
    closeModal();
  }
});

modal.addEventListener('transitionend', (e) => {
  if (e.target === modal && !modal.classList.contains('active')) {
    modal.style.display = 'none';
  }
});
</script>

<?= $this->endSection() ?>