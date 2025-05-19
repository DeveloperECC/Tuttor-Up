<header class="header">
    <?php if (isset($mostrar_buscador_header) && $mostrar_buscador_header === true): ?>
        <div class="search-bar">
            <input type="text" id="searchInput" name="q" placeholder="¿Qué quieres aprender hoy?" class="busqueda-input">
            <button type="button" id="searchButton"><i class="fas fa-search"></i></button>
        </div>
    <?php else: ?>
        <div></div> 
    <?php endif; ?>

     <div class="right-icons">
         
         <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <!-- Si el usuario está logueado -->
            <span class="user-greeting">Hola, <?= htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario') ?></span>
            <a href="<?= BASE_URL ?>/auth/logout" class="icon-button auth-button" id="logoutButton" aria-label="Cerrar Sesión">
                 <i class="fas fa-sign-out-alt"></i>
            </a>
            <a href="<?= BASE_URL ?>/perfil" class="icon-button" id="userProfileButton" aria-label="Perfil de Usuario">
                <i class="fas fa-user-circle"></i>
            </a>
         <?php else: ?>
            <!-- Si el usuario NO está logueado -->
            <a href="<?= BASE_URL ?>/auth/loginForm" class="icon-button auth-button" id="loginRegisterButton" aria-label="Iniciar Sesión o Registrarse">
                 <i class="fas fa-sign-in-alt"></i>
                 <span class="auth-button-text">Acceder</span>
            </a>
            <button type="button" class="icon-button" id="userProfileButton" aria-label="Perfil de Usuario" style="display:none;">
                <i class="fas fa-user-circle"></i>
            </button> <!-- Ocultar icono de perfil si no está logueado -->
         <?php endif; ?>

        
    </div>
</header>