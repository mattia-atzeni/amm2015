<?php

include_once 'php/controller/BaseController.php';
FrontController::dispatch();

class FrontController {
    public static function dispatch() {
        session_start();
        if (isset($_REQUEST["page"])) {
            switch ($_REQUEST["page"]) {
                case "login":
                    $controller = new BaseController();
                    $controller->handleInput();
                    break;
            }
        }
    }
}