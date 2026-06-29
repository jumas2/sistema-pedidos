<?php
class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    // ============================================
    // AUTENTICACIÓN
    // ============================================
    public function autenticar($email, $password) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            return false;
        }
        
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            WHERE u.email = :email AND u.activo = 1
        ");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $this->db->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id")
                ->execute(['id' => $user['id']]);
            return $user;
        }
        return false;
    }
    
    // ============================================
    // CRUD
    // ============================================
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
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre
            FROM usuarios u
            LEFT JOIN roles r ON u.rol_id = r.id
            WHERE u.email = :email
        ");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['email'] = strtolower(trim($data['email']));
        $data['telefono'] = trim($data['telefono'] ?? '');
        $data['dni'] = trim($data['dni'] ?? '');
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id, area_id, telefono, dni, activo) 
                VALUES (:nombre, :email, :password, :rol_id, :area_id, :telefono, :dni, :activo)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['email'] = strtolower(trim($data['email']));
        $data['telefono'] = trim($data['telefono'] ?? '');
        $data['dni'] = trim($data['dni'] ?? '');
        $data['id'] = $id;
        
        $sql = "UPDATE usuarios SET 
                    nombre = :nombre, 
                    email = :email, 
                    rol_id = :rol_id, 
                    area_id = :area_id,
                    telefono = :telefono,
                    dni = :dni,
                    activo = :activo
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    // ============================================
    // MÉTODOS PARA FORMULARIOS
    // ============================================
    public function getAreas() {
        $stmt = $this->db->query("SELECT * FROM areas WHERE activo = 1 ORDER BY nombre");
        return $stmt->fetchAll();
    }
    
    public function getRoles() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY nivel DESC");
        return $stmt->fetchAll();
    }
    
    // ============================================
    // MÉTODOS PARA LOGÍSTICA / TRANSPORTISTAS
    // ============================================
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
        
        $nombre_completo = explode(' ', $usuario['nombre'], 2);
        $nombre = $nombre_completo[0] ?? $usuario['nombre'];
        $apellido = $nombre_completo[1] ?? '';
        
        $result = $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $usuario['dni'] ?? '',
            'telefono' => $usuario['telefono'] ?? '',
            'usuario_id' => $usuario_id
        ]);
        
        return ['success' => $result, 'message' => $result ? 'Transportista creado desde usuario' : 'Error al crear transportista'];
    }
}
?>
