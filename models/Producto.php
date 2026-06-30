<?php
class Producto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    // ============================================
    // GENERAR CÓDIGO AUTOMÁTICO
    // ============================================
    public function generarCodigo() {
        $stmt = $this->db->query("SELECT codigo FROM productos ORDER BY id DESC LIMIT 1");
        $last = $stmt->fetch();
        
        if ($last && preg_match('/^P(\d+)$/', $last['codigo'], $matches)) {
            $numero = intval($matches[1]) + 1;
        } else {
            $numero = 1;
        }
        
        return 'P' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
    
    // ============================================
    // CRUD
    // ============================================
    
    public function all() {
        $stmt = $this->db->query("
            SELECT p.*, 
                   c.nombre as categoria_nombre,
                   l.nombre as linea_nombre
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN lineas l ON p.linea_id = l.id
            ORDER BY p.nombre
        ");
        return $stmt->fetchAll();
    }
    
    public function allInactivos() {
        $stmt = $this->db->query("
            SELECT p.*, 
                   c.nombre as categoria_nombre,
                   l.nombre as linea_nombre
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN lineas l ON p.linea_id = l.id
            WHERE p.activo = 0
            ORDER BY p.nombre
        ");
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   c.nombre as categoria_nombre,
                   l.nombre as linea_nombre
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN lineas l ON p.linea_id = l.id
            WHERE p.id = :id AND p.activo = 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function findInactivo($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   c.nombre as categoria_nombre,
                   l.nombre as linea_nombre
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN lineas l ON p.linea_id = l.id
            WHERE p.id = :id AND p.activo = 0
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $data['codigo'] = strtoupper(trim($data['codigo'] ?? ''));
        $data['nombre'] = strtoupper(trim($data['nombre'] ?? ''));
        $data['descripcion'] = strtoupper(trim($data['descripcion'] ?? ''));
        $data['unidad_medida'] = strtoupper(trim($data['unidad_medida'] ?? 'UNIDAD'));
        $data['ubicacion'] = strtoupper(trim($data['ubicacion'] ?? ''));
        $data['categoria_id'] = $data['categoria_id'] ? (int)$data['categoria_id'] : null;
        $data['linea_id'] = $data['linea_id'] ? (int)$data['linea_id'] : null;
        $data['precio'] = floatval($data['precio'] ?? 0);
        $data['stock'] = intval($data['stock'] ?? 0);
        $data['stock_minimo'] = intval($data['stock_minimo'] ?? 0);
        $data['activo'] = intval($data['activo'] ?? 1);
        $data['moneda'] = $data['moneda'] ?? 'PEN';
        
        $sql = "INSERT INTO productos (
                    codigo, nombre, descripcion, categoria_id, linea_id, unidad_medida, 
                    precio, stock, stock_minimo, ubicacion, activo, moneda
                ) VALUES (
                    :codigo, :nombre, :descripcion, :categoria_id, :linea_id, :unidad_medida,
                    :precio, :stock, :stock_minimo, :ubicacion, :activo, :moneda
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $data['codigo'] = strtoupper(trim($data['codigo'] ?? ''));
        $data['nombre'] = strtoupper(trim($data['nombre'] ?? ''));
        $data['descripcion'] = strtoupper(trim($data['descripcion'] ?? ''));
        $data['unidad_medida'] = strtoupper(trim($data['unidad_medida'] ?? 'UNIDAD'));
        $data['ubicacion'] = strtoupper(trim($data['ubicacion'] ?? ''));
        $data['categoria_id'] = $data['categoria_id'] ? (int)$data['categoria_id'] : null;
        $data['linea_id'] = $data['linea_id'] ? (int)$data['linea_id'] : null;
        $data['precio'] = floatval($data['precio'] ?? 0);
        $data['stock'] = intval($data['stock'] ?? 0);
        $data['stock_minimo'] = intval($data['stock_minimo'] ?? 0);
        $data['activo'] = intval($data['activo'] ?? 1);
        $data['moneda'] = $data['moneda'] ?? 'PEN';
        $data['id'] = $id;
        
        $sql = "UPDATE productos SET 
                    codigo = :codigo, 
                    nombre = :nombre, 
                    descripcion = :descripcion,
                    categoria_id = :categoria_id,
                    linea_id = :linea_id,
                    unidad_medida = :unidad_medida,
                    precio = :precio, 
                    stock = :stock,
                    stock_minimo = :stock_minimo,
                    ubicacion = :ubicacion,
                    activo = :activo,
                    moneda = :moneda
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function cambiarEstado($id, $activo) {
        $stmt = $this->db->prepare("UPDATE productos SET activo = :activo WHERE id = :id");
        return $stmt->execute(['id' => $id, 'activo' => $activo]);
    }
    
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM productos WHERE activo = 1");
        return $stmt->fetch()['total'];
    }


    public function updateStock($producto_id, $cantidad) {
        try {
            // Verificar stock disponible
            $stmt = $this->db->prepare("SELECT stock FROM productos WHERE id = :id");
            $stmt->execute(['id' => $producto_id]);
            $producto = $stmt->fetch();
            
            if (!$producto) {
                error_log("Producto ID $producto_id no encontrado");
                return false;
            }
            
            if ($producto['stock'] < $cantidad) {
                error_log("Stock insuficiente para producto ID $producto_id. Stock: {$producto['stock']}, Requerido: $cantidad");
                return false;
            }
            
            // Actualizar stock
            $sql = "UPDATE productos SET stock = stock - :cantidad WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $producto_id,
                'cantidad' => $cantidad
            ]);
            
        } catch (PDOException $e) {
            error_log("Error en updateStock: " . $e->getMessage());
            return false;
        }
    }
    }
?>
