<?php
namespace TECWEB\MYAPI\Controllers;

use TECWEB\MYAPI\Querys;

require_once __DIR__ . '/../Models/Querys.php';

    class AppController{
        public function login($email, $password){
            $prodObj = new Querys();
            $prodObj->loginUser($email, $password);
            echo $prodObj->getData();
            unset($prodObj);
        }
    }
?>