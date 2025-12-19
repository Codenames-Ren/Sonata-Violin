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

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
  </style>
</head>

<body class="bg-gradient-to-br from-[#667eea] to-[#764ba2] min-h-screen font-sans">

<?php
$uri = service('uri')->getPath();
$role = session('role');

$menuGroups = [];

// DASHBOARD (ADMIN & OPERATOR ONLY)
if (in_array($role, ['admin', 'operator'])) {
  $menuGroups[] = [
    'id'=>'dashboard',
    'label'=>'Dashboard',
    'icon'=>'fa-home',
    'items'=>null,
    'link'=>'/dashboard'
  ];
}

//  MANAGEMENT (ADMIN ONLY)
if ($role === 'admin') {
  $menuGroups[] = [
    'id'=>'management',
    'label'=>'Management',
    'icon'=>'fa-boxes-stacked',
    'items'=>[
      ['/siswa','Siswa','fa-user-graduate'],
      ['/instruktur','Instruktur','fa-chalkboard-user'],
      ['/ruang-kelas','Ruang Kelas','fa-door-open'],
      ['/paket','Paket Kursus','fa-box-open'],
    ]
  ];
}

// OPERATIONS
if (in_array($role, ['admin', 'operator'])) {
  $menuGroups[] = [
    'id'=>'operations',
    'label'=>'Operations',
    'icon'=>'fa-briefcase',
    'items'=>[
      ['/jadwal','Jadwal Kelas','fa-calendar-days'],
      ['/pendaftaran','Pendaftaran','fa-file-signature'],
      ['/pembayaran','Verifikasi Pembayaran','fa-money-check-dollar'],
      ['/absensi','Absensi','fa-user-check'],
    ]
  ];
}

//  INSTRUKTUR MENU (KHUSUS)
if ($role === 'instruktur') {
  $menuGroups[] = [
    'id'=>'instruktur',
    'label'=>'Pengajaran',
    'icon'=>'fa-chalkboard',
    'items'=>[
      ['/jadwal','Jadwal Kelas','fa-calendar-days'],
      ['/absensi','Absensi','fa-user-check'],
      ['/progress','Progress Kursus','fa-chart-line'],
    ]
  ];
}

// REPORTS (ADMIN & OPERATOR)
if (in_array($role, ['admin', 'operator'])) {
  $menuGroups[] = [
    'id'=>'reports',
    'label'=>'Reports',
    'icon'=>'fa-chart-pie',
    'items'=>[
      ['/progress','Progress Kursus','fa-chart-line'],
      ['/laporan','Laporan','fa-file-pdf'],
    ]
  ];
}
?>

<!-- MOBILE OVERLAY -->
<div id="mobileOverlay"
  onclick="closeMobileSidebar()"
  class="hidden md:hidden fixed inset-0 bg-black/60 z-[90] opacity-0 transition-opacity duration-300 pointer-events-none">
</div>

