<?php

namespace App\Helper;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use App\Router\Route;

class TwigManager
{
    private static ?Environment $twig = null;

    public static function getInstance(): Environment
    {
        if (self::$twig === null) {
            $viewPath = Route::getViewPath();
            $loader = new FilesystemLoader($viewPath);
            self::$twig = new Environment($loader, [
                'cache' => false, // Set to a cache directory in production
                'debug' => true,
            ]);

            // Add custom filters
            self::$twig->addFilter(new TwigFilter('uppercase', function ($string) {
                return strtoupper($string);
            }));

            self::$twig->addFilter(new TwigFilter('limit', function ($array, $limit) {
                return array_slice($array, 0, $limit);
            }));
        }

        return self::$twig;
    }

    public static function render(string $template, array $data = []): string
    {
        $twig = self::getInstance();
        return $twig->render($template, $data);
    }
}