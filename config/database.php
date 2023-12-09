<?php
class Database
{
    private $host = "h22.seohost.pl";
    private $db_name = "srv40917_stomatologia";
    private $username = "srv40917_stomatologia";
    private $password = "php2023";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
