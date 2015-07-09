<?php

include_once 'php/model/UserFactory.php';
include_once 'php/view/ViewDescriptor.php';

class BaseController {

    const User = 'user';
    const Role = 'role';
    protected $vd;

    public function __construct() {
        $this->vd = new ViewDescriptor();
    }

    public function handleInput() {
        if (isset($_REQUEST["cmd"])) {
            switch ($_REQUEST["cmd"]) {
                case 'login':
                    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
                    $password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
                    if ( !$this->login($username, $password) ) {
                        $this->vd->addErrorMessage('username-password', "Utente sconosciuto o password errata");
                    }
                    break;
                case "logout":
                    $this->logout();
            }
        }
        
        $this->showHomePage();
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
    
    protected function showHomePage() {
        if (!$this->loggedIn()) {
            $path = "php/view/login/";
        } else {
            $user = UserFactory::getUserById($_SESSION[self::User]);
            $path = "php/view/provider/";
        }
        
        $this->vd->setContent($path . "content.php");
        $this->vd->setNavigationBar($path . "navigationBar.php");
        
        $vd = $this->vd;
        
        require "php/view/master.php";
    }

}
