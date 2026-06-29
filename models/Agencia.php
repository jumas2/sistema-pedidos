<?php
class Agencia {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("SELECT * FROM agencias ORDER BY nombre");
        return $stmt->fetchAll();
    }
}
?>
