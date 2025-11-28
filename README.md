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

Welcome to the Comibyte PHP Mini Framework! This is a lightweight, modern, and intuitive mini-framework designed for rapid development of PHP applications. Whether you're a beginner learning PHP or an experienced developer looking for a simple solution, this framework provides a solid foundation with essential tools to build elegant web applications with minimal setup.

This framework is inspired by the simplicity and elegance of popular frameworks like Laravel, but it's much simpler and easier to understand, making it perfect for learning how modern web frameworks work.

---

## Table of Contents

- [What is a PHP Framework?](#what-is-a-php-framework)
- [Why Use Comibyte?](#why-use-comibyte)
- [Core Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Quick Start Guide](#quick-start)
- [Project Structure](#structure)
- [Core Concepts](#concepts)
  - [Entry Point and Bootstrap](#entry-point)
  - [Routing](#routing)
  - [Controllers](#controllers)
  - [Middleware](#middleware)
  - [Views](#views)
    - [Traditional PHP Views](#traditional-php-views)
    - [Twig Template Engine](#twig-templates)
  - [Models & Database](#models)
  - [CSRF Protection](#csrf-protection)
  - [Email Service](#email)
  - [Helper Functions](#helpers)
- [Step-by-Step Tutorial](#tutorial)
- [Usage Examples](#examples)
- [Contributing](#contribute)
- [License](#license)

---

<a name="what-is-a-php-framework"></a>
## What is a PHP Framework?

A PHP framework is a platform that provides a structure and components for building web applications. Think of it as a toolkit with pre-built solutions for common tasks like:

- Handling web requests (routing)
- Managing databases
- Rendering web pages (templating)
- Securing forms from attacks
- Sending emails
- And much more...

Instead of writing everything from scratch, a framework gives you a foundation to build upon, saving you time and helping you write better, more organized code.

<a name="why-use-comibyte"></a>
## Why Use Comibyte?

1. **Beginner-Friendly**: Simple structure and clear documentation make it easy to learn
2. **Lightweight**: Fast and doesn't overload your application with unnecessary features
3. **Modern Practices**: Teaches you industry-standard development techniques
4. **Flexible**: Use traditional PHP templates or modern Twig templates
5. **Educational**: Perfect for learning how web frameworks work under the hood
6. **No Magic**: Everything is transparent and easy to understand

<a name="features"></a>
## ✨ Core Features

- **Elegant Routing Engine**: Define clean, simple routes with parameter support and middleware
- **Controller Support**: Organize your code with MVC-style controllers
- **Middleware System**: Protect your routes with authentication, guest-only, and custom middleware
- **Simple View Engine**: Easily render PHP views with data passing
- **Twig Template Engine**: Modern templating engine with automatic detection
- **Database Abstraction**: Support for MySQL, SQLite, and PostgreSQL with query builder
- **Rich Helper Library**: A comprehensive set of helper functions for common tasks
- **Environment Configuration**: Uses `.env` files for easy management of application configuration
- **CSRF Protection**: Built-in helpers to protect your forms from cross-site request forgery
- **Integrated Mailer**: A simple wrapper around PHPMailer to send emails easily
- **JSON API Support**: Simple helpers for building RESTful APIs
- **Input Sanitization**: Robust input sanitization to prevent XSS and other attacks

<a name="requirements"></a>
## Requirements

To use this framework, you need:

- **PHP 8.2 or higher** - The programming language the framework is built with
- **Composer** - A tool for managing PHP dependencies (libraries/packages)
- **A local web server** - Like Apache, Nginx, or PHP's built-in server
- **A database** - MySQL, SQLite, or PostgreSQL (optional, depending on your needs)

Don't worry if you're not familiar with these yet - we'll explain them as we go!

<a name="installation"></a>
## 🚀 Installation

Installing the Comibyte framework is simple. Follow these steps:

### Step 1: Install Prerequisites

1. **Install PHP 8.2+**: Download from [php.net](https://www.php.net/downloads)
2. **Install Composer**: Download from [getcomposer.org](https://getcomposer.org/download/)

### Step 2: Get the Framework

Open your terminal/command prompt and run:

```bash
git clone https://github.com/ComibyteOrg/Comibyte-PHP-Framework.git
cd Comibyte-PHP-Framework
```

If you don't have Git installed, you can download the ZIP file from GitHub instead.

### Step 3: Install Dependencies

Run this command to install all required packages:

```bash
composer install
```

This will download and install:
- PHPMailer (for sending emails)
- DotEnv (for environment configuration)
- Twig (for modern templating)
- And other necessary libraries

### Step 4: Configure Your Environment

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
   
2. Open `.env` in a text editor and configure your settings:
   ```ini
   # Application
   APP_NAME="Comibyte"
   APP_URL=http://localhost:8000

   # Database (choose one)
   DB_CONNECTION=sqlite
   DB_DATABASE=database/db.sqlite

   # Mail Server (for sending emails)
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_FROM_ADDRESS="hello@comibyte.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

### Step 5: Start the Development Server

You can use PHP's built-in server for development:

```bash
php -S localhost:8000 -t .
```

Now visit `http://localhost:8000` in your browser to see the welcome page!

<a name="quick-start"></a>
## ⚡ Quick Start Guide

Let's create your first page with the Comibyte framework!

### 1. Create a Route

Open `routes/web.php` and add:

```php
Route::get('/hello', function () {
    return view('hello');
});
```

### 2. Create a View

Create `Resources/views/hello.php`:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Hello World</title>
</head>
<body>
    <h1>Hello, Comibyte!</h1>
    <p>Welcome to your first page.</p>
</body>
</html>
```

### 3. Visit Your Page

Go to `http://localhost:8000/hello` in your browser!

That's it! You've created your first page with the Comibyte framework.

<a name="structure"></a>
## 📁 Project Structure

Understanding the project structure is crucial for working with the framework:

```
├── App/                        # Main application code
│   ├── Controller/             # Controllers (business logic)
│   ├── Database/               # Database connection and query tools
│   ├── Helper/                 # Utility functions and services
│   ├── Middleware/             # Request filtering logic
│   ├── Model/                  # Data models (database representations)
│   └── Router/                 # Routing system
├── Resources/                  # Assets and views
│   ├── css/                    # Stylesheets
│   ├── js/                     # JavaScript files
│   └── views/                  # Template files (both PHP and Twig)
│       ├── home.php            # Traditional PHP view
│       ├── home.twig           # Twig template (automatically detected)
│       └── twig/               # Directory for Twig templates
├── routes/                     # Route definitions
│   └── web.php                 # Web route definitions
├── vendor/                     # Composer dependencies (automatically generated)
├── .env                        # Environment configuration
├── .env.example                # Example environment file
├── composer.json               # Composer dependency list
├── index.php                   # Application entry point
└── README.md                   # This file
```

<a name="concepts"></a>
## Core Concepts

Let's dive into the core concepts that make the Comibyte framework work.

<a name="entry-point"></a>
### Entry Point and Bootstrap

Every web request to your application starts with `index.php`. This file is like the "front door" of your application.

Here's what happens when someone visits your website:

1. **Autoloading**: Composer automatically loads all the classes you need
2. **Session Management**: Starts PHP sessions for user authentication
3. **Error Reporting**: Enables error reporting in development mode
4. **Route Setup**: Configures the routing system
5. **Dispatch**: Figures out which code should handle the request

```php
// index.php - Simplified version
require __DIR__ . "/vendor/autoload.php";

// Start session for user authentication
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configure the routing system
use App\Router\Route;
Route::setViewPath(__DIR__ . '/Resources/views');

// Load route definitions
include __DIR__ . "/routes/web.php";

// Handle the request
Route::dispatch();
```

<a name="routing"></a>
### Routing

Routing is how the framework knows which code to run for each URL. All routes are defined in `routes/web.php`.

#### Basic Routes

```php
// Show the homepage
Route::get('/', function () {
    return view('home');
});

// Handle a form submission
Route::post('/contact', function () {
    // Process the contact form
});
```

#### Routes with Parameters

```php
// Show a specific user (e.g., /users/123)
Route::get('/users/{id}', function ($id) {
    return "User ID: " . $id;
});

// Show a specific post comment (e.g., /posts/5/comments/10)
Route::get('/posts/{postId}/comments/{commentId}', function ($postId, $commentId) {
    return "Post: " . $postId . ", Comment: " . $commentId;
});
```

#### Routes with Controllers

Instead of putting all logic in the route file, you can organize it in controllers:

```php
// routes/web.php
Route::get('/profile', [ProfileController::class, 'show']);

// App/Controller/ProfileController.php
class ProfileController {
    public function show() {
        return view('profile');
    }
}
```

<a name="controllers"></a>
### Controllers

Controllers are PHP classes that organize your application's logic. They're like managers that coordinate between different parts of your application.

#### Creating a Controller

Create `App/Controller/UserController.php`:

```php
<?php
namespace App\Controller;

class UserController
{
    public function index()
    {
        // Show a list of all users
        $users = ['Alice', 'Bob', 'Charlie'];
        return view('users.index', ['users' => $users]);
    }
    
    public function show($id)
    {
        // Show a specific user
        $user = "User " . $id;
        return view('users.show', ['user' => $user]);
    }
}
```

#### Using Controllers in Routes

```php
// routes/web.php
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
```

<a name="middleware"></a>
### Middleware

Middleware acts as a filter for HTTP requests. It runs before your controller and can modify the request or stop it entirely.

#### Example: Authentication Middleware

```php
<?php
namespace App\Middleware;

use App\Helper\Helper;

class AuthMiddleware
{
    public function handle($params, $next)
    {
        // Check if user is logged in
        if (!Helper::session('user_id')) {
            // Redirect to login page if not authenticated
            echo Helper::redirect('/login');
            return;
        }
        
        // Continue to the next middleware or controller
        return $next($params);
    }
}
```

#### Applying Middleware

```php
// Only logged-in users can access the dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');
```

<a name="views"></a>
### Views

Views are templates that generate HTML. The framework supports both traditional PHP templates and modern Twig templates.

<a name="traditional-php-views"></a>
#### Traditional PHP Views

These are regular PHP files with HTML mixed with PHP code.

**Creating a View:**
```php
<!-- Resources/views/welcome.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Hello, <?php echo htmlspecialchars($name); ?>!</h1>
    <p>Today is <?php echo date('Y-m-d'); ?>.</p>
</body>
</html>
```

**Using a View:**
```php
Route::get('/welcome', function () {
    return view('welcome', ['name' => 'John']);
});
```

<a name="twig-templates"></a>
#### Twig Template Engine

Twig is a modern templating engine that makes writing templates cleaner and safer.

##### Automatic Twig Detection

The framework automatically detects and uses Twig templates when available:

```php
// This will automatically use welcome.twig if it exists
return view('welcome', ['name' => 'John']);
```

The system checks for:
1. `Resources/views/welcome.twig`
2. If not found, falls back to `Resources/views/welcome.php`

##### Creating a Twig Template

```twig
{# Resources/views/welcome.twig #}
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Hello, {{ name }}!</h1>
    <p>Today is {{ "now"|date("Y-m-d") }}.</p>
</body>
</html>
```

##### Template Inheritance

One of Twig's powerful features is template inheritance, which allows you to create reusable layouts:

```twig
{# Resources/views/layout.twig - Base layout #}
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}My Website{% endblock %}</title>
</head>
<body>
    <header>
        <nav>Navigation Menu</nav>
    </header>
    
    <main>
        {% block content %}{% endblock %}
    </main>
    
    <footer>
        <p>&copy; 2025 My Website</p>
    </footer>
</body>
</html>

{# Resources/views/page.twig - Extending the layout #}
{% extends "layout.twig" %}

{% block title %}My Page Title{% endblock %}

{% block content %}
    <h1>Welcome to My Page</h1>
    <p>This content goes in the main section.</p>
{% endblock %}
```

##### Twig Features

Twig provides many useful features:

```twig
{# Variables #}
<h1>{{ page_title }}</h1>
<p>User: {{ user.name }}</p>

{# Conditionals #}
{% if user.is_admin %}
    <p>Welcome, admin!</p>
{% else %}
    <p>Welcome, user!</p>
{% endif %}

{# Loops #}
<ul>
{% for item in items %}
    <li>{{ item.name }}</li>
{% endfor %}
</ul>

{# Filters #}
<p>{{ article.content|slice(0, 100) }}...</p>
<p>Published: {{ article.date|date("F j, Y") }}</p>
```

<a name="models"></a>
### Models & Database

Models represent data in your application, typically from a database.

#### Database Configuration

Configure your database in the `.env` file:

```ini
# SQLite (simple file-based database)
DB_CONNECTION=sqlite
DB_DATABASE=database/db.sqlite

# Or MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_app
DB_USERNAME=root
DB_PASSWORD=password
```

#### Query Builder

The framework includes a simple query builder:

```php
use App\Database\DatabaseFactory;
use App\Database\DatabaseQuery;

// Create database connection
$db = DatabaseFactory::create(env('DB_CONNECTION'), [
    'database' => env('DB_DATABASE')
]);

// Create query builder
$query = new DatabaseQuery($db);

// Select records
$users = $query->select('users', '*', 'active = ?', [1]);

// Insert record
$id = $query->insert('users', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// Update record
$query->update('users', [
    'name' => 'Jane Doe'
], 'id = ?', [1]);

// Delete record
$query->delete('users', 'id = ?', [1]);
```


## 🛠️ Available ORM Methods in DB Helper Class

### 🔍 select()

Select multiple rows.

```php
DB::select('users', ['role' => 'admin']);
```

Returns **array of objects**.

---

### 🔎 find()

Find a single row.

```php
DB::find('users', ['id' => 5]);
```

Returns **one object** or `null`.

---

### ➕ insert()

Insert a new record.

```php
DB::insert('users', [
    'name' => 'John',
    'email' => 'john@email.com'
]);
```

Returns **insert ID**.

---

### 📝 update()

Update a record.

```php
DB::update('users', ['name' => 'New'], ['id' => 3]);
```

Returns **number of affected rows**.

---

### ❌ delete()

Delete rows.

```php
DB::delete('users', ['id' => 10]);
```

Returns **number of deleted rows**.

---

### 🧾 raw()

Execute any SQL query.

```php
DB::raw("SELECT COUNT(*) FROM users");
```

Returns **PDOStatement result**.

---

## 📘 Usage Examples

### Example: Creating a User

```php
DB::insert('users', [
    'username' => 'comibyte',
    'email' => 'dev@example.com',
    'password' => 'hashed'
]);
```

### Example: Updating User

```php
DB::update('users', ['email' => 'new@gmail.com'], ['id' => 4]);
```

### Example: Getting All Users

```php
$admins = DB::select('users', ['role' => 'admin']);
```

### Example: Raw Query

```php
DB::raw("DELETE FROM logs WHERE created_at < NOW() - INTERVAL 30 DAY");
```

---

## ⚠️ Error Handling

All methods wrap PDO errors inside `PDOException`.

Use try/catch:

```php
try {
    $user = DB::find('users', ['id' => 1]);
} catch (\Exception $e) {
    echo $e->getMessage();
}
```


<a name="csrf-protection"></a>
### CSRF Protection

Cross-Site Request Forgery (CSRF) protection prevents malicious websites from submitting forms on behalf of your users.

#### Adding CSRF to Forms

```php
<form method="POST" action="/contact">
    <?php echo Helper::csrf_field(); ?>
    <input type="text" name="name" placeholder="Your Name">
    <textarea name="message" placeholder="Your Message"></textarea>
    <button type="submit">Send</button>
</form>
```

#### Validating CSRF Tokens

In your controller:

```php
public function contact()
{
    // Check if the CSRF token is valid
    if (Helper::request('_token') !== Helper::csrf_token()) {
        Helper::returnJson(['error' => 'Invalid CSRF token'], 419);
        return;
    }
    
    // Process the form...
}
```

<a name="email"></a>
### Email Service

Sending emails is easy with the built-in email service.

#### Configuration

Set up your email settings in `.env`:

```ini
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="Your App"
```

#### Sending Emails

```php
use App\Helper\EmailService;

$emailService = new EmailService();
$result = $emailService->sendContactEmail(
    $name, 
    $email, 
    $message
);

if ($result) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
```

<a name="helpers"></a>
### Helper Functions

Helpers are utility functions that make common tasks easier.

#### Debugging Helpers

```php
// Dump and die - great for debugging
dd($variable);

// Or use the class method
Helper::dd($variable);
```

#### Session Helpers

```php
// Set a session value
Helper::session('user_id', 123);

// Get a session value
$userId = Helper::session('user_id');

// Check if session exists
if (Helper::session('user_id')) {
    // User is logged in
}
```

#### Security Helpers

```php
// Sanitize user input to prevent XSS
$name = Helper::sanitize($_POST['name']);

// Generate CSRF token field
echo Helper::csrf_field();

// Get current CSRF token
$token = Helper::csrf_token();
```

#### URL Helpers

```php
// Generate full URL
$url = url('/profile');

// Generate asset URL
$cssUrl = asset('/css/style.css');
```

<a name="tutorial"></a>
## 🎓 Step-by-Step Tutorial

Let's build a simple blog application to demonstrate how everything works together.

### Step 1: Create a Posts Controller

Create `App/Controller/PostsController.php`:

```php
<?php
namespace App\Controller;

use function App\Router\view;

class PostsController
{
    public function index()
    {
        // Sample posts data (in a real app, this would come from a database)
        $posts = [
            [
                'id' => 1,
                'title' => 'Welcome to My Blog',
                'content' => 'This is the first post...',
                'author' => 'John Doe',
                'date' => '2025-01-15'
            ],
            [
                'id' => 2,
                'title' => 'Learning PHP Frameworks',
                'content' => 'Today I learned about...',
                'author' => 'Jane Smith',
                'date' => '2025-01-20'
            ]
        ];
        
        return view('posts.index', ['posts' => $posts]);
    }
    
    public function show($id)
    {
        // In a real app, fetch from database
        $post = [
            'id' => $id,
            'title' => 'Sample Post ' . $id,
            'content' => 'This is the content of post #' . $id,
            'author' => 'Author Name',
            'date' => date('Y-m-d')
        ];
        
        return view('posts.show', ['post' => $post]);
    }
}
```

### Step 2: Create Routes

Edit `routes/web.php`:

```php
<?php
use App\Router\Route;
use App\Controller\PostsController;

Route::get('/posts', [PostsController::class, 'index']);
Route::get('/posts/{id}', [PostsController::class, 'show']);
```

### Step 3: Create Twig Templates

Create `Resources/views/posts/index.twig`:

```twig
{% extends "twig/layout.twig" %}

{% block title %}Blog Posts{% endblock %}

{% block content %}
<h1>Blog Posts</h1>

<div class="posts">
    {% for post in posts %}
    <div class="post-preview">
        <h2><a href="/posts/{{ post.id }}">{{ post.title }}</a></h2>
        <p class="meta">By {{ post.author }} on {{ post.date|date("F j, Y") }}</p>
        <p>{{ post.content|slice(0, 150) }}...</p>
        <a href="/posts/{{ post.id }}">Read more</a>
    </div>
    {% endfor %}
</div>
{% endblock %}
```

Create `Resources/views/posts/show.twig`:

```twig
{% extends "twig/layout.twig" %}

{% block title %}{{ post.title }}{% endblock %}

{% block content %}
<article class="post">
    <h1>{{ post.title }}</h1>
    <p class="meta">By {{ post.author }} on {{ post.date|date("F j, Y") }}</p>
    
    <div class="content">
        {{ post.content }}
    </div>
    
    <a href="/posts">&larr; Back to all posts</a>
</article>
{% endblock %}
```

### Step 4: Test Your Application

Visit these URLs in your browser:
- `http://localhost:8000/posts` - List all posts
- `http://localhost:8000/posts/1` - View first post

Congratulations! You've built a simple blog application using the Comibyte framework.

<a name="examples"></a>
## 💡 Usage Examples

Here are some practical examples to help you understand how to use the framework.

### Complete Contact Form

```php
// routes/web.php
Route::get('/contact', function () {
    return view('contact');
});

Route::post('/contact', [ContactController::class, 'send']);

// App/Controller/ContactController.php
class ContactController
{
    public function send()
    {
        // Validate CSRF token
        if (Helper::request('_token') !== Helper::csrf_token()) {
            Helper::returnJson(['error' => 'Invalid CSRF token'], 419);
            return;
        }
        
        // Sanitize input
        $name = Helper::sanitize(Helper::request('name'));
        $email = Helper::sanitize(Helper::request('email'));
        $message = Helper::sanitize(Helper::request('message'));
        
        // Send email
        $emailService = new EmailService();
        $result = $emailService->sendContactEmail($name, $email, $message);
        
        if ($result) {
            Helper::returnJson(['success' => 'Message sent successfully']);
        } else {
            Helper::returnJson(['error' => 'Failed to send message'], 500);
        }
    }
}
```

```twig
{# Resources/views/contact.twig #}
{% extends "twig/layout.twig" %}

{% block content %}
<h1>Contact Us</h1>

<form method="POST" action="/contact">
    {{ csrf_field()|raw }}
    
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div>
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
    </div>
    
    <button type="submit">Send Message</button>
</form>
{% endblock %}
```

### User Dashboard with Authentication

```php
// routes/web.php
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// App/Middleware/AuthMiddleware.php
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

### API Endpoint

```php
// routes/web.php
Route::get('/api/users', function () {
    $users = [
        ['id' => 1, 'name' => 'John'],
        ['id' => 2, 'name' => 'Jane']
    ];
    Helper::returnJson(['users' => $users]);
});
```

<a name="contribute"></a>
## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

1. **Report Bugs**: If you find an issue, please open a GitHub issue
2. **Fix Bugs**: Submit pull requests with bug fixes
3. **Add Features**: Implement new features and submit pull requests
4. **Improve Documentation**: Help make this guide better for beginners
5. **Translate**: Help translate documentation to other languages

To contribute code:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

<a name="license"></a>
## 📄 License

The Comibyte PHP Mini Framework is open-source software licensed under the MIT License. This means you can:

- Use it for personal projects
- Use it for commercial projects
- Modify the code
- Distribute your modified version

Feel free to use it and adapt it for your own projects!