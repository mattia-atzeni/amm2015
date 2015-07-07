<?php

include_once './php/model/UserFactory.php';
include_once './php/view/ViewDescriptor.php';

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
                    $this->login($vd, $username, $password);
                    if ($this->loggedIn()) {
                        $this->showHome($vd);
                        //$user = UserFactory::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
                    } else {
                        $this->showLoginPage($vd);
                    }
                    break;
                case 'logout':
                    $this->logout($vd);
                default : $this->showLoginPage($vd);
            }
        } else {
            if ($this->loggedIn()) {
            //$user = UserFactory::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
                $this->showHome($vd);
            } else {
            $this->showLoginPage($vd);
            }
        }

        require "./php/view/master.php";
    }

    protected function login($vd, $username, $password) {
        $user = UserFactory::login($username, $password);
        if (isset($user)) {
            $_SESSION[self::user] = $user->getId();
            $vd->setErrorMessage("OK");
        } else {
            $vd->setErrorMessage("Utente sconosciuto o password errata");
        }
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

    private function showLoginPage($vd) {
        $vd->setTitle("login");
        $vd->setNavigationBar("./php/view/login/navigationBar.php");
        $vd->setContent("./php/view/login/content.php");
    }
    
    private function showHome($vd) {
        $vd->setContent("./php/view/user/content.php");
        $vd->setNavigationBar("./php/view/login/navigationBar.php");
    }

}
