<?php
/**
 * CSRF Token Class
 * Handles CSRF token generation and validation
 */

class CSRF {
    private static $tokenName = 'csrf_token';
    
    /**
     * Generate CSRF token
     */
    public static function generate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[self::$tokenName])) {
            $_SESSION[self::$tokenName] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION[self::$tokenName];
    }
    
    /**
     * Get CSRF token
     */
    public static function token() {
        return self::generate();
    }
    
    /**
     * Validate CSRF token
     */
    public static function validate($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[self::$tokenName])) {
            return false;
        }
        
        return hash_equals($_SESSION[self::$tokenName], $token);
    }
    
    /**
     * Get token input field HTML
     */
    public static function field() {
        return '<input type="hidden" name="csrf_token" value="' . self::token() . '">';
    }
    
    /**
     * Get token header name
     */
    public static function getTokenName() {
        return self::$tokenName;
    }
}

