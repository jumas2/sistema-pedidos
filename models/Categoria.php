<?php
class Categoria {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        try {
            $stmt = $this->db->query("
                SELECT c.*, l.nombre as linea_nombre 
                FROM categorias c
                LEFT JOIN lineas l ON c.linea_id = l.id
                WHERE c.activo = 1 
                ORDER BY c.nombre
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en Categoria::all(): " . $e->getMessage());
            return [];
        }
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, l.nombre as linea_nombre 
            FROM categorias c
            LEFT JOIN lineas l ON c.linea_id = l.id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['descripcion'] = strtoupper(trim($data['descripcion'] ?? ''));
        
        $sql = "INSERT INTO categorias (nombre, descripcion, linea_id) 
                VALUES (:nombre, :descripcion, :linea_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['descripcion'] = strtoupper(trim($data['descripcion'] ?? ''));
        $data['id'] = $id;
        
        $sql = "UPDATE categorias SET 
                nombre = :nombre, 
                descripcion = :descripcion, 
                linea_id = :linea_id,
                activo = :activo
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE categorias SET activo = 0 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