<!-- SIDEBAR -->
<aside id="sidebar"
  class="fixed top-0 left-0 h-full flex flex-col z-[100] w-[260px] min-w-[260px] max-w-[260px]
    p-5 bg-gradient-to-b from-[#0f172a] to-[#1e293b]
    shadow-[4px_0_24px_rgba(0,0,0,0.3)] border-r border-white/5
    -translate-x-full md:translate-x-0 transition-transform duration-300">

  <div class="mb-6 pb-5 border-b border-white/[0.08] flex items-center gap-3">
    <div class="w-[42px] h-[42px] rounded-[19px] bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center shadow-[0_4px_12px_rgba(6,182,212,0.4)]">
      <img src="<?= base_url('image/icon.png'); ?>" class="logo">
    </div>
    <div>
      <span class="text-white font-bold text-lg block leading-tight">Sonata Violin</span>
      <span class="text-cyan-400 text-xs font-medium">
        <?= ucfirst($role); ?> Panel
      </span>
    </div>
  </div>

  <nav class="w-full flex flex-col gap-2">
  <?php foreach ($menuGroups as $group):
    if ($group === null) continue;

    $hasSub = !is_null($group['items']);
    $active = false;

    if (!$hasSub && $group['link'] && ltrim($group['link'],'/') === $uri) $active = true;

    if ($hasSub) {
      foreach ($group['items'] as $it) {
        if (ltrim($it[0], '/') === $uri) $active = true;
      }
    }
  ?>

    <?php if ($hasSub): ?>
      <div
        class="sidebar-item-full w-full flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 text-slate-300 text-sm font-medium cursor-pointer relative overflow-hidden
        before:content-[''] before:absolute before:left-0 before:top-0 before:h-full before:w-[3px] before:bg-cyan-500 before:scale-y-0 before:transition-transform before:duration-200
        hover:bg-cyan-500/10 hover:text-white hover:translate-x-1 hover:before:scale-y-100
        <?= $active ? 'bg-gradient-to-r from-cyan-500/15 to-cyan-500/5 text-white font-semibold before:scale-y-100' : ''; ?>"
        data-menu="<?= $group['id']; ?>"
        onmouseenter="openFlyoutMenu('<?= $group['id']; ?>')"
        onclick="toggleMobileSubmenu('<?= $group['id']; ?>')"
      >
        <i class="fa <?= $group['icon']; ?> w-5 text-center text-base"></i>
        <span><?= $group['label']; ?></span>
        <i class="fa fa-chevron-right ml-auto text-xs transition-transform duration-200"></i>
      </div>

      <div id="mobile-submenu-<?= $group['id']; ?>"
        class="mobile-submenu max-h-0 overflow-hidden transition-[max-height] duration-300 ml-5 mt-1 md:hidden">
        <?php foreach ($group['items'] as $it): ?>
          <a href="<?= base_url($it[0]); ?>"
            class="flex items-center gap-2.5 px-4 py-2.5 text-slate-400 text-[13px] rounded-lg transition-all duration-150 mb-0.5
            hover:bg-cyan-500/10 hover:text-white hover:translate-x-0.5">
            <i class="fa <?= $it[2]; ?> w-4 text-center text-xs"></i>
            <span><?= $it[1]; ?></span>
          </a>
        <?php endforeach; ?>
      </div>

    <?php else: ?>

      <a href="<?= base_url($group['link']); ?>"
        class="sidebar-item-full w-full flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-200 text-slate-300 text-sm font-medium cursor-pointer relative overflow-hidden
        before:content-[''] before:absolute before:left-0 before:top-0 before:h-full before:w-[3px] before:bg-cyan-500 before:scale-y-0 before:transition-transform before:duration-200
        hover:bg-cyan-500/10 hover:text-white hover:translate-x-1 hover:before:scale-y-100
        <?= $active ? 'bg-gradient-to-r from-cyan-500/15 to-cyan-500/5 text-white font-semibold before:scale-y-100' : ''; ?>">
        <i class="fa <?= $group['icon']; ?> w-5 text-center text-base"></i>
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

    <a href="<?= base_url('/logout'); ?>"
      class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/10 hover:bg-red-500/20 transition-all text-red-400 hover:text-red-300">
      <i class="fa fa-right-from-bracket"></i>
      <span class="text-sm font-medium">Logout</span>
    </a>
  </div>
</aside>

<!-- FLYOUT PANELS -->
<?php foreach ($menuGroups as $group):
  if ($group === null) continue;
  if (empty($group['items'])) continue;
?>
  <div id="flyout-<?= $group['id']; ?>"
    class="flyout fixed left-[268px] min-w-[220px] max-w-[280px] z-[60] opacity-0 invisible -translate-x-2.5 transition-all duration-200 pointer-events-none rounded-2xl p-2 backdrop-blur-[20px]
      bg-[#0f172a]/95 border border-cyan-500/20 shadow-[0_12px_32px_rgba(0,0,0,0.4)] max-md:hidden">

    <div class="px-3 py-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
      <?= $group['label']; ?>
    </div>

    <div class="flex flex-col gap-1">
      <?php foreach ($group['items'] as $it): ?>
        <a href="<?= base_url($it[0]); ?>"
          class="flex items-center gap-3 px-3 py-2.5 rounded-[10px] text-slate-300 transition-all duration-150 text-sm
          hover:bg-cyan-500/15 hover:text-white hover:translate-x-0.5">
          <i class="fa <?= $it[2]; ?> w-[18px] text-center text-sm text-slate-500"></i>
          <span><?= $it[1]; ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endforeach; ?>

<!-- MAIN -->
<div class="app-main min-h-screen ml-[260px] transition-[margin] duration-300 max-md:ml-0">

  <header class="backdrop-blur-xl bg-white/90 border-b border-black/[0.08] shadow-[0_1px_3px_rgba(0,0,0,0.05)] px-6 py-4 sticky top-0 z-30">
    <div class="flex items-center justify-between">

      <div class="flex items-center gap-4">
        <button id="mobileToggle"
          class="w-[50px] h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-[10px] flex items-center justify-center shadow-[0_4px_12px_rgba(6,182,212,0.35)] border border-white/20 transition-all duration-200 cursor-pointer
          hover:-translate-y-0.5 hover:shadow-[0_6px_16px_rgba(6,182,212,0.45)]
          active:translate-y-0 active:shadow-[0_2px_8px_rgba(6,182,212,0.35)]
          md:hidden">
          <i class="fa fa-bars text-base text-white"></i>
        </button>

        <div>
          <h1 class="text-2xl font-bold text-slate-800"><?= $page_title ?? ($role === 'instruktur' ? 'Jadwal Kelas' : 'Dashboard') ?></h1>
          <p class="text-sm text-slate-500"><?= $page_subtitle ?? ($role === 'instruktur'
            ? 'Jadwal mengajar sesuai kelas yang diampu.'
            : 'Pantau progres, jaga kelancaran operasional.') ?>
          </p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center transition-all">
          <i class="fa fa-bell text-slate-600"></i>
        </button>

        <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 text-white flex items-center justify-center font-semibold shadow-lg">
            <?= strtoupper(substr(session('username'), 0, 1)); ?>
          </div>

          <div class="hidden md:block">
            <p class="text-sm font-semibold text-slate-700"><?= session('nama'); ?></p>
            <p class="text-xs text-slate-500"><?= ucfirst(session('role')); ?></p>
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

// ========= DESKTOP FLYOUT =========
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
    }, 120);
  });

  flyout.addEventListener("mouseenter", () => {
    if (!isMobile()) {
      flyout.classList.add("opacity-100", "visible", "translate-x-0", "pointer-events-auto");
    }
  });

  flyout.addEventListener("mouseleave", () => {
    if (!isMobile()) hideFlyout(flyout);
  });
});

