<?php

include_once 'Category.php';
include_once 'Database.php';

class CategoryFactory {
    
    private function __construct() {
        
    }
    
     public static function getCategories() {
            
        $categories = null;
        $mysqli = Database::connect();
        if (!isset($mysqli)) {
            return null;
        }
        $query = "select id, name from categories";
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0){
            error_log("[getCategories] Errore nella esecuzione della query $mysqli->errno : $mysqli->error", 0);
        } else {
            $categories = array();
            while($row = $result->fetch_array()){
                $categories[] = self::getCategoryFromArray($row);
            }
            return $categories;
        }
    }
    
    public static function getCategoryFromArray($array) {   
        /// controlli mancanti
        $category = new Category();
        $category->setId($array['id']);        
        $category->setName($array['name']);
        return $category;
    }
    
    public static function getCategoryFromId($id) {
        $mysqli = Database::connect();
        if (!isset($mysqli)) {
            return null;
        }

        $query = "select id, name from categories
                  where id = ?";
        
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getCategoryFromId] impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[getCategoryFromId] impossibile effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $user = self::loadUser($stmt);
        $mysqli->close();
        return $user;
    }
}

