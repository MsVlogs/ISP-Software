<?php
class Database
{
    protected $connect;  // Change to protected so subclasses can access it
    private $host = "localhost";
    private $user = "root";
    private $db = "isp_db";
    private $pass = "password";

    public function __construct()
    {
        try {
            // Establishing the PDO connection
            $this->connect = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db, $this->user, $this->pass);
            // Set error mode to exception for better error handling
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection errors
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Method to get the connection
    public function getConnection()
    {
        return $this->connect;
    }
}
