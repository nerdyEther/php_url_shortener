<?php
// Add these headers at the top of index.php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'Database.php';
require_once 'URLShortener.php';

class URLShortenerAPI {
    private $db;
    private $shortener;

    public function __construct() {
        $this->db = new Database();
        $this->shortener = new URLShortener($this->db);
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        if ($method === 'POST' && $path === '/shorten') {
            $data = json_decode(file_get_contents('php://input'), true);
            $longUrl = $data['url'] ?? '';
            
            if (empty($longUrl)) {
                http_response_code(400);
                echo json_encode(['error' => 'URL is required']);
                return;
            }
            
            $shortCode = $this->shortener->shortenURL($longUrl);
            echo json_encode(['shortCode' => $shortCode]);
            
        } elseif ($method === 'GET' && preg_match('/^\/([a-zA-Z0-9]{6})$/', $path, $matches)) {
            $shortCode = $matches[1];
            $longUrl = $this->shortener->getLongURL($shortCode);
            
            if ($longUrl) {
                header("Location: $longUrl", true, 301);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'URL not found']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
        }
    }
}

// Initialize API and handle request
$api = new URLShortenerAPI();
$api->handleRequest();