# Comibyte PHP Framework

<div align="center">
  <img src="https://i.imgur.com/your-image-url.png" alt="Comibyte Welcome Page" width="700">
  <img src="https://imgs.search.brave.com/a2QJ4QGpzGpXeDGHk1c-pL3FdZ-v47YnUIxeu4pjCe4/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9vbHV3/YWRpbXUtYWRlZGVq/aS53ZWIuYXBwL2lt/YWdlcy9sb2dvLnBu/Zw" alt="Comibyte Welcome Page" width="400">
  <br>
  <p>
    <img src="https://img.shields.io/badge/PHP-8.2%2B-blue?style=for-the-badge&logo=php" alt="PHP Version">
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
    <img src="https://img.shields.io/badge/Status-In%20Development-orange?style=for-the-badge" alt="Status">
  </p>
</div>

Welcome to the Comibyte PHP Framework! A lightweight, modern, and intuitive mini-framework designed for rapid development of PHP applications. It provides a solid foundation with essential tools like a powerful router, a simple view engine, and a rich set of helper functions, allowing you to build elegant applications with minimal setup.

This framework is inspired by the simplicity and elegance of frameworks like Laravel, aiming to provide a productive and enjoyable development experience.

---

## Table of Contents

- [Core Features](#features)
- [Installation](#installation)
- [Project Structure](#structure)
- [Core Concepts](#concepts)
  - [Routing](#routing)
  - [Controllers](#controllers)
  - [Middleware](#middleware)
  - [Views](#views)
  - [Models & Database](#models)
  - [CSRF Protection](#csrf-protection)
  - [Email Service](#email)
- [Helper Functions Guide](#helpers)
  - [Debugging](#debugging)
  - [Configuration & Paths](#paths)
  - [URL & Assets](#urls)
  - [Request & Response](#requests)
  - [Session & Forms](#sessions)
  - [Security](#security)
  - [Content & Strings](#strings)
  - [Arrays](#arrays)
- [Contributing](#contribute)
- [License](#license)

---

<a name="features"></a>
## ✨ Core Features

- **Elegant Routing Engine**: Define clean, simple routes for your application.
- **Controller Support**: Organize your code with MVC-style controllers.
- **Middleware Support**: Protect your routes with middleware for authentication, logging, and more.
- **Simple View Engine**: Easily render PHP views with data.
- **Rich Helper Library**: A comprehensive set of helper functions to speed up common tasks.
- **Environment Configuration**: Uses `.env` files for easy management of application configuration.
- **CSRF Protection**: Built-in helpers to protect your forms from cross-site request forgery.
- **Integrated Mailer**: A simple wrapper around PHPMailer to send emails easily.
- **JSON Responses**: Simple helpers for building APIs.

<a name="installation"></a>
## Requirements

- PHP >= 7.4
- Composer
- A local web server (e.g., Apache, Nginx) or PHP's built-in server.

### 🚀 Installation

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/ComibyteOrg/Comibyte-PHP-Framework.git
    cd comibyte-framework
    ```

2.  **Install Dependencies:**
    This will install PHPMailer, DotEnv, and other necessary packages.
    ```bash
    composer install
    ```

3.  **Set Up Your Environment:**
    -   Copy the `.env.example` file to a new file named `.env`. This file will hold your application secrets and environment-specific settings.
        ```bash
        cp .env.example .env
        ```
    -   Open the `.env` file and configure your application settings, especially `APP_URL` and your mail server credentials if you plan to send emails.
        ```ini
        # Application
        APP_NAME="Comibyte"
        APP_URL=http://localhost:8000

        # Mail Server
        MAIL_HOST=smtp.mailtrap.io
        MAIL_PORT=2525
        MAIL_USERNAME=your-mailtrap-username
        MAIL_PASSWORD=your-mailtrap-password
        MAIL_FROM_ADDRESS="hello@comibyte.com"
        MAIL_FROM_NAME="${APP_NAME}"
        ```

4.  **Start the Local Development Server:**
    You can use PHP's built-in server for quick development.
    ```bash
    php -S localhost:8000 -t .
    ```

Now, visit `http://localhost:8000` in your browser to see the welcome page!

<a name="structure"></a>
## 📁 Project Structure

```
├── App/
│   ├── Helper/
│   │   └── Helper.php      # Core helper functions
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   └── GuestMiddleware.php
│   ├── Model/
│   │   └── Admin.php       # Example model
│   └── Router/
│       └── Route.php       # The routing engine
├── Resources/
│   └── views/              # Your view files (.php)
│       └── home.php
├── routes/
│   └── web.php             # Web route definitions
├── vendor/                 # Composer dependencies
├── .env                    # Environment configuration
├── .env.example            # Example environment file
├── composer.json           # Composer dependency list
├── index.php               # Application entry point
└── README.md               # This file
```

## Core Concepts

### Routing

All web routes are defined in `routes/web.php`. The router supports basic HTTP verbs.

**Basic GET Route:**
```php
// routes/web.php
use App\Router\Route;
use function App\Router\view;

Route::get('/', function () {
    return view('home');
});
```

**Route with Parameters:**
```php
Route::get('/users/{id}', function ($id) {
    // Logic to fetch user with $id
    return "User ID: " . $id;
});
```

### Views

Views are simple PHP files located in the `Resources/views` directory. You can render a view using the `view()` function.

**Rendering a View:**
```php
Route::get('/', function () {
    // Renders Resources/views/home.php
    return view('home');
});
```

**Passing Data to a View:**
```php
// In your route
Route::get('/profile', function () {
    $user = ['name' => 'John Doe'];
    return view('profile', ['user' => $user]);
});

// In Resources/views/profile.php
<h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
```

You can also use "dot" notation for views in subdirectories.
```php
// Renders Resources/views/admin/dashboard.php
return view('admin.dashboard');
```

### Middleware

Middleware provides a mechanism for filtering HTTP requests entering your application. For example, an `auth` middleware can verify the user is authenticated before they can access a route.

**Registering Middleware:**
Middleware is registered in `index.php`.
```php
// index.php
Route::registerMiddleware('auth', AuthMiddleware::class);
```

**Assigning Middleware to Routes:**
You can assign middleware to a route using the `middleware()` method.
```php
Route::get('/dashboard', function () {
    // Only authenticated users can access this
})->middleware('auth');
```

### CSRF Protection

The framework provides an easy way to protect your application from cross-site request forgery (CSRF) attacks.

1.  **Add the CSRF field to your form:**
    Use the `csrf_field()` helper inside any `<form>` tag.
    ```php
    <form method="POST" action="/profile">
        <?php echo App\Helper\Helper::csrf_field(); ?>
        <!-- ... other form inputs ... -->
        <button type="submit">Submit</button>
    </form>
    ```

2.  **Verify the token:**
    In your route handling the POST request, compare the submitted token with the one in the session.
    ```php
    use App\Helper\Helper;

    Route::post('/profile', function () {
        if (Helper::request('_token') !== Helper::csrf_token()) {
            // Token mismatch, handle error
            Helper::returnJson(['error' => 'Invalid CSRF token'], 419);
            return;
        }
        // Process form data...
    });
    ```

### Helper Functions

The `App\Helper\Helper` class contains dozens of useful functions to make your life easier. Here are just a few examples.

**`dd(...$args)`**
Dump the given variable(s) and end the script. Perfect for debugging.
```php
dd($myVariable, $anotherVariable);
```

**`env(string $key, $default = null)`**
Gets the value of an environment variable from your `.env` file.
```php
$appName = Helper::env('APP_NAME', 'Comibyte Framework');
```

**`asset(string $path)`**
Generate a URL for a public asset (CSS, JS, images).
```php
<link rel="stylesheet" href="<?php echo Helper::asset('css/app.css'); ?>">
```

**`session(string $key, $default = null)`**
Get or set a session value.
```php
// Set a session value
Helper::session('user_id', 123);

// Get a session value
$userId = Helper::session('user_id');
```

**`redirect(string $page)`**
Redirect the user to another page.
```php
return Helper::redirect('/login');
```

**`sanitize(string $input)`**
Sanitize user input to prevent XSS attacks.
```php
$cleanComment = Helper::sanitize($_POST['comment']);
```

**`auth()`**
A shortcut to get the currently authenticated user.
```php
$currentUser = Helper::auth();
if ($currentUser) {
    echo "Hello, " . $currentUser->name;
}
```

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for any bugs or feature requests.

## License

The Comibyte PHP Framework is open-source software. Feel free to use it and adapt it for your own projects.
