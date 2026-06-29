<?php
class DetallePedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario, monto) 
                VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario, :monto)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function deleteByPedido($pedido_id) {
        $stmt = $this->db->prepare("DELETE FROM detalle_pedidos WHERE pedido_id = :pedido_id");
        return $stmt->execute(['pedido_id' => $pedido_id]);
    }
    
    public function getByPedido($pedido_id) {
        $stmt = $this->db->prepare("
            SELECT d.*, p.codigo, p.nombre as producto_nombre 
            FROM detalle_pedidos d
            JOIN productos p ON d.producto_id = p.id
            WHERE d.pedido_id = :pedido_id
        ");
        $stmt->execute(['pedido_id' => $pedido_id]);
        return $stmt->fetchAll();
    }
}
?>
