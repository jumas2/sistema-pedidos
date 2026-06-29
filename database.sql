-- Crear base de datos
CREATE DATABASE IF NOT EXISTS pedidos_db;
USE pedidos_db;

-- Tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'vendedor', 'logistica') DEFAULT 'vendedor',
    activo BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ruc_dni VARCHAR(20) UNIQUE NOT NULL,
    direccion_fiscal TEXT,
    zona VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla agencias
CREATE TABLE IF NOT EXISTS agencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla vendedores
CREATE TABLE IF NOT EXISTS vendedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_pedido VARCHAR(20) UNIQUE NOT NULL,
    cliente_id INT NOT NULL,
    vendedor_id INT,
    usuario_registro_id INT,
    usuario_atendio_id INT,
    oc VARCHAR(50),
    condicion ENUM('Boleta-Contado', 'Boleta-Crédito', 'Factura-Contado', 'Factura-Crédito', 'Muestra') NOT NULL,
    fecha_pedido DATE NOT NULL,
    fecha_entrega DATE,
    zona VARCHAR(100),
    agencia_id INT,
    direccion_envio TEXT,
    nombre_recibe VARCHAR(100),
    dni_recibe VARCHAR(20),
    observacion TEXT,
    subtotal DECIMAL(10,2) DEFAULT 0,
    igv DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) DEFAULT 0,
    estado ENUM('Pendiente', 'Atendido') DEFAULT 'Pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (vendedor_id) REFERENCES vendedores(id),
    FOREIGN KEY (agencia_id) REFERENCES agencias(id)
);

-- Tabla detalle_pedidos
CREATE TABLE IF NOT EXISTS detalle_pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL CHECK (cantidad > 0),
    precio_unitario DECIMAL(10,2) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Usuarios por defecto (contraseña: admin123)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Vendedor Demo', 'vendedor@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendedor'),
('Logística Demo', 'logistica@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'logistica');

-- Datos de prueba - Agencias
INSERT INTO agencias (nombre, direccion) VALUES
('Olva Courier', 'Av. Ejemplo 123, Lima'),
('Serpost', 'Av. Otro Ejemplo 456, Lima'),
('Shalom', 'Calle Principal 789, Lima');

-- Datos de prueba - Vendedores
INSERT INTO vendedores (nombre, email) VALUES
('Juan Pérez', 'juan@empresa.com'),
('María Gómez', 'maria@empresa.com'),
('Carlos López', 'carlos@empresa.com');

-- Datos de prueba - Clientes
INSERT INTO clientes (nombre, ruc_dni, direccion_fiscal, zona, telefono) VALUES
('Empresa ABC SAC', '12345678901', 'Av. Principal 789, Miraflores', 'Miraflores', '999999999'),
('Comercial XYZ EIRL', '98765432109', 'Calle Secundaria 456, Surco', 'Surco', '888888888'),
('Distribuidora Los Andes', '11111111111', 'Av. Los Andes 123, San Isidro', 'San Isidro', '777777777');

-- Datos de prueba - Productos
INSERT INTO productos (codigo, nombre, precio, stock) VALUES
('P001', 'Laptop HP Pavilion', 2500.00, 10),
('P002', 'Monitor Dell 24"', 800.00, 15),
('P003', 'Teclado Logitech', 120.00, 30),
('P004', 'Mouse Inalámbrico', 45.00, 25),
('P005', 'Disco SSD 1TB', 350.00, 8);
