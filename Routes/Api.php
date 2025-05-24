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

$app->post('/loginUser', function (Request $request, Response $response) use ($controller) {
    $params = (array) $request->getParsedBody();
    
    // Llama al método login del controlador
    $result = $controller->login($params['email'], $params['password']);
    
    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
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

$app->run();
?>