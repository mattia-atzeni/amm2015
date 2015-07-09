<?php

include_once 'Platform.php';
include_once 'Database.php';

class PlatformFactory {
    public static function getPlatformByLink($link) {
        $host = parse_url($link, PHP_URL_HOST);
        if (!$host ) {
            $link = "http://" . $link;
            $host = parse_url($link, PHP_URL_HOST);
        }
        
        $db = new Database();
        $db->connect();
        
        if ($host) {
            $db->prepare("select * from platforms where link like ?");
            $db->bind("s", "%$host%");
        } else {
            $db->prepare("select * from platforms where name = Altro");
        }
        
        $row = $db->fetch();
        $db->close();
        
        if (isset($row)) {
            return self::getPlatformFromArray($row);
        } else {
            return null;
        }
    }
    
    public static function getPlatformById($id) {
        $db = new Database();
        $db->connect();
        $db->prepare("select * from platforms where id = ?");
        $db->bind('i', $id);
        $row = $db->fetch();
        $db->close();
        
        if (isset($row)) {
            return self::getPlatformFromArray($row);
        } else {
            return null;
        }
    }
    
    private static function getPlatformFromArray($array) {
        $platform = new Platform();
        
        if (!isset($array)) {
            return null;
        }
        
        if (isset($array['name']) && isset($array['id'])) {
            $platform->setName($array['name']);
            
            if (!$platform->setId($array['id'])) {
                return null;
            }
        } else {
            return null;
        }
        
        if (isset($array['link'])) {
            $platform->setLink($array['link']);
        }
        
        return $platform;
    }
}