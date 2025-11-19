<?php
use App\Router\Route;
use function App\Router\view;


Route::get("/", function () {
    return view("home");
});