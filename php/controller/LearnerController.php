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
        if ($this->loggedIn()) {
            $user = UserFactory::getUserById($_SESSION[BaseController::User]);
            $subpage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage'] : "home";

            if (isset($subpage)) {
                $this->vd->setSubpage($subpage);
            }

            $cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null;

            if (isset($cmd)) {
                switch ($cmd) {
                    case "join":
                        if (isset($_REQUEST['course_id'])) {
                            $course = CourseFactory::getCourseById($_REQUEST['course_id']);
                            if (isset($course)) {
                                if (CourseFactory::enroll($user, $course)) {
                                    break;
                                }
                            }
                        }
                        $this->vd->addErrorMessage("enrollment", "Impossibile completare l'iscrizione al corso");
                        break;
                }
            }
        }
        
        $this->showHomePage();
    }
}
