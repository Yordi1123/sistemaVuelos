<?php
/**
 * Helper de Validación
 * Sistema de Reserva de Vuelos
 */

/**
 * Validar email
 * @param string $email
 * @return bool
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar longitud de contraseña
 * @param string $password
 * @param int $min_length
 * @return bool
 */
function validate_password_length($password, $min_length = PASSWORD_MIN_LENGTH) {
    return strlen($password) >= $min_length;
}

/**
 * Validar que un campo no esté vacío
 * @param mixed $value
 * @return bool
 */
function validate_required($value) {
    if (is_string($value)) {
        return trim($value) !== '';
    }
    return !empty($value);
}

/**
 * Validar longitud mínima
 * @param string $value
 * @param int $min
 * @return bool
 */
function validate_min_length($value, $min) {
    return strlen($value) >= $min;
}

/**
 * Validar longitud máxima
 * @param string $value
 * @param int $max
 * @return bool
 */
function validate_max_length($value, $max) {
    return strlen($value) <= $max;
}

/**
 * Validar número de teléfono (formato básico)
 * @param string $phone
 * @return bool
 */
function validate_phone($phone) {
    return preg_match('/^[0-9\+\-\(\)\s]{7,20}$/', $phone);
}

/**
 * Validar fecha (formato Y-m-d)
 * @param string $date
 * @return bool
 */
function validate_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Validar que la fecha sea futura
 * @param string $date
 * @return bool
 */
function validate_future_date($date) {
    if (!validate_date($date)) {
        return false;
    }
    $input_date = new DateTime($date);
    $today = new DateTime('today');
    return $input_date >= $today;
}

/**
 * Validar número entero
 * @param mixed $value
 * @return bool
 */
function validate_integer($value) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}

/**
 * Validar número decimal
 * @param mixed $value
 * @return bool
 */
function validate_float($value) {
    return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
}

/**
 * Validar rango numérico
 * @param numeric $value
 * @param numeric $min
 * @param numeric $max
 * @return bool
 */
function validate_range($value, $min, $max) {
    return $value >= $min && $value <= $max;
}

/**
 * Validar formulario completo
 * @param array $data
 * @param array $rules
 * @return array Errores encontrados
 */
function validate_form($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $field_rules) {
        $value = $data[$field] ?? '';
        
        foreach ($field_rules as $rule => $param) {
            switch ($rule) {
                case 'required':
                    if (!validate_required($value)) {
                        $errors[$field] = "El campo es obligatorio";
                    }
                    break;
                    
                case 'email':
                    if (!empty($value) && !validate_email($value)) {
                        $errors[$field] = "Email inválido";
                    }
                    break;
                    
                case 'min_length':
                    if (!validate_min_length($value, $param)) {
                        $errors[$field] = "Debe tener al menos {$param} caracteres";
                    }
                    break;
                    
                case 'max_length':
                    if (!validate_max_length($value, $param)) {
                        $errors[$field] = "No debe exceder {$param} caracteres";
                    }
                    break;
                    
                case 'phone':
                    if (!empty($value) && !validate_phone($value)) {
                        $errors[$field] = "Teléfono inválido";
                    }
                    break;
                    
                case 'date':
                    if (!empty($value) && !validate_date($value)) {
                        $errors[$field] = "Fecha inválida";
                    }
                    break;
            }
        }
    }
    
    return $errors;
}
