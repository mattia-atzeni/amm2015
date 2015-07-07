<?php
include_once './php/Settings.php';

class Database {    
    public static function connect() {
        $mysqli = new mysqli();
        $mysqli->connect(Settings::$db_host, Settings::$db_user, Settings::$db_password, Settings::$db_name);
        if ($mysqli->errno != 0) {
            $header = self::buildErrorMessageHeader();
            error_log ("$header errore nella connessione al database - ($mysqli->connect_errno) : $mysqli->connect_error");
            return null;
        } else { 
            return $mysqli;
        }
    }
    
    public static function prepareStatement($mysqli, $query) {
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            $header = self::buildErrorMessageHeader();
            error_log("$header impossibile inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        
        return $stmt;
    }
    
    public static function inputBind($stmt) {
        $args = func_get_args();
        array_splice($args, 0, 1);
        $ref = new ReflectionClass('mysqli_stmt');
        $method = $ref->getMethod("bind_param");
        if (!$method->invokeArgs($stmt, $args) ) {
            $header = self::buildErrorMessageHeader();
            error_log("$header impossibile effettuare il binding in input");
            return null;
        } 
       /* if ( !call_user_func_array(array($stmt, "bind_param"), self::toReference($args)) ) {
            $header = self::buildErrorMessageHeader();
            error_log("$header impossibile effettuare il binding in input");
            return null;
        }*/
    }
   /*  
    private static function toReference($array) {
        $result = array();
        
        foreach ($array as $key => $value) {
            $result[$key] = &$array[$key];
        }
        
        return $result;
    }
   */
    
    private static function buildErrorMessageHeader() {
        $trace = debug_backtrace();
        $caller = $trace[2];
        $class = "";

        if (isset($caller['class'])) {
            $class = "{$caller['class']} : ";
        }
        
        $header = "[$class{$caller['function']}]";
        return $header;
    }
    
    private static function prova() {
        $arg_list = func_get_args();
        $numargs = func_num_args();
        for ($i = 0; $i < $numargs; $i++) {
            echo "Argument $i is: " . $arg_list[$i] . "\n";
        }
    }
}

