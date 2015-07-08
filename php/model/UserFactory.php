<?php
include_once 'Database.php';
include_once 'User.php';

class UserFactory {
    
    private function __construct() {
        
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
            $user = self::getUserFromArray($row);
        } else {
            return null;
        }
        return $user;
    }
    
    /*private function loadUser(mysqli_stmt $stmt) {
        
        if (!$stmt->execute()) {
            error_log("[loadUser] impossibile eseguire lo statement");
            return null;
        }
        $row = array();
        
        $bind = Database::outputBind(
                    $stmt,
                    $row['id'], 
                    $row['username'], 
                    $row['password'], 
                    $row['first_name'], 
                    $row['last_name'],
                    $row['email'],
                    $row['isProvider'] );
        
        if (!$bind) {
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }
        
    }*/

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
    
    public static function getUserFromId($id) {
        $db = new Database();
        $db->connect();
        $db->prepare("select * from users where id = ?");
        $db->bind('i', $id);
        $row = $db->fetch();
        $db->close();
        
        if (isset($row)) {
            return self::getUserFromArray($row);
        } else {
            echo "oh-oh<br/>$id";
            return null;
        }
    }
    
}

