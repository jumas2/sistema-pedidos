<?php
class Linea {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    // Obtener todas las líneas
    public function all() {
        try {
            $stmt = $this->db->query("SELECT * FROM lineas WHERE activo = 1 ORDER BY nombre");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en Linea::all(): " . $e->getMessage());
            return [];
        }
    }
    
    // Obtener una línea por ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM lineas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    // Obtener líneas por categoría
    public function getByCategoria($categoria_id) {
        // Como las líneas son independientes, devolvemos todas las líneas activas
        // Si necesitas filtrar por categoría, ajusta según tu estructura
        return $this->all();
    }
    
    // Crear una línea
    public function create($data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['descripcion'] = strtoupper(trim($data['descripcion'] ?? ''));
        
        $sql = "INSERT INTO lineas (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Actualizar una línea
    public function update($id, $data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['descripcion'] = strtoupper(trim($data['descripcion'] ?? ''));
        $data['id'] = $id;
        
        $sql = "UPDATE lineas SET nombre = :nombre, descripcion = :descripcion, activo = :activo WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    // Desactivar una línea
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE lineas SET activo = 0 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>
