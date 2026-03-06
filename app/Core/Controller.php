<?php
/**
 * Base Controller Class
 * Parent class for all controllers
 */

class Controller {
    protected $db;
    protected $data = [];
    protected $model;
    
    public function __construct($params = []) {
        $this->db = Database::getInstance();
        $this->data = [];
    }
    
    /**
     * Load model
     */
    public function model($model) {
        $modelFile = "../app/Models/" . $model . ".php";
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            $modelClass = "App\Models\\" . $model;
            return new $modelClass();
        }
        
        return null;
    }
    
    /**
     * Render view
     */
    public function view($view, $data = []) {
        extract($data);
        
        $viewFile = "../resources/views/" . $view . ".php";
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View file not found: " . $view;
        }
    }
    
    /**
     * Redirect to URL
     */
    public function redirect($url) {
        header("Location: " . $url);
        exit;
    }
    
    /**
     * Redirect back
     */
    public function back() {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    /**
     * Get session flash message
     */
    public function session() {
        return new Session();
    }
    
    /**
     * JSON response
     */
    public function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Sanitize input
     */
    public function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
            return $data;
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Check if request is POST
     */
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    public function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}

