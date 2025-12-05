<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= APP_NAME ?> - Reserva tus vuelos de forma rápida y segura">
    <title><?= $page_title ?? APP_NAME ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/booking.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/payment_profile.css') ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?= url('/') ?>">
                    <i class="fas fa-plane-departure"></i>
                    <span><?= APP_NAME ?></span>
                </a>
            </div>
            
            <div class="navbar-menu">
                <ul class="navbar-nav">
                    <li><a href="<?= url('/') ?>">Inicio</a></li>
                    <li><a href="<?= url('/flights/search') ?>">Buscar Vuelos</a></li>
                    
                    <?php if (is_authenticated()): ?>
                        <li><a href="<?= url('/profile/dashboard') ?>">Mi Perfil</a></li>
                        <li><a href="<?= url('/profile/dashboard') ?>">Mis Reservas</a></li>
                        <li>
                            <span class="user-name">
                                <i class="fas fa-user-circle"></i>
                                <?= escape_html(get_user_name()) ?>
                            </span>
                        </li>
                        <li><a href="<?= url('/logout') ?>" class="btn-logout">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="<?= url('/login') ?>" class="btn-login">Iniciar Sesión</a></li>
                        <li><a href="<?= url('/register') ?>" class="btn-register">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php
    $flash = get_flash();
    if ($flash):
    ?>
    <div class="flash-message flash-<?= $flash['type'] ?>">
        <div class="container">
            <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'error' ? 'exclamation-circle' : 'info-circle') ?>"></i>
            <span><?= escape_html($flash['message']) ?></span>
            <button class="flash-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="main-content">
