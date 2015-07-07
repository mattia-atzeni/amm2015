<?php
include_once 'BaseController.php';
include_once 'php/view/ViewDescriptor.php';
include_once 'php/model/CourseFactory.php';
include_once 'php/model/Course.php';
include_once 'php/model/CategoryFactory.php';

class ProviderController extends BaseController {
    public function handleInput() {
        $vd = new ViewDescriptor();
        $subpage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage'] : null;
        
        if (isset($subpage)) {
            switch ($subpage) {
                case "new_course":
                    $categories = CategoryFactory::getCategories();
                    $vd->setContent("php/view/provider/new_course.php");
                    break;
                default: $this->showHomePage($vd);
            }
        }
        
        $cmd = isset($_REQUEST['cmd']) ? $_REQUEST['cmd'] : null;
        
        if (isset($cmd)) {
            switch ($cmd) {
                case "save_course":
                    $course = CourseFactory::buildFromArray($_REQUEST);                    
            }
        }
        
        require "php/view/master.php";
    }
}
