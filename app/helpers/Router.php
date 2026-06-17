<?php

class Router {
    private $routes = [];

    public function __construct($routes) {
        $this->routes = $routes;
    }

    public function dispatch($uri, $method) {
        // Strip query string completely, only keep the path
        $uriPath = parse_url($uri, PHP_URL_PATH);
        if (!$uriPath) {
            $uriPath = '/';
        }

        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $scriptDir  = rtrim(dirname($scriptName), '/');

        $baseDir = preg_replace('#/public$#i', '', $scriptDir);
        $baseDir = rtrim($baseDir, '/');

        // Remove the script directory prefix or base directory prefix from the URI path
        if (!empty($scriptDir) && $scriptDir !== '/' && strpos($uriPath, $scriptDir) === 0) {
            $uriPath = substr($uriPath, strlen($scriptDir));
        } elseif (!empty($baseDir) && $baseDir !== '/' && strpos($uriPath, $baseDir) === 0) {
            $uriPath = substr($uriPath, strlen($baseDir));
        }

        // Clean slashes and normalize
        $uriPath = trim($uriPath, '/');

        // Empty path = home
        if ($uriPath === '') {
            $uriPath = '/';
        }

        $matched = false;

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routePath => $target) {
                $normRoute = trim($routePath, '/');
                if ($normRoute === '') {
                    $normRoute = '/';
                }

                if ($uriPath === $normRoute) {
                    $matched = true;

                    $controllerName = $target[0];
                    $action         = $target[1];
                    $middleware     = $target[2] ?? null;

                    // Run middleware if required
                    if ($middleware) {
                        $this->runMiddleware($middleware);
                    }

                    // Load base Controller first
                    $baseFile = __DIR__ . '/../controllers/Controller.php';
                    if (file_exists($baseFile)) {
                        require_once $baseFile;
                    }

                    // Load the specific controller
                    $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
                    if (!file_exists($controllerFile)) {
                        $this->abort(500, "Contrôleur introuvable : $controllerName");
                    }

                    require_once $controllerFile;

                    if (!class_exists($controllerName)) {
                        $this->abort(500, "Classe '$controllerName' non définie.");
                    }

                    $instance = new $controllerName();

                    if (!method_exists($instance, $action)) {
                        $this->abort(500, "Méthode '$action' inexistante dans '$controllerName'.");
                    }

                    $instance->$action();
                    return;
                }
            }
        }

        if (!$matched) {
            $this->abort(404, "La page « $uriPath » est introuvable.");
        }
    }

    private function runMiddleware($name) {
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        if ($name === 'auth') {
            AuthMiddleware::handleClient();
        } elseif ($name === 'admin') {
            AuthMiddleware::handleAdmin();
        }
    }

    private function abort($code = 404, $message = '') {
        http_response_code($code);
        $title         = "Erreur $code";
        $error_message = $message;

        $errorFile = __DIR__ . '/../views/errors/error.php';
        if (file_exists($errorFile)) {
            require_once $errorFile;
        } else {
            echo "<!DOCTYPE html><html><head><title>$title</title></head>"
               . "<body style='font-family:sans-serif;text-align:center;padding:60px'>"
               . "<h1>$title</h1><p>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>"
               . "<a href='" . base_url('/') . "'>← Accueil</a></body></html>";
        }
        exit();
    }
}
