<?php
include_once 'BaseController.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/Course.php';
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/HostFactory.php';

class ProviderController extends BaseController {
      
    public function __construct() {
        parent::__construct();
    }
    
    public function handleInput() {
        if ($this->loggedIn()) {
            $this->vd->setPage("provider");
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $subpage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage'] : "home";

            if (isset($subpage)) {
                $this->vd->setSubpage($subpage);
            }

            $cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null;

            if (isset($cmd)) {
                switch ($cmd) {
                    case "save_course": $this->handleSaveCourseCmd(); break;
                    case "remove":      $this->handleRemoveCmd(); break;
                    case "cancel":      $this->vd->setSubpage("home"); break;
                }
            }
            
            if ($subpage == "home") {
                $this->vd->addScript("js/new_course_form.js");
            }

            $this->preparePage();
            $vd = $this->vd;
            
            require "php/view/master.php";
        }
    }
    
    private function handleSaveCourseCmd() {
        $course = $this->getCourse();
        if (isset($course)) {
            if (CourseFactory::saveCourse($course)) {
                $this->vd->setSubpage("home");
                return;
            } else {
                $this->vd->addErrorMessage("dberror", "Impossibile salvare il corso");
            }
        }
    }
    
    private function handleRemoveCmd() {
        if (isset($_REQUEST['course_id'])) {
            $course_id = $_REQUEST['course_id'];
            if (isset($course_id)) {
                if (CourseFactory::removeCourseById($course_id)) {
                    return;
                }
            }
        }
        
        $this->vd->addErrorMessage("remove", "Errore durante la rimozione del corso");
    }
    
    private function getCourse() {
        $course = new Course();
        $valid = array(
            "name" => false,
            "link" => false,
            "category" => false
        );
        
        if ( isset($_REQUEST['name']) ) {
           if ($course->setName($_REQUEST['name'])) {
                $valid['name'] = true;
           }
        }
        
        if (isset($_REQUEST['link'])) {
            if ($course->setLink($_REQUEST['link'])) {
                $valid['link'] = true;
            }
        }
        
        if (isset($_REQUEST['category'])) {
            $category = CategoryFactory::getCategoryById($_REQUEST['category']);
            if (isset($category)) {
                $valid['category'] = true;
                $course->setCategory($category);
            }
        }
        
        if ($valid['name'] && $valid['link'] && $valid['category']) {
            $host = HostFactory::getHostByLink($_REQUEST['link']);
            $course->setHost($host);
            
            $course->setOwner(UserFactory::getUserById($_SESSION[BaseController::User]));
            
            return $course;
                    
        } else {
            if (!$valid['name']) {
                $this->vd->addErrorMessage('name', 'Nome non valido');
            }

            if (!$valid['link']) {
                $this->vd->addErrorMessage('link', 'Link non valido');
            }

            if (!$valid['category']) {
                $this->vd->addErrorMessage('category', 'Categoria non valida');
            }
            
            return null;
        }   
    }
}
