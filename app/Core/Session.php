<?php
/**
 * Session Class
 * Handles session management including flash messages
 */

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Set session value
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public function has($key) {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session key
     */
    public function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Set flash message
     */
    public function flash($key, $value = null) {
        if ($value === null) {
            // Get flash message
            if (isset($_SESSION['flash'][$key])) {
                $value = $_SESSION['flash'][$key];
                unset($_SESSION['flash'][$key]);
                return $value;
            }
            return null;
        } else {
            // Set flash message
            if (!isset($_SESSION['flash'])) {
                $_SESSION['flash'] = [];
            }
            $_SESSION['flash'][$key] = $value;
        }
    }
    
    /**
     * Check if flash message exists
     */
    public function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }
    
    /**
     * Get current user
     */
    public function user($key = null) {
        if ($key === null) {
            return $_SESSION['user'] ?? null;
        }
        return $_SESSION['user'][$key] ?? null;
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }
    
    /**
     * Check user role
     */
    public function hasRole($role) {
        return $this->isLoggedIn() && ($_SESSION['user']['role'] ?? '') === $role;
    }
    
    /**
     * Destroy session
     */
    public function destroy() {
        session_destroy();
    }
}

