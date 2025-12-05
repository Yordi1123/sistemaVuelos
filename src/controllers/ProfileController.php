<?php
/**
 * Controlador de Perfil
 */

class ProfileController {
    private $userModel;
    private $bookingModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->bookingModel = new Booking();
    }
    
    /**
     * Dashboard del usuario
     */
    public function dashboard() {
        require_auth();
        
        $user = session_get('user');
        $bookings = $this->bookingModel->getUserBookings($user['id_usuario']);
        
        require VIEWS_PATH . '/profile/dashboard.php';
    }
    
    /**
     * Editar perfil
     */
    public function edit() {
        require_auth();
        
        $user = session_get('user');
        
        if (is_post()) {
            $data = [
                'nombre' => post_param('nombre'),
                'apellido' => post_param('apellido'),
                'telefono' => post_param('telefono'),
                'fecha_nacimiento' => post_param('fecha_nacimiento'),
                'direccion' => post_param('direccion')
            ];
            
            if ($this->userModel->updateProfile($user['id_usuario'], $data)) {
                // Actualizar sesión
                $updated_user = $this->userModel->findById($user['id_usuario']);
                session_set('user', $updated_user);
                
                set_flash('success', 'Perfil actualizado correctamente');
                redirect(url('/profile/dashboard'));
            } else {
                set_flash('error', 'Error al actualizar perfil');
            }
        }
        
        require VIEWS_PATH . '/profile/edit.php';
    }
    
    /**
     * Cambiar contraseña
     */
    public function changePassword() {
        require_auth();
        
        if (!is_post()) {
            redirect(url('/profile/dashboard'));
            return;
        }
        
        $user = session_get('user');
        $current_password = post_param('current_password');
        $new_password = post_param('new_password');
        $confirm_password = post_param('confirm_password');
        
        // Validaciones
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            set_flash('error', 'Todos los campos son requeridos');
            redirect(url('/profile/edit'));
            return;
        }
        
        if ($new_password !== $confirm_password) {
            set_flash('error', 'Las contraseñas no coinciden');
            redirect(url('/profile/edit'));
            return;
        }
        
        if (strlen($new_password) < 6) {
            set_flash('error', 'La contraseña debe tener al menos 6 caracteres');
            redirect(url('/profile/edit'));
            return;
        }
        
        // Verificar contraseña actual
        $user_data = $this->userModel->findById($user['id_usuario']);
        if (!verify_password($current_password, $user_data['password'])) {
            set_flash('error', 'Contraseña actual incorrecta');
            redirect(url('/profile/edit'));
            return;
        }
        
        // Cambiar contraseña
        if ($this->userModel->changePassword($user['id_usuario'], $new_password)) {
            set_flash('success', 'Contraseña cambiada correctamente');
        } else {
            set_flash('error', 'Error al cambiar contraseña');
        }
        
        redirect(url('/profile/edit'));
    }
}
