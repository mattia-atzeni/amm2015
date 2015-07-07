<?php
include_once 'Database.php';
include_once 'User.php';

class UserFactory {
    
    private function __construct() {
        
    }
    
    public static function login($username, $password) {
        $mysqli = Database::connect();
        if (!isset($mysqli)) {
            error_log("[login] impossibile connettersi al database");
            $mysqli->close();
            return null;
        }

        $query = "select id, username, password, first_name, last_name, email, isProvider from users
                  where username = ? and password = ?";
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[login] impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[login] impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $user = self::loadUser($stmt);
        $mysqli->close();
        return $user;
    }
    
    private function loadUser(mysqli_stmt $stmt) {
        
        if (!$stmt->execute()) {
            error_log("[loadUser] impossibile eseguire lo statement");
            return null;
        }
        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['username'], 
                $row['password'], 
                $row['first_name'], 
                $row['last_name'],
                $row['email'],
                $row['isProvider'] );
        
        if (!$bind) {
            error_log("[loadUser] impossibile effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::buildUser($row);
    }

    private function buildUser($row) {
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
    
}

