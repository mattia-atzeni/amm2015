<?php
include_once './php/Settings.php';

class Database {    
    public static function connect() {
        $mysqli = new mysqli();
        $mysqli->connect(Settings::$db_host, Settings::$db_user, Settings::$db_password, Settings::$db_name);
        if ($mysqli->errno != 0){
            return null;
        } else { 
            return $mysqli;
        }
    }
}

