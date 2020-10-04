<?php

namespace Database;

class Sqlite implements Db {
    private static $db;
 
    public static function connect() {
    	if (!self::$db) {
            self::$db = new \PDO("sqlite:database.db");
        }
        
        return self::$db;
    }
}