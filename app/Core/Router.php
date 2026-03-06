<?php
/**
 * Router Class
 * Handles URL routing with MVC pattern
 */

class Router {
    protected $routes = [];
    protected $params = [];
    
    /**
     * Add a route
     */
    public function add($route, $params = []) {
        // Convert route to regex
        if ($route !== '' && $route !== '/') {
            $route = preg_replace('/\//', '\\/', $route);
            $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
            $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
            $route = '/^' . $route . '$/i';
        } else {
            // Root path - match both empty string and /
            $route = '/^(\/?)$/i';
        }
        
        $this->routes[$route] = $params;
    }
    
    /**
     * Add GET route
     */
    public function get($route, $params = []) {
        $this->add($route, $params);
    }
    
    /**
     * Add POST route
     */
    public function post($route, $params = []) {
        $this->add($route, $params);
    }
    
    /**
     * Dispatch the route
     */
    public function dispatch($url) {
        $url = $this->removeQueryString($url);
        
        // Normalize URL - convert empty to '/'
        if ($url === '') {
            $url = '/';
        }
        
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "{$controller}Controller";
            
            if (class_exists($controller)) {
                $controllerObject = new $controller($this->params);
                
                if (isset($this->params['action'])) {
                    $action = $this->params['action'];
                    $action = $this->convertToCamelCase($action);
                    
                    if (method_exists($controllerObject, $action)) {
                        $controllerObject->$action();
                    } else {
                        $this->notFound();
                    }
                } else {
                    $controllerObject->index();
                }
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }
    
    /**
     * Match URL to route
     */
    protected function match($url) {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
    
    /**
     * Remove query string from URL
     */
    protected function removeQueryString($url) {
        if ($url !== '') {
            $parts = explode('?', $url);
            $url = $parts[0];
        }
        return $url;
    }
    
    /**
     * Convert string to StudlyCaps
     */
    protected function convertToStudlyCaps($string) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
    
    /**
     * Convert string to CamelCase
     */
    protected function convertToCamelCase($string) {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
    /**
     * Get current params
     */
    public function getParams() {
        return $this->params;
    }
    
    /**
     * Handle 404 Not Found
     */
    protected function notFound() {
        http_response_code(404);
        echo "404 - Page Not Found";
        exit;
    }
}
