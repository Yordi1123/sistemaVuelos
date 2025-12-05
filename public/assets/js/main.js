/**
 * JavaScript Principal
 * Sistema de Reserva de Vuelos
 */

// Esperar a que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-cerrar mensajes flash después de 5 segundos
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(function(flash) {
        setTimeout(function() {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                flash.remove();
            }, 300);
        }, 5000);
    });
    
    // Validación de formulario de registro
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');
            
            if (password && passwordConfirm) {
                if (password.value !== passwordConfirm.value) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden');
                    passwordConfirm.focus();
                    return false;
                }
                
                if (password.value.length < 8) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 8 caracteres');
                    password.focus();
                    return false;
                }
            }
        });
        
        // Mostrar/ocultar contraseña
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');
        togglePasswordButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });
    }
    
    // Validación de email en tiempo real
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#ef4444';
                showInputError(this, 'Email inválido');
            } else {
                this.style.borderColor = '#e5e7eb';
                hideInputError(this);
            }
        });
    });
    
    // Animación suave al hacer scroll
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Menú responsive (hamburger)
    const menuToggle = document.querySelector('.menu-toggle');
    const navbarMenu = document.querySelector('.navbar-menu');
    
    if (menuToggle && navbarMenu) {
        menuToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
    
});

// Función para mostrar error en input
function showInputError(input, message) {
    let errorDiv = input.nextElementSibling;
    if (!errorDiv || !errorDiv.classList.contains('input-error')) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'input-error';
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
    }
    errorDiv.textContent = message;
    errorDiv.style.color = '#ef4444';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
}

// Función para ocultar error en input
function hideInputError(input) {
    const errorDiv = input.nextElementSibling;
    if (errorDiv && errorDiv.classList.contains('input-error')) {
        errorDiv.remove();
    }
}

// Función para formatear precio
function formatPrice(amount) {
    return 'S/ ' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Función para formatear fecha
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('es-PE', options);
}

// Función para formatear hora
function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit' });
}

// Exportar funciones para uso global
window.flightSystem = {
    formatPrice: formatPrice,
    formatDate: formatDate,
    formatTime: formatTime,
    showInputError: showInputError,
    hideInputError: hideInputError
};
