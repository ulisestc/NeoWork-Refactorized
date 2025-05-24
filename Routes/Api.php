<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use NEOWORK_REFACTORIZED\Controllers\AppController;
require_once '../Controllers/AppController.php';
require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath("/NeoWork_Refactorized/Routes");

$controller = new AppController();

// Asegúrate de tener este middleware AL PRINCIPIO de tu aplicación Slim
$app->addBodyParsingMiddleware();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
// Api.php
$app->post('/loginUser', function (Request $request, Response $response) use ($controller) {
    session_start(); // Iniciar sesión

    $params = (array) $request->getParsedBody();    
    $result = $controller->loginUs($params['email'], $params['password']);
    
    if ($result['success']) {
        $_SESSION['id_candidato'] = $result['user']['id_candidato'];
        $_SESSION['user_type'] = 'candidato'; // Opcional: para identificar tipo de usuario
    }

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/loginCompany', function (Request $request, Response $response) use ($controller) {
    $params = (array) $request->getParsedBody();
    
    // Llama al método login del controlador
    $result = $controller->loginCom($params['email'], $params['password']);
    
    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/mandarSolicitud', function (Request $request, Response $response) use ($controller) {
    // Desactivar la salida directa de errores
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    if (ob_get_length()) ob_clean();

    $params = $request->getParsedBody();
    $idPuesto    = $params['id_puesto']    ?? null;
    $idCandidato = $params['id_candidato'] ?? null;

    if (!$idPuesto || !$idCandidato) {
        $payload = ['success'=>false,'message'=>'Faltan id_puesto o id_candidato'];
        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type','application/json')->withStatus(400);
    }

    $fecha = date('Y-m-d H:i:s');
    $estado = 'estado';

    $result = $controller->solicitarTrabajo($idPuesto, $idCandidato, $fecha, "enviada");

    // Limpiar cualquier salida previa
    if (ob_get_length()) ob_clean();

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type','application/json');
});

$app->post('/registerUser', function (Request $request, Response $response) {
    // Para datos JSON, usar getBody() en lugar de getParsedBody()
    $json = $request->getBody()->getContents();
    $data = json_decode($json, true);
    
    // Debug: log de los datos recibidos
    error_log("JSON recibido en API: " . $json);
    error_log("Data parseada en API: " . print_r($data, true));
    
    // Verificar si el JSON es válido
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $errorResponse = [
            'status' => 'error',
            'message' => 'JSON inválido: ' . json_last_error_msg()
        ];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    
    // Extraer los campos (nota el cambio de 'email' a 'correo')
    $nombre     = $data['nombre'] ?? null;
    $apellidos  = $data['apellidos'] ?? null;
    $edad       = $data['edad'] ?? null;
    $sexo       = $data['sexo'] ?? null;
    $correo     = $data['email'] ?? null;  // En JS envías 'email'
    $contraseña = $data['contraseña'] ?? null;
    $fecha      = date('Y-m-d H:i:s');

    // Debug: log de los campos extraídos
    error_log("Campos extraídos:");
    error_log("nombre: " . var_export($nombre, true));
    error_log("apellidos: " . var_export($apellidos, true));
    error_log("correo: " . var_export($correo, true));
    error_log("contraseña: " . var_export($contraseña, true));

    $controller = new AppController();
    $result = $controller->register($nombre, $apellidos, $edad, $sexo, $correo, $contraseña, $fecha);
    
    // Verificar que $result no sea null
    if ($result === null) {
        $result = json_encode([
            'status' => 'error',
            'message' => 'Error interno del servidor'
        ]);
    }
    
    // Escribir la respuesta JSON
    $response->getBody()->write($result);
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getReviews/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    // Llama al método getReviews del controlador
    $result = $controller->getReviews($id);
    
    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getUser/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    // Llama al método getUser del controlador
    $result = $controller->getUser($id);
    
    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getCompany/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    // Llama al método getCompany del controlador
    $result = $controller->getCompany($id);
    
    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getJobs', function (Request $request, Response $response) use ($controller) {
    $raw = $controller->getJobs();              
    $result = json_decode($raw, true);          
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getApplications/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    // Llama al método getApplications del controlador
    $result = $controller->getApplications($id);
    
    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->delete('/deleteJob/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    // Llama al método deleteJob del controlador
    $result = $controller->deleteJob($id);
    
    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/registerCompany', function (Request $request, Response $response) {
    $json = $request->getBody()->getContents();
    $data = json_decode($json, true);

     // Debug: log de los datos recibidos
     error_log("JSON recibido en API: " . $json);
     error_log("Data parseada en API: " . print_r($data, true));

    // Verificar si el JSON es válido
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $errorResponse = [
            'status' => 'error',
            'message' => 'JSON inválido: ' . json_last_error_msg()
        ];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $nombre     = $data['nombre'];
    $direccion  = $data['direccion'];
    $area       = $data['area'];
    $email      = $data['email'];
    $password     = $data['password'];
    $fecha      = date('Y-m-d H:i:s');

    $controller = new AppController();
    $result = $controller->registerEmpresa($nombre, $direccion, $area, $email, $password, $fecha);

    // Verificar que $result no sea null
    if ($result === null) {
        $result = json_encode([
            'status' => 'error',
            'message' => 'Error interno del servidor'
        ]);
    }

    $response->getBody()->write($result);

    return $response->withHeader('Content-Type', 'application/json');

});

$app->run();
?>