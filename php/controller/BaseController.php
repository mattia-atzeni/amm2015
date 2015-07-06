<?php
include_once './php/model/UserFactory.php';

class BaseController {
    public function handleInput() {
        include 'php/view/login.php';
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        self::login($username, $password);
    }
    
    private function login($username, $password) {
        $user = UserFactory::login($username, $password);
        if (isset($user)) {
           echo "OK";
        } else {
            echo "Errore";
        }
    }
}

