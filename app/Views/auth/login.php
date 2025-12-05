<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login - Kursus Biola</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    * { font-family: 'Poppins', sans-serif; }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .auth-container {
      position: relative;
      width: 100%;
      max-width: 950px;
      height: 600px;
      background: white;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      overflow: hidden;
    }

    .slide-panel {
      position: absolute;
      top: 0;
      left: 0;
      width: 50%;
      height: 100%;
      transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      z-index: 10;
      pointer-events: none;
    }

    .slide-panel.active {
      transform: translateX(100%);
    }

    .form-panel {
      position: absolute;
      top: 0;
      width: 50%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      transition: opacity 0.3s ease;
    }

    .form-panel.left { left: 0; }
    .form-panel.right { right: 0; }

    .overlay-panel {
      position: absolute;
      width: 50%;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px;
      text-align: center;
      color: white;
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%), 
            url('<?= base_url('image/bg1.jpg'); ?>') center/cover no-repeat;
      transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .overlay-left {
      left: 0;
      transform: translateX(-100%);
    }

    .overlay-right {
      right: 0;
      transform: translateX(0);
    }

    .slide-panel.active ~ .overlay-left {
      transform: translateX(0);
    }

    .slide-panel.active ~ .overlay-right {
      transform: translateX(100%);
    }

    .social-btn {
      display: none !important;
    }

    input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .paw-bg {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0.1;
      background-image: 
        radial-gradient(circle at 20% 30%, rgba(255,255,255,0.3) 5px, transparent 5px),
        radial-gradient(circle at 60% 70%, rgba(255,255,255,0.3) 5px, transparent 5px),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.3) 5px, transparent 5px);
      background-size: 150px 150px;
      pointer-events: none;
    }

    /* ========== MOBILE RESPONSIVE ========== */
    @media (max-width: 768px) {
      body {
        padding: 16px;
      }

      .auth-container {
        max-width: 100%;
        width: 100%;
        height: auto;
        min-height: 500px;
        max-height: 90vh;
        border-radius: 20px;
      }

      .form-panel {
        padding: 28px 20px;
      }

      .w-full.max-w-sm {
        max-width: 100%;
        width: 100%;
      }

      /* HIDE: Logo icon di atas form */
      .form-panel .w-16.h-16 {
        display: none !important;
      }

      /* HIDE: Social media buttons - SEMUA MODE */
      .form-panel .flex.justify-center.gap-3 {
        display: none !important;
      }

      /* HIDE: Divider "or use credentials" - SEMUA MODE */
      .form-panel .flex.items-center.gap-3 {
        display: none !important;
      }

      /* Text header - lebih kecil dan compact */
      .text-center.mb-8 {
        margin-bottom: 1.25rem !important;
      }

      .text-3xl {
        font-size: 1.5rem !important;
        line-height: 1.3 !important;
        margin-bottom: 0.375rem !important;
      }

      .text-slate-500.text-sm {
        font-size: 0.8125rem !important;
        line-height: 1.4 !important;
      }

      /* Input fields - lebih compact */
      .mb-4 {
        margin-bottom: 0.875rem !important;
      }

      input {
        padding: 0.6875rem 0.875rem 0.6875rem 2.625rem !important;
        font-size: 0.875rem !important;
        border-radius: 0.75rem !important;
      }

      input::placeholder {
        font-size: 0.875rem !important;
      }

      /* Icons di input */
      .absolute.left-4 {
        left: 0.875rem !important;
        font-size: 0.9375rem !important;
      }

      .absolute.right-4 {
        right: 0.875rem !important;
        font-size: 0.9375rem !important;
      }

      /* Remember me & Forgot password - DIPERKECIL */
      .flex.items-center.justify-between.mb-6 {
        margin-bottom: 1rem !important;
      }

      .flex.items-center.justify-between label span,
      .flex.items-center.justify-between a {
        font-size: 0.6875rem !important;
      }

      .flex.items-center.gap-2 input[type="checkbox"] {
        width: 0.75rem !important;
        height: 0.75rem !important;
      }

      /* Submit button */
      button[type="submit"] {
        padding: 0.8125rem 1rem !important;
        font-size: 0.9375rem !important;
        border-radius: 0.75rem !important;
      }

      /* Overlay panel */
      .overlay-panel {
        padding: 28px 20px !important;
      }

      .overlay-panel h2 {
        font-size: 1.625rem !important;
        margin-bottom: 0.625rem !important;
      }

      .overlay-panel p {
        font-size: 0.8125rem !important;
        margin-bottom: 1.5rem !important;
        line-height: 1.5 !important;
      }

      .overlay-panel i.fa-user-shield,
      .overlay-panel i.fa-users-cog {
        font-size: 2.75rem !important;
        margin-bottom: 1rem !important;
      }

      .overlay-panel button {
        padding: 0.625rem 1.75rem !important;
        font-size: 0.875rem !important;
      }
    }

    /* Extra small devices */
    @media (max-width: 480px) {
      body {
        padding: 12px;
      }

      .auth-container {
        min-height: 480px;
        border-radius: 18px;
      }

      .form-panel {
        padding: 24px 18px;
      }

      .text-3xl {
        font-size: 1.375rem !important;
      }

      .text-slate-500.text-sm {
        font-size: 0.75rem !important;
      }

      input {
        padding: 0.625rem 0.75rem 0.625rem 2.375rem !important;
        font-size: 0.8125rem !important;
      }

      input::placeholder {
        font-size: 0.8125rem !important;
      }

      .absolute.left-4 {
        left: 0.75rem !important;
        font-size: 0.875rem !important;
      }

      .absolute.right-4 {
        right: 0.75rem !important;
        font-size: 0.875rem !important;
      }

      /* Remember me & Forgot password - LEBIH KECIL LAGI */
      .flex.items-center.justify-between label span,
      .flex.items-center.justify-between a {
        font-size: 0.625rem !important;
      }

      .flex.items-center.gap-2 input[type="checkbox"] {
        width: 0.6875rem !important;
        height: 0.6875rem !important;
      }

      button[type="submit"] {
        padding: 0.75rem 0.875rem !important;
        font-size: 0.875rem !important;
      }

      .overlay-panel h2 {
        font-size: 1.5rem !important;
      }

      .overlay-panel p {
        font-size: 0.75rem !important;
      }

      .overlay-panel i.fa-user-shield,
      .overlay-panel i.fa-users-cog {
        font-size: 2.5rem !important;
      }

      .overlay-panel button {
        padding: 0.5625rem 1.5rem !important;
        font-size: 0.8125rem !important;
      }
    }
  </style>
