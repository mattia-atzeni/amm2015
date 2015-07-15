<?php

include_once 'Database.php';
include_once 'Course.php';
include_once 'UserFactory.php';
include_once 'CategoryFactory.php';
include_once 'HostFactory.php';


class CourseFactory {
    
    private function __construct() {
        
    }
    
    private static function getCourseFromArray(&$array) {
        $course = new Course();
        $course->setId($array['id']);
        $course->setName($array['name']);
        $course->setLink($array['link']);
        $course->setCategory(CategoryFactory::getCategoryById($array['category_id']));
        $course->setOwner(UserFactory::getUserById($array['owner_id']));
        $course->setHost(HostFactory::getHostById($array['host_id']));
        
        return $course;
    }
    
    public static function saveCourse(Course $course) {
        $query = "insert into courses (name, link, category_id, owner_id, host_id)
                  values (?, ?, ?, ?, ?)";
        
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ssiii', 
                $course->getName(),
                $course->getLink(),
                $course->getCategory()->getId(),
                $course->getOwner()->getId(),
                $course->getHost()->getId() );
        
        $db->execute();
        $db->close();
        return !$db->error();       
    }
    
    public static function &getCoursesByOwnerId($owner_id) {      
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
        return $courses;
    }
    
    public static function &getCourses() {
        $courses = array();
        $db = new Database();
        $db->connect();
        $query = "select * from courses";
        $db->prepare($query);
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        
        return $courses;
    }
    
    public static function getCourseById($id) {
        
        $row = Database::selectById("select * from courses where id = ?", $id);
        
        if (isset($row)) {
            return self::getCourseFromArray($row);
        } else {
            return null;
        }
    }
    
    public static function enroll(User $user, Course $course) {
        $query = "insert into courses_learners (learner_id, course_id) values (?, ?)";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ii', $user->getId(), $course->getId());
        $db->execute();
        $db->close();
        return !$db->error();
    }
    
    public static function isEnrolled(User $user, Course $course) {
        $query = "select * from courses_learners where learner_id = ? and course_id = ?";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ii', $user->getId(), $course->getId());
        $row = $db->fetch();
        $db->close();
        return isset($row);
    }
    
    public static function unenroll(User $user, Course $course) {
        $query = "delete from courses_learners where learner_id = ? and course_id = ?";
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('ii', $user->getId(), $course->getId());
        $db->execute();
        $db->close();
        return !$db->error();
    }
    
    public static function &getCoursesByLearnerId($id) {
        $courses = array();
        $query = "select courses.id, courses.name, courses.link, courses.category_id, courses.host_id, courses.owner_id "
                . "from courses_learners "
                . "join courses on courses.id = courses_learners.course_id "
                . "join users on users.id = courses_learners.learner_id "
                . "where courses_learners.learner_id = ?";
        
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        $db->bind('i', $id);
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        return $courses;
    }
    
    public static function removeCourseById($id) {
        $db = new Database();
        $db->connect();
        $db->prepare("delete from courses_learners where course_id = ?");
        $db->bind('i', $id);
        $db->getMysqli()->autocommit(false);
        $db->execute();
        $db->prepare("delete from courses where id = ?");
        $db->bind('i', $id);
        $db->execute();
        
        if (!$db->error()) {
            if ($db->getStmt()->affected_rows == 1) {
                $db->getMysqli()->commit();
                $db->getMysqli()->autocommit(true);
                $db->close();
                return true;
            }      
        }
        
        $db->getMysqli()->rollback();
        $db->close();
        return false;
    }
    
    private static function buildOrCondition($length, $field) {
        if ($length > 0) {
            $condition = "";
            for ($i = 0; $i < $length - 1; $i++) {
                $condition .= "$field = ? OR ";
            }
            $condition .= "$field = ?";
            return $condition;
        }
        
        return null;
    }
    
    private static function buildBindString($length) {
        $string = "";
        if ($length > 0) {
            for ($i = 0; $i < $length; $i++) {
                $string .= 'i';
            }
        }
        return $string;
    }
    
    public static function &filter($name, &$categories, &$hosts) {
        $categoriesCondition = self::buildOrCondition(count($categories), "category_id");
        $hostsCondition = self::buildOrCondition(count($hosts), "host_id");
        $query = "select * from courses where name like ?";
        
        if (isset($categoriesCondition)) {
            $query .= " AND ($categoriesCondition)";
        }
        
        if (isset($hostsCondition)) {
            $query .= " AND ($hostsCondition)";
        }
        
        $bindString = "s" . self::buildBindString(count($categories)) . self::buildBindString(count($hosts));
        
   
        $args = array_merge(array($bindString, "%$name%"), $categories, $hosts);
        
        $db = new Database();
        $db->connect();
        $db->prepare($query);
        
        call_user_func_array(array($db, 'bind'), $args);
        
        while ($row = $db->fetch()) {
            $courses[] = self::getCourseFromArray($row);
        }
        $db->close();
        return $courses;
    }
}

