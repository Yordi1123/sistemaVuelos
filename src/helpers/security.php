<?php
/**
 * Helper de Seguridad
 * Sistema de Reserva de Vuelos
 */

/**
 * Hash de contraseña
 * @param string $password
 * @return string
 */
function hash_password($password) {
    return password_hash($password, HASH_ALGORITHM, ['cost' => HASH_COST]);
}

/**
 * Verificar contraseña
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Sanitizar entrada de texto
 * @param string $data
 * @return string
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Sanitizar email
 * @param string $email
 * @return string
 */
function sanitize_email($email) {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

/**
 * Generar token CSRF
 * @return string
 */
function generate_csrf_token() {
    if (!session_has('csrf_token')) {
        session_set('csrf_token', bin2hex(random_bytes(32)));
    }
    return session_get('csrf_token');
}

/**
 * Verificar token CSRF
 * @param string $token
 * @return bool
 */
function verify_csrf_token($token) {
    return session_has('csrf_token') && hash_equals(session_get('csrf_token'), $token);
}

/**
 * Generar código aleatorio alfanumérico
 * @param int $length
 * @param string $prefix
 * @return string
 */
function generate_code($length = 6, $prefix = '') {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $prefix . $code;
}

/**
 * Escapar salida HTML
 * @param string $data
 * @return string
 */
function escape_html($data) {
    if ($data === null || $data === '') {
        return '';
    }
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
}

/**
 * Prevenir XSS en arrays
 * @param array $data
 * @return array
 */
function sanitize_array($data) {
    $clean = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $clean[$key] = sanitize_array($value);
        } else {
            $clean[$key] = sanitize_input($value);
        }
    }
    return $clean;
}
