<?php
class URLShortener {
    private $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    public function shortenURL($longUrl) {
        // Check if URL already exists
        $stmt = $this->db->query(
            "SELECT short_code FROM urls WHERE long_url = ?",
            [$longUrl]
        );
        
        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $result['short_code'];
        }
        
        // Generate new short code
        do {
            $shortCode = $this->generateShortCode();
            $stmt = $this->db->query(
                "SELECT id FROM urls WHERE short_code = ?",
                [$shortCode]
            );
        } while ($stmt->fetch());
        
        // Save new URL
        $this->db->query(
            "INSERT INTO urls (short_code, long_url) VALUES (?, ?)",
            [$shortCode, $longUrl]
        );
        
        return $shortCode;
    }
    
    public function getLongURL($shortCode) {
        $stmt = $this->db->query(
            "SELECT long_url FROM urls WHERE short_code = ?",
            [$shortCode]
        );
        
        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $result['long_url'];
        }
        
        return null;
    }
    
    private function generateShortCode($length = 6) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $code;
    }
}