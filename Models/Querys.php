<?php
namespace NEOWORK_REFACTORIZED\Models;

use NEOWORK_REFACTORIZED\Models\DataBase as DataBase;
require_once __DIR__ . '/DataBase.php';

class Querys extends DataBase{
    private $data = NULL;

    public function __construct($user='root', $pass='', $db='neowork'){
        $this->data = array();
        parent::__construct($user, $pass, $db);
    }

    public function getData(){
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    public function getDataArreglo(){
        return $this->data;
    }

    public function loginUser($email, $password){
        $this->data = array();
    
        // Prepara la consulta para evitar inyección SQL
        $stmt = $this->conexion->prepare(
            "SELECT * FROM Candidatos WHERE correo = ? AND contraseña = ?"
        );
    
        if ($stmt) {
            $stmt->bind_param("ss", $email, $password);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $this->data['success'] = true;
                    $this->data['message'] = 'Inicio de sesión exitoso';
                    $this->data['user'] = $user; // Devuelve los datos del usuario
                } else {
                    $this->data['success'] = false;
                    $this->data['message'] = 'Correo o contraseña incorrectos';
                }
            } else {
                $this->data['success'] = false;
                $this->data['message'] = 'Error al ejecutar la consulta: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $this->data['success'] = false;
            $this->data['message'] = 'Error en la preparación de la consulta: ' . $this->conexion->error;
        }
    
        $this->conexion->close();
    }

    public function loginCompany($email, $password){
        $this->data = array();
    
        // Prepara la consulta para evitar inyección SQL
        $stmt = $this->conexion->prepare(
            "SELECT * FROM Empresas WHERE correo = ? AND contraseña = ?"
        );
    
        if ($stmt) {
            $stmt->bind_param("ss", $email, $password);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $this->data['success'] = true;
                    $this->data['message'] = 'Inicio de sesión exitoso';
                    $this->data['user'] = $user; // Devuelve los datos del usuario
                } else {
                    $this->data['success'] = false;
                    $this->data['message'] = 'Correo o contraseña incorrectos';
                }
            } else {
                $this->data['success'] = false;
                $this->data['message'] = 'Error al ejecutar la consulta: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $this->data['success'] = false;
            $this->data['message'] = 'Error en la preparación de la consulta: ' . $this->conexion->error;
        }
    
