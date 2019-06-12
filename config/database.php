<?php
session_start();

class Database{
    
    public $conn;
    
    public function getConnection(){
        
        $host = "SQLEXPRESS";
        $username = "sa";
        $password = "password";
        $db_name = "prq";
        
        // $host = "localhost";
        // $username = "root";
        // $password = "";
        // $db_name = "prq";
        
        $this->conn = null;
        try {
            // $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password); 
            $this->conn = new PDO("sqlsrv:Server=$host;Database=$db_name",$username, $password);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connection Established!";
        } catch (PDOException $exception) {
            echo "Database Connection Error!" . $exception->getMessage();
        }
        return $this->conn;
    }
    
}
