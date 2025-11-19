<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . "/vendor/autoload.php";

// 🧠 Custom fallback autoloader (in case Composer misses App\)
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug: log request URI and session id to help trace redirect loops (remove in production)
error_log("[DEBUG] Request URI: " . ($_SERVER['REQUEST_URI'] ?? '') . " | PHPSESSID: " . session_id());

use App\Router\Route;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use function App\Router\view;

// ------------------------------------------------------------------------

Route::setViewPath(__DIR__ . '/Resources/views');
Route::registerMiddleware('auth', AuthMiddleware::class);
Route::registerMiddleware('guest', GuestMiddleware::class);

// ------------------------------------------------------------------------

include __DIR__ . "/routes/web.php";    

// ------------------------------------------------------------------------
Route::dispatch();