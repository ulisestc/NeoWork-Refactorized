<?php
require_once '../Controllers/AppController.php';

use TECWEB\MYAPI\Controllers\AppController;

$controller = new AppController();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'login':
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $controller->login($email, $password);
        break;
    default:
        echo json_encode(["error" => "Acción no válida"]);
}
?>