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
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    .login-container {
      width: 100%;
      max-width: 1000px;
      background: white;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      overflow: hidden;
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 600px;
    }

    .login-sidebar {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%), 
                  url('<?= base_url('image/bg1.jpg') ?>') center/cover no-repeat;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: white;
      position: relative;
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

    .login-form-panel {
      padding: 60px 50px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* ========== MOBILE RESPONSIVE ========== */
    @media (max-width: 768px) {
      body {
        padding: 16px;
      }

      .login-container {
        grid-template-columns: 1fr;
        max-height: 90vh;
        min-height: auto;
        border-radius: 20px;
      }

      .login-sidebar {
        padding: 32px 24px;
        min-height: 200px;
      }

      .login-sidebar h1 {
        font-size: 1.75rem !important;
        margin-bottom: 0.5rem !important;
      }

      .login-sidebar p {
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
      }

      .login-sidebar i.fa-user-shield {
        font-size: 3rem !important;
        margin-bottom: 1rem !important;
      }

      .login-form-panel {
        padding: 32px 24px;
      }

      .text-3xl {
        font-size: 1.5rem !important;
        line-height: 1.3 !important;
        margin-bottom: 0.5rem !important;
      }

      .text-slate-500 {
        font-size: 0.8125rem !important;
      }

      input {
        padding: 0.75rem 0.875rem 0.75rem 2.75rem !important;
        font-size: 0.875rem !important;
      }

      input::placeholder {
        font-size: 0.875rem !important;
      }

      .absolute.left-4 {
        left: 0.875rem !important;
        font-size: 0.9375rem !important;
      }

      .absolute.right-4 {
        right: 0.875rem !important;
        font-size: 0.9375rem !important;
      }

      .flex.items-center.justify-between label span,
      .flex.items-center.justify-between a {
        font-size: 0.75rem !important;
      }

      .flex.items-center.gap-2 input[type="checkbox"] {
        width: 0.875rem !important;
        height: 0.875rem !important;
      }

      button[type="submit"] {
        padding: 0.875rem 1rem !important;
        font-size: 0.9375rem !important;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 12px;
      }

      .login-sidebar {
        padding: 24px 20px;
        min-height: 180px;
      }

      .login-sidebar h1 {
        font-size: 1.5rem !important;
      }

      .login-sidebar p {
        font-size: 0.8125rem !important;
      }

      .login-sidebar i.fa-user-shield {
        font-size: 2.5rem !important;
      }

      .login-form-panel {
        padding: 28px 20px;
      }

      .text-3xl {
        font-size: 1.375rem !important;
      }

      .text-slate-500 {
        font-size: 0.75rem !important;
      }

      input {
        padding: 0.6875rem 0.75rem 0.6875rem 2.5rem !important;
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

      .flex.items-center.justify-between label span,
      .flex.items-center.justify-between a {
        font-size: 0.6875rem !important;
      }

      .flex.items-center.gap-2 input[type="checkbox"] {
        width: 0.75rem !important;
        height: 0.75rem !important;
      }

      button[type="submit"] {
        padding: 0.8125rem 0.875rem !important;
        font-size: 0.875rem !important;
      }
    }
  </style>
</head>
<body>

<div class="login-container">
  
  <!-- LEFT SIDEBAR - GAMBAR & BRANDING -->
  <div class="login-sidebar">
    <div class="paw-bg"></div>
    <div class="relative z-10">
      <i class="fas fa-user-shield text-7xl mb-6 opacity-90 drop-shadow-lg"></i>
      <h1 class="text-4xl font-bold mb-3 drop-shadow-lg">Welcome Back!</h1>
      <p class="text-white/90 text-lg leading-relaxed drop-shadow">
        Internal system access for authorized staff only. Please sign in with your credentials.
      </p>
    </div>
  </div>

  <!-- RIGHT PANEL - LOGIN FORM -->
  <div class="login-form-panel">
    <div class="w-full max-w-md">

      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-slate-800 mb-2">Admin Login</h2>
        <p class="text-slate-500 text-sm">Enter your credentials to continue</p>
      </div>

      <!-- Login Form -->
      <form method="POST" action="<?= base_url('auth/loginProcess') ?>" id="loginForm">
        <div class="mb-5">
          <div class="relative">
            <input type="text" name="username" id="usernameInput" required
                  class="w-full px-4 py-3 pl-11 bg-gray-50 border border-gray-200 rounded-xl transition-all"
                  placeholder="Username">
            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>

        <div class="mb-5">
          <div class="relative">
            <input type="password" name="password" id="loginPassword" required
                   class="w-full px-4 py-3 pl-11 pr-11 bg-gray-50 border border-gray-200 rounded-xl transition-all"
                   placeholder="Password">
            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <button type="button" onclick="togglePassword('loginPassword', this)" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between mb-6">
          <label class="flex items-center gap-2 cursor-pointer group">
            <input type="checkbox" id="rememberMe" class="w-4 h-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Remember me</span>
          </label>
          <a href="#" class="text-sm text-purple-600 hover:text-purple-700 font-medium transition-colors">
            Forgot Password?
          </a>
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
          <i class="fas fa-sign-in-alt mr-2"></i>Sign In
        </button>

        <div class="mt-6 text-center">
          <p class="text-xs text-gray-400">
            <i class="fas fa-shield-alt mr-1"></i>
            Protected by enterprise security
          </p>
        </div>
      </form>

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

  // AJAX LOGIN - Alert muncul di login SEBELUM redirect
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const rememberMe = document.getElementById('rememberMe').checked;
    const username = document.getElementById('usernameInput').value;
    const password = document.getElementById('loginPassword').value;
    
    // Validasi
    if (!username || !password) {
      Swal.fire({
        icon: 'warning',
        title: 'Oops...',
        text: 'Username dan password harus diisi!',
        confirmButtonColor: '#667eea'
      });
      return;
    }

    // Save credentials jika remember me checked
    if (rememberMe) {
      localStorage.setItem('rememberedUsername', username);
      localStorage.setItem('rememberedPassword', password);
    } else {
      localStorage.removeItem('rememberedUsername');
      localStorage.removeItem('rememberedPassword');
    }

    // AJAX REQUEST
    const formData = new FormData(this);
    
    fetch('<?= base_url('auth/loginProcess') ?>', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Login Berhasil!',
          text: data.message,
          confirmButtonColor: '#667eea',
          confirmButtonText: 'OK'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '<?= base_url('dashboard') ?>';
          }
        }).then(() => {
            window.location.href = '<?= base_url('dashboard') ?>';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            text: data.message,
            confirmButtonColor: '#667eea'
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Terjadi Kesalahan',
          text: 'Gagal menghubungi server. Silakan coba lagi.',
          confirmButtonColor: '#667eea'
        });
      });
    });

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

  // Prevent back button after logout
  history.pushState(null, null, location.href);
  window.onpopstate = function () {
    history.go(1);
  };
</script>

</body>
</html>