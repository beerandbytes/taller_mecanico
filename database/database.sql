-- Base de datos para el Trabajo Final PHP/MySQL
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS trabajo_final_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE trabajo_final_php;

-- Tabla users_data: Información personal de los usuarios
CREATE TABLE IF NOT EXISTS users_data (
    idUser INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    fecha_de_nacimiento DATE NOT NULL,
    direccion TEXT,
    sexo ENUM('Masculino', 'Femenino', 'Otro') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla users_login: Información de inicio de sesión
CREATE TABLE IF NOT EXISTS users_login (
    idLogin INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL UNIQUE,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'user') NOT NULL,
    FOREIGN KEY (idUser) REFERENCES users_data(idUser) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla citas: Citas de los usuarios
CREATE TABLE IF NOT EXISTS citas (
    idCita INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL,
    fecha_cita DATE NOT NULL,
    motivo_cita TEXT,
    FOREIGN KEY (idUser) REFERENCES users_data(idUser) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla noticias: Noticias escritas por administradores
CREATE TABLE IF NOT EXISTS noticias (
    idNoticia INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL UNIQUE,
    imagen VARCHAR(255) NOT NULL,
    texto TEXT NOT NULL,
    fecha DATE NOT NULL,
    idUser INT NOT NULL,
    FOREIGN KEY (idUser) REFERENCES users_data(idUser) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador de ejemplo
-- Contraseña: admin123 (encriptada con password_hash)
INSERT INTO users_data (nombre, apellidos, email, telefono, fecha_de_nacimiento, direccion, sexo) 
VALUES ('Admin', 'Sistema', 'admin@sistema.com', '123456789', '1990-01-01', 'Calle Admin 123', 'Masculino');

INSERT INTO users_login (idUser, usuario, password, rol) 
VALUES (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- La contraseña 'admin123' está encriptada con password_hash de PHP

