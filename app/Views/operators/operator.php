<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
* { transition: all 0.15s ease; }

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
  margin-bottom: 1.5rem;
}

.table-container table {
  width: 100%;
  border-collapse: collapse;
}

.table-container thead {
  background: #f8fafc;
  border-bottom: 2px solid #e2e8f0;
}

.table-container thead th {
  padding: 1rem;
  font-weight: 600;
  color: #475569;
  text-transform: uppercase;
  font-size: .75rem;
  letter-spacing: .05em;
  text-align: center;
}

.table-container tbody td {
  padding: 1rem;
  color: #334155;
  text-align: center;
}

.table-container tbody tr {
  border-bottom: 1px solid #f1f5f9;
}

.table-container tbody tr:hover {
  background: #fafbfc;
}

.badge {
  display: inline-block;
  padding: .375rem .75rem;
  border-radius: 9999px;
  font-size: .75rem;
  font-weight: 600;
}

.badge-success {
  background: #d1fae5;
  color: #065f46;
}

.badge-warning {
  background: #fef3c7;
  color: #92400e;
}

.btn-action {
  padding: .35rem .55rem;
  border-radius: .375rem;
  font-size: .75rem;
  font-weight: 500;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: .25rem;
  white-space: nowrap;
}

.btn-edit {
  background: #e0e7ff;
  color: #4338ca;
}

