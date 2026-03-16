<?php
// utils/Router.php

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
        $path = parse_url($uri, PHP_URL_PATH);
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $scriptDir = dirname($scriptName);
        
        // Normalize slashes
        $scriptDir = str_replace('\\', '/', $scriptDir);
        
        // If we are in the /public directory but the REQUEST_URI does not contain /public
        // (which happens with the root .htaccess rewrite), we need to check against the parent dir.
        
        // 1. Try exact match (e.g. /smart_revision_planner/public/dashboard)
        if (strpos($path, $scriptDir) === 0) {
            $path = substr($path, strlen($scriptDir));
        } 
        // 2. Try match with parent of public (e.g. /smart_revision_planner/dashboard)
        // Only if scriptDir ends with /public
        elseif (substr($scriptDir, -7) === '/public') {
            $projectRoot = substr($scriptDir, 0, -7); // Remove /public
            if (strpos($path, $projectRoot) === 0) {
                $path = substr($path, strlen($projectRoot));
            }
        }

        // Ensure path starts with / and clean trailing slashes
        if (empty($path)) {
            $path = '/';
        }
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = substr($path, 0, -1);
        } 
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $handler =$route['handler'];
                
                // Explode "Controller@method"
                if (is_string($handler) && strpos($handler, '@') !== false) {
                    list($controllerName, $methodName) = explode('@', $handler);
                    
                    // Include controller file if needed (convention: controllers/Name.php)
                    require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
                    
                    $controller = new $controllerName();
                    return $controller->$methodName();
                } else {
                    // Callable
                    return call_user_func($handler);
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        require_once __DIR__ . '/../views/404.php';
    }
}
