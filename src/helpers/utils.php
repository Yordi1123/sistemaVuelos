<?php
/**
 * Helper de Utilidades Generales
 * Sistema de Reserva de Vuelos
 */

/**
 * Redireccionar a una URL
 * @param string $url
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: " . $url);
        exit;
    } else {
        echo "<script>window.location.href='{$url}';</script>";
        exit;
    }
}

/**
 * Obtener URL base
 * @param string $path
 * @return string
 */
function url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}

/**
 * Obtener URL de asset
 * @param string $path
 * @return string
 */
function asset($path) {
    return APP_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Formatear precio
 * @param float $amount
 * @param string $currency
 * @return string
 */
function format_price($amount, $currency = 'S/') {
    return $currency . ' ' . number_format($amount, 2, '.', ',');
}

/**
 * Formatear fecha
 * @param string $date
 * @param string $format
 * @return string
 */
function format_date($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    $dt = new DateTime($date);
    return $dt->format($format);
}

/**
 * Formatear fecha y hora
 * @param string $datetime
 * @param string $format
 * @return string
 */
function format_datetime($datetime, $format = 'd/m/Y H:i') {
    if (empty($datetime)) return '';
    $dt = new DateTime($datetime);
    return $dt->format($format);
}

/**
 * Calcular duración entre dos fechas
 * @param string $start
 * @param string $end
 * @return string
 */
function calculate_duration($start, $end) {
    $start_dt = new DateTime($start);
    $end_dt = new DateTime($end);
    $interval = $start_dt->diff($end_dt);
    
    $hours = $interval->h + ($interval->days * 24);
    $minutes = $interval->i;
    
    return sprintf("%dh %02dm", $hours, $minutes);
}

/**
 * Obtener nombre del día en español
 * @param string $date
 * @return string
 */
function get_day_name($date) {
    $days = [
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    ];
    
    $dt = new DateTime($date);
    $day_english = $dt->format('l');
    return $days[$day_english] ?? $day_english;
}

/**
 * Obtener nombre del mes en español
 * @param string $date
 * @return string
 */
function get_month_name($date) {
    $months = [
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    ];
    
    $dt = new DateTime($date);
    $month_english = $dt->format('F');
    return $months[$month_english] ?? $month_english;
}

/**
 * Truncar texto
 * @param string $text
 * @param int $length
 * @param string $suffix
 * @return string
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Depuración (solo en modo debug)
 * @param mixed $data
 * @param bool $die
 */
function debug($data, $die = false) {
    if (DEBUG_MODE) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($die) die();
    }
}

/**
 * Obtener valor de array de forma segura
 * @param array $array
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function array_get($array, $key, $default = null) {
    return $array[$key] ?? $default;
}

/**
 * Verificar si la petición es POST
 * @return bool
 */
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verificar si la petición es GET
 * @return bool
 */
function is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Obtener parámetro GET
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function get_param($key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Obtener parámetro POST
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function post_param($key, $default = null) {
    return $_POST[$key] ?? $default;
}
