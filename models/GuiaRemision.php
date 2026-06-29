<?php
class GuiaRemision {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("
            SELECT g.*, p.numero_pedido, c.nombre as cliente_nombre, 
                   t.nombre as transportista_nombre, t.apellido as transportista_apellido
            FROM guias_remision g
            LEFT JOIN pedidos p ON g.pedido_id = p.id
            LEFT JOIN clientes c ON p.cliente_id = c.id
            LEFT JOIN transportistas t ON g.transportista_id = t.id
            ORDER BY g.id DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT g.*, p.numero_pedido, c.nombre as cliente_nombre,
                   t.nombre as transportista_nombre, t.apellido as transportista_apellido
            FROM guias_remision g
            LEFT JOIN pedidos p ON g.pedido_id = p.id
            LEFT JOIN clientes c ON p.cliente_id = c.id
            LEFT JOIN transportistas t ON g.transportista_id = t.id
            WHERE g.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getByPedido($pedido_id) {
        $stmt = $this->db->prepare("SELECT * FROM guias_remision WHERE pedido_id = :pedido_id");
        $stmt->execute(['pedido_id' => $pedido_id]);
        return $stmt->fetch();
    }
    
    public function getPendientes() {
        $stmt = $this->db->query("
            SELECT g.*, p.numero_pedido, c.nombre as cliente_nombre
            FROM guias_remision g
            LEFT JOIN pedidos p ON g.pedido_id = p.id
            LEFT JOIN clientes c ON p.cliente_id = c.id
            WHERE g.estado IN ('Pendiente', 'En Proceso')
            ORDER BY g.fecha_emision ASC
        ");
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $sql = "INSERT INTO guias_remision (numero_guia, pedido_id, transportista_id, fecha_emision, direccion_entrega, observacion) 
                VALUES (:numero_guia, :pedido_id, :transportista_id, :fecha_emision, :direccion_entrega, :observacion)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $data['id'] = $id;
        $sql = "UPDATE guias_remision SET 
                transportista_id = :transportista_id,
                fecha_entrega = :fecha_entrega,
                direccion_entrega = :direccion_entrega,
                observacion = :observacion,
                estado = :estado
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function updateEstado($id, $estado) {
        $stmt = $this->db->prepare("UPDATE guias_remision SET estado = :estado WHERE id = :id");
        return $stmt->execute(['id' => $id, 'estado' => $estado]);
    }
    
    public function updateTransportista($id, $transportista_id) {
        $stmt = $this->db->prepare("UPDATE guias_remision SET transportista_id = :transportista_id WHERE id = :id");
        return $stmt->execute(['id' => $id, 'transportista_id' => $transportista_id]);
    }
    
    public function getByTransportista($transportista_id) {
        $stmt = $this->db->prepare("
            SELECT g.*, p.numero_pedido, c.nombre as cliente_nombre
            FROM guias_remision g
            LEFT JOIN pedidos p ON g.pedido_id = p.id
            LEFT JOIN clientes c ON p.cliente_id = c.id
            WHERE g.transportista_id = :transportista_id
            ORDER BY g.fecha_emision DESC
        ");
        $stmt->execute(['transportista_id' => $transportista_id]);
        return $stmt->fetchAll();
    }
    
    public function getMisDespachos($transportista_id) {
        return $this->getByTransportista($transportista_id);
    }
}
?>
