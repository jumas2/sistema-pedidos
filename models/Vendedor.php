<?php
class Vendedor {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("SELECT * FROM vendedores ORDER BY nombre");
        return $stmt->fetchAll();
    }
}
?>
