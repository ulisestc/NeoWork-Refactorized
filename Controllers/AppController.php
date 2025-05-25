<?php
namespace NEOWORK_REFACTORIZED\Controllers;

use NEOWORK_REFACTORIZED\Models\Querys;
require_once __DIR__ . '/../Models/Querys.php';

class AppController {
    
    public function loginUs($email, $password) {
        $query = new Querys();
        $query->loginUser($email, $password);
        return $query->getDataArreglo(); // Devolver en lugar de echo
    }

    public function loginCom($email, $password) {
        $query = new Querys();
        $query->loginCompany($email, $password);
        return $query->getDataArreglo(); // Devolver en lugar de echo
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
        
        return json_encode($response); 
    }

    public function registerEmpresa($nombre, $direccion, $area, $email, $password, $fecha) {

        $query = new Querys();
        $query->registerCompany($nombre, $direccion, $area, $email, $password);

        // Obtener el resultado y convertirlo al formato esperado por el frontend
        $result = json_decode($query->getData(), true);
        
        $response = [
            'status' => $result['success'] ? 'success' : 'error',
            'message' => $result['message']
        ];
        
        return json_encode($response);
    }

    public function solicitarTrabajo($idPuesto, $idCandidato, $fechaSolicitud, $estado): array {
        $query = new Querys();
        $ok = $query->registrarSolicitud($idPuesto, $idCandidato, $fechaSolicitud, $estado);
    
        // Obtener el mensaje y el success de $query
        $raw = json_decode($query->getData(), true);
    
        return [
            'success' => $raw['success'] ?? false,
            'message' => $raw['message'] ?? 'Error al procesar la solicitud'
        ];
    }

    public function agregarVacante($id_empresa, $titulo, $descripcion, $salario, $prestaciones, $fecha_publicacion): array {
        $query = new Querys();
        $ok = $query->agregarVacante($id_empresa, $titulo, $descripcion, $salario, $prestaciones, $fecha_publicacion);
    
        // Decodificar la data que generó el Querys
        $raw = json_decode($query->getData(), true);

    return [
        'success' => $raw['success'] ?? false,
        'message' => $raw['message'] ?? 'Error al procesar la solicitud',
        'id'      => $raw['insert_id'] ?? null    // si quieres devolver el ID generado
    ];
    }

    public function addReview($id_empresa, $id_candidato, $puesto_desempenado, $tiempo_laborado_meses, $comentario, $ambiente_laboral, $prestaciones, $salario, $fecha
    ): array {
        $query = new Querys();
        $ok = $query->addReview($id_empresa, $id_candidato, $puesto_desempenado, $tiempo_laborado_meses, $comentario, $ambiente_laboral, $prestaciones, $salario, $fecha);
    
        // Decodificar la data que generó el Querys
        $raw = json_decode($query->getData(), true);

    return [
        'success' => $raw['success'] ?? false,
        'message' => $raw['message'] ?? 'Error al procesar la solicitud',
        'id'      => $raw['insert_id'] ?? null    // si quieres devolver el ID generado
    ];
    }

    public function editJob($id, $id_empresa, $titulo, $descripcion, $salario, $prestaciones, $fecha_publicacion): array {
        $query = new Querys();
        return $query->editJob($id, $id_empresa, $titulo, $descripcion, $salario, $prestaciones, $fecha_publicacion);
    }
    

    public function getReviews($id) {
        $query = new Querys();
        $query->getReviews($id);
        return $query->getData();
    }

    public function getUser($id) {
        $query = new Querys();
        $query->getUser($id);
        return $query->getData();
    }

    public function getCompany($id) {
        $query = new Querys();
        $query->getCompany($id);
        return $query->getData();
    }

    public function getJobs(){
        $query = new Querys();
        $query->getJobs();
        return $query->getData();
    }

    public function getJobsCompany($id) {
        try {
            $query = new Querys();
            $query->getJobsCompany($id);
            return $query->getData();
        } catch (Exception $e) {
            error_log('Error en AppController::getJobsCompany: ' . $e->getMessage());
            
            return json_encode([
                'success' => false,
                'message' => 'Error al obtener las vacantes de la empresa',
                'data' => []
            ]);
        }
    }

    public function getJob($id) {
        $query = new Querys();
        $result = $query->getJob($id);  // ya devuelve ['success'=>…, 'data'=>…]
        return $result;
    }
    
    public function getApplications($id){
        $query = new Querys();
        $query->getApplications($id);
        return $query->getData();
    }

    public function deleteJob($id){
        $query = new Querys();
        $query->deleteJob($id);
        return $query->getData();
    }
}
?>