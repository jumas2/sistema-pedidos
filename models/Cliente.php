<?php
class Cliente {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("SELECT * FROM clientes ORDER BY nombre");
        return $stmt->fetchAll();
    }
    
    public function allInactivos() {
        $stmt = $this->db->query("SELECT * FROM clientes WHERE activo = 0 ORDER BY nombre");
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function findByRuc($ruc_dni) {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE ruc_dni = :ruc_dni");
        $stmt->execute(['ruc_dni' => $ruc_dni]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['ruc_dni'] = strtoupper(trim($data['ruc_dni']));
        $data['direccion_fiscal'] = strtoupper(trim($data['direccion_fiscal'] ?? ''));
        $data['zona'] = strtoupper(trim($data['zona'] ?? ''));
        $data['telefono'] = trim($data['telefono'] ?? '');
        $data['email'] = strtolower(trim($data['email'] ?? ''));
        
        $sql = "INSERT INTO clientes (nombre, ruc_dni, direccion_fiscal, zona, telefono, email, activo) 
                VALUES (:nombre, :ruc_dni, :direccion_fiscal, :zona, :telefono, :email, 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $data['nombre'] = strtoupper(trim($data['nombre']));
        $data['ruc_dni'] = strtoupper(trim($data['ruc_dni']));
        $data['direccion_fiscal'] = strtoupper(trim($data['direccion_fiscal'] ?? ''));
        $data['zona'] = strtoupper(trim($data['zona'] ?? ''));
        $data['telefono'] = trim($data['telefono'] ?? '');
        $data['email'] = strtolower(trim($data['email'] ?? ''));
        $data['id'] = $id;
        
        $sql = "UPDATE clientes SET 
                nombre = :nombre, 
                ruc_dni = :ruc_dni, 
                direccion_fiscal = :direccion_fiscal, 
                zona = :zona, 
                telefono = :telefono, 
                email = :email
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function cambiarEstado($id, $activo) {
        $stmt = $this->db->prepare("UPDATE clientes SET activo = :activo WHERE id = :id");
        return $stmt->execute(['id' => $id, 'activo' => $activo]);
    }
    
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM clientes WHERE activo = 1");
        return $stmt->fetch()['total'];
    }
}
?>
