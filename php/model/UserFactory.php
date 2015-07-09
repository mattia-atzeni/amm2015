<?php
include_once 'Database.php';
include_once 'User.php';

class UserFactory {
    
    private function __construct() {
        
    }
    
    private static function getUserFromArray($row) {
        $user = new User();
        $user->setId($row["id"]);
        $user->setFirstName($row['first_name']);
        $user->setLastName($row['last_name']);
        $user->setEmail($row['email']);
        $user->setUsername($row['username']);
        $user->setPassword($row['password']);
        
        if ($row['isProvider']) {
            $user->setRole(User::Provider);
        } else {
            $user->setRole(User::Learner);
        }
        return $user;
    }
    
    public static function login($username, $password) {
        $db = new Database();
        $db->connect();
        $query = "select id, username, password, first_name, last_name, email, isProvider from users
                  where username = ? and password = ?";
 
        $db->prepare($query);
        $db->bind('ss', $username, $password);
        $row = $db->fetch();
        $db->close();
        if (isset($row)) {
            return self::getUserFromArray($row);
        } else {
            return null;
        }
    }
    
    public static function getUserById($id) {
        $db = new Database();
        $db->connect();
        $db->prepare("select * from users where id = ?");
        $db->bind('i', $id);
        $row = $db->fetch();
        $db->close();
        
        if (isset($row)) {
            return self::getUserFromArray($row);
        } else {
            return null;
        }
    }
    
}