.btn-edit:hover { background: #c7d2fe; }

.btn-toggle-active {
  background: #fef3c7;
  color: #92400e;
}

.btn-toggle-active:hover { background: #fde68a; }

.btn-toggle-inactive {
  background: #d1fae5;
  color: #065f46;
}

.btn-toggle-inactive:hover { background: #a7f3d0; }

.btn-delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-delete:hover { background: #fecaca; }

.text-protected {
  font-size: .75rem;
  color: #9ca3af;
  font-style: italic;
}

/* Mobile Cards */
.mobile-cards {
  display: none;
}

.card-item {
  background: white;
  border-radius: .75rem;
  padding: 1rem;
  margin-bottom: 1rem;
  box-shadow: 0 1px 3px rgba(0,0,0,.1);
  border: 1px solid #e5e7eb;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: .75rem;
  padding-bottom: .75rem;
  border-bottom: 1px solid #f3f4f6;
}

.card-title {
  font-weight: 700;
  color: #111827;
  font-size: 1rem;
}

.card-subtitle {
  color: #6b7280;
  font-size: .875rem;
  margin-top: .125rem;
}

.card-badge {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: .25rem;
}

.card-role {
  background: #dbeafe;
  color: #1e40af;
  padding: .25rem .5rem;
  border-radius: .375rem;
  font-size: .75rem;
  font-weight: 600;
}

.card-details {
  display: grid;
  gap: .5rem;
  margin-bottom: .75rem;
}

.card-detail {
  font-size: .875rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-detail-label {
  color: #6b7280;
  font-size: .75rem;
}

.card-detail-value {
  color: #111827;
  font-weight: 600;
}

.card-actions {
  display: flex;
  gap: .5rem;
  padding-top: .75rem;
  border-top: 1px solid #f3f4f6;
  flex-wrap: wrap;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: .5rem;
  margin-top: 1.5rem;
}

.pagination button {
  padding: .5rem 1rem;
  border: 2px solid #e5e7eb;
  background: white;
  color: #374151;
  border-radius: .5rem;
  font-weight: 600;
  cursor: pointer;
  font-size: .875rem;
}

.pagination button:hover:not(:disabled) {
  background: #f9fafb;
  border-color: #667eea;
  color: #667eea;
}

.pagination button:disabled {
  opacity: .5;
  cursor: not-allowed;
}

.pagination .page-info {
  color: #6b7280;
  font-weight: 500;
  font-size: .875rem;
  padding: 0 .5rem;
}

/* Modal */
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
  transform: translate(-50%, -50%) scale(0.96);
  background: white;
  border-radius: 1rem;
  width: 90%;
  max-width: 26rem;
  max-height: 85vh;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  opacity: 0;
  transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
}

.modal-overlay.active .modal-box {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1);
}

.modal-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1.25rem;
  border-radius: 1rem 1rem 0 0;
  position: relative;
  flex-shrink: 0;
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

.modal-close:hover { background: rgba(255,255,255,.3); }

.modal-body {
  padding: 1.25rem;
  overflow-y: auto;
  flex: 1;
  min-height: 0;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  font-size: .875rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: .5rem;
}

.form-input, .form-select {
  width: 100%;
  padding: .625rem .875rem;
  border: 2px solid #e5e7eb;
  border-radius: .5rem;
  font-size: .875rem;
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

.modal-body::-webkit-scrollbar { width: 6px; }
.modal-body::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
.modal-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

.modal-footer {
  padding: .875rem 1.25rem;
  background: #f9fafb;
  border-radius: 0 0 1rem 1rem;
  display: flex;
  justify-content: flex-end;
  gap: .625rem;
  flex-shrink: 0;
}

.btn-secondary {
  padding: .5rem 1rem;
  border-radius: .375rem;
  font-weight: 600;
  font-size: .875rem;
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
  padding: .5rem 1.25rem;
  border-radius: .375rem;
  font-weight: 600;
  font-size: .875rem;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
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

  /* Hide table, show cards */
  .table-container {
    display: none;
  }

  .mobile-cards {
    display: block;
  }

  /* Modal Mobile Fix */
  .modal-box {
    width: 95%;
    max-width: 95%;
    max-height: 90vh;
    top: 5%;
    transform: translate(-50%, 0) scale(0.96);
  }

  .modal-overlay.active .modal-box {
    transform: translate(-50%, 0) scale(1);
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

  .form-label {
    font-size: .8rem;
  }

  .form-input, .form-select {
    padding: .5rem .65rem;
    font-size: .8rem;
  }

  .modal-footer {
    padding: .75rem 1rem;
    flex-direction: column;
  }

  .btn-secondary, .btn-submit {
    width: 100%;
    justify-content: center;
  }

  .alert {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
  }

  .pagination button {
    padding: .4rem .75rem;
    font-size: .8rem;
  }

  .pagination .page-info {
    font-size: .8rem;
  }

  .card-actions .btn-action {
    font-size: .7rem;
    padding: .3rem .5rem;
  }
}
</style>

<!-- HEADER -->
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

<!-- TABLE DESKTOP -->
<div class="table-container">
  <table>
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
    <tbody id="tableBody">
      <?php if (!empty($operators)): ?>
        <?php foreach ($operators as $index => $op): ?>
        <tr class="table-row" data-index="<?= $index ?>">
          <td><?= $index + 1 ?></td>
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
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center;">
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
        <?php endforeach; ?>
      <?php else: ?>
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

<!-- MOBILE CARDS -->
<div class="mobile-cards" id="mobileCards">
  <?php if (!empty($operators)): ?>
    <?php foreach ($operators as $index => $op): ?>
    <div class="card-item" data-index="<?= $index ?>">
      <div class="card-header">
        <div>
          <div class="card-title"><?= esc($op['username']) ?></div>
          <div class="card-subtitle"><?= esc($op['nama_lengkap']) ?></div>
        </div>
        <div class="card-badge">
          <div class="card-role"><?= ucfirst(esc($op['role'])) ?></div>
          <?php if (($op['status'] ?? 'aktif') === 'aktif'): ?>
            <span class="badge badge-success">Aktif</span>
          <?php else: ?>
            <span class="badge badge-warning">Nonaktif</span>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="card-details">
        <div class="card-detail">
          <span class="card-detail-label">Dibuat</span>
          <span class="card-detail-value"><?= date('d M Y', strtotime($op['created_at'])) ?></span>
        </div>
      </div>

      <div class="card-actions">
        <button class="btn-action btn-edit btnEdit" style="flex: 1;"
          data-id="<?= $op['id'] ?>"
          data-username="<?= esc($op['username'], 'attr') ?>"
          data-nama="<?= esc($op['nama_lengkap'], 'attr') ?>"
          data-role="<?= esc($op['role'], 'attr') ?>">
          <i class="fa fa-pen"></i> Edit
        </button>

        <?php if ($op['role'] !== 'admin'): ?>
          <form method="POST" action="<?= base_url('/settings/operators/toggle-status/'.$op['id']) ?>" style="flex: 1;">
            <?= csrf_field() ?>
            <button type="submit" class="btn-action <?= ($op['status'] === 'aktif') ? 'btn-toggle-active' : 'btn-toggle-inactive' ?>" style="width: 100%;">
              <i class="fa fa-<?= ($op['status'] === 'aktif') ? 'pause' : 'play' ?>"></i>
              <?= ($op['status'] === 'aktif') ? 'Nonaktif' : 'Aktif' ?>
            </button>
          </form>

          <form method="POST" 
                action="<?= base_url('/settings/operators/delete/'.$op['id']) ?>"
                style="flex: 1;"
                onsubmit="return confirm('Hapus operator ini?')">
            <?= csrf_field() ?>
            <button type="submit" class="btn-action btn-delete" style="width: 100%;">
              <i class="fa fa-trash"></i> Hapus
            </button>
          </form>
        <?php else: ?>
          <span class="text-protected" style="flex: 1; text-align: center;">🔒 Protected</span>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="empty-state">
      <i class="fa fa-users" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
      <p>Belum ada operator yang terdaftar</p>
    </div>
  <?php endif; ?>
</div>

<!-- PAGINATION -->
<?php if (!empty($operators)): ?>
<div class="pagination">
  <button id="btnPrev" onclick="changePage(-1)">
    <i class="fa fa-chevron-left"></i> Prev
  </button>
  <span class="page-info">
    Halaman <span id="currentPage">1</span> dari <span id="totalPages">1</span>
  </span>
  <button id="btnNext" onclick="changePage(1)">
    Next <i class="fa fa-chevron-right"></i>
  </button>
</div>
<?php endif; ?>

<!-- MODAL -->
<div id="modal" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
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

        <div class="form-group">
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
// Pagination
let currentPage = 1;
let itemsPerPage = window.innerWidth <= 768 ? 3 : 8;

function updatePagination() {
  const isMobile = window.innerWidth <= 768;
  itemsPerPage = isMobile ? 3 : 8;
  
  const items = isMobile 
    ? document.querySelectorAll('.card-item[data-index]')
    : document.querySelectorAll('.table-row[data-index]');
  
  const totalItems = items.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage);
  
  if (currentPage > totalPages) currentPage = totalPages || 1;
  
  items.forEach((item, index) => {
    const page = Math.floor(index / itemsPerPage) + 1;
    item.style.display = page === currentPage ? '' : 'none';
  });
  
  document.getElementById('currentPage').textContent = currentPage;
  document.getElementById('totalPages').textContent = totalPages;
  document.getElementById('btnPrev').disabled = currentPage === 1;
  document.getElementById('btnNext').disabled = currentPage === totalPages;
}

function changePage(direction) {
  currentPage += direction;
  updatePagination();
}

window.addEventListener('resize', updatePagination);
updatePagination();

// Modal
const modal = document.getElementById('modal');

function closeModal() {
  modal.classList.remove('active');
}

function openModal() {
  modal.style.display = 'block';
  requestAnimationFrame(() => modal.classList.add('active'));
}

document.getElementById('btnOpenCreate').addEventListener('click', () => {
  document.getElementById('modalTitle').textContent = 'Tambah Operator Baru';
  document.getElementById('modalForm').action = "<?= base_url('/settings/operators/create') ?>";
  ['op_id', 'op_username', 'op_nama', 'op_password'].forEach(id => {
    document.getElementById(id).value = '';
  });
  document.getElementById('op_role').value = 'operator';
  document.getElementById('op_role').removeAttribute('disabled');
  openModal();
});

document.querySelectorAll('.btnEdit').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.id;
    document.getElementById('modalTitle').textContent = 'Edit Operator';
    document.getElementById('op_id').value = id;
    document.getElementById('op_username').value = btn.dataset.username;
    document.getElementById('op_nama').value = btn.dataset.nama;
    document.getElementById('op_role').value = btn.dataset.role;

    if (btn.dataset.role === 'admin') {
      document.getElementById('op_role').setAttribute('disabled', true);
    } else {
      document.getElementById('op_role').removeAttribute('disabled');
    }

    document.getElementById('modalForm').action = "<?= base_url('/settings/operators/update/') ?>" + id;
    openModal();
  });
});

document.getElementById('btnCloseModal').addEventListener('click', closeModal);
document.getElementById('btnCloseModalX').addEventListener('click', closeModal);
document.getElementById('modal').addEventListener('click', (e) => {
  if (e.target.id === 'modal') closeModal();
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
});
modal.addEventListener('transitionend', (e) => {
  if (e.target === modal && !modal.classList.contains('active')) {
    modal.style.display = 'none';
  }
});
</script>

<?= $this->endSection() ?>