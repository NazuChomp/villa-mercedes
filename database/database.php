<?php

function connection() {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'villa_mercedes_resort';
    
    static $conn = new mysqli($host, $user, $pass, $dbname);
    
    if($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}
