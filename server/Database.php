<?php
class Database {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=url_shortener",
                "root",
                "826004",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Create table if it doesn't exist
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS urls (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    short_code VARCHAR(6) UNIQUE,
                    long_url TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}