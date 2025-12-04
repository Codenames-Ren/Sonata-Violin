<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= $title ?? 'Dashboard'; ?></title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
      --violin-1: #0f172a;
      --violin-2: #1e293b;
      --accent: #06b6d4;
    }

    * { font-family: 'Inter', sans-serif; }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }

    /* -------------------- HAMBURGER BUTTON -------------------- */
    #mobileToggle {
      width: 50px;
      height: 40px;
      background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 12px rgba(6, 182, 212, 0.35);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.2s ease;
      cursor: pointer;
    }

    #mobileToggle:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(6, 182, 212, 0.45);
    }

    #mobileToggle:active {
      transform: translateY(0);
      box-shadow: 0 2px 8px rgba(6, 182, 212, 0.35);
    }

    #mobileToggle i {
      font-size: 16px;
      color: white;
    }

    /* Hide hamburger on desktop */
    @media (min-width: 769px) {
      #mobileToggle {
        display: none !important;
      }
    }

    /* Show hamburger on mobile */
    @media (max-width: 768px) {
      #mobileToggle {
        display: flex !important;
      }
    }

    /* -------------------- SIDEBAR DESKTOP -------------------- */
    @media (min-width: 769px) {
      #sidebar {
        width: 260px !important;
        min-width: 260px;
        max-width: 260px;
        padding: 20px 16px;
        background: linear-gradient(180deg, var(--violin-1) 0%, var(--violin-2) 100%);
        box-shadow: 4px 0 24px rgba(0,0,0,0.3);
        border-right: 1px solid rgba(255,255,255,0.05);
      }

      .sidebar-item-full {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 12px;
        transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
        color: #cbd5e1;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        position: relative;
        overflow: hidden;
      }

      .sidebar-item-full::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: var(--accent);
        transform: scaleY(0);
        transition: transform .2s ease;
      }

      .sidebar-item-full:hover {
        background: rgba(6, 182, 212, 0.1);
        color: white;
        transform: translateX(4px);
      }

      .sidebar-item-full:hover::before {
        transform: scaleY(1);
      }

      .sidebar-item-full.active {
        background: linear-gradient(90deg, rgba(6, 182, 212, 0.15), rgba(6, 182, 212, 0.05));
        color: white;
        font-weight: 600;
      }

      .sidebar-item-full.active::before {
        transform: scaleY(1);
      }

      .sidebar-icon {
        width: 20px;
        text-align: center;
        font-size: 16px;
      }

      .sidebar-item-full i.fa-chevron-right {
        margin-left: auto;
        font-size: 12px;
        transition: transform .2s ease;
      }

      .sidebar-item-full:hover i.fa-chevron-right {
        transform: translateX(2px);
      }

      .sidebar-button {
        display: none !important;
      }

      /* Hide mobile overlay on desktop */
      #mobileOverlay {
        display: none !important;
      }
    }

    /* -------------------- SIDEBAR MOBILE -------------------- */
    @media (max-width: 768px) {
      #sidebar {
        width: 260px;
        min-width: 260px;
        max-width: 260px;
        background: linear-gradient(180deg, var(--violin-1) 0%, var(--violin-2) 100%);
        transform: translateX(-100%);
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 100;
        padding: 20px 16px;
        box-shadow: 4px 0 24px rgba(0,0,0,0.3);
      }

      #sidebar.open {
        transform: translateX(0);
      }

      .sidebar-item-full {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 12px;
        transition: all .2s ease;
        color: #cbd5e1;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
      }

      .sidebar-item-full:hover {
        background: rgba(6, 182, 212, 0.1);
        color: white;
      }

      .sidebar-item-full.active {
        background: linear-gradient(90deg, rgba(6, 182, 212, 0.15), rgba(6, 182, 212, 0.05));
        color: white;
        font-weight: 600;
      }

      .sidebar-icon {
        width: 20px;
        text-align: center;
        font-size: 16px;
      }

      .sidebar-item-full i.fa-chevron-right {
        margin-left: auto;
        font-size: 12px;
        transition: transform .2s ease;
      }

      .sidebar-item-full.expanded i.fa-chevron-right {
        transform: rotate(90deg);
      }

      .sidebar-button {
        display: none !important;
      }

      #mobileOverlay {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 90;
        opacity: 0;
        transition: opacity .3s ease;
        pointer-events: none;
      }

      #mobileOverlay.open {
        opacity: 1;
        pointer-events: auto;
      }
    }

    /* -------------------- FLYOUT PANELS (Desktop) -------------------- */
    @media (min-width: 769px) {
      .flyout {
        position: fixed;
        left: 268px;
        min-width: 220px;
        max-width: 280px;
        z-index: 60;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px);
        transition: all .2s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        border-radius: 16px;
        padding: 8px;
        backdrop-filter: blur(20px) saturate(180%);
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(6, 182, 212, 0.2);
        box-shadow: 0 12px 32px rgba(0,0,0,0.4);
      }

      .flyout.active {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
        pointer-events: auto;
      }

      .flyout-title {
        padding: 8px 12px;
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      .flyout a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        color: #cbd5e1;
        transition: all .15s ease;
        font-size: 14px;
      }

      .flyout a i {
        width: 18px;
        text-align: center;
        font-size: 14px;
        color: #64748b;
      }

      .flyout a:hover {
        background: rgba(6, 182, 212, 0.15);
        color: white;
        transform: translateX(2px);
      }

      .flyout a:hover i {
        color: var(--accent);
      }

      /* Mobile submenu (inline) */
      .mobile-submenu {
        display: none;
      }
    }

    /* -------------------- MOBILE SUBMENU (Inline) -------------------- */
    @media (max-width: 768px) {
      .flyout {
        display: none !important;
      }

      .mobile-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height .3s ease;
        margin-left: 20px;
        margin-top: 4px;
      }

      .mobile-submenu.expanded {
        max-height: 500px;
      }

      .mobile-submenu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        color: #94a3b8;
        font-size: 13px;
        border-radius: 8px;
        transition: all .15s ease;
        margin-bottom: 2px;
      }

      .mobile-submenu a i {
        width: 16px;
        text-align: center;
        font-size: 12px;
      }

      .mobile-submenu a:hover {
        background: rgba(6, 182, 212, 0.1);
        color: white;
        transform: translateX(2px);
      }
    }

    /* -------------------- MAIN LAYOUT -------------------- */
    .app-main {
      margin-left: 260px;
      transition: margin .3s ease;
    }

    @media (max-width: 768px) {
      .app-main { margin-left: 0; }
    }

    .topbar {
      backdrop-filter: blur(12px);
      background: rgba(255,255,255,0.9);
      border-bottom: 1px solid rgba(0,0,0,0.08);
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .sidebar-logo {
      margin-bottom: 24px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .sidebar-logo .logo-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--accent), #0891b2);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
    }

  </style>