</head>
<body>

<div class="auth-container">
  
  <!-- Sliding Panel (Left) -->
  <div class="slide-panel" id="slidePanel">
    <div class="paw-bg"></div>
  </div>

  <!-- LOGIN FORM -->
  <div class="form-panel left" id="loginPanel">
    <div class="w-full max-w-sm">

      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg">
          <i class="fas fa-user-shield text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-slate-800 mb-2">Admin Login</h2>
        <p class="text-slate-500 text-sm">Internal system access for staff only</p>
      </div>

      <div class="flex justify-center gap-3 mb-6">
        <button class="social-btn bg-blue-500 hover:bg-blue-600">
          <i class="fab fa-facebook-f text-white"></i>
        </button>
        <button class="social-btn bg-white hover:bg-gray-50 border border-gray-200">
          <i class="fab fa-google text-gray-600"></i>
        </button>
        <button class="social-btn bg-gradient-to-br from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600">
          <i class="fab fa-instagram text-white"></i>
        </button>
      </div>

      <div class="flex items-center gap-3 mb-6">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-sm text-gray-400">use credentials</span>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <!-- Login Form -->
      <form method="POST" action="/login" id="loginForm">
        <div class="mb-4">
          <div class="relative">
            <input type="text" name="username" id="usernameInput" required
                  class="w-full px-4 py-3 pl-11 bg-gray-50 border border-gray-200 rounded-xl transition-all"
                  placeholder="Username">
            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>

        <div class="mb-4">
          <div class="relative">
            <input type="password" name="password" id="loginPassword" required
                   class="w-full px-4 py-3 pl-11 pr-11 bg-gray-50 border border-gray-200 rounded-xl transition-all"
                   placeholder="Password">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <button type="button" onclick="togglePassword('loginPassword', this)" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between mb-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" id="rememberMe" class="w-4 h-4 rounded border-gray-300 text-purple-600">
            <span class="text-sm text-slate-600">Remember me</span>
          </label>
          <a href="#" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
            Forgot Password?
          </a>
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg">
          Sign In
        </button>
      </form>

    </div>
  </div>

  <!-- REGISTER FORM -->
  <div class="form-panel right" id="registerPanel">
    <div class="w-full max-w-sm">

      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg">
          <i class="fas fa-user-plus text-white text-2xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-slate-800 mb-2">Staff Registration</h2>
        <p class="text-slate-500 text-sm">Create new staff account</p>
      </div>

      <div class="flex justify-center gap-3 mb-6">
        <button class="social-btn bg-blue-500 hover:bg-blue-600">
          <i class="fab fa-facebook-f text-white"></i>
        </button>
        <button class="social-btn bg-white hover:bg-gray-50 border border-gray-200">
          <i class="fab fa-google text-gray-600"></i>
        </button>
        <button class="social-btn bg-gradient-to-br from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600">
          <i class="fab fa-instagram text-white"></i>
        </button>
      </div>

      <div class="flex items-center gap-3 mb-6">
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="text-sm text-gray-400">fill the form</span>
        <div class="flex-1 h-px bg-gray-200"></div>
      </div>

      <!-- Register -->
      <form method="POST" action="/register">
        <div class="mb-4">
          <div class="relative">
            <input type="text" name="nama_lengkap" required
                   class="w-full px-4 py-3 pl-11 bg-gray-50 border border-gray-200 rounded-xl transition-all"
                   placeholder="Full Name">
            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>

        <div class="mb-4">
          <div class="relative">
            <input type="text" name="username" required
                   class="w-full px-4 py-3 pl-11 bg-gray-50 border border-gray-200 rounded-xl transition-all"
                   placeholder="Username">
            <i class="fa-solid fa-circle-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>

        <div class="mb-4">
          <div class="relative">
            <input type="password" name="password" id="registerPassword" required
                   class="w-full px-4 py-3 pl-11 pr-11 bg-gray-50 border border-gray-200 rounded-xl transition-all"
                   placeholder="Password">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <button type="button" onclick="togglePassword('registerPassword', this)" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg">
          Create Account
        </button>
      </form>

    </div>
  </div>

  <!-- Overlay Left -->
  <div class="overlay-panel overlay-left">
    <div class="paw-bg"></div>
    <div class="relative z-10">
      <i class="fas fa-user-shield text-6xl mb-4 opacity-90"></i>
      <h2 class="text-4xl font-bold mb-3">Already Registered?</h2>
      <p class="text-white/90 mb-8">Sign in with your staff credentials to access the system</p>
      <button onclick="showLogin()" 
              class="px-8 py-3 border-2 border-white text-white font-semibold rounded-full hover:bg-white hover:text-purple-600 transition-all">
        Sign In
      </button>
    </div>
  </div>

  <!-- Overlay Right -->
  <div class="overlay-panel overlay-right">
    <div class="paw-bg"></div>
    <div class="relative z-10">
      <i class="fas fa-users-cog text-6xl mb-4 opacity-90"></i>
      <h2 class="text-4xl font-bold mb-3">New Staff?</h2>
      <p class="text-white/90 mb-8">Register your staff account to get system access</p>
      <button onclick="showRegister()" 
              class="px-8 py-3 border-2 border-white text-white font-semibold rounded-full hover:bg-white hover:text-purple-600 transition-all">
        Sign Up
      </button>
    </div>
  </div>

