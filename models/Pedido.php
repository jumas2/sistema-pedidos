<?php
class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("
            SELECT p.*, c.nombre as cliente_nombre 
            FROM pedidos p 
            JOIN clientes c ON p.cliente_id = c.id 
            ORDER BY p.id DESC
        ");
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as cliente_nombre, c.ruc_dni, c.direccion_fiscal,
                   a.nombre as agencia_nombre, v.nombre as vendedor_nombre
            FROM pedidos p 
            JOIN clientes c ON p.cliente_id = c.id 
            LEFT JOIN agencias a ON p.agencia_id = a.id
            LEFT JOIN vendedores v ON p.vendedor_id = v.id
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $sql = "INSERT INTO pedidos (
            numero_pedido, cliente_id, vendedor_id, usuario_registro_id, 
            oc, condicion, fecha_pedido, fecha_entrega, zona, 
            agencia_id, direccion_envio, nombre_recibe, dni_recibe, 
            observacion, subtotal, igv, total, moneda, estado
        ) VALUES (
            :numero_pedido, :cliente_id, :vendedor_id, :usuario_registro_id,
            :oc, :condicion, :fecha_pedido, :fecha_entrega, :zona,
            :agencia_id, :direccion_envio, :nombre_recibe, :dni_recibe,
            :observacion, :subtotal, :igv, :total, :moneda, :estado
        )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $sql = "UPDATE pedidos SET 
            cliente_id = :cliente_id,
            vendedor_id = :vendedor_id,
            oc = :oc,
            condicion = :condicion,
            fecha_pedido = :fecha_pedido,
            fecha_entrega = :fecha_entrega,
            zona = :zona,
            agencia_id = :agencia_id,
            direccion_envio = :direccion_envio,
            nombre_recibe = :nombre_recibe,
            dni_recibe = :dni_recibe,
            observacion = :observacion,
            subtotal = :subtotal,
            igv = :igv,
            total = :total,
            moneda = :moneda
            WHERE id = :id
        ";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function updateEstado($id, $estado, $usuario_id) {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = :estado, usuario_atendio_id = :usuario_id WHERE id = :id");
        return $stmt->execute(['id' => $id, 'estado' => $estado, 'usuario_id' => $usuario_id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM detalle_pedidos WHERE pedido_id = :id");
        $stmt->execute(['id' => $id]);
        $stmt = $this->db->prepare("DELETE FROM pedidos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function anular($id, $usuario_id) {
        $stmt = $this->db->prepare("UPDATE pedidos SET estado = 'Anulado', usuario_atendio_id = :usuario_id WHERE id = :id");
        return $stmt->execute(['id' => $id, 'usuario_id' => $usuario_id]);
    }
    
    public function clone($id) {
        $pedido = $this->find($id);
        if (!$pedido) return false;
        $detalles = $this->getDetalles($id);
        if (empty($detalles)) return false;
        
        $nuevo_numero = generarNumeroPedido();
        $data = [
            'numero_pedido' => $nuevo_numero,
            'cliente_id' => $pedido['cliente_id'],
            'vendedor_id' => $pedido['vendedor_id'],
            'usuario_registro_id' => $_SESSION['usuario']['id'],
            'oc' => $pedido['oc'],
            'condicion' => $pedido['condicion'],
            'fecha_pedido' => $pedido['fecha_pedido'],
            'fecha_entrega' => $pedido['fecha_entrega'],
            'zona' => $pedido['zona'],
            'agencia_id' => $pedido['agencia_id'],
            'direccion_envio' => $pedido['direccion_envio'],
            'nombre_recibe' => $pedido['nombre_recibe'],
            'dni_recibe' => $pedido['dni_recibe'],
            'observacion' => $pedido['observacion'],
            'subtotal' => $pedido['subtotal'],
            'igv' => $pedido['igv'],
            'total' => $pedido['total'],
            'moneda' => $pedido['moneda'],
            'estado' => 'Pendiente'
        ];
        $nuevo_id = $this->create($data);
        if ($nuevo_id) {
            $detalleModel = new DetallePedido();
            foreach ($detalles as $detalle) {
                $detalleModel->create([
                    'pedido_id' => $nuevo_id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'monto' => $detalle['monto']
                ]);
            }
            return $nuevo_id;
        }
        return false;
    }
    
    public function getDetalles($pedido_id) {
        $stmt = $this->db->prepare("
            SELECT d.*, pr.codigo, pr.nombre as producto_nombre
            FROM detalle_pedidos d
            JOIN productos pr ON d.producto_id = pr.id
            WHERE d.pedido_id = :pedido_id
        ");
        $stmt->execute(['pedido_id' => $pedido_id]);
        return $stmt->fetchAll();
    }
    
    public function getPendientes() {
        $stmt = $this->db->query("
            SELECT p.*, c.nombre as cliente_nombre 
            FROM pedidos p 
            JOIN clientes c ON p.cliente_id = c.id 
            WHERE p.estado = 'Pendiente'
            ORDER BY p.fecha_entrega ASC
        ");
        return $stmt->fetchAll();
    }
    
    public function getRecientes($limite = 5) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as cliente_nombre 
            FROM pedidos p 
            JOIN clientes c ON p.cliente_id = c.id 
            ORDER BY p.created_at DESC, p.id DESC 
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getEstadisticas() {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'Atendido' THEN 1 ELSE 0 END) as atendidos,
                SUM(CASE WHEN estado = 'Anulado' THEN 1 ELSE 0 END) as anulados
            FROM pedidos
        ");
        return $stmt->fetch();
    }
    
    // ============================================
    // OBTENER PEDIDOS POR ESTADO (PARA DASHBOARD)
    // ============================================
    public function getByEstado($estado) {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as cliente_nombre 
            FROM pedidos p 
            JOIN clientes c ON p.cliente_id = c.id 
            WHERE p.estado = :estado
            ORDER BY p.id DESC
        ");
        $stmt->execute(['estado' => $estado]);
        return $stmt->fetchAll();
    }
}
?>
