<?php
class Despacho {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO despachos (guia_id, estado, ubicacion, observacion, usuario_registro_id) 
                VALUES (:guia_id, :estado, :ubicacion, :observacion, :usuario_registro_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function getByGuia($guia_id) {
        $stmt = $this->db->prepare("
            SELECT d.*, u.nombre as usuario_nombre
            FROM despachos d
            LEFT JOIN usuarios u ON d.usuario_registro_id = u.id
            WHERE d.guia_id = :guia_id
            ORDER BY d.created_at DESC
        ");
        $stmt->execute(['guia_id' => $guia_id]);
        return $stmt->fetchAll();
    }
}
?>
