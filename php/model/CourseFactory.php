<?php

include_once 'Database.php';
include_once 'Course.php';

class CourseFactory {
    
    private function __construct() {
        
    }
    
    public static function newCourse(Course $course) {
        $query = "insert into courses (name, link, category_id, owner_id, platform_id)
                  values (?, ?, ?, ?, ?)";
        $this->toDatabase($course, $query);
        
    }
    
    private function toDatabase(Course $course, $query){
        $mysqli = Database::connect();
        if (!isset($mysqli)) {
            error_log("[toDatabase] errore nella connessione al database");
            return 0;
        }

        $stmt = $mysqli->stmt_init();
       
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[toDatabase] impossibile inizializzare il prepared statement");
            $mysqli->close();
            return 0;
        }
        
        $bind = $stmt->bind_param('ssiii', 
                $course->getName(),
                $course->getLink(),
                $course->getCategory(),
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

