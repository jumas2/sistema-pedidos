<?php
class Empresa {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function get() {
        $stmt = $this->db->query("SELECT * FROM empresa LIMIT 1");
        return $stmt->fetch();
    }
    
    public function update($data) {
        // Obtener los campos de la tabla
        $stmt = $this->db->query("DESCRIBE empresa");
        $campos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Construir el SET dinámicamente
        $sets = [];
        $params = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $campos) && $key !== 'id') {
                $sets[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        
        if (empty($sets)) {
            return false;
        }
        
        $sql = "UPDATE empresa SET " . implode(', ', $sets) . " WHERE id = 1";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function updateLogo($logo) {
        $stmt = $this->db->prepare("UPDATE empresa SET logo = :logo WHERE id = 1");
        return $stmt->execute(['logo' => $logo]);
    }
}
?>
