<?php
session_start();
// Carrega variáveis do .env manualmente
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        if (!getenv($name)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}
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