</head>
<body>

<?php
$uri = service('uri')->getPath();

$role = session('role');

$menuGroups = [
      ['id'=>'dashboard','label'=>'Dashboard','icon'=>'fa-home','items'=>null,'link'=>'/dashboard'],

      $role === 'admin'
      ? ['id'=>'management','label'=>'Management','icon'=>'fa-boxes-stacked','items'=>[
          ['/siswa','Siswa','fa-user-graduate'],
          ['/instruktur','Instruktur','fa-chalkboard-user'],
          ['/ruangan','Ruang Kelas','fa-door-open'],
          ['/paket','Paket Kursus','fa-box-open'],
      ]]
      : null,

      ['id'=>'operations','label'=>'Operations','icon'=>'fa-briefcase','items'=>[
          ['/jadwal','Jadwal Kelas','fa-calendar-days'],
          ['/pendaftaran','Pendaftaran','fa-file-signature'],
          ['/verifikasi','Verifikasi Pembayaran','fa-money-check-dollar'],
          ['/absensi','Absensi','fa-user-check'],
      ]],

      ['id'=>'reports','label'=>'Reports','icon'=>'fa-chart-pie','items'=>[
          ['/progress','Progress Kursus','fa-chart-line'],
          ['/laporan','Laporan','fa-file-pdf'],
      ]],
  ];
?>

