<?php
/**
 * Controlador de Autenticación
 * Sistema de Reserva de Vuelos
 */

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Mostrar formulario de login
     */
    public function showLogin() {
        require_guest();
        require VIEWS_PATH . '/auth/login.php';
    }
    
    /**
     * Mostrar formulario de registro
     */
    public function showRegister() {
        require_guest();
        require VIEWS_PATH . '/auth/register.php';
    }
    
    /**
     * Procesar login
     */
    public function login() {
        require_guest();
        
        if (!is_post()) {
            redirect(url('/login'));
            return;
        }
        
        $email = sanitize_email(post_param('email'));
        $password = post_param('password');
        
        // Validación básica
        if (empty($email) || empty($password)) {
            set_flash('error', ERROR_REQUIRED_FIELDS);
            redirect(url('/login'));
            return;
        }
        
        // Autenticar usuario
        $user = $this->userModel->authenticate($email, $password);
        
        if ($user) {
            // Verificar estado del usuario
            if ($user['estado'] !== USER_STATUS_ACTIVE) {
                set_flash('error', 'Tu cuenta está inactiva o suspendida');
                redirect(url('/login'));
                return;
            }
            
            // Establecer sesión
            set_user_session($user);
            set_flash('success', SUCCESS_LOGIN);
            redirect(url('/'));
        } else {
            set_flash('error', ERROR_INVALID_CREDENTIALS);
            redirect(url('/login'));
        }
    }
    
    /**
     * Procesar registro
     */
    public function register() {
        require_guest();
        
        if (!is_post()) {
            redirect(url('/register'));
            return;
        }
        
        // Obtener datos del formulario
        $data = [
            'nombre' => sanitize_input(post_param('nombre')),
            'apellido' => sanitize_input(post_param('apellido')),
            'email' => sanitize_email(post_param('email')),
            'password' => post_param('password'),
            'password_confirm' => post_param('password_confirm'),
            'telefono' => sanitize_input(post_param('telefono')),
            'fecha_nacimiento' => post_param('fecha_nacimiento'),
            'documento_identidad' => sanitize_input(post_param('documento_identidad')),
            'tipo_documento' => post_param('tipo_documento', 'DNI')
        ];
        
        // Validar campos requeridos
        if (empty($data['nombre']) || empty($data['apellido']) || 
            empty($data['email']) || empty($data['password'])) {
            set_flash('error', ERROR_REQUIRED_FIELDS);
            redirect(url('/register'));
            return;
        }
        
        // Validar email
        if (!validate_email($data['email'])) {
            set_flash('error', ERROR_INVALID_EMAIL);
            redirect(url('/register'));
            return;
        }
        
        // Validar que el email no exista
        if ($this->userModel->emailExists($data['email'])) {
            set_flash('error', ERROR_EMAIL_EXISTS);
            redirect(url('/register'));
            return;
        }
        
        // Validar longitud de contraseña
        if (!validate_password_length($data['password'])) {
            set_flash('error', ERROR_PASSWORD_LENGTH);
            redirect(url('/register'));
            return;
        }
        
        // Validar que las contraseñas coincidan
        if ($data['password'] !== $data['password_confirm']) {
            set_flash('error', 'Las contraseñas no coinciden');
            redirect(url('/register'));
            return;
        }
        
        // Registrar usuario
        $user_id = $this->userModel->register($data);
        
        if ($user_id) {
            set_flash('success', SUCCESS_REGISTER);
            redirect(url('/login'));
        } else {
            set_flash('error', ERROR_DATABASE);
            redirect(url('/register'));
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        require_auth();
        
        clear_user_session();
        session_destroy_all();
        
        set_flash('success', SUCCESS_LOGOUT);
        redirect(url('/login'));
    }
}
