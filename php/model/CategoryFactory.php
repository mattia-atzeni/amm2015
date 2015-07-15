<?php

include_once 'Category.php';
include_once 'Database.php';

class CategoryFactory {
    
    private function __construct() {
        
    }
    
    public static function &getCategories() {
            
        $categories = array();
        $db = new Database();
        $db->connect();
        $query = "select * from categories";
        $db->prepare($query);
        
        while ($row = $db->fetch()) {
            $categories[] = self::getCategoryFromArray($row);
        }
        $db->close();
        
        return $categories;
    }
    
    
    
    private static function getCategoryFromArray(&$array) {   
        $category = new Category();
        $category->setId($array['id']);        
        $category->setName($array['name']);
        return $category;
    }
    
    public static function getCategoryById($id) {
        $query = "select id, name from categories
                  where id = ?";
        
        $row = Database::selectById($query, $id);
        
        if (isset($row)) {
            return self::getCategoryFromArray($row);
        } else {
            return null;
        }   
    }
}

