<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
/* Smooth transitions */
* {
  transition: all 0.15s ease;
}

/* Header Section dengan gradient subtle */
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
  color: rgba(255, 255, 255, 0.9);
  margin: 0.25rem 0 0 0;
}

/* Button Primary yang lebih eye-catching */
.btn-primary {
  background: white;
  color: #667eea;
  padding: 0.625rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  border: none;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  cursor: pointer;
}

.btn-primary:hover {
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  transform: translateY(-1px);
}

/* Flash Messages dengan style baru */
.alert {
  padding: 1rem 1.25rem;
  border-radius: 0.75rem;
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

/* Table Container dengan style card modern */
.table-container {
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

/* Table Header dengan background */
.table-container thead {
  background: #f8fafc;
  border-bottom: 2px solid #e2e8f0;
}

.table-container thead th {
  padding: 1rem;
  font-weight: 600;
  color: #475569;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
}

/* Table Body */
.table-container tbody td {
  padding: 1rem;
  color: #334155;
  vertical-align: middle;
  text-align: center;
}

.table-container tbody tr {
  border-bottom: 1px solid #f1f5f9;
}

.table-container tbody tr:hover {
  background: #fafbfc;
}

.table-container tbody tr:last-child {
  border-bottom: none;
}

/* Badge Status dengan style baru */
.badge {
  display: inline-block;
  padding: 0.375rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.025em;
}

.badge-success {
  background: #d1fae5;
  color: #065f46;
}

.badge-warning {
  background: #fef3c7;
  color: #92400e;
}

/* Action Buttons dengan warna berbeda */
.btn-action {
  padding: 0.35rem 0.55rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 500;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  white-space: nowrap;
}

.btn-edit {
  background: #e0e7ff;
  color: #4338ca;
}

.btn-edit:hover {
  background: #c7d2fe;
}

.btn-toggle-active {
  background: #fef3c7;
  color: #92400e;
}

.btn-toggle-active:hover {
  background: #fde68a;
}

.btn-toggle-inactive {
  background: #d1fae5;
  color: #065f46;
}

.btn-toggle-inactive:hover {
  background: #a7f3d0;
}

.btn-delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-delete:hover {
  background: #fecaca;
}

/* Modal Overlay */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0);
  backdrop-filter: blur(0px);
  z-index: 9999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active {
  display: flex;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(2px);
}

/* Modal Content dengan shadow lebih tegas */
.modal-box {
  background: white;
  border-radius: 1rem;
  width: 100%;
  max-width: 26rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  max-height: 85vh;
  overflow-y: auto;
  margin: auto;
  opacity: 0;
  transform: translateY(-20px) scale(0.96);
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active .modal-box {
  opacity: 1;
  transform: translateY(0) scale(1);
}

/* Modal Header dengan gradient */
.modal-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1.25rem;
  border-radius: 1rem 1rem 0 0;
}

.modal-header h3 {
  color: white;
  font-size: 1.125rem;
  font-weight: 700;
  margin: 0;
}

.modal-close {
  position: absolute;
  top: 1.25rem;
  right: 1.25rem;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: none;
  width: 1.75rem;
  height: 1.75rem;
  border-radius: 0.375rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
}

.modal-close:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Modal Body */
.modal-body {
  padding: 1.25rem;
}

/* Form Elements */
.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
}

.form-input, .form-select {
  width: 100%;
  padding: 0.625rem 0.875rem;
  border: 2px solid #e5e7eb;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  color: #1f2937;
}

.form-input:focus, .form-select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-input:disabled, .form-select:disabled {
  background: #f3f4f6;
  cursor: not-allowed;
}

/* Modal Footer */
.modal-footer {
  padding: 0.875rem 1.25rem;
  background: #f9fafb;
  border-radius: 0 0 1rem 1rem;
  display: flex;
  justify-content: flex-end;
  gap: 0.625rem;
}

.btn-secondary {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 600;
  font-size: 0.875rem;
  border: 2px solid #e5e7eb;
  background: white;
  color: #6b7280;
  cursor: pointer;
}

.btn-secondary:hover {
  background: #f9fafb;
  border-color: #d1d5db;
}

.btn-submit {
  padding: 0.5rem 1.25rem;
  border-radius: 0.375rem;
  font-weight: 600;
  font-size: 0.875rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  cursor: pointer;
}

.btn-submit:hover {
  box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.3);
}

