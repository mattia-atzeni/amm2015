<?php
include_once 'BaseController.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/Course.php';
include_once 'php/model/CategoryFactory.php';
include_once 'php/model/PlatformFactory.php';

class ProviderController extends BaseController {
      
    public function __construct() {
        parent::__construct();
    }
    
    public function handleInput() {
        $subpage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage'] : null;
        
        if (isset($subpage)) {
            $this->vd->setSubpage($subpage);
        }
        
        $cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null;
        
        if (isset($cmd)) {
            switch ($cmd) {
                case "save_course":
                    $course = $this->getCourse();
                    if (isset($course)) {
                        if (CourseFactory::saveCourse($course)) {
                            $this->vd->setSubpage(null);
                        } else {
                            $this->vd->addErrorMessage("dberror", "Impossibile salvare il corso");
                        }
                    }
                    break;
                case "cancel":
                    $this->vd->setSubpage(null);
                    break;
            }
        }
        
        $this->showHomePage();
    }
    
    private function getCourse() {
        $course = new Course();
        
        if (isset($_REQUEST['name'])) {
            if (!$course->setName($_REQUEST['name'])) {
                $this->vd->addErrorMessage('name', "Nome non valido");
            }
        }
        
        if (isset($_REQUEST['link'])) {
            if (!$course->setLink($_REQUEST['link'])) {
                $this->vd->addErrorMessage('link', "Link non valido");
            }
        }
        
        if (isset($_REQUEST['category'])) {
            $category = CategoryFactory::getCategoryById($_REQUEST['category']);
            if (isset($category)) {
                $course->setCategory($category);
            } else {
                $this->vd->addErrorMessage('category', "Categoria non valida");
            }
        }
        
        if (count($this->vd->getErrorMessages()) == 0) {
            $platform = PlatformFactory::getPlatformByLink($_REQUEST['link']);
            $course->setPlatform($platform);
            
            $course->setOwner(UserFactory::getUserById($_SESSION[BaseController::User]));
            
            return $course;
        } else {
            return null;
        }   
    }
}
