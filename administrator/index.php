<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - Sistem Pendukung Keputusan PKH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="Sistem Pendukung Keputusan Rekomendasi Penerima Bantuan PKH dengan Metode SAW">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%23212529'/%3E%3Cpath d='M38 25c-4 0-7 3-7 7 0 2 1 4 2 5l7 7 7-7c1-1 2-3 2-5 0-4-3-7-7-7zm0 4c1 0 2 1 2 2s-1 2-2 2-2-1-2-2 1-2 2-2zM20 55h8c2 0 4 2 4 4v12c0 2-2 4-4 4h-8c-2 0-4-2-4-4V59c0-2 2-4 4-4zm28 0h28c2 0 4 2 4 4v12c0 2-2 4-4 4H48c-2 0-4-2-4-4V59c0-2 2-4 4-4z' fill='white'/%3E%3C/svg%3E">
    <link rel="shortcut icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%23212529'/%3E%3Cpath d='M38 25c-4 0-7 3-7 7 0 2 1 4 2 5l7 7 7-7c1-1 2-3 2-5 0-4-3-7-7-7zm0 4c1 0 2 1 2 2s-1 2-2 2-2-1-2-2 1-2 2-2zM20 55h8c2 0 4 2 4 4v12c0 2-2 4-4 4h-8c-2 0-4-2-4-4V59c0-2 2-4 4-4zm28 0h28c2 0 4 2 4 4v12c0 2-2 4-4 4H48c-2 0-4-2-4-4V59c0-2 2-4 4-4z' fill='white'/%3E%3C/svg%3E">
    <link rel="apple-touch-icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%23212529'/%3E%3Cpath d='M38 25c-4 0-7 3-7 7 0 2 1 4 2 5l7 7 7-7c1-1 2-3 2-5 0-4-3-7-7-7zm0 4c1 0 2 1 2 2s-1 2-2 2-2-1-2-2 1-2 2-2zM20 55h8c2 0 4 2 4 4v12c0 2-2 4-4 4h-8c-2 0-4-2-4-4V59c0-2 2-4 4-4zm28 0h28c2 0 4 2 4 4v12c0 2-2 4-4 4H48c-2 0-4-2-4-4V59c0-2 2-4 4-4z' fill='white'/%3E%3C/svg%3E">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Ensure relative URLs resolve to /administrator/ on Vercel -->
    <base href="/administrator/">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Modern Framework CSS -->
    <link rel="stylesheet" href="css/modern-framework.css">

    <!-- Admin custom theme (system-aware) -->
    <link rel="stylesheet" href="css/admin-theme.css">
    <!-- Jarvis Modern Palette Theme -->
    <link rel="stylesheet" href="css/theme-modern-palette.css">

    <style>
      body {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 50%, var(--accent-blue) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        position: relative;
        overflow-x: hidden;
        overflow-y: auto;
      }
      
      body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)" /></svg>');
        opacity: 0.3;
        pointer-events: none;
      }

      .login-container {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 450px;
        animation: slideUp 0.8s ease-out;
        margin: 2rem auto;
        display: flex;
        flex-direction: column;
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        box-shadow: 
          0 20px 40px rgba(12, 24, 33, 0.2),
          0 0 80px rgba(50, 74, 95, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
      }

      .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-accent);
      }

      .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
      }

      .login-logo {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--white);
        font-size: 2rem;
        box-shadow: var(--shadow-lg);
      }

      .login-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #000000 !important;
        margin-bottom: 0.5rem;
        line-height: 1.3;
      }

      .login-subtitle {
        color: #000000 !important;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.5;
      }

      .login-form {
        margin-bottom: 1.5rem;
      }

      .form-group {
        margin-bottom: 1.5rem;
        position: relative;
      }

      .form-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid rgba(204, 201, 220, 0.3);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: var(--white);
        color: var(--primary-dark);
      }

      .form-input:focus {
        outline: none;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(50, 74, 95, 0.1);
        transform: translateY(-1px);
      }

      .form-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1.1rem;
        transition: var(--transition);
      }

      .form-input:focus + .form-icon {
        color: var(--accent-blue);
      }

      .login-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        font-size: 0.875rem;
      }

      .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #000000 !important;
        cursor: pointer;
        transition: var(--transition);
      }

      .remember-me:hover {
        color: #000000 !important;
      }

      .remember-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(204, 201, 220, 0.5);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
      }

      .remember-checkbox.checked {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: var(--white);
      }

      .login-btn {
        width: 100%;
        padding: 1rem 2rem;
        background: var(--gradient-primary);
        color: var(--white);
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
      }

      .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(12, 24, 33, 0.3);
      }

      .login-btn:active {
        transform: translateY(0);
      }

      .login-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
      }

      .login-btn:hover::before {
        left: 100%;
      }

      .login-footer {
        text-align: center;
        color: #000000 !important;
        font-size: 0.8rem;
        line-height: 1.5;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(204, 201, 220, 0.2);
      }

      .system-info {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        font-weight: 500;
        color: #000000 !important;
      }

      .error-message {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #dc2626;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        display: none;
      }

      .floating-shapes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
      }

      .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 20s infinite linear;
      }

      .shape:nth-child(1) {
        width: 80px;
        height: 80px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
      }

      .shape:nth-child(2) {
        width: 60px;
        height: 60px;
        top: 60%;
        right: 10%;
        animation-delay: 5s;
      }

      .shape:nth-child(3) {
        width: 100px;
        height: 100px;
        bottom: 20%;
        left: 20%;
        animation-delay: 10s;
      }

      .shape:nth-child(4) {
        width: 40px;
        height: 40px;
        top: 30%;
        right: 30%;
        animation-delay: 15s;
      }

      @keyframes float {
        0%, 100% {
          transform: translateY(0) rotate(0deg);
          opacity: 0.3;
        }
        50% {
          transform: translateY(-20px) rotate(180deg);
          opacity: 0.7;
        }
      }

      @media (max-width: 480px) {
        body {
          padding: 0.5rem;
          min-height: 100vh;
          overflow-y: auto;
          -webkit-overflow-scrolling: touch;
        }
        
        .login-card {
          padding: 2rem 1.5rem;
          margin: 1rem 0;
          border-radius: 20px;
        }
        
        .login-title {
          font-size: 1.5rem;
          color: #000000 !important;
        }
        
        .login-subtitle {
          font-size: 0.85rem;
          color: #000000 !important;
        }
      }

      /* Scroll enhancement for all devices */
      html {
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
      }

      /* Additional black text enforcement */
      .login-header h1,
      .login-header p,
      .login-footer,
      .login-footer p,
      .system-info,
      .system-info span,
      .remember-me,
      .remember-me span {
        color: #000000 !important;
      }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="theme-modern">
    
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
      <div class="shape"></div>
      <div class="shape"></div>
      <div class="shape"></div>
      <div class="shape"></div>
    </div>

    <div class="login-container">
      <div class="login-card">
        <div class="login-header">
          <div class="login-logo">
            <i class="fas fa-hand-holding-heart"></i>
          </div>
          <h1 class="login-title">Sistem PKH SAW</h1>
          <p class="login-subtitle">
            Sistem Pendukung Keputusan Rekomendasi Penerima Bantuan<br>
            Program Keluarga Harapan dengan Metode SAW
          </p>
        </div>

        <div id="error-message" class="error-message">
          <i class="fas fa-exclamation-circle"></i>
          <span id="error-text">Username atau password salah!</span>
        </div>

        <form action="cek_login.php" method="post" class="login-form" id="loginForm">
          <div class="form-group">
            <input type="text" class="form-input" placeholder="Username" name="username" id="username" required autocomplete="username">
            <i class="fas fa-user form-icon"></i>
          </div>

          <div class="form-group">
            <input type="password" class="form-input" placeholder="Password" name="password" id="password" required autocomplete="current-password">
            <i class="fas fa-lock form-icon"></i>
          </div>

          <div class="login-options">
            <label class="remember-me" for="remember">
              <div class="remember-checkbox" id="rememberCheckbox">
                <i class="fas fa-check" style="display: none;"></i>
              </div>
              <input type="checkbox" id="remember" style="display: none;">
              <span>Ingat saya</span>
            </label>
          </div>

          <button type="submit" class="login-btn" id="loginBtn">
            <i class="fas fa-sign-in-alt"></i>
            <span style="color: #ffffff !important;">Masuk ke Sistem</span>
          </button>
        </form>

        <div class="login-footer">
          <div class="system-info">
            <i class="fas fa-shield-alt"></i>
            <span>Sistem Informasi Dinas Sosial</span>
          </div>
          <p style="margin-top: 0.5rem; opacity: 0.8; color: #000000 !important;">
            Republik Indonesia © 2025
          </p>
        </div>
      </div>
    </div>

    <!-- Modern Framework JavaScript -->
    <script src="js/modern-framework.js"></script>
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const rememberCheckbox = document.getElementById('remember');
        const rememberCheckboxUI = document.getElementById('rememberCheckbox');
        const rememberIcon = rememberCheckboxUI.querySelector('i');
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const errorMessage = document.getElementById('error-message');
        
        // Remember me checkbox functionality
        rememberCheckboxUI.addEventListener('click', function() {
          rememberCheckbox.checked = !rememberCheckbox.checked;
          updateCheckboxUI();
        });
        
        function updateCheckboxUI() {
          if (rememberCheckbox.checked) {
            rememberCheckboxUI.classList.add('checked');
            rememberIcon.style.display = 'block';
          } else {
            rememberCheckboxUI.classList.remove('checked');
            rememberIcon.style.display = 'none';
          }
        }
        
        // Form submission with loading state
        loginForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const username = document.getElementById('username').value.trim();
          const password = document.getElementById('password').value.trim();
          
          if (!username || !password) {
            showError('Username dan password harus diisi!');
            return;
          }
          
          // Show loading state
          loginBtn.innerHTML = `
            <div class="modern-spinner" style="width: 20px; height: 20px; border-width: 2px;"></div>
            <span>Memverifikasi...</span>
          `;
          loginBtn.disabled = true;
          
          // Hide any existing errors
          errorMessage.style.display = 'none';
          
          // Submit form after short delay for visual feedback
          setTimeout(() => {
            loginForm.submit();
          }, 800);
        });
        
        function showError(message) {
          const errorText = document.getElementById('error-text');
          errorText.textContent = message;
          errorMessage.style.display = 'block';
          
          // Shake animation
          errorMessage.style.animation = 'shake 0.5s ease-in-out';
          setTimeout(() => {
            errorMessage.style.animation = '';
          }, 500);
        }
        
        // Check for login errors from PHP
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('error') === '1') {
          showError('Username atau password salah!');
        } else if (urlParams.get('error') === '2') {
          showError('Sesi Anda telah berakhir. Silakan login kembali.');
        }
        
        // Auto-focus on username field
        document.getElementById('username').focus();
        
        // Add floating label effect
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
          input.addEventListener('focus', function() {
            this.parentNode.classList.add('focused');
          });
          
          input.addEventListener('blur', function() {
            if (!this.value) {
              this.parentNode.classList.remove('focused');
            }
          });
          
          // Check if field has value on load
          if (input.value) {
            input.parentNode.classList.add('focused');
          }
        });
      });
      
      // Add shake animation CSS
      const style = document.createElement('style');
      style.textContent = `
        @keyframes shake {
          0%, 100% { transform: translateX(0); }
          10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
          20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
      `;
      document.head.appendChild(style);
    </script>
  </body>
</html>