/* Responsive Design */
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

  .modal-box {
    max-width: 95%;
    margin: 1rem;
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
    font-size: 0.75rem;
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

/* Protected Text */
.text-protected {
  font-size: 0.75rem;
  color: #9ca3af;
  font-style: italic;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #9ca3af;
}
</style>

<!-- Header Section -->
<div class="header-section">
  <div style="display: flex; justify-content: space-between; align-items: center;">
    <div>
      <h2>Management Operator</h2>
      <p>Kelola akun operator & admin dengan mudah</p>
    </div>
    <button id="btnOpenCreate" class="btn-primary">
      <i class="fa fa-plus"></i> Tambah Operator
    </button>
  </div>
</div>

<!-- Flash Messages -->
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
  <table style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th style="width: 60px;">#</th>
        <th>Username</th>
        <th>Nama Lengkap</th>
        <th style="width: 100px;">Role</th>
        <th style="width: 100px;">Status</th>
        <th style="width: 120px;">Dibuat</th>
        <th style="width: 230px;">Aksi</th>
      </tr>
    </thead>

    <tbody>
    <?php if (!empty($operators)): $i = 1; foreach ($operators as $op): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><strong><?= esc($op['username']) ?></strong></td>
        <td><?= esc($op['nama_lengkap']) ?></td>
        <td><?= ucfirst(esc($op['role'])) ?></td>
        <td>
          <?php if (($op['status'] ?? 'aktif') === 'aktif'): ?>
            <span class="badge badge-success">Aktif</span>
          <?php else: ?>
            <span class="badge badge-warning">Nonaktif</span>
          <?php endif; ?>
        </td>
        <td><?= date('d M Y', strtotime($op['created_at'])) ?></td>
        <td>
          <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button class="btn-action btn-edit btnEdit"
              data-id="<?= $op['id'] ?>"
              data-username="<?= esc($op['username'], 'attr') ?>"
              data-nama="<?= esc($op['nama_lengkap'], 'attr') ?>"
              data-role="<?= esc($op['role'], 'attr') ?>">
              <i class="fa fa-pen"></i> Edit
            </button>

            <?php if ($op['role'] !== 'admin'): ?>
              <form method="POST" action="<?= base_url('/settings/operators/toggle-status/'.$op['id']) ?>" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn-action <?= ($op['status'] === 'aktif') ? 'btn-toggle-active' : 'btn-toggle-inactive' ?>">
                  <i class="fa fa-<?= ($op['status'] === 'aktif') ? 'pause' : 'play' ?>"></i>
                  <?= ($op['status'] === 'aktif') ? 'Nonaktifkan' : 'Aktifkan' ?>
                </button>
              </form>

              <form method="POST" 
                    action="<?= base_url('/settings/operators/delete/'.$op['id']) ?>"
                    style="display: inline;"
                    onsubmit="return confirm('Hapus operator ini? (soft delete)')">
                <?= csrf_field() ?>
                <button type="submit" class="btn-action btn-delete">
                  <i class="fa fa-trash"></i> Hapus
                </button>
              </form>
            <?php else: ?>
              <span class="text-protected">🔒 Protected</span>
            <?php endif; ?>
          </div>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr>
        <td colspan="7">
          <div class="empty-state">
            <i class="fa fa-users" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
            <p>Belum ada operator yang terdaftar</p>
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
    <div class="modal-header" style="position: relative;">
      <h3 id="modalTitle">Tambah Operator</h3>
      <button type="button" class="modal-close" id="btnCloseModalX">
        <i class="fa fa-times"></i>
      </button>
    </div>

    <form id="modalForm" method="POST" action="<?= base_url('/settings/operators/create') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="op_id">

      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Username</label>
          <input type="text" name="username" id="op_username" required class="form-input" placeholder="Masukkan username">
        </div>

        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama_lengkap" id="op_nama" required class="form-input" placeholder="Masukkan nama lengkap">
        </div>

        <div class="form-group">
          <label class="form-label">Role</label>
          <select name="role" id="op_role" class="form-select">
            <option value="operator">Operator</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div class="form-group" id="passwordField">
          <label class="form-label">Password</label>
          <input type="password" name="password" id="op_password" class="form-input" placeholder="Masukkan password">
          <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">
            <i class="fa fa-info-circle"></i> Kosongkan jika tidak ingin mengubah password
          </p>
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
  modal.style.display = 'flex';
  void modal.offsetWidth;
  requestAnimationFrame(() => {
    modal.classList.add('active');
  });
}

// Open Create Modal
document.getElementById('btnOpenCreate').addEventListener('click', () => {
  document.getElementById('modalTitle').textContent = 'Tambah Operator Baru';
  document.getElementById('modalForm').action = "<?= base_url('/settings/operators/create') ?>";
  document.getElementById('op_id').value = '';
  document.getElementById('op_username').value = '';
  document.getElementById('op_nama').value = '';
  document.getElementById('op_role').value = 'operator';
  document.getElementById('op_role').removeAttribute('disabled');
  document.getElementById('op_password').value = '';
  
  openModal();
});

// Open Edit Modal
document.querySelectorAll('.btnEdit').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.id;
    const username = btn.dataset.username;
    const nama = btn.dataset.nama;
    const role = btn.dataset.role;

    document.getElementById('modalTitle').textContent = 'Edit Operator';
    document.getElementById('op_id').value = id;
    document.getElementById('op_username').value = username;
    document.getElementById('op_nama').value = nama;
    document.getElementById('op_role').value = role;

    if (role === 'admin') {
      document.getElementById('op_role').setAttribute('disabled', true);
    } else {
      document.getElementById('op_role').removeAttribute('disabled');
    }

    document.getElementById('modalForm').action = "<?= base_url('/settings/operators/update/') ?>" + id;
    
    openModal();
  });
});

// Close Modal
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