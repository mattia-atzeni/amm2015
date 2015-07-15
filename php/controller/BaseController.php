<?php

include_once 'php/model/UserFactory.php';
include_once 'php/view/ViewDescriptor.php';
include_once 'php/model/User.php';

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
                        $this->vd->addErrorMessage('login', "Utente sconosciuto o password errata");
                    }
                    break;
                case "logout":
                    $this->logout();
            }
        }
        
        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[self::User]);
        }
        
        $this->preparePage();
        $vd = $this->vd;
        
        require "php/view/master.php";
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
    
    protected function preparePage() {

        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            switch ($_SESSION[self::Role]) {
                case User::Learner:
                    $this->vd->setPage("learner");
                    $this->vd->setNavigationBar('php/view/learner/navigationBar.php');
                    break;
                case User::Provider:
                    $this->vd->setPage("provider");
                    $this->vd->addScript("js/jquery-2.1.1.min.js");
                    $this->vd->addScript("js/new_course_form.js");
                    $this->vd->setNavigationBar('php/view/provider/navigationBar.php');
                    break;
            }
        } else {
            $this->vd->setPage("login");
        }
        
        $path = "php/view/" . $this->vd->getPage();
        $this->vd->setContent($path . "/content.php");     
    }
}