<!-- MOBILE OVERLAY -->
<div id="mobileOverlay" onclick="closeMobileSidebar()"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="fixed top-0 left-0 h-full flex flex-col z-40">

  <div class="sidebar-logo flex items-center gap-3">
    <div class="logo-icon">
      <i class="fa fa-music text-white text-xl"></i>
    </div>
    <div>
      <span class="text-white font-bold text-lg block leading-tight">Sonata Violin</span>
      <span class="text-cyan-400 text-xs font-medium">Admin Panel</span>
    </div>
  </div>

  <nav class="w-full flex flex-col gap-2">

    <?php foreach ($menuGroups as $group): 
      if ($group === null) continue;

      $hasSub = !is_null($group['items']);
      $active = false;

      if (!$hasSub && $group['link'] && ltrim($group['link'],'/') === $uri) {
          $active = true;
      }

      if ($hasSub) {
          foreach ($group['items'] as $it) {
              if (ltrim($it[0], '/') === $uri) $active = true;
          }
      }
    ?>

      <!-- MENU ITEM -->
      <?php if ($hasSub): ?>

        <div class="sidebar-item-full <?= $active ? 'active' : ''; ?>"
            data-menu="<?= $group['id']; ?>"
            onmouseenter="openFlyoutMenu('<?= $group['id']; ?>')"
            onclick="toggleMobileSubmenu('<?= $group['id']; ?>')">

          <i class="fa <?= $group['icon']; ?> sidebar-icon"></i>
          <span><?= $group['label']; ?></span>
          <i class="fa fa-chevron-right"></i>
        </div>

        <!-- MOBILE SUBMENU -->
        <div id="mobile-submenu-<?= $group['id']; ?>" class="mobile-submenu">
          <?php foreach ($group['items'] as $it): ?>
            <a href="<?= base_url($it[0]); ?>">
              <i class="fa <?= $it[2]; ?>"></i>
              <span><?= $it[1]; ?></span>
            </a>
          <?php endforeach; ?>
        </div>

      <?php else: ?>

        <a href="<?= base_url($group['link']); ?>"
          class="sidebar-item-full <?= $active ? 'active' : ''; ?>">
          <i class="fa <?= $group['icon']; ?> sidebar-icon"></i>
          <span><?= $group['label']; ?></span>
        </a>

      <?php endif; ?>

    <?php endforeach; ?>

  </nav>

<div class="flex-1"></div>

  <div class="pt-4 border-t border-white/10 space-y-2">
    <?php if (session('role') === 'admin'): ?>
    <a href="<?= base_url('/settings/operators'); ?>" 
      class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 transition-all">
      <i class="fa fa-cog text-cyan-400"></i>
      <span class="text-slate-300 text-sm font-medium">Manajemen Operator</span>
    </a>
    <?php endif; ?>
    
    <a href="<?= base_url('/logout'); ?>" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/10 hover:bg-red-500/20 transition-all text-red-400 hover:text-red-300">
      <i class="fa fa-right-from-bracket"></i>
      <span class="text-sm font-medium">Logout</span>
    </a>
  </div>

</aside>

<!-- FLYOUT PANELS (Desktop Only) -->
  <?php foreach ($menuGroups as $group): 
        if ($group === null) continue;
        if (empty($group['items'])) continue;
  ?>
  <div id="flyout-<?= $group['id']; ?>" class="flyout" aria-hidden="true">
    <div class="flyout-title"><?= $group['label']; ?></div>
    <div class="flex flex-col gap-1">
      <?php foreach ($group['items'] as $it): ?>
        <a href="<?= base_url($it[0]); ?>">
          <i class="fa <?= $it[2]; ?>"></i>
          <span><?= $it[1]; ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endforeach; ?>

