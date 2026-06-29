<?php
class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("
            SELECT u.*, r.nombre as rol_nombre, a.nombre as area_nombre
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            LEFT JOIN areas a ON u.area_id = a.id
            ORDER BY u.nombre
        ");
        return $stmt->fetchAll();
    }
    
    public function getByArea($area_nombre) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre, a.nombre as area_nombre
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            LEFT JOIN areas a ON u.area_id = a.id
            WHERE a.nombre = :area_nombre AND u.activo = 1
            ORDER BY u.nombre
        ");
        $stmt->execute(['area_nombre' => $area_nombre]);
        return $stmt->fetchAll();
    }
    
    public function getByRol($rol_nombre) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre, a.nombre as area_nombre
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            LEFT JOIN areas a ON u.area_id = a.id
            WHERE r.nombre = :rol_nombre AND u.activo = 1
            ORDER BY u.nombre
        ");
        $stmt->execute(['rol_nombre' => $rol_nombre]);
        return $stmt->fetchAll();
    }
    
    public function getConductores() {
        return $this->getByRol('conductor');
    }
    
    public function getLogistica() {
        return $this->getByArea('LOGÍSTICA');
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre, a.nombre as area_nombre
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            LEFT JOIN areas a ON u.area_id = a.id
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function crearTransportista($usuario_id) {
        // Verificar si el usuario ya es transportista
        $stmt = $this->db->prepare("SELECT id FROM transportistas WHERE usuario_id = :usuario_id");
        $stmt->execute(['usuario_id' => $usuario_id]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El usuario ya es transportista'];
        }
        
        // Obtener datos del usuario
        $usuario = $this->find($usuario_id);
        if (!$usuario) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }
        
        // Crear transportista con los datos del usuario
        $sql = "INSERT INTO transportistas (nombre, apellido, dni, telefono, usuario_id, activo) 
                VALUES (:nombre, :apellido, :dni, :telefono, :usuario_id, 1)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'nombre' => explode(' ', $usuario['nombre'])[0] ?? $usuario['nombre'],
            'apellido' => implode(' ', array_slice(explode(' ', $usuario['nombre']), 1)) ?? '',
            'dni' => '',
            'telefono' => $usuario['telefono'] ?? '',
            'usuario_id' => $usuario_id
        ]);
        
        return ['success' => $result, 'message' => $result ? 'Transportista creado desde usuario' : 'Error al crear transportista'];
    }
}
?>
