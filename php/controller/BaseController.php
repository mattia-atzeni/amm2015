<?php

include_once 'php/model/UserFactory.php';
include_once 'php/view/ViewDescriptor.php';

class BaseController {

    const User = 'user';
    const Role = 'role';

    public function __construct() {
        
    }

    public function handleInput() {
        $vd = new ViewDescriptor();
        $user = null;
        if (isset($_REQUEST["cmd"])) {
            switch ($_REQUEST["cmd"]) {
                case 'login':
                    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
                    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
                    if ( $this->login($username, $password) ) {
                        $user = UserFactory::getUserById($_SESSION[self::User]);
                    } else {
                        $vd->setErrorMessage("Utente sconosciuto o password errata");
                    }
                    break;
                case "logout":
                    $this->logout();
            }
        } else {
            if ($this->loggedIn()) {
                $user = UserFactory::getUserById($_SESSION[self::User]);
            }
        }
        $this->showHomePage($vd, $user);
    }

    protected function login($username, $password) {
        $user = UserFactory::login($username, $password);
        if (isset($user)) {
            $_SESSION[self::User] = $user->getId();
            $_SESSION[self::Role] = $user->getRole();
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
        return isset($_SESSION) && array_key_exists(self::User, $_SESSION);
    }
    
    protected function showHomePage($vd, $user) {
        if (!isset($user)) {
            $path = "php/view/login/";
        } else {
            $path = "php/view/provider/";
        }
        
        $vd->setContent($path . "content.php");
        $vd->setNavigationBar($path . "navigationBar.php");
        
        require "php/view/master.php";
    }

}
