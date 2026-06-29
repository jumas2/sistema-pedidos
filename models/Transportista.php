<?php
class Transportista {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function all() {
        $stmt = $this->db->query("
            SELECT t.*, 
                   v.placa as vehiculo_placa, v.marca as vehiculo_marca, v.modelo as vehiculo_modelo,
                   v.estado as vehiculo_estado
            FROM transportistas t
            LEFT JOIN vehiculos v ON t.vehiculo_asignado_id = v.id
            ORDER BY t.nombre
        ");
        return $stmt->fetchAll();
    }
    
    public function allInactivos() {
        $stmt = $this->db->query("
            SELECT t.*, 
                   v.placa as vehiculo_placa, v.marca as vehiculo_marca, v.modelo as vehiculo_modelo,
                   v.estado as vehiculo_estado
            FROM transportistas t
            LEFT JOIN vehiculos v ON t.vehiculo_asignado_id = v.id
            WHERE t.activo = 0
            ORDER BY t.nombre
        ");
        return $stmt->fetchAll();
    }
    
    public function find($id) {
        $stmt = $this->db->prepare("
            SELECT t.*, 
                   v.placa as vehiculo_placa, v.marca as vehiculo_marca, v.modelo as vehiculo_modelo,
                   v.estado as vehiculo_estado
            FROM transportistas t
            LEFT JOIN vehiculos v ON t.vehiculo_asignado_id = v.id
            WHERE t.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $sql = "INSERT INTO transportistas (nombre, apellido, dni, telefono, licencia, activo) 
                VALUES (:nombre, :apellido, :dni, :telefono, :licencia, 1)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'nombre' => $data['nombre'] ?? '',
            'apellido' => $data['apellido'] ?? '',
            'dni' => $data['dni'] ?? '',
            'telefono' => $data['telefono'] ?? '',
            'licencia' => $data['licencia'] ?? ''
        ]);
        return ['success' => $result, 'message' => $result ? 'Transportista creado' : 'Error al crear'];
    }
    
    public function update($id, $data) {
        $sql = "UPDATE transportistas SET 
                nombre = :nombre,
                apellido = :apellido,
                dni = :dni,
                telefono = :telefono,
                licencia = :licencia,
                activo = :activo
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        $result = $stmt->execute($data);
        return ['success' => $result, 'message' => $result ? 'Transportista actualizado' : 'Error al actualizar'];
    }
    
