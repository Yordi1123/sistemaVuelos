    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-plane-departure"></i> <?= APP_NAME ?></h3>
                    <p>Tu mejor opción para reservar vuelos de forma rápida, segura y confiable.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="<?= url('/') ?>">Inicio</a></li>
                        <li><a href="<?= url('/flights/search') ?>">Buscar Vuelos</a></li>
                        <li><a href="<?= url('/about') ?>">Acerca de</a></li>
                        <li><a href="<?= url('/contact') ?>">Contacto</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Soporte</h4>
                    <ul>
                        <li><a href="<?= url('/help') ?>">Centro de Ayuda</a></li>
                        <li><a href="<?= url('/terms') ?>">Términos y Condiciones</a></li>
                        <li><a href="<?= url('/privacy') ?>">Política de Privacidad</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <ul class="contact-info">
                        <li><i class="fas fa-phone"></i> +51 1 234 5678</li>
                        <li><i class="fas fa-envelope"></i> info@sistemavuelos.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> Lima, Perú</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Todos los derechos reservados.</p>
                <p>Desarrollado como proyecto académico - Sistemas de Información II</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