<!-- MAIN -->
<div class="app-main min-h-screen">

  <header class="topbar px-6 py-4 sticky top-0 z-30">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <button id="mobileToggle">
          <i class="fa fa-bars"></i>
        </button>

        <div>
          <h1 class="text-2xl font-bold text-slate-800"><?= $page_title ?? 'Dashboard'; ?></h1>
          <p class="text-sm text-slate-500"><?= $page_subtitle ?? 'Track your progress and stay on target'; ?></p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center transition-all">
          <i class="fa fa-bell text-slate-600"></i>
        </button>

        <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 
            text-white flex items-center justify-center font-semibold shadow-lg">
              <?= strtoupper(substr(session('username'), 0, 1)); ?>
          </div>
          <div class="hidden md:block">
            <p class="text-sm font-semibold text-slate-700">
                <?= session('nama'); ?>
            </p>
            <p class="text-xs text-slate-500">
                <?= ucfirst(session('role')); ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="p-6">
    <div class="bg-white/95 backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-white/20">
      <?= $this->renderSection('content'); ?>
    </div>
  </main>
</div>

<script>
  const isMobile = () => window.innerWidth <= 768;
  let openFlyout = null;

  // DESKTOP: Hover to show flyout
  document.querySelectorAll('[data-menu]').forEach(trigger => {
    const id = trigger.dataset.menu;
    const flyout = document.getElementById("flyout-" + id);
    if (!flyout) return;

    trigger.addEventListener("mouseenter", () => {
      if (isMobile()) return;
      showFlyout(trigger, flyout);
    });

    trigger.addEventListener("mouseleave", () => {
      if (isMobile()) return;
      setTimeout(() => { 
        if (!flyout.matches(":hover")) {
          hideFlyout(flyout);
        }
      }, 150);
    });

    flyout.addEventListener("mouseenter", () => {
      if (isMobile()) return;
      flyout.classList.add("active");
    });

    flyout.addEventListener("mouseleave", () => {
      if (isMobile()) return;
      hideFlyout(flyout);
    });
  });

  function showFlyout(trigger, flyout) {
    if (isMobile()) return;
    
    // Close all other flyouts first
    document.querySelectorAll(".flyout.active").forEach(f => {
      if (f !== flyout) {
        hideFlyout(f);
      }
    });
    
    const rect = trigger.getBoundingClientRect();
    flyout.style.top = rect.top + window.scrollY + "px";
    flyout.classList.add("active");
    openFlyout = flyout;
  }

  function hideFlyout(flyout) {
    flyout.classList.remove("active");
    openFlyout = null;
  }

  // MOBILE: Toggle inline submenu
  function toggleMobileSubmenu(id) {
    if (!isMobile()) return;
    
    const submenu = document.getElementById("mobile-submenu-" + id);
    const trigger = document.querySelector(`[data-menu="${id}"]`);
    
    if (!submenu) return;

    const isExpanded = submenu.classList.contains("expanded");
    
    // Close all other submenus
    document.querySelectorAll(".mobile-submenu").forEach(s => {
      s.classList.remove("expanded");
    });
    document.querySelectorAll(".sidebar-item-full").forEach(t => {
      t.classList.remove("expanded");
    });
    
    // Toggle current submenu
    if (!isExpanded) {
      submenu.classList.add("expanded");
      trigger.classList.add("expanded");
    }
  }

  // MOBILE SIDEBAR TOGGLE
  const sidebar = document.getElementById("sidebar");
  const mobileOverlay = document.getElementById("mobileOverlay");
  const toggleBtn = document.getElementById("mobileToggle");

  toggleBtn?.addEventListener("click", () => {
    sidebar.classList.add("open");
    mobileOverlay.classList.add("open");
  });

  function closeMobileSidebar() {
    sidebar.classList.remove("open");
    mobileOverlay.classList.remove("open");
    
    // Close all submenus when closing sidebar
    document.querySelectorAll(".mobile-submenu").forEach(s => {
      s.classList.remove("expanded");
    });
    document.querySelectorAll(".sidebar-item-full").forEach(t => {
      t.classList.remove("expanded");
    });
  }

  // Close sidebar when clicking a link on mobile
  document.querySelectorAll('.mobile-submenu a').forEach(link => {
    link.addEventListener('click', () => {
      if (isMobile()) {
        closeMobileSidebar();
      }
    });
  });

  function openFlyoutMenu(id) {
    if (isMobile()) return;

    const trigger = document.querySelector(`[data-menu="${id}"]`);
    const flyout = document.getElementById("flyout-" + id);

    if (!trigger || !flyout) return;

    showFlyout(trigger, flyout);
  }

</script>

</body>
</html>