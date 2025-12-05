<?php
/**
 * Helper de Sesiones
 * Sistema de Reserva de Vuelos
 */

/**
 * Iniciar sesión si no está iniciada
 */
function session_init() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();
        
        // Regenerar ID de sesión periódicamente para seguridad
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutos
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}

/**
 * Establecer variable de sesión
 * @param string $key
 * @param mixed $value
 */
function session_set($key, $value) {
    session_init();
    $_SESSION[$key] = $value;
}

/**
 * Obtener variable de sesión
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function session_get($key, $default = null) {
    session_init();
    return $_SESSION[$key] ?? $default;
}

/**
 * Verificar si existe variable de sesión
 * @param string $key
 * @return bool
 */
function session_has($key) {
    session_init();
    return isset($_SESSION[$key]);
}

/**
 * Eliminar variable de sesión
 * @param string $key
 */
function session_delete($key) {
    session_init();
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

/**
 * Destruir sesión completamente
 */
function session_destroy_all() {
    session_init();
    $_SESSION = [];
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

/**
 * Verificar si el usuario está autenticado
 * @return bool
 */
function is_authenticated() {
    return session_has('user_id') && session_has('user_email');
}

/**
 * Obtener ID del usuario autenticado
 * @return int|null
 */
function get_user_id() {
    return session_get('user_id');
}

/**
 * Obtener email del usuario autenticado
 * @return string|null
 */
function get_user_email() {
    return session_get('user_email');
}

/**
 * Obtener nombre del usuario autenticado
 * @return string|null
 */
function get_user_name() {
    return session_get('user_name');
}

/**
 * Establecer datos de usuario en sesión
 * @param array $user
 */
function set_user_session($user) {
    session_set('user_id', $user['id_usuario']);
    session_set('user_email', $user['email']);
    session_set('user_name', $user['nombre'] . ' ' . $user['apellido']);
}

/**
 * Limpiar sesión de usuario
 */
function clear_user_session() {
    session_delete('user_id');
    session_delete('user_email');
    session_delete('user_name');
}

/**
 * Establecer mensaje flash
 * @param string $type (success, error, warning, info)
 * @param string $message
 */
function set_flash($type, $message) {
    session_set('flash_type', $type);
    session_set('flash_message', $message);
}

/**
 * Obtener y limpiar mensaje flash
 * @return array|null
 */
function get_flash() {
    if (session_has('flash_message')) {
        $flash = [
            'type' => session_get('flash_type'),
            'message' => session_get('flash_message')
        ];
        session_delete('flash_type');
        session_delete('flash_message');
        return $flash;
    }
    return null;
}

/**
 * Requerir autenticación (redirigir si no está autenticado)
 * @param string $redirect_url
 */
function require_auth($redirect_url = '/login') {
    if (!is_authenticated()) {
        set_flash('warning', 'Debes iniciar sesión para acceder a esta página');
        redirect($redirect_url);
        exit;
    }
}

/**
 * Requerir invitado (redirigir si está autenticado)
 * @param string $redirect_url
 */
function require_guest($redirect_url = '/') {
    if (is_authenticated()) {
        redirect($redirect_url);
        exit;
    }
}
