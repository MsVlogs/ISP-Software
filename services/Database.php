<?php
class Database
{
    protected $connect;
    private $host = "localhost";
    private $user = "root";
    private $db = "isp_db";
    private $pass = "password";

    public function __construct()
    {
        try {
            $this->connect = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=utf8mb4",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Keep database credentials and internal connection details out of the response.
            error_log("Database connection failed: " . $e->getMessage());
            $this->connect = null;
        }
    }

    public function getConnection()
    {
        return $this->connect;
    }

    protected function requireConnection(): PDO
    {
        if (!$this->connect instanceof PDO) {
            throw new RuntimeException("Database connection is unavailable.");
        }

        return $this->connect;
    }
}