        $this->conexion->close();
    }


    public function registerUser($nombre, $apellidos, $edad, $sexo, $correo, $contraseña, $fecha_registro = null) {
        $this->data = [];
    
        // Logs para debug (puedes quitar después)
        error_log("registerUser called with:");
        error_log("  nombre: " . var_export($nombre, true));
        error_log("  apellidos: " . var_export($apellidos, true));
        error_log("  edad: " . var_export($edad, true));
        error_log("  sexo: " . var_export($sexo, true));
        error_log("  correo: " . var_export($correo, true));
        error_log("  contraseña: " . var_export($contraseña, true));
    
        // 1) Validación de campos obligatorios
        if (empty($nombre) || empty($apellidos) || empty($correo) || empty($contraseña)) {
            $this->data['success'] = false;
            $this->data['message'] = "Los campos nombre, apellidos, correo y contraseña son obligatorios";
            $this->conexion->close();
            return;
        }
    
        // 2) Verificar si el correo ya existe
        $checkSql = "SELECT 1 FROM Candidatos WHERE correo = ? LIMIT 1";
        if ($checkStmt = $this->conexion->prepare($checkSql)) {
            $checkStmt->bind_param("s", $correo);
            $checkStmt->execute();
            $checkStmt->store_result();
    
            if ($checkStmt->num_rows > 0) {
                $this->data['success'] = false;
                $this->data['message'] = "El correo electrónico ya está registrado";
                $checkStmt->close();
                $this->conexion->close();
                return;
            }
    
            $checkStmt->close();
        } else {
            // Error preparando la consulta de verificación
            $this->data['success'] = false;
            $this->data['message'] = "Error al verificar correo: " . $this->conexion->error;
            error_log("Error preparación SELECT: " . $this->conexion->error);
            $this->conexion->close();
            return;
        }
    
        // 3) Insertar el nuevo usuario
        // Si no se pasó fecha, la generamos ahora
        if (is_null($fecha_registro)) {
            $fecha_registro = date('Y-m-d H:i:s');
        }
    
        $insertSql = "
            INSERT INTO Candidatos 
              (nombre, apellidos, edad, sexo, correo, contraseña, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        if ($stmt = $this->conexion->prepare($insertSql)) {
            $stmt->bind_param("ssissss", 
                $nombre, 
                $apellidos, 
                $edad, 
                $sexo, 
                $correo, 
                $contraseña, 
                $fecha_registro
            );
    
            if ($stmt->execute()) {
                $this->data['success'] = true;
                $this->data['message'] = "Registro exitoso";
            } else {
                $this->data['success'] = false;
                $this->data['message'] = "Error al ejecutar INSERT: " . $stmt->error;
                error_log("Error SQL INSERT: " . $stmt->error);
            }
    
            $stmt->close();
        } else {
            $this->data['success'] = false;
            $this->data['message'] = "Error al preparar INSERT: " . $this->conexion->error;
            error_log("Error preparación INSERT: " . $this->conexion->error);
        }
    
        // 4) Cerrar conexión
        $this->conexion->close();
    }

    public function registerCompany($nombre, $direccion, $area, $email, $password, $fecha_registro = null) {
        $this->data = [];
    
        $checkSql = "SELECT 1 FROM Empresas WHERE correo = ? LIMIT 1";
        if ($checkStmt = $this->conexion->prepare($checkSql)) {
            $checkStmt->bind_param("s", $correo);
            $checkStmt->execute();
            $checkStmt->store_result();
    
            if ($checkStmt->num_rows > 0) {
                $this->data['success'] = false;
                $this->data['message'] = "El correo electrónico ya está registrado";
                $checkStmt->close();
                $this->conexion->close();
                return;
            }
    
            $checkStmt->close();
        } else {
            // Error preparando la consulta de verificación
            $this->data['success'] = false;
            $this->data['message'] = "Error al verificar correo: " . $this->conexion->error;
            error_log("Error preparación SELECT: " . $this->conexion->error);
            $this->conexion->close();
            return;
        }
    
        // 3) Insertar el nuevo usuario
        // Si no se pasó fecha, la generamos ahora
        if (is_null($fecha_registro)) {
            $fecha_registro = date('Y-m-d H:i:s');
        }
    
        $insertSql = "
            INSERT INTO Empresas
              (nombre_empresa, direccion, area, correo, contraseña, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        if ($stmt = $this->conexion->prepare($insertSql)) {
            $stmt->bind_param("ssssss", 
                $nombre, 
                $direccion, 
                $area, 
                $email, 
                $password, 
                $fecha_registro
            );
    
            if ($stmt->execute()) {
                $this->data['success'] = true;
                $this->data['message'] = "Registro exitoso";
            } else {
                $this->data['success'] = false;
                $this->data['message'] = "Error al ejecutar INSERT: " . $stmt->error;
                error_log("Error SQL INSERT: " . $stmt->error);
            }
    
            $stmt->close();
        } else {
            $this->data['success'] = false;
            $this->data['message'] = "Error al preparar INSERT: " . $this->conexion->error;
            error_log("Error preparación INSERT: " . $this->conexion->error);
        }
    
        $this->conexion->close();
    }
    
    public function registrarSolicitud($idPuesto, $idCandidato, $fechaSolicitud, $estado) {
        // Reset de data
        $this->data = [];
    
        $insertSql = "
            INSERT INTO Solicitudes
                (id_puesto, id_candidato, fecha_solicitud, estado)
            VALUES (?, ?, ?, ?)
        ";
    
        $stmt = $this->conexion->prepare($insertSql);
        if (!$stmt) {
            $this->data['success'] = false;
            $this->data['message'] = "Error en preparación: " . $this->conexion->error;
            error_log("registrarSolicitud prepare error: " . $this->conexion->error);
            return false;
        }
    
        $stmt->bind_param("iiss", $idPuesto, $idCandidato, $fechaSolicitud, $estado);
        if ($stmt->execute()) {
            $this->data['success'] = true;
            $this->data['message'] = "Solicitud registrada con éxito";
        } else {
            $this->data['success'] = false;
            $this->data['message'] = "Error en ejecución: " . $stmt->error;
            error_log("registrarSolicitud execute error: " . $stmt->error);
        }
    
        $stmt->close();
        return $this->data['success'];
    }

    public function agregarVacante($id_empresa, $titulo, $descripcion, $salario, $prestaciones, $fecha_publicacion) {
        // Reset de data
    $this->data = [];

    // 1) Ajustar placeholders: son 6 columnas -> 6 '?'
    $insertSql = "
        INSERT INTO Puestos
            (id_empresa, titulo, descripcion, salario, prestaciones, fecha_publicacion)
        VALUES (?, ?, ?, ?, ?, ?)
    ";

    $stmt = $this->conexion->prepare($insertSql);
    if (!$stmt) {
        $this->data['success'] = false;
        $this->data['message'] = "Error en preparación: " . $this->conexion->error;
        error_log("agregarVacante prepare error: " . $this->conexion->error);
        return false;
    }

        $stmt->bind_param(
            "isssss",
            $id_empresa,
            $titulo,
            $descripcion,
            $salario,
            $prestaciones,
            $fecha_publicacion
        );

        if ($stmt->execute()) {
            $this->data['success']   = true;
            $this->data['message']   = "Vacante publicada con éxito";
            $this->data['insert_id'] = $stmt->insert_id;
        } else {
            $this->data['success'] = false;
            $this->data['message'] = "Error en ejecución: " . $stmt->error;
            error_log("agregarVacante execute error: " . $stmt->error);
        }

        $stmt->close();
        return $this->data['success'];
    }

    public function editJob($id, $id_empresa, $titulo, $descripcion, $salario, $prestaciones, $fecha_publicacion) {
        $this->data = [];
    
        $sql = "
            UPDATE Puestos
            SET 
                id_empresa       = ?,
                titulo           = ?,
                descripcion      = ?,
                salario          = ?,
                prestaciones     = ?,
                fecha_publicacion= ?
            WHERE id_puesto = ?
        ";
    
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            $this->data = [
                'success' => false,
                'message' => 'Error en prepare: ' . $this->conexion->error
            ];
            return $this->data;
        }
    
        // Tipos: i = int, s = string (6 strings y al final otro int para el id)
        $stmt->bind_param(
            "isssssi",
            $id_empresa,
            $titulo,
            $descripcion,
            $salario,
            $prestaciones,
            $fecha_publicacion,
            $id
        );
    
        if ($stmt->execute()) {
            $this->data = [
                'success' => true,
                'message' => 'Vacante editada con éxito'
            ];
        } else {
            $this->data = [
                'success' => false,
                'message' => 'Error en execute: ' . $stmt->error
            ];
        }
        $stmt->close();
        return $this->data;
    }
    

    public function getUser($id) {
        $this->data = array();
    
        $query = "SELECT nombre, apellidos, edad, sexo, correo, fecha_registro FROM candidatos WHERE id_candidato = $id";
        $result = $this->conexion->query($query);
    
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $this->data[] = $row;
                }
                $this->data['success'] = true;
                $this->data['message'] = 'Perfil obtenido exitosamente';
            } else {
                $this->data['success'] = false;
                $this->data['message'] = 'No se encontró el perfil';
            }
        } else {
            $this->data['success'] = false;
            $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
        }
    
        $this->conexion->close();
    }
    
    public function getCompany($id) {
        $this->data = array();
    
        $query = "SELECT nombre_empresa, direccion, area, correo, fecha_registro FROM empresas WHERE id_empresa = $id";
        $result = $this->conexion->query($query);
    
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $this->data[] = $row;
                }
                $this->data['success'] = true;
                $this->data['message'] = 'Perfil de empresa obtenido exitosamente';
            } else {
                $this->data['success'] = false;
                $this->data['message'] = 'No se encontró el perfil de la empresa';
            }
        } else {
            $this->data['success'] = false;
            $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
        }
    
        $this->conexion->close();
    }

    public function getJobsCompany($id) {
        $this->data = array();
        $vacantes = array();
    
        if (!$this->conexion) {
            error_log('Error: No hay conexión a la base de datos');
            $this->data = array(
                'success' => false,
                'message' => 'Error de conexión a la base de datos',
                'data' => array()
            );
            return;
        }
    
        try {
            // Consulta para obtener todas las vacantes de una empresa específica
            $query = "SELECT * FROM puestos WHERE id_empresa = ? ORDER BY fecha_publicacion DESC";
            
            // Preparar la consulta
            $stmt = $this->conexion->prepare($query);
            
            if (!$stmt) {
                error_log('Error al preparar la consulta: ' . $this->conexion->error);
                $this->data = array(
                    'success' => false,
                    'message' => 'Error al preparar la consulta de base de datos',
                    'data' => array()
                );
                return;
            }
    
            // Bind parameter
            $stmt->bind_param("i", $id);
            
            // Ejecutar la consulta
            if (!$stmt->execute()) {
                error_log('Error al ejecutar la consulta: ' . $stmt->error);
                $this->data = array(
                    'success' => false,
                    'message' => 'Error al ejecutar la consulta',
                    'data' => array()
                );
                $stmt->close();
                return;
            }
    
            // Obtener los resultados
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $vacantes[] = $row;
                }
                
                $this->data = array(
                    'success' => true,
                    'message' => 'Vacantes encontradas exitosamente',
                    'data' => $vacantes
                );
            } else {
                $this->data = array(
                    'success' => false,
                    'message' => 'No se encontraron vacantes para esta empresa',
                    'data' => array()
                );
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            error_log('Error en getJobsCompany: ' . $e->getMessage());
            $this->data = array(
                'success' => false,
                'message' => 'Error interno al procesar la consulta',
                'data' => array()
            );
        }
    }

    public function getJobs() {
        $this->data = array();
        $vacantes = array();
        
        // DEBUG: Verificar conexión
        if (!$this->conexion) {
            error_log('Error: No hay conexión a la base de datos');
            $this->data = array(
                'success' => false,
                'message' => 'Error de conexión a la base de datos',
                'data' => array()
            );
            return;
        }
        
        $query = "
            SELECT 
                Puestos.*, 
                Empresas.nombre_empresa, 
                Empresas.direccion 
            FROM Puestos 
            JOIN Empresas ON Puestos.id_empresa = Empresas.id_empresa 
            ORDER BY Puestos.fecha_publicacion DESC
        ";

        $result = $this->conexion->query($query);
        
        // DEBUG: Log de la query
        error_log('Query ejecutada: ' . $query);
        error_log('Resultado de query: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        if ($result) {
            error_log('Número de filas encontradas: ' . $result->num_rows);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $vacantes[] = $row;
                }
                
                error_log('Vacantes procesadas: ' . count($vacantes));
                
                $this->data = array(
                    'success' => true,
                    'message' => 'Vacantes obtenidas exitosamente',
                    'data' => $vacantes
                );
            } else {
                $this->data = array(
                    'success' => false,
                    'message' => 'No se encontraron vacantes',
                    'data' => array()
                );
            }
        } else {
            error_log('Error en query: ' . $this->conexion->error);
            $this->data = array(
                'success' => false,
                'message' => 'Error en la consulta: ' . $this->conexion->error,
                'data' => array()
            );
        }
        
        $this->conexion->close();
    }

    public function getJob($id) {
        $this->data = [];
    
        // Preparar la consulta con placeholder
        $sql = "
            SELECT 
                Puestos.id_puesto,
                Puestos.id_empresa,
                Puestos.titulo,
                Puestos.descripcion,
                Puestos.salario,
                Puestos.prestaciones,
                Puestos.fecha_publicacion,
                Empresas.nombre_empresa,
                Empresas.direccion
            FROM Puestos 
            JOIN Empresas ON Puestos.id_empresa = Empresas.id_empresa 
            WHERE Puestos.id_puesto = ?
        ";
    
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            $this->data = [
                'success' => false,
                'message' => 'Error en prepare: ' . $this->conexion->error,
                'data'    => null
            ];
            return $this->data;
        }
    
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            $this->data = [
                'success' => false,
                'message' => 'Error en execute: ' . $stmt->error,
                'data'    => null
            ];
            return $this->data;
        }
    
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $this->data = [
                'success' => true,
                'message' => 'Puesto encontrado',
                'data'    => $row
            ];
        } else {
            $this->data = [
                'success' => false,
                'message' => 'No existe el puesto',
                'data'    => null
            ];
        }
    
        $stmt->close();
        return $this->data;
    }
    

    public function getApplications($id) {
        $this->data = array();
    
        $query = "SELECT * FROM solicitudes WHERE id_puesto = $id";
        $result = $this->conexion->query($query);
    
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $this->data[] = $row;
                }
                $this->data['success'] = true;
                $this->data['message'] = 'Postulaciones obtenidas exitosamente';
            } else {
                $this->data['success'] = false;
                $this->data['message'] = 'No se encontraron postulaciones';
            }
        } else {
            $this->data['success'] = false;
            $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
        }
    
        $this->conexion->close();
    }

    public function deleteJob($id){
        $this->data = array();
        //ELIOMINA COMENTARIOS
        $query1 = "DELETE FROM comentariospuestos WHERE id_puesto = $id";
        $this->conexion->query($query1);

        // ELIMINA SOLICITUDES
        $query2 = "DELETE FROM solicitudes WHERE id_puesto = $id";
        $this->conexion->query($query2);

        // ELIMINA PUESTO
        $query3 = "DELETE FROM puestos WHERE id_puesto = $id";
        // $this->conexion->query($query2);
        $result = $this->conexion->query($query3);
        
        if ($result) {
            $this->data['success'] = true;
            $this->data['message'] = 'Vacante eliminada exitosamente';
        } else {
            $this->data['success'] = false;
            $this->data['message'] = 'Error al eliminar la vacante: ' . $this->conexion->error;
        }
        $this->conexion->close();
    }
    
}
?>