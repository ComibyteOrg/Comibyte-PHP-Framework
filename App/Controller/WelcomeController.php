<?php

namespace App\Controller;

use function App\Router\view;

class WelcomeController
{
    public function index()
    {
        // This demonstrates how simple it is to pass data to Twig templates
        $data = [
            'framework' => 'Comibyte PHP Mini Framework',
            'features' => [
                'Elegant Routing Engine',
                'Controller Support',
                'Middleware System',
                'Twig Template Engine',
                'Database Abstraction',
                'Rich Helper Library'
            ],
            'benefits' => [
                'Lightweight and fast',
                'Easy to learn and use',
                'Modern development practices',
                'Secure by default',
                'Well-documented'
            ]
        ];

        // Just call view('welcome') and it will automatically use welcome.twig!
        return view('welcome', $data);
    }
}