<?php

include_once 'Database.php';
include_once 'Course.php';
include_once 'CategoryFactory.php';

class CourseFactory {
    
    private function __construct() {
        
    }
    
    public static function getCourseFromArray(&$array) {
        if (isset($array)) {
            $course = new Course();
            if (isset($array['name']) && isset($array['link']) && isset($array['category'])) {
                $course->setName($array['name']);
                $course->setLink($array['link']);
                $course->setCategory(CategoryFactory::getCategoryFromId($array['category']));
            }
        }
    }
    
    public static function newCourse(Course $course) {
        $query = "insert into courses (name, link, category_id, owner_id, platform_id)
                  values (?, ?, ?, ?, ?)";
        self::toDatabase($course, $query);
        
    }
    
    private static function toDatabase(Course $course, $query){
        $mysqli = Database::connect();
        if (!isset($mysqli)) {
            return 0;
        }

        $stmt = Database::prepareStatement($mysqli, $query);
        if (!isset($stmt)) {
            return 0;
        }
        
        $bind = $stmt->bind_param('ssiii', 
                $course->getName(),
                $course->getLink(),
                $course->getCategory()->getId(),
                $course->getOwner(),
                $course->getPlatform());
        
        if (!$bind) {
            error_log("[toDatabase] impossibile effettuare il binding in input");
            $mysqli->close();
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[toDatabase] impossibile eseguire lo statement");
            $mysqli->close();
            return 0;
        }

        $mysqli->close();
        return $stmt->affected_rows;
    }
}

