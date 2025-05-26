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
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
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
    session_start();
    $params = (array) $request->getParsedBody();
    
    // Llama al método login del controlador
    $result = $controller->loginCom($params['email'], $params['password']);

    if ($result['success']) {
        $_SESSION['id_empresa'] = $result['user']['id_empresa'];
        $_SESSION['user_type'] = 'empresa'; // Opcional: para identificar tipo de usuario
    }
    
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
    
    if (is_string($result)) {
        $result = json_decode($result, true); // o false si quieres stdClass
    }

    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/agregarVacante', function (Request $request, Response $response, array $args) {
    $json = $request->getBody()->getContents();
    $data = json_decode($json, true);

    // Validar JSON
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $errorResponse = [
            'success' => false,
            'message' => 'JSON inválido: ' . json_last_error_msg()
        ];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
    }

    $id_empresa       = $data['id_empresa']       ?? null;
    $titulo           = $data['nombre_vacante']   ?? '';
    $descripcion      = $data['requerimientos']   ?? '';
    $salario          = $data['salario']          ?? '';
    $prestaciones     = $data['prestaciones']     ?? '';
    $fecha_publicacion = date('Y-m-d H:i:s');

    $controller = new AppController();
    $result = $controller->agregarVacante(
        $id_empresa,
        $titulo,
        $descripcion,
        $salario,
        $prestaciones,
        $fecha_publicacion
    );

    $response->getBody()->write(json_encode($result));
    return $response
        ->withHeader('Content-Type', 'application/json');
});

$app->post('/addReview', function (Request $request, Response $response, array $args) {
    $json = $request->getBody()->getContents();
    $data = json_decode($json, true);

    // Validar JSON
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $errorResponse = [
            'success' => false,
            'message' => 'JSON inválido: ' . json_last_error_msg()
        ];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
    }

    $id_empresa       = $data['id_empresa'];
    $id_candidato     = $data['id_candidato'];
    $puesto_desempenado = $data['puesto_desempenado'];
    $tiempo_laborado_meses = $data['tiempo_laborado_meses'];
    $comentario = $data['comentario'];
    $ambiente_laboral  = $data['ambiente_laboral'];
    $prestaciones     = $data['prestaciones'];
    $salario     = $data['salario'];
    $fecha = date('Y-m-d H:i:s');

    $controller = new AppController();
    $result = $controller->addReview(
        $id_empresa,
        $id_candidato,
        $puesto_desempenado,
        $tiempo_laborado_meses,
        $comentario,
        $ambiente_laboral,
        $prestaciones,
        $salario,
        $fecha
    );

    $response->getBody()->write(json_encode($result));
    return $response
        ->withHeader('Content-Type', 'application/json');
});

$app->post('/editJob/{id}', function (Request $request, Response $response, $args) use ($controller) {
    
    $id = (int)$args['id'];
    $json = $request->getBody()->getContents();
    $data = json_decode($json, true);

    // Validar JSON
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $errorResponse = [
            'success' => false,
            'message' => 'JSON inválido: ' . json_last_error_msg()
        ];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
    }

    $id_empresa       = $data['id_empresa']       ?? null;
    $titulo           = $data['nombre_vacante']   ?? '';
    $descripcion      = $data['requerimientos']   ?? '';
    $salario          = $data['salario']          ?? '';
    $prestaciones     = $data['prestaciones']     ?? '';
    $fecha_publicacion = date('Y-m-d H:i:s');

    error_log("Editando vacante ID: $id");
    error_log("Datos recibidos: " . print_r($data, true));

    $controller = new AppController();
    $result = $controller->editJob(
        $id,
        $id_empresa,
        $titulo,
        $descripcion,
        $salario,
        $prestaciones,
        $fecha_publicacion
    );

    $response->getBody()->write(json_encode($result));
    return $response
        ->withHeader('Content-Type', 'application/json');
});


$app->get('/getUser/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    $result = $controller->getUser($id);

    // Pasar string a jjsoooon D:
    if (is_string($result)) {
        $result = json_decode($result, true); // o false si quieres stdClass
    }

    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});


$app->get('/getCompany/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    // Llama al método getCompany del controlador
    $result = $controller->getCompany($id);
   
    // Pasar string a jjsoooon D:
    if (is_string($result)) {
        $result = json_decode($result, true); // o false si quieres stdClass
    }
    
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



    $app->get('/getJob/{id}', function (Request $request, Response $response, array $args) use ($controller) {
        $id = (int)$args['id'];           
        $result = $controller->getJob($id);
    
        $payload = json_encode($result);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });
    

$app->get('/getJobsCompany/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    try {
        // Obtener el ID desde los argumentos de la ruta
        $id = $args['id'];
        
        // Validar que el ID sea válido
        if (empty($id) || !is_numeric($id)) {
            $result = [
                'success' => false,
                'message' => 'ID de empresa inválido',
                'data' => []
            ];
            $response->getBody()->write(json_encode($result));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
        
        // Llamar al controlador
        $raw = $controller->getJobsCompany($id);
        $result = json_decode($raw, true);
        
        // Verificar si la decodificación fue exitosa
        if (json_last_error() !== JSON_ERROR_NONE) {
            $result = [
                'success' => false,
                'message' => 'Error al procesar los datos',
                'data' => []
            ];
        }
        
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
        
    } catch (Exception $e) {
        error_log('Error en getJobsCompany: ' . $e->getMessage());
        
        $result = [
            'success' => false,
            'message' => 'Error interno del servidor',
            'data' => []
        ];
        
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->get('/getApplications/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    
    // Llama al método getApplications del controlador
    $result = $controller->getApplications($id);

    if (is_string($result)) {
        $result = json_decode($result, true); // o false si quieres stdClass
    }

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

$app->get('/getComments/{id}', function (Request $request, Response $response, array $args) use ($controller) {
    $id = $args['id'];
    // Llama al método getComments del controlador
    $result = $controller->getComments($id);
    
    if (is_string($result)) {
        $result = json_decode($result, true); // o false si quieres stdClass
    }

    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/addComment', function (Request $request, Response $response) use ($controller) {
    $json = $request->getBody()->getContents();
    $data = json_decode($json, true);

    // Validar JSON
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        $errorResponse = [
            'success' => false,
            'message' => 'JSON inválido: ' . json_last_error_msg()
        ];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
    }

    $id_puesto = $data['id_puesto'];
    $id_candidato = $data['id_candidato'];
    $comment = $data['comment'];
    $fecha = date('Y-m-d H:i:s');

    // Llama al método addComment del controlador
    $result = $controller->addComment($id_puesto, $id_candidato, $comment, $fecha);

    // Escribe la respuesta JSON
    $response->getBody()->write(json_encode($result));
    
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
?>