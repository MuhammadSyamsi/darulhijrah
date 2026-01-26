<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Sistaff Darul Hijrah</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #d7f5e3, #b2e5c2);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }
    .logo {
      margin-top: 50px;
      margin-bottom: 25px;
    }
    .logo img {
      width: 200px;
      height: 200px;
      object-fit: contain;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.75);
      border-radius: 1.5rem 1.5rem 0 0;
      width: 100%;
      max-width: 480px;
      height: 60vh;
      padding: 2rem;
      box-shadow: 0 -5px 25px rgba(0,0,0,0.15);
      position: fixed;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .form-header {
      text-align: center;
      font-weight: 700;
      font-size: 1.7rem;
      margin-bottom: 1rem;
      color: #2e7d32;
    }
    .input-box {
      background: #fff;
      border-radius: 1rem;
      padding: 1rem;
      border: 1px solid #ccc;
    }
    .input-group-text {
      background: none;
      border: none;
      font-size: 1.2rem;
    }
    .form-control {
      border: none;
      background: transparent;
      box-shadow: none;
      color: #333;
    }
    .form-control::placeholder {
      color: #888;
    }
    .form-control:focus {
      outline: none;
      box-shadow: none;
    }
    hr {
      margin: 0.8rem 0;
      border-top: 1px solid #ddd;
    }
    .login-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .login-actions a {
      font-weight: 600;
      color: #2e7d32;
      text-decoration: none;
    }
    .login-actions a:hover {
      color: #1b5e20;
    }
    .login-btn {
      border-radius: 50rem;
      padding: 0.6rem 2rem;
      font-weight: 500;
      background: #66bb6a;
      border: none;
      color: #fff;
    }
    .login-btn:hover {
      background: #43a047;
    }
    .footer-label {
      text-align: center;
      font-weight: 600;
      color: #2e7d32;
      margin-top: 1rem;
    }
    .icon-user { color: #007bff; }
    .icon-pass { color: #e53935; }
  </style>
</head>
<body>

  <!-- Logo -->
  <div class="logo text-center">
    <img src="<?= base_url('./assets/images/logo.png') ?>" alt="Logo Ponpes Darul Hijrah">
  </div>

  <!-- Card -->
  <div class="login-card">

    <div>
      <!-- Flash Error -->
      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger py-2">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <?= view('Myth\Auth\Views\_message_block') ?>

      <!-- Header Form -->
      <div class="form-header">Login</div>

      <!-- Form -->
      <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <div class="input-box mb-4">
          <!-- Username / Email -->
          <div class="input-group mb-2">
            <span class="input-group-text">
              <i class="bi bi-person-circle icon-user"></i>
            </span>

            <?php if ($config->validFields === ['email']): ?>
              <input type="email" 
                     name="login" 
                     class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" 
                     placeholder="<?= lang('Auth.email') ?>">
                     <hr>
            <?php else: ?>
              <input type="text" 
                     name="login" 
                     class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" 
                     placeholder="<?= lang('Auth.emailOrUsername') ?>" 
                     required>
            <?php endif; ?>
          </div>

            <hr>

          <!-- Password -->
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-key-fill icon-pass"></i>
            </span>
            <input type="password" 
                   name="password" 
                   class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" 
                   placeholder="<?= lang('Auth.password') ?>" 
                   required>
          </div>
        </div>

        <?php if ($config->allowRemembering): ?>
          <div class="form-check mb-3">
            <input type="checkbox" name="remember" class="form-check-input" 
                   <?php if (old('remember')) : ?> checked <?php endif ?>>
            <label class="form-check-label"><?= lang('Auth.rememberMe') ?></label>
          </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="login-actions">
          <a href="https://wa.me/6289520821215">Lupa Password?</a>
          <button type="submit" class="btn login-btn px-4">Login</button>
        </div>
      </form>
    </div>

    <!-- Error feedback -->
    <div class="invalid-feedback">
      <?= session('errors.password') ?>
    </div>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>