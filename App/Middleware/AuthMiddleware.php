<?php
namespace App\Middleware;
class AuthMiddleware
{
    public function handle(array $params, callable $next)
    {
        if (isset($_SESSION['user_id']) || isset($_COOKIE['remember_me'])) {
            return $next($params);
        } else {
            header('Location: /admin/login');
            exit;
        }
    }
}