</div>

<script>
  // Load saved credentials saat halaman load
  window.addEventListener('DOMContentLoaded', function() {
    const savedUsername = localStorage.getItem('rememberedUsername');
    const savedPassword = localStorage.getItem('rememberedPassword');
    
    if (savedUsername && savedPassword) {
      document.getElementById('usernameInput').value = savedUsername;
      document.getElementById('loginPassword').value = savedPassword;
      document.getElementById('rememberMe').checked = true;
    }
  });

  // Save/remove credentials saat submit
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    const rememberMe = document.getElementById('rememberMe').checked;
    const username = document.getElementById('usernameInput').value;
    const password = document.getElementById('loginPassword').value;
    
    if (rememberMe) {
      localStorage.setItem('rememberedUsername', username);
      localStorage.setItem('rememberedPassword', password);
    } else {
      localStorage.removeItem('rememberedUsername');
      localStorage.removeItem('rememberedPassword');
    }
  });

  function showRegister() {
    document.getElementById('slidePanel').classList.add('active');
  }

  function showLogin() {
    document.getElementById('slidePanel').classList.remove('active');
  }

  function togglePassword(id, el) {
    const input = document.getElementById(id);
    const icon = el.querySelector("i");
    if (input.type === "password") {
      input.type = "text";
      icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
      input.type = "password";
      icon.classList.replace("fa-eye-slash", "fa-eye");
    }
  }
</script>

</body>
</html>