<?php
use App\Router\Route;
use App\Controller\WelcomeController;
use function App\Router\view;


Route::get("/", [WelcomeController::class, 'index']);