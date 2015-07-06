<?php
include_once './php/model/UserFactory.php';
include_once './php/view/ViewDescriptor.php';

class BaseController {
    
    public function __construct() {
  
    }
    
    public function handleInput() {
        $vd = new ViewDescriptor();
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
        self::login($vd, $username, $password);
        $vd->setTitle("login");
        $vd->setNavigationBar("./php/view/login/navigationBar.php");
        $vd->setContent("./php/view/login/content.php");
        require "./php/view/master.php";
    }
    
    private function login($vd, $username, $password) {
        $user = UserFactory::login($username, $password);
        if (isset($user)) {
           $vd->setErrorMessage("OK");
        } else {
           $vd->setErrorMessage("Utente sconosciuto o password errata");
        }
    }
}

