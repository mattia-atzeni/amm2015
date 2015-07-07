<?php

include_once 'php/controller/BaseController.php';
include_once 'php/controller/ProviderController.php';


FrontController::dispatch();

class FrontController {
    public static function dispatch() {
        //echo "session_start";
        session_start();
        if (isset($_REQUEST["page"])) {
            switch ($_REQUEST["page"]) {
                case "login":
                    $controller = new BaseController();
                    $controller->handleInput();
                    break;
                case "learner":
                    $controller = new LearnerController();
                    $controller->handleInput();
                    break;
                case "provider":
                    $controller = new ProviderController();
                    $controller->handleInput();
                    break;
                default:
                    self::write404();
            }
        }
    }
    
    public static function write404() {      
        header('HTTP/1.0 404 Not Found');
        $title = "File non trovato";
        $message = "La pagina richiesta non &egrave; disponibile";
        require 'php/error.php';
        exit();
    }
    
    public static function write403() {
        header('HTTP/1.0 403 Forbidden');
        $title = "Accesso negato";
        require 'php/error.php';
        exit();
    }
}
