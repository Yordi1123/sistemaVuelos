<?php
$page_title = 'Inicio - ' . APP_NAME;
require VIEWS_PATH . '/layouts/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">
            <i class="fas fa-plane-departure"></i>
            Encuentra tu Próximo Destino
        </h1>
        <p class="hero-subtitle">Reserva vuelos de forma rápida, segura y al mejor precio</p>
        
        <div class="hero-cta">
            <a href="<?= url('/flights/search') ?>" class="btn btn-primary btn-large">
                <i class="fas fa-search"></i>
                Buscar Vuelos
            </a>
            <?php if (!is_authenticated()): ?>
            <a href="<?= url('/register') ?>" class="btn btn-secondary btn-large">
                <i class="fas fa-user-plus"></i>
                Registrarse
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <h2 class="section-title">¿Por qué elegirnos?</h2>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Búsqueda Inteligente</h3>
                <p>Encuentra vuelos por horarios, tarifas y disponibilidad en tiempo real</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Pago Seguro</h3>
                <p>Tus datos están protegidos con encriptación de última generación</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Disponibilidad 24/7</h3>
                <p>Reserva tus vuelos en cualquier momento, desde cualquier lugar</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>Gestión Fácil</h3>
                <p>Administra tus reservas y boletos desde tu perfil personal</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <h3>Múltiples Aerolíneas</h3>
                <p>Acceso a las principales aerolíneas nacionales e internacionales</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Soporte Dedicado</h3>
                <p>Atención al cliente para resolver todas tus dudas</p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="container">
        <h2 class="section-title">¿Cómo funciona?</h2>
        
        <div class="steps-grid">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Busca tu Vuelo</h3>
                <p>Ingresa origen, destino y fecha para encontrar opciones disponibles</p>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <i class="fas fa-chair"></i>
                </div>
                <h3>Selecciona Asientos</h3>
                <p>Elige tus asientos preferidos y categoría de vuelo</p>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3>Realiza el Pago</h3>
                <p>Completa tu reserva con un pago seguro</p>
            </div>
            
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>Recibe tu Boleto</h3>
                <p>Obtén tu boleto electrónico al instante</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>¿Listo para tu próximo viaje?</h2>
            <p>Comienza a buscar vuelos ahora y descubre las mejores ofertas</p>
            <a href="<?= url('/flights/search') ?>" class="btn btn-light btn-large">
                <i class="fas fa-plane-departure"></i>
                Buscar Vuelos Ahora
            </a>
        </div>
    </div>
</section>

<?php require VIEWS_PATH . '/layouts/footer.php'; ?>
