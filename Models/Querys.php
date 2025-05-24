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

    public function getReviews($id) {
        $this->data = array();
    
        $query = "SELECT * FROM reseñas WHERE id_empresa = $id";
        $result = $this->conexion->query($query);
    
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $this->data[] = $row;
                }
                $this->data['success'] = true;
                $this->data['message'] = 'Reseñas obtenidas exitosamente';
            } else {
                $this->data['success'] = false;
                $this->data['message'] = 'No se encontraron reseñas';
            }
        } else {
            $this->data['success'] = false;
            $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
        }
    
        $this->conexion->close();
    }
    
    
}
?>