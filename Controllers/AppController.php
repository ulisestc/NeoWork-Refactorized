<?php
namespace NEOWORK_REFACTORIZED\Controllers;

use NEOWORK_REFACTORIZED\Models\Querys;
require_once __DIR__ . '/../Models/Querys.php';

class AppController {
    
    public function login($email, $password) {
        $query = new Querys();
        $query->loginUser($email, $password);
        return $query->getData(); // Devolver en lugar de echo
    }
    
    public function register($nombre, $apellidos, $edad, $sexo, $correo, $contraseña, $fecha) {
        // Debug: log de parámetros recibidos en controller
        error_log("Controller register - Parámetros:");
        error_log("nombre: " . var_export($nombre, true));
        error_log("apellidos: " . var_export($apellidos, true));
        error_log("correo: " . var_export($correo, true));
        error_log("contraseña: " . var_export($contraseña, true));
        
        $query = new Querys();
        $query->registerUser($nombre, $apellidos, $edad, $sexo, $correo, $contraseña, $fecha);
        
        // Obtener el resultado y convertirlo al formato esperado por el frontend
        $result = json_decode($query->getData(), true);
        
        $response = [
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message']
        ];
        
        return json_encode($response); // IMPORTANTE: return aquí
    }

    public function getReviews($id) {
        $query = new Querys();
        $query->getReviews($id);
        return $query->getData();
    }
}
?>