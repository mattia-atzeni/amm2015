<?php

class Settings {
    public static $db_host = 'localhost';
    public static $db_user = 'mooc';
    public static $db_password = 'moocca';
    public static $db_name='mooc';

    public static function getApplicationPath() {
        return "http://localhost/mooc/";
    }  
}

