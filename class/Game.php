<?php
class Game {
    private $conn;
    private $table_name = "games";

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create($title, $release_year, $publisher_id, $platform_id) {
        $query = "INSERT INTO " . $this->table_name . " (title, release_year, publisher_id, platform_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$title, $release_year, $publisher_id, $platform_id]);
    }

    // READ (Dengan Relasi JOIN)
    public function read() {
        $query = "SELECT g.id, g.title, g.release_year, p.name as publisher, pl.name as platform 
                  FROM " . $this->table_name . " g
                  JOIN publishers p ON g.publisher_id = p.id
                  JOIN platforms pl ON g.platform_id = pl.id
                  ORDER BY g.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // UPDATE
    public function update($id, $title, $release_year, $publisher_id, $platform_id) {
        $query = "UPDATE " . $this->table_name . " SET title = ?, release_year = ?, publisher_id = ?, platform_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$title, $release_year, $publisher_id, $platform_id, $id]);
    }

    // DELETE
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
    
    // READ ONE (Mengambil satu data spesifik untuk form edit)
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        
        // Langsung return array asosiatif (bukan object stmt) karena datanya pasti hanya 1
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }
}
?>