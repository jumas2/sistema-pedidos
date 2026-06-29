<?php
class Vehiculo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("SELECT * FROM vehiculos WHERE activo = 1 ORDER BY placa");
        return $stmt->fetchAll();
    }
    
    public function allInactivos() {
        $stmt = $this->db->query("SELECT * FROM vehiculos WHERE activo = 0 ORDER BY placa");
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM vehiculos WHERE id = :id AND activo = 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function findInactivo($id) {
        $stmt = $this->db->prepare("SELECT * FROM vehiculos WHERE id = :id AND activo = 0");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getDisponibles() {
        $stmt = $this->db->query("SELECT * FROM vehiculos WHERE estado = 'Disponible' AND activo = 1 ORDER BY placa");
        return $stmt->fetchAll();
    }
    
    public function getByEstado($estado) {
        $stmt = $this->db->prepare("SELECT * FROM vehiculos WHERE estado = :estado AND activo = 1 ORDER BY placa");
        $stmt->execute(['estado' => $estado]);
        return $stmt->fetchAll();
    }
    
    public function getEnUso() {
        $stmt = $this->db->query("SELECT * FROM vehiculos WHERE estado = 'En Uso' AND activo = 1 ORDER BY placa");
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $sql = "INSERT INTO vehiculos (placa, marca, modelo, anio, color, tipo, capacidad_carga, estado, observacion, activo) 
                VALUES (:placa, :marca, :modelo, :anio, :color, :tipo, :capacidad_carga, :estado, :observacion, 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $data['id'] = $id;
        $sql = "UPDATE vehiculos SET 
                placa = :placa,
                marca = :marca,
                modelo = :modelo,
                anio = :anio,
                color = :color,
                tipo = :tipo,
                capacidad_carga = :capacidad_carga,
                estado = :estado,
                observacion = :observacion
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function updateEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE vehiculos SET estado = :estado WHERE id = :id");
        return $stmt->execute(['id' => $id, 'estado' => $estado]);
    }
    
    public function cambiarEstado($id, $activo) {
        $stmt = $this->db->prepare("UPDATE vehiculos SET activo = :activo WHERE id = :id");
        return $stmt->execute(['id' => $id, 'activo' => $activo]);
    }
}
?>
