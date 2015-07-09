<?php

include_once 'Database.php';
include_once 'Course.php';
include_once 'UserFactory.php';
include_once 'CategoryFactory.php';
include_once 'PlatformFactory.php';


class CourseFactory {
    
    private function __construct() {
        
    }
    
    private static function getCourseFromArray(&$array) {
        $course = new Course();
        
        $course->setName($array['name']);
        $course->setLink($array['link']);
        $course->setCategory(CategoryFactory::getCategoryById($array['category_id']));
        $course->setOwner(UserFactory::getUserById($array['owner_id']));
        $course->setPlatform(PlatformFactory::getPlatformById($array['platform_id']));
        
        return $course;
    }
    
    public static function saveCourse(Course $course) {
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
        return !$db->error();       
    }
    
    public static function getCoursesByOwner($owner_id) {
        
        $courses = array();
        $query = "select * from courses where owner_id = ?";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('i', $owner_id );
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        if (!$db->error()) {
            return $courses;
        } else {
            return null;
        }
    }
}

