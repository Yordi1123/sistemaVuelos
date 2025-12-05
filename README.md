# 🛫 Sistema de Reserva de Vuelos

Sistema web de reserva de vuelos desarrollado con PHP nativo, MySQL y arquitectura MVC.

## 📋 Características

- ✅ Autenticación de usuarios (Login/Registro)
- ✅ Búsqueda de vuelos por horarios, tarifas y estado
- ✅ Reserva de vuelos con múltiples pasajeros
- ✅ Selección de asientos
- ✅ Procesamiento de pagos (simulado)
- ✅ Gestión de perfil de usuario
- ✅ Historial de reservas

## 🛠️ Tecnologías

- **Backend**: PHP 8.x (Nativo)
- **Base de Datos**: MySQL 8.x
- **Frontend**: HTML5, CSS3, JavaScript Vanilla
- **Servidor**: Apache (Laragon)
- **Conexión BD**: PDO

## 📁 Estructura del Proyecto

```
sistemaVuelos/
├── config/              # Configuración
├── database/            # Scripts SQL
├── src/                 # Código fuente PHP
│   ├── controllers/     # Controladores
│   ├── models/          # Modelos
│   └── helpers/         # Funciones auxiliares
├── public/              # Archivos públicos
│   ├── assets/          # CSS, JS, imágenes
│   └── index.php        # Punto de entrada
└── views/               # Vistas HTML
```

## 🚀 Instalación

### 1. Clonar el proyecto
```bash
git clone <repository-url>
cd sistemaVuelos
```

### 2. Configurar la base de datos
```bash
# Importar el schema en MySQL
mysql -u root -p < database/schema.sql

# (Opcional) Importar datos de prueba
mysql -u root -p < database/seed_data.sql
```

### 3. Configurar conexión a BD
Copiar `config/database.example.php` a `config/database.php` y editar credenciales:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_vuelos');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Iniciar servidor
Con Laragon, el proyecto estará disponible en:
```
http://localhost/sistemaVuelos/public
```

## 📖 Uso

1. **Registrarse**: Crear una cuenta de usuario
2. **Buscar vuelos**: Seleccionar origen, destino y fecha
3. **Reservar**: Elegir vuelo y asientos
4. **Pagar**: Completar información de pago
5. **Ver reservas**: Acceder al perfil para gestionar reservas

## 👥 Autor

Desarrollado como proyecto académico - PRÁCTICA 12: SISTEMAS DE INFORMACIÓN II

## 📄 Licencia

Este proyecto es de uso académico.
