<?php
/**
 * Auth Middleware
 * Checks if user is logged in
 */

class AuthMiddleware {
    /**
     * Handle authentication check
     */
    public function handle() {
        $session = new Session();
        
        if (!$session->isLoggedIn()) {
            $session->flash('error', 'Vui lòng đăng nhập để tiếp tục');
            header("Location: /websitebatminton/admin/login");
            exit;
        }
        
        return true;
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin() {
        $session = new Session();
        
        return $session->hasRole('admin');
    }
    
    /**
     * Require admin role
     */
    public function requireAdmin() {
        if (!$this->isAdmin()) {
            $session = new Session();
            $session->flash('error', 'Bạn không có quyền truy cập');
            header("Location: /websitebatminton/admin/login");
            exit;
        }
    }
}

