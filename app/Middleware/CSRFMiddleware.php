<?php
/**
 * CSRF Middleware
 * Validates CSRF token on POST requests
 */

class CSRFMiddleware {
    /**
     * Handle CSRF validation
     */
    public function handle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            
            if (!CSRF::validate($token)) {
                $session = new Session();
                $session->flash('error', 'Token không hợp lệ. Vui lòng thử lại.');
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        
        return true;
    }
    
    /**
     * Skip CSRF check for specific routes
     */
    public function skip($routes = []) {
        $currentRoute = $_GET['url'] ?? '';
        
        foreach ($routes as $route) {
            if (strpos($currentRoute, $route) !== false) {
                return true;
            }
        }
        
        return false;
    }
}

