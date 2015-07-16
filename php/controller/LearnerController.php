<?php

include_once 'BaseController.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/Course.php';
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/HostFactory.php';

class LearnerController extends BaseController {
      
    public function __construct() {
        parent::__construct();
    }
    
    public function handleInput() {
        $user = null;
        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            
            if (isset($_REQUEST['cmd'])) {
                switch ($_REQUEST['cmd']) {
                    case "join":    $this->handleJoinCmd(); break;
                    case "uneroll": $this->handleUnerollCmd(); break;
                    case "filter":  $courses = $this->handleFilterCmd(); break;
                        
                }
            }
            
            $subpage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage'] : "home";

            if (isset($subpage)) {
                $this->vd->setSubpage($subpage);
                switch ($subpage) {
                    case "home":    $courses = CourseFactory::getCoursesByLearnerId($user->getId()); break;
                    case "catalog": $courses = CourseFactory::getCourses(); break;
                    case "filter":  $this->vd->addScript("js/jquery-2.1.1.min.js");
                                    $this->vd->addScript("js/filter.js");
                                    $categories = CategoryFactory::getCategories();
                                    if ($this->vd->isJson()) {
                                        $this->vd->setSubpage('courses_filter_json');
                                    }
                                    break;
                }
            }
        }
        
        $this->preparePage($user);
        $hosts = HostFactory::getHosts(5);
        $vd = $this->vd;
        require "php/view/master.php";
    }
    
    private function handleJoinCmd() {
        if (isset($_REQUEST['course_id'])) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $course = CourseFactory::getCourseById($_REQUEST['course_id']);
            if (isset($course)) {
                if (CourseFactory::enroll($user, $course)) {
                    return;
                } elseif (CourseFactory::isEnrolled($user, $course)) {
                    $this->vd->addErrorMessage("Sei già iscritto a questo corso");
                    return;
                }
            }
        }
        
        $this->vd->addErrorMessage("Impossibile completare l'iscrizione al corso");
    }
    
    private function handleUnerollCmd() {
        if (isset($_REQUEST['course_id'])) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $course = CourseFactory::getCourseById($_REQUEST['course_id']);
            if (isset($course)) {
                if (CourseFactory::unenroll($user, $course)) {
                    return;
                }
            }
        }
        
        $this->vd->addErrorMessage("Impossibile abbandonare il corso");
    }
    
    private function &handleFilterCmd() {
        
        if (isset($_REQUEST['name']) ) {
            $name = $_REQUEST['name'];
        } else {
            $name = '';
        }
        
        $categories = array();
        if (isset($_REQUEST['categories'])) {
            foreach ($_REQUEST['categories'] as $category) {
                $tmp = filter_var($category, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                if (isset($tmp)) {
                    $categories[] = $tmp;
                } else {
                    $this->vd->addErrorMessage("$category: categoria non valida");
                    break;
                }
            }
        }
        
        $hosts = array();
        if (isset($_REQUEST['hosts'])) {
            foreach ($_REQUEST['hosts'] as $host) {
                $tmp = filter_var($host, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                if (isset($tmp)) {
                    $hosts[] = $tmp;
                } else {
                    $this->vd->addErrorMessage("$host: host non valido");
                    break;
                }
            }
        }
        
        $this->vd->toggleJson();
        
        return CourseFactory::filter($name, $categories, $hosts);               
    }
}
