<?php
// Estas variables vienen de $datos_layout['datos_vista'] en ControladorAuth.php
// y se hacen disponibles aquí por el extract() en el método cargarLayout del controlador.
// O directamente si el controlador de auth renderiza esta vista sin el layout principal.

// Si no se usa el layout principal, debemos definir ASSETS_URL si es necesario.
// Pero si $css_especifico y $js_especifico se manejan en el controlador,
// y el controlador carga este archivo directamente (sin layout_principal),
// entonces los enlaces CSS/JS deben estar aquí.

// Asumimos que $datos_vista contiene las variables necesarias
$showLogin = $datos_vista['showLogin'] ?? true; // Por defecto muestra login
$showRegister = $datos_vista['showRegister'] ?? false;

$login_error = $datos_vista['login_error'] ?? null;
$register_error = $datos_vista['register_error'] ?? null;
$register_success = $datos_vista['register_success'] ?? (isset($_GET['success']) && $_GET['success'] == 1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($titulo_pagina ?? 'Acceso - TUTTOR-UP') ?></title>
  <!-- Enlazamos el CSS específico para autenticación -->
  <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/estilos_auth.css">
  <!-- Podrías querer algunas variables CSS si no se cargan globalmente -->
  <!-- <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/variables.css"> -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body>
  <div class="auth-page-wrapper"> <!-- Nuevo wrapper para la página de autenticación -->
      <div class="auth-logo-container">
          <a href="<?= BASE_URL ?>/">
              <img src="<?= ASSETS_URL ?>/imagenes/logo.png" alt="Logo Tuttor-Up" class="auth-logo-img">
              <span class="auth-logo-text">TUTTOR-UP</span>
          </a>
      </div>

      <?php if ($register_success): ?>
        <div class="alert success">¡Registro exitoso! Por favor, inicia sesión.</div>
      <?php endif; ?>

      <?php if ($login_error): ?>
        <div class="alert error"><?= htmlspecialchars($login_error, ENT_QUOTES) ?></div>
      <?php endif; ?>
      
      <?php if ($register_error): ?>
        <div class="alert error"><?= htmlspecialchars($register_error, ENT_QUOTES) ?></div>
      <?php endif; ?>

      <div class="auth-container">
        <!-- Registro -->
        <div class="auth-form-container <?= $showRegister ? 'form-active' : 'form-inactive' ?>" id="registroFormContainer">
          <h1>Regístrate</h1>
          <form action="<?= BASE_URL ?>/auth/procesarRegistro" method="POST">
            <input type="text" name="name" placeholder="Nombre completo" required value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES) ?>">
            <input type="text" name="cedula" placeholder="DNI / Cédula" required pattern="[0-9]+" title="Solo números" value="<?= htmlspecialchars($_POST['cedula'] ?? '', ENT_QUOTES) ?>">
            <input type="email" name="email" placeholder="ejemplo@mail.com" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>">
            <input type="password" name="password" placeholder="Contraseña (mín. 6 caracteres)" required minlength="6">
            <button type="submit" name="registro">Registrar</button>
          </form>
          <p class="toggle-form">
            ¿Ya tienes cuenta? <a href="#" id="showLoginLink">Iniciar sesión</a>
          </p>
        </div>
        
        <!-- Login -->
        <div class="auth-form-container <?= $showLogin ? 'form-active' : 'form-inactive' ?>" id="loginFormContainer">
          <h2>Iniciar sesión</h2>
          <form action="<?= BASE_URL ?>/auth/procesarLogin" method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>">
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" name="boton_ingresar">Ingresar</button>
          </form>
          <p class="toggle-form">
            ¿No tienes cuenta? <a href="#" id="showRegisterLink">Regístrate</a>
          </p>
        </div>
      </div>
  </div>

  <script src="<?= ASSETS_URL ?>/js/auth_toggle.js"></script>
</body>
</html>