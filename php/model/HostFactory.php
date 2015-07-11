<?php

include_once 'Host.php';
include_once 'Database.php';

class HostFactory {
    public static function getHostByLink($link) {
        $host = parse_url($link, PHP_URL_HOST);
        if (!$host ) {
            $link = "http://" . $link;
            $host = parse_url($link, PHP_URL_HOST);
        }
        
        $db = new Database();
        $db->connect();
        $db->prepare("select * from hosts where link like ?");
        $db->bind("s", "%$host%");

        $row = $db->fetch();
        $db->close();
        
        if (isset($row)) {
            return self::getHostFromArray($row);
        } else {
            $result = new Host();
            $result->setName("altro");
            return $result;
        }
    }
    
    public static function getHostById($id) {
        
        if (!isset($id)) {
            $result = new Host();
            $result->setName("altro");
            return $result;
        }
        
        $row = Database::selectById("select * from hosts where id = ?", $id);
        
        if (isset($row)) {
            return self::getHostFromArray($row);
        } else {
            return null;
        }
    }
    
    private static function getHostFromArray($array) {
        $host = new Host();
        
        if (!isset($array)) {
            return null;
        }
        
        if (isset($array['name']) && isset($array['id'])) {
            $host->setName($array['name']);
            
            if (!$host->setId($array['id'])) {
                return null;
            }
        } else {
            return null;
        }
        
        if (isset($array['link'])) {
            $host->setLink($array['link']);
        }
        
        return $host;
    }
    
    public static function getHosts($limit) {
            
        $categories = array();
        $db = new Database();
        $db->connect();
        $query = "select * from hosts limit ?";
        $db->prepare($query);
        $db->bind('i', $limit);
        while ($row = $db->fetch()) {
            $categories[] = self::getHostFromArray($row);
        }
        $db->close();
        
        return $categories;
    }
}