<?php

include_once 'php/model/UserFactory.php';
include_once 'php/view/ViewDescriptor.php';
include_once 'php/model/User.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/HostFactory.php';
include_once 'php/model/CategoryFactory.php';


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
        
        $user = null;
        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[self::User]);
        }
        
        $this->preparePage($user);
        $this->showPage($user);
        
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
    
    private function showProviderPage($user) {
        $courses = CourseFactory::getCoursesByOwnerId($user->getId());
        $categories = CategoryFactory::getCategories();
        $vd = $this->vd;
        $hosts = HostFactory::getHosts(5);
        require 'php/view/master.php';
    }
    
    private function showLearnerPage($user) {
        $courses = CourseFactory::getCoursesByLearnerId($user->getId());  
        $vd = $this->vd;
        $hosts = HostFactory::getHosts(5);
        require 'php/view/master.php';
    }
    
    protected function preparePage($user) {
        if (isset($user)) {
            switch ($user->getRole()) {
                case User::Learner:
                    $this->vd->setPage("learner");
                    $this->vd->setNavigationBar('php/view/learner/navigationBar.php');
                    $this->vd->setContent('php/view/learner/content.php');
                    return;
                case User::Provider:
                    $this->vd->setPage("provider");
                    $this->vd->addScript("js/jquery-2.1.1.min.js");
                    $this->vd->addScript("js/new_course_form.js");
                    $this->vd->setNavigationBar('php/view/provider/navigationBar.php');
                    $this->vd->setContent('php/view/provider/content.php');
                    return;
            }
        } else {
            $this->vd->setContent("php/view/login/content.php");
        } 
    }
    
    private function showPage($user) {
        if (isset($user)) {
            switch ($user->getRole()) {
                case User::Learner:
                    $this->showLearnerPage($user);
                    return;
                case User::Provider:
                    $this->showProviderPage($user);
                    return;
            }
         } else {
             $vd = $this->vd;
             $hosts = HostFactory::getHosts(5);
             require "php/view/master.php";
         }
    }
}
