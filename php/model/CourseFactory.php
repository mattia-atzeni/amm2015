<?php

include_once 'Database.php';
include_once 'Course.php';
include_once 'CategoryFactory.php';
include_once 'PlatformFactory.php';
include_once 'UserFactory.php';

class CourseFactory {
    
    private function __construct() {
        
    }
    
    public static function getCourseFromArray(&$array) {
        if (isset($array)) {
            $course = new Course();
            if (isset($array['name']) && isset($array['link']) && isset($array['category'])) {
                $course->setName($array['name']);
                $course->setLink($array['link']);
                $course->setCategory(CategoryFactory::getCategoryById($array['category']));
                $course->setPlatform(PlatformFactory::getPlatformByLink($array['link']));
                $course->setOwner(UserFactory::getUserById($_SESSION[BaseController::User]));
                return $course;
            }
        }
        return null;
    }
    
    public static function newCourse(Course $course) {
        $query = "insert into courses (name, link, category_id, owner_id, platform_id)
                  values (?, ?, ?, ?, ?)";
        
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ssiii', 
                $course->getName(),
                $course->getLink(),
                $course->getCategory()->getId(),
                $course->getOwner()->getId(),
                $course->getPlatform()->getId() );
        
        $db->execute();
        $db->close();
        return $db->error();
        
    }
}

