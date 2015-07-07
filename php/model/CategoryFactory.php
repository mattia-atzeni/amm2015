<?php

include_once 'Category.php';

class CategoryFactory {
    
    private function __construct() {
        
    }
    
     public static function getCategories() {
        $categories = null;
        $mysqli = Database::connect();
        if (!isset($mysqli)) {
            error_log("[toDatabase] errore nella connessione al database");
            return 0;
        }
        $query = "select id, name from categories";
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0){
            error_log("Errore nella esecuzione della query $mysqli->errno : $mysqli->error", 0);
        } else {
            $categories = array();
            while($row = $result->fetch_array()){
                $categories[] = self::buildCategoryFromArray($row);
            }
            return $categories;
        }
    }
    
    private function buildCategoryFromArray($array) {
        $category = new Category();
        $category->setId($array['id']);        
        $category->setName($array['name']);
        return $category;
    }
}

