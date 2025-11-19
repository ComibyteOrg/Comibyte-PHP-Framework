# Comibyte PHP Mini Framework

<div align="center">
  <img src="https://imgs.search.brave.com/a2QJ4QGpzGpXeDGHk1c-pL3FdZ-v47YnUIxeu4pjCe4/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly9vbHV3/YWRpbXUtYWRlZGVq/aS53ZWIuYXBwL2lt/YWdlcy9sb2dvLnBu/Zw" alt="Comibyte Welcome Page" width="300">
  <br>
  <p>
    <img src="https://img.shields.io/badge/PHP-8.2%2B-blue?style=for-the-badge&logo=php" alt="PHP Version">
    <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
    <img src="https://img.shields.io/badge/Status-In%20Development-orange?style=for-the-badge" alt="Status">
  </p>
</div>

Welcome to the Comibyte PHP Mini Framework! A lightweight, modern, and intuitive mini-framework designed for rapid development of PHP applications. It provides a solid foundation with essential tools like a powerful router, a simple view engine, database abstraction, middleware support, and a rich set of helper functions, allowing you to build elegant applications with minimal setup.

This framework is inspired by the simplicity and elegance of frameworks like Laravel, aiming to provide a productive and enjoyable development experience.

---

## Table of Contents

- [Core Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Project Structure](#structure)
- [Core Concepts](#concepts)
  - [Entry Point and Bootstrap](#entry-point)
  - [Routing](#routing)
  - [Controllers](#controllers)
    - [Input Sanitization](#sanitize)
  - [Middleware](#middleware)
  - [Views](#views)
  - [Models & Database](#models)
  - [CSRF Protection](#csrf-protection)
  - [Email Service](#email)
  - [Helper Functions](#helpers)
- [Usage Examples](#examples)
- [Contributing](#contribute)
- [License](#license)

---

<a name="features"></a>
## ✨ Core Features

- **Elegant Routing Engine**: Define clean, simple routes with parameter support and middleware.
- **Controller Support**: Organize your code with MVC-style controllers.
- **Middleware System**: Protect your routes with authentication, guest-only, and custom middleware.
- **Simple View Engine**: Easily render PHP views with data passing.
- **Database Abstraction**: Support for MySQL, SQLite, and PostgreSQL with query builder.
- **Rich Helper Library**: A comprehensive set of helper functions for common tasks.
- **Environment Configuration**: Uses `.env` files for easy management of application configuration.
- **CSRF Protection**: Built-in helpers to protect your forms from cross-site request forgery.
- **Integrated Mailer**: A simple wrapper around PHPMailer to send emails easily.
- **JSON API Support**: Simple helpers for building RESTful APIs.
- **Input Sanitization**: Robust input sanitization to prevent XSS and other attacks.

<a name="requirements"></a>
## Requirements

- PHP >= 8.2
- Composer
- A local web server (e.g., Apache, Nginx) or PHP's built-in server.
- Database (MySQL, SQLite, or PostgreSQL) - optional, depending on your needs.

<a name="installation"></a>
## 🚀 Installation

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

        # Database (choose one)
        DB_CONNECTION=sqlite
        DB_DATABASE=database/db.sqlite

        # Or for MySQL/PostgreSQL:
        # DB_CONNECTION=mysql
        # DB_HOST=127.0.0.1
        # DB_PORT=3306
        # DB_DATABASE=your_database
        # DB_USERNAME=your_username
        # DB_PASSWORD=your_password

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
│   ├── Controller/
│   │   └── HomeController.php      # Example controller with authentication logic
│   ├── Database/
│   │   ├── Connect.php             # Database connection utilities
│   │   ├── DatabaseFactory.php     # Factory for creating database connections
│   │   ├── DatabaseInterface.php   # Database interface contract
│   │   ├── DatabaseQuery.php       # Query builder for CRUD operations
│   │   ├── MySQLConnection.php     # MySQL database implementation
│   │   ├── PostgreSQLConnection.php # PostgreSQL database implementation
│   │   ├── SQLiteConnection.php    # SQLite database implementation
│   │   └── db.sqlite               # SQLite database file
│   ├── Helper/
│   │   ├── CSRF.php                # CSRF token generation
│   │   ├── EmailService.php        # Email sending service using PHPMailer
│   │   └── Helper.php              # Core helper functions including sanitize
│   ├── Middleware/
│   │   ├── AuthMiddleware.php      # Authentication middleware
│   │   └── GuestMiddleware.php     # Guest-only middleware
│   ├── Model/
│   │   └── (User.php, Admin.php)   # Models (currently referenced but not implemented)
│   └── Router/
│       └── Route.php               # The routing engine
├── Resources/
│   ├── css/
│   ├── js/
│   └── views/                      # PHP view files
│       └── home.php
├── routes/
│   └── web.php                     # Web route definitions
├── vendor/                         # Composer dependencies
├── .env                            # Environment configuration
├── .env.example                    # Example environment file
├── composer.json                   # Composer dependency list
├── index.php                       # Application entry point
└── README.md                       # This file
```

<a name="concepts"></a>
## Core Concepts

<a name="entry-point"></a>
### Entry Point and Bootstrap

The application starts with `index.php`, which handles:

- **Autoloading**: Uses Composer's autoloader and a custom fallback for App classes.
- **Session Management**: Starts PHP sessions for user authentication.
- **Error Reporting**: Enables error reporting in development.
- **Route Setup**: Registers middleware aliases and includes route definitions.
- **Dispatch**: Processes the incoming request and routes it to the appropriate handler.

```php
// index.php
require __DIR__ . "/vendor/autoload.php";

// Custom autoloader fallback
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

use App\Router\Route;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

Route::setViewPath(__DIR__ . '/Resources/views');
Route::registerMiddleware('auth', AuthMiddleware::class);
Route::registerMiddleware('guest', GuestMiddleware::class);

include __DIR__ . "/routes/web.php";
Route::dispatch();
```

<a name="routing"></a>
### Routing

All web routes are defined in `routes/web.php`. The router supports HTTP verbs, route parameters, and middleware.

**Basic Routes:**
```php
use App\Router\Route;
use function App\Router\view;

Route::get('/', function () {
    return view('home');
});

Route::post('/login', [HomeController::class, 'login']);
```

**Route with Parameters:**
```php
Route::get('/users/{id}', function ($id) {
    return "User ID: " . $id;
});

Route::get('/posts/{id}/comments/{commentId}', function ($id, $commentId) {
    return "Post ID: " . $id . ", Comment ID: " . $commentId;
});
```

**Routes with Middleware:**
```php
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/login', function () {
    return view('login');
})->middleware('guest');
```

The `Route.php` class handles:
- Route registration with method and path
- Parameter extraction using regex
- Middleware chaining
- Request dispatching

<a name="controllers"></a>
### Controllers

Controllers organize your application logic. They handle requests and return responses.

**Example Controller (`App/Controller/HomeController.php`):**
```php
<?php
namespace App\Controller;

use App\Helper\Helper;

class HomeController
{
    public function login()
    {
        $email = Helper::sanitize(Helper::request('email'));
        $password = Helper::request('password');

        // CSRF check
        if (Helper::request('_token') !== Helper::csrf_token()) {
            Helper::returnJson(['error' => 'Invalid CSRF token'], 419);
            return;
        }

        // Authentication logic...
    }
}
```

<a name="sanitize"></a>
#### Input Sanitization

The framework provides a robust `sanitize()` function in `App/Helper/Helper.php` to prevent XSS attacks and clean user input.

**How the `sanitize()` function works:**

```php
public static function sanitize($input)
{
    $input = trim($input ?? '');                    // Remove whitespace from beginning and end
    $input = htmlspecialchars($input);              // Convert special characters to HTML entities
    $input = stripcslashes($input);                 // Remove backslashes
    $input = htmlentities(strip_tags($input));      // Convert to HTML entities and remove HTML tags

    return $input;
}
```

**Step-by-step explanation:**
1. **`trim()`**: Removes leading and trailing whitespace, including spaces, tabs, and newlines.
2. **`htmlspecialchars()`**: Converts special characters to HTML entities:
   - `&` becomes `&amp;`
   - `<` becomes `<`
   - `>` becomes `>`
   - `"` becomes `"`
   - `'` becomes `&#039;`
3. **`stripcslashes()`**: Removes backslashes that were added by magic quotes or user input.
4. **`strip_tags()`**: Removes all HTML and PHP tags from the string.
5. **`htmlentities()`**: Converts all applicable characters to HTML entities, providing an additional layer of protection.

**Usage in Controllers:**
```php
$name = Helper::sanitize(Helper::request('name'));
$email = Helper::sanitize(Helper::request('email'));
$message = Helper::sanitize(Helper::request('message'));
```

This multi-layered approach ensures that user input is safe for display and prevents common XSS vulnerabilities while preserving the intended content.

<a name="middleware"></a>
### Middleware

Middleware provides a mechanism for filtering HTTP requests. It's executed before the route handler.

**Authentication Middleware (`App/Middleware/AuthMiddleware.php`):**
```php
<?php
namespace App\Middleware;

use App\Helper\Helper;

class AuthMiddleware
{
    public function handle($params, $next)
    {
        if (!Helper::session('user_id')) {
            echo Helper::redirect('/login');
            return;
        }
        return $next($params);
    }
}
```

**Guest Middleware (`App/Middleware/GuestMiddleware.php`):**
```php
<?php
namespace App\Middleware;

use App\Helper\Helper;

class GuestMiddleware
{
    public function handle($params, $next)
    {
        if (Helper::session('user_id')) {
            echo Helper::redirect('/dashboard');
            return;
        }
        return $next($params);
    }
}
```

Middleware is registered in `index.php` and applied to routes using the `middleware()` method.

<a name="views"></a>
### Views

Views are PHP files located in `Resources/views/`. They render HTML with embedded PHP.

**Rendering a View:**
```php
Route::get('/', function () {
    return view('home');
});
```

**Passing Data to Views:**
```php
Route::get('/profile', function () {
    $user = ['name' => 'John Doe'];
    return view('profile', ['user' => $user]);
});
```

**Dot Notation for Subdirectories:**
```php
return view('admin.dashboard'); // Renders Resources/views/admin/dashboard.php
```

The `view()` function in `Route.php` handles path resolution and data extraction.

<a name="models"></a>
### Models & Database

The framework includes a database abstraction layer supporting MySQL, SQLite, and PostgreSQL.

**Database Factory:**
```php
// Create a database connection
$db = DatabaseFactory::create('sqlite', ['database' => 'path/to/db.sqlite']);
```

**Query Builder (`App/Database/DatabaseQuery.php`):**
```php
$query = new DatabaseQuery($db);

// Select
$users = $query->select('users', '*', 'active = ?', [1]);

// Insert
$id = $query->insert('users', ['name' => 'John', 'email' => 'john@example.com']);

// Update
$query->update('users', ['name' => 'Jane'], 'id = ?', [1]);

// Delete
$query->delete('users', 'id = ?', [1]);
```

**Note:** The User and Admin models are referenced in controllers but not implemented in the current codebase. You would need to create these models extending a base Model class with methods like `find()`, `findByEmail()`, `save()`, `delete()`.

<a name="csrf-protection"></a>
### CSRF Protection

The framework provides built-in CSRF protection.

**Add CSRF Token to Forms:**
```php
<form method="POST" action="/login">
    <?php echo Helper::csrf_field(); ?>
    <!-- form fields -->
</form>
```

**Verify Token in Controller:**
```php
if (Helper::request('_token') !== Helper::csrf_token()) {
    Helper::returnJson(['error' => 'Invalid CSRF token'], 419);
    return;
}
```

The token is stored in the session and regenerated for each request.

<a name="email"></a>
### Email Service

Email sending is handled by `App/Helper/EmailService.php` using PHPMailer.

**Configuration in `.env`:**
```ini
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="Your App"
```

**Sending Emails:**
```php
$emailService = new EmailService();
$result = $emailService->sendContactEmail($name, $email, $message);
```

<a name="helpers"></a>
### Helper Functions

The `App\Helper\Helper` class contains numerous utility functions:

**Debugging:**
- `dd(...$args)`: Dump and die
- `Helper::dd($variable);`

**Environment & Configuration:**
- `env(string $key, $default = null)`: Get environment variable
- `asset(string $path)`: Generate asset URL
- `url(string $path)`: Generate full URL

**Session & Request:**
- `session(string $key, $default = null)`: Get/set session value
- `old(string $key, $default = '')`: Get old input
- `method()`: Get request method

**Content Manipulation:**
- `excerpt(string $html, int $length = 150, string $suffix = '...')`: Create text excerpt
- `readingTime(string $content, int $wpm = 200)`: Estimate reading time
- `slugify(string $string, string $separator = '-')`: Create URL slug

**Paths:**
- `base_path(string $path = '')`: Get project root path
- `app_path(string $path = '')`: Get App directory path
- `storage_path(string $path = '')`: Get storage directory path

**Security:**
- `csrf_field()`: Generate CSRF input field
- `csrf_token()`: Get current CSRF token

**Utilities:**
- `redirect(string $page, int $seconds = 0)`: Redirect user
- `returnJson(array $data, int $statusCode = 200)`: Return JSON response
- `set_alert(string $type, string $message)`: Create Bootstrap alert
- `array_get(array $array, string $key, $default = null)`: Get array value with dot notation

<a name="examples"></a>
## Usage Examples

**Complete Login System:**
```php
// routes/web.php
Route::get('/login', function () {
    return view('login');
})->middleware('guest');

Route::post('/login', [HomeController::class, 'login']);

// App/Controller/HomeController.php
public function login()
{
    $email = Helper::sanitize(Helper::request('email'));
    $password = Helper::request('password');

    if (Helper::request('_token') !== Helper::csrf_token()) {
        Helper::returnJson(['error' => 'Invalid CSRF token'], 419);
        return;
    }

    $user = User::findByEmail($email);
    if ($user && password_verify($password, $user->password)) {
        Helper::session('user_id', $user->id);
        Helper::returnJson(['success' => 'Login successful', 'redirect' => '/dashboard']);
    } else {
        Helper::returnJson(['error' => 'Invalid credentials'], 401);
    }
}
```

**API Endpoint with JSON Response:**
```php
Route::get('/api/users', function () {
    $users = User::all(); // Assuming User model exists
    Helper::returnJson(['users' => $users]);
});
```

**Contact Form with Email:**
```php
Route::post('/contact', [HomeController::class, 'contact']);

public function contact()
{
    $name = Helper::sanitize(Helper::request('name'));
    $email = Helper::sanitize(Helper::request('email'));
    $message = Helper::sanitize(Helper::request('message'));

    if (Helper::request('_token') !== Helper::csrf_token()) {
        Helper::returnJson(['error' => 'Invalid CSRF token'], 419);
        return;
    }

    $emailService = new EmailService();
    $result = $emailService->sendContactEmail($name, $email, $message);

    if ($result) {
        Helper::returnJson(['success' => 'Message sent successfully']);
    } else {
        Helper::returnJson(['error' => 'Failed to send message'], 500);
    }
}
```

<a name="contribute"></a>
## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue for any bugs or feature requests.

<a name="license"></a>
## License

The Comibyte PHP Mini Framework is open-source software licensed under the MIT License. Feel free to use it and adapt it for your own projects.