function showFlyout(trigger, flyout) {
  document.querySelectorAll(".flyout").forEach(f => {
    if (f !== flyout) hideFlyout(f);
  });

  const rect = trigger.getBoundingClientRect();
  flyout.style.top = rect.top + "px";

  flyout.classList.remove("opacity-0", "invisible", "-translate-x-2.5", "pointer-events-none");
  flyout.classList.add("opacity-100", "visible", "translate-x-0", "pointer-events-auto");
}

function hideFlyout(flyout) {
  flyout.classList.remove("opacity-100", "visible", "translate-x-0", "pointer-events-auto");
  flyout.classList.add("opacity-0", "invisible", "-translate-x-2.5", "pointer-events-none");
}

// ========= MOBILE SUBMENU =========
function toggleMobileSubmenu(id) {
  if (!isMobile()) return;

  const submenu = document.getElementById("mobile-submenu-" + id);
  const trigger = document.querySelector(`[data-menu="${id}"]`);

  if (!submenu) return;

  const isExpanded = submenu.classList.contains("max-h-[500px]");

  document.querySelectorAll(".mobile-submenu").forEach(s => {
    s.classList.remove("max-h-[500px]");
    s.classList.add("max-h-0");
  });

  document.querySelectorAll(".sidebar-item-full i.fa-chevron-right").forEach(icon => {
    icon.classList.remove("rotate-90");
  });

  if (!isExpanded) {
    submenu.classList.add("max-h-[500px]");
    submenu.classList.remove("max-h-0");
    const chevron = trigger.querySelector("i.fa-chevron-right");
    chevron?.classList.add("rotate-90");
  }
}

// ========= MOBILE SIDEBAR =========
const sidebar = document.getElementById("sidebar");
const mobileOverlay = document.getElementById("mobileOverlay");
const toggleBtn = document.getElementById("mobileToggle");

toggleBtn?.addEventListener("click", () => {
  sidebar.classList.remove("-translate-x-full");
  sidebar.classList.add("translate-x-0");

  mobileOverlay.classList.remove("hidden", "opacity-0", "pointer-events-none");
  mobileOverlay.classList.add("opacity-100", "pointer-events-auto");
});

function closeMobileSidebar() {
  sidebar.classList.add("-translate-x-full");
  sidebar.classList.remove("translate-x-0");

  mobileOverlay.classList.add("opacity-0", "pointer-events-none");
  mobileOverlay.classList.remove("opacity-100", "pointer-events-auto");

  setTimeout(() => {
    mobileOverlay.classList.add("hidden");
  }, 300);

  document.querySelectorAll(".mobile-submenu").forEach(s => {
    s.classList.remove("max-h-[500px]");
    s.classList.add("max-h-0");
  });

  document.querySelectorAll(".sidebar-item-full i.fa-chevron-right").forEach(icon => {
    icon.classList.remove("rotate-90");
  });
}

// Close when clicking submenu link
document.querySelectorAll('.mobile-submenu a').forEach(link => {
  link.addEventListener('click', () => {
    if (isMobile()) closeMobileSidebar();
  });
});
</script>

</body>
</html>