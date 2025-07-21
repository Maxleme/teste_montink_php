<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
// Carrega rotas
$routes = require __DIR__ . '/routes.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = dirname($_SERVER['SCRIPT_NAME']);
if ($base !== '/' && strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}
$uri = trim($uri, '/');

if (isset($routes[$uri])) {
    [$controller, $action, $params] = $routes[$uri];
} else {
    // fallback: /controller/acao/param1/param2
    $segments = explode('/', $uri);
    $controller = !empty($segments[0]) ? ucfirst($segments[0]) : 'Produto';
    $action = !empty($segments[1]) ? $segments[1] : 'index';
    $params = array_slice($segments, 2);
}
$controllerClass = 'App\\Controllers\\' . $controller . 'Controller';
if (class_exists($controllerClass)) {
    $ctrl = new $controllerClass();
    if (method_exists($ctrl, $action)) {
        call_user_func_array([$ctrl, $action], $params);
        exit;
    }
}
http_response_code(404);
echo 'Página não encontrada.'; 