    public function cambiarEstado($id, $activo) {
        $stmt = $this->db->prepare("UPDATE transportistas SET activo = :activo WHERE id = :id");
        return $stmt->execute(['id' => $id, 'activo' => $activo]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE transportistas SET activo = 0 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function asignarUsuario($transportista_id, $usuario_id) {
        $stmt = $this->db->prepare("UPDATE transportistas SET usuario_id = :usuario_id WHERE id = :id");
        return $stmt->execute(['usuario_id' => $usuario_id, 'id' => $transportista_id]);
    }
    
    public function asignarVehiculo($transportista_id, $vehiculo_id, $tipo = 'temporal') {
        try {
            $stmt = $this->db->prepare("SELECT id, estado FROM vehiculos WHERE id = :id");
            $stmt->execute(['id' => $vehiculo_id]);
            $vehiculo = $stmt->fetch();
            
            if (!$vehiculo || $vehiculo['estado'] !== 'Disponible') {
                return false;
            }
            
            $this->liberarVehiculo($transportista_id);
            
            $stmt = $this->db->prepare("UPDATE transportistas SET vehiculo_asignado_id = :vehiculo_id, tipo_asignacion_vehiculo = :tipo WHERE id = :id");
            $result = $stmt->execute([
                'vehiculo_id' => $vehiculo_id,
                'tipo' => $tipo,
                'id' => $transportista_id
            ]);
            
            if ($result) {
                $stmt = $this->db->prepare("UPDATE vehiculos SET estado = 'En Uso' WHERE id = :id");
                $stmt->execute(['id' => $vehiculo_id]);
                
                $stmt = $this->db->prepare("INSERT INTO historial_asignacion_vehiculo 
                    (transportista_id, vehiculo_id, fecha_asignacion, tipo_asignacion, usuario_registro_id) 
                    VALUES (:transportista_id, :vehiculo_id, CURDATE(), :tipo, :usuario_id)");
                $stmt->execute([
                    'transportista_id' => $transportista_id,
                    'vehiculo_id' => $vehiculo_id,
                    'tipo' => $tipo,
                    'usuario_id' => $_SESSION['usuario']['id'] ?? 1
                ]);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en asignarVehiculo: " . $e->getMessage());
            return false;
        }
    }
    
    public function asignarVehiculoAGuia($guia_id, $vehiculo_id) {
        try {
            $stmt = $this->db->prepare("SELECT id, estado FROM vehiculos WHERE id = :id");
            $stmt->execute(['id' => $vehiculo_id]);
            $vehiculo = $stmt->fetch();
            
            if (!$vehiculo || $vehiculo['estado'] !== 'Disponible') {
                return false;
            }
            
            $stmt = $this->db->prepare("UPDATE guias_remision SET vehiculo_asignado_id = :vehiculo_id WHERE id = :id");
            $result = $stmt->execute(['vehiculo_id' => $vehiculo_id, 'id' => $guia_id]);
            
            if ($result) {
                $stmt = $this->db->prepare("UPDATE vehiculos SET estado = 'En Uso' WHERE id = :id");
                $stmt->execute(['id' => $vehiculo_id]);
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en asignarVehiculoAGuia: " . $e->getMessage());
            return false;
        }
    }
    
    public function liberarVehiculo($transportista_id) {
        try {
            $stmt = $this->db->prepare("SELECT vehiculo_asignado_id FROM transportistas WHERE id = :id");
            $stmt->execute(['id' => $transportista_id]);
            $transportista = $stmt->fetch();
            
            if ($transportista && $transportista['vehiculo_asignado_id']) {
                $stmt = $this->db->prepare("UPDATE historial_asignacion_vehiculo 
                    SET fecha_devolucion = CURDATE() 
                    WHERE transportista_id = :transportista_id AND fecha_devolucion IS NULL");
                $stmt->execute(['transportista_id' => $transportista_id]);
                
                $stmt = $this->db->prepare("UPDATE vehiculos SET estado = 'Disponible' WHERE id = :id");
                $stmt->execute(['id' => $transportista['vehiculo_asignado_id']]);
                
                $stmt = $this->db->prepare("UPDATE transportistas SET vehiculo_asignado_id = NULL, tipo_asignacion_vehiculo = NULL WHERE id = :id");
                return $stmt->execute(['id' => $transportista_id]);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en liberarVehiculo: " . $e->getMessage());
            return false;
        }
    }
    
    public function liberarVehiculoDeGuia($guia_id) {
        try {
            $stmt = $this->db->prepare("SELECT vehiculo_asignado_id FROM guias_remision WHERE id = :id");
            $stmt->execute(['id' => $guia_id]);
            $guia = $stmt->fetch();
            
            if ($guia && $guia['vehiculo_asignado_id']) {
                $stmt = $this->db->prepare("UPDATE vehiculos SET estado = 'Disponible' WHERE id = :id");
                $stmt->execute(['id' => $guia['vehiculo_asignado_id']]);
                
                $stmt = $this->db->prepare("UPDATE guias_remision SET vehiculo_asignado_id = NULL WHERE id = :id");
                return $stmt->execute(['id' => $guia_id]);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en liberarVehiculoDeGuia: " . $e->getMessage());
            return false;
        }
    }
    
    public function getHistorialAsignaciones($transportista_id) {
        $stmt = $this->db->prepare("
            SELECT h.*, v.placa, v.marca, v.modelo, u.nombre as usuario_nombre
            FROM historial_asignacion_vehiculo h
            LEFT JOIN vehiculos v ON h.vehiculo_id = v.id
            LEFT JOIN usuarios u ON h.usuario_registro_id = u.id
            WHERE h.transportista_id = :transportista_id
            ORDER BY h.fecha_asignacion DESC
        ");
        $stmt->execute(['transportista_id' => $transportista_id]);
        return $stmt->fetchAll();
    }
}
?>
