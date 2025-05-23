<?php
namespace TECWEB\MYAPI;

use TECWEB\MYAPI\DataBase as DataBase;
require_once __DIR__ . '/DataBase.php';

class Querys extends DataBase{
    private $data = NULL;

    public function __construct($user='root', $pass='ContrasenaSegura', $db='neowork'){
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
}
?>