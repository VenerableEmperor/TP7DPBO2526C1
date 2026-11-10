<?php
class Database {
    private $host = "localhost";
    private $db_name = "game_library";
    private $username = "root"; // sesuaikan
    private $password = "";     // sesuaikan
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Menggunakan PDO 
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>