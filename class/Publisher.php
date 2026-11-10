<?php
class Publisher {
    private $conn;
    private $table_name = "publishers";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Asumsi Create, Update, Delete untuk Publisher memiliki format prepared statement sama seperti Game.
}
?>