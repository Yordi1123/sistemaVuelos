<?php
/**
 * Modelo de Usuario
 * Sistema de Reserva de Vuelos
 */

class User {
    private $db;
    private $table = 'usuarios';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Registrar nuevo usuario
     * @param array $data
     * @return int|false ID del usuario creado o false
     */
    public function register($data) {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (nombre, apellido, email, password, telefono, fecha_nacimiento, 
                     documento_identidad, tipo_documento, direccion, ciudad, pais) 
                    VALUES 
                    (:nombre, :apellido, :email, :password, :telefono, :fecha_nacimiento, 
                     :documento_identidad, :tipo_documento, :direccion, :ciudad, :pais)";
            
            $stmt = $this->db->prepare($sql);
            
            $hashed_password = hash_password($data['password']);
            
            $stmt->execute([
                ':nombre' => $data['nombre'],
                ':apellido' => $data['apellido'],
                ':email' => $data['email'],
                ':password' => $hashed_password,
                ':telefono' => $data['telefono'] ?? null,
                ':fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                ':documento_identidad' => $data['documento_identidad'] ?? null,
                ':tipo_documento' => $data['tipo_documento'] ?? 'DNI',
                ':direccion' => $data['direccion'] ?? null,
                ':ciudad' => $data['ciudad'] ?? null,
                ':pais' => $data['pais'] ?? 'Perú'
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error en registro: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Buscar usuario por email
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al buscar usuario: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Buscar usuario por ID
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id_usuario = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al buscar usuario: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Verificar si el email ya existe
     * @param string $email
     * @return bool
     */
    public function emailExists($email) {
        return $this->findByEmail($email) !== false;
    }
    
    /**
     * Autenticar usuario
     * @param string $email
     * @param string $password
     * @return array|false Usuario si las credenciales son correctas
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && verify_password($password, $user['password'])) {
            // Actualizar último acceso
            $this->updateLastAccess($user['id_usuario']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Actualizar último acceso
     * @param int $user_id
     * @return bool
     */
    private function updateLastAccess($user_id) {
        try {
            $sql = "UPDATE {$this->table} SET ultimo_acceso = NOW() WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $user_id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Actualizar perfil de usuario
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function updateProfile($user_id, $data) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET nombre = :nombre, 
                        apellido = :apellido, 
                        telefono = :telefono, 
                        fecha_nacimiento = :fecha_nacimiento,
                        direccion = :direccion,
                        ciudad = :ciudad,
                        pais = :pais
                    WHERE id_usuario = :id";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':nombre' => $data['nombre'],
                ':apellido' => $data['apellido'],
                ':telefono' => $data['telefono'] ?? null,
                ':fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
                ':direccion' => $data['direccion'] ?? null,
                ':ciudad' => $data['ciudad'] ?? null,
                ':pais' => $data['pais'] ?? 'Perú',
                ':id' => $user_id
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al actualizar perfil: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Cambiar contraseña
     * @param int $user_id
     * @param string $new_password
     * @return bool
     */
    public function changePassword($user_id, $new_password) {
        try {
            $sql = "UPDATE {$this->table} SET password = :password WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            
            $hashed_password = hash_password($new_password);
            
            return $stmt->execute([
                ':password' => $hashed_password,
                ':id' => $user_id
            ]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al cambiar contraseña: " . $e->getMessage());
            }
            return false;
        }
    }
    
    /**
     * Cambiar estado de usuario
     * @param int $user_id
     * @param string $status
     * @return bool
     */
    public function changeStatus($user_id, $status) {
        try {
            $sql = "UPDATE {$this->table} SET estado = :estado WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':estado' => $status,
                ':id' => $user_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Eliminar cuenta de usuario
     * @param int $user_id
     * @return bool
     */
    public function deleteAccount($user_id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id_usuario = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $user_id]);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Error al eliminar cuenta: " . $e->getMessage());
            }
            return false;
        }
    }
}
