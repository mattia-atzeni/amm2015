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
        $this->vd->setPage("learner");
        
        if ($this->loggedIn()) {
            $subpage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage'] : "home";

            if (isset($subpage)) {
                $this->vd->setSubpage($subpage);
            }

            $cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null;

            if (isset($cmd)) {
                switch ($cmd) {
                    case "join": $this->handleJoinCmd(); break;
                    case "uneroll": $this->handleUnerollCmd(); break;
                        
                }
            }
        }
        
        $this->showPage();
    }
    
    private function handleJoinCmd() {
        if (isset($_REQUEST['course_id'])) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $course = CourseFactory::getCourseById($_REQUEST['course_id']);
            if (isset($course)) {
                if (CourseFactory::enroll($user, $course)) {
                    return;
                } elseif (CourseFactory::isEnrolled($user, $course)) {
                    $this->vd->addErrorMessage("enrollment", "Sei già iscritto a questo corso");
                    return;
                }
            }
        }
        
        $this->vd->addErrorMessage("enrollment", "Impossibile completare l'iscrizione al corso");
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
        
        $this->vd->addErrorMessage("uneroll", "Impossibile abbandonare il corso");
    }
}