<?php

include_once 'php/model/UserFactory.php';
include_once 'php/view/ViewDescriptor.php';

class BaseController {

    const user = 'user';

    public function __construct() {
        
    }

    public function handleInput() {
        $vd = new ViewDescriptor();
        if (isset($_REQUEST["cmd"])) {
            switch ($_REQUEST["cmd"]) {
                case 'login':
                    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
                    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
                    if (! $this->login($username, $password) ) {
                        $vd->setErrorMessage("Utente sconosciuto o password errata");
                    }
                    break;
                case "logout":
                    $this->logout();
            }
        }
        $this->showHomePage($vd);
    }

    protected function login($username, $password) {
        $user = UserFactory::login($username, $password);
        if (isset($user)) {
            $_SESSION[self::user] = $user->getId();
            return true;
        }
        return false; 
    }
    
    protected function logout() {
        // reset array $_SESSION
        $_SESSION = array();
        // termino la validita' del cookie di sessione
        if (session_id() != '' || isset($_COOKIE[session_name()])) {
            // imposto il termine di validita' al mese scorso
            setcookie(session_name(), '', time() - 2592000, '/');
        }
        // distruggo il file di sessione
        session_destroy();
    }

    protected function loggedIn() {
        return isset($_SESSION) && array_key_exists(self::user, $_SESSION);
    }
    
    protected function showHomePage($vd) {
        if (!$this->loggedIn()) {
            $path = "php/view/login/";
        } else {
            $path = "php/view/provider/";
        }
        
        $vd->setContent($path . "content.php");
        $vd->setNavigationBar($path . "navigationBar.php");
        
        require "php/view/master.php";
    }

}
