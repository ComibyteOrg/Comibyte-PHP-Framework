<?php
namespace App\Helper;

// We need access to the database model to query for the user
use App\Model\Admin;

class Helper
{
    // Sanitize Inputs 
    public static function sanitize($input)
    {
        $input = trim($input ?? '');
        $input = htmlspecialchars($input);
        $input = stripcslashes($input);
        $input = htmlentities(strip_tags($input));

        return $input;
    }

    // Redirect Users
    public static function redirect($page, $seconds = 0)
    {
        return "<!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv='refresh' content='$seconds;url=$page'>
                <title>Redirecting...</title>
            </head>
            ";
    }


    // show alerts 
    public static function set_alert($type, $message)
    {
        return $alerts =
            "
                <div class='alert alert-$type alert-dismissible fade show text-center' role='alert'>
                    $message
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            ";
    }


    // write into a txt file 
    public static function writetxt($file_name, $values = array())
    {
        $filepath = $file_name;
        $file = fopen($filepath, 'a');

        $value = implode(',', $values);

        if ($file) {
            $data = [$value];

            fputcsv($file, $data) . "\n";
            fclose($file);
        }

        return $file;

    }


    // Delete from txt file 
    public static function deletetxt($file_name, $cond)
    {
        $filepath = $file_name;
        $condition = $cond;

        $lines = file($filepath, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $key => $line) {
            if (stripos($line, $condition) !== false) {
                unset($lines[$key]);
            }
        }

        $deleted = file_put_contents($filepath, implode("\n", $lines));

        return $deleted;
    }


    public static function view($file, $data = [], $return = false)
    {
        // Accept dot notation like 'admin.login' and convert to path
        $original = $file;
        $file = str_replace('.', '/', $file);

        // If the caller provided a full path or included .php, normalize it
        if (substr($file, -4) !== '.php') {
            $file = rtrim($file, '/') . '.php';
        }

        // Prefer Router view path if available to keep single source of truth
        $viewsDirCandidates = [
            // Router::getViewPath() may or may not exist; check gracefully
            function_exists('App\\Router\\Route::getViewPath') ? @\App\Router\Route::getViewPath() : null,
            realpath(__DIR__ . '/../../Resources/views/')
        ];

        // Resolve the first valid views directory
        $viewsDir = null;
        foreach ($viewsDirCandidates as $candidate) {
            if (is_string($candidate) && !empty($candidate) && realpath($candidate)) {
                $viewsDir = realpath($candidate);
                break;
            }
        }

        // Fallback to packaged views dir if nothing found
        if (!$viewsDir) {
            $viewsDir = realpath(__DIR__ . '/../../Resources/views/');
        }

        // Build absolute path
        $filepath = realpath($viewsDir . '/' . $file);

        // Debug information
        error_log("Attempting to load view: " . $original . " => " . $file);
        error_log("Full filepath: " . $filepath);
        error_log("Views directory: " . $viewsDir);

        // Security: ensure the resolved path is inside the views directory
        if ($filepath && strpos($filepath, $viewsDir) === 0 && file_exists($filepath)) {
            // Extract data array to variables
            if (!empty($data) && is_array($data)) {
                extract($data, EXTR_SKIP);
            }

            // Optionally return output as string
            if ($return) {
                ob_start();
                include $filepath;
                return ob_get_clean();
            } else {
                include $filepath;
            }
        } else {
            // Log error with more details
            error_log("View file not found: " . $original);
            error_log("Resolved filepath: " . $filepath);
            error_log("Views directory: " . $viewsDir);
            echo "View File Not found: " . $original;
        }
    }


    public static function returnJson(array $data, int $statusCode = 200): void
    {
        // End any previous output buffers (to prevent extra HTML)
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Set response headers
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);

        // Return JSON
        echo json_encode($data);

        // Stop further script execution
        exit;
    }

    /**
     * Creates a short, plain-text excerpt from a string of HTML.
     *
     * @param string $html The input HTML string.
     * @param int $length The desired maximum length of the excerpt.
     * @param string $suffix The string to append if the text is truncated.
     * @return string The generated excerpt.
     */
    public static function excerpt(string $html, int $length = 150, string $suffix = '...'): string
    {
        // 1. Remove HTML tags to get plain text.
        $text = strip_tags($html);

        // 2. If the text is already short enough, no need to truncate.
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        // 3. Find the last space within the desired length to avoid cutting words.
        $lastSpace = mb_strrpos(mb_substr($text, 0, $length), ' ');
        $truncatedText = $lastSpace ? mb_substr($text, 0, $lastSpace) : mb_substr($text, 0, $length);

        return $truncatedText . $suffix;
    }

    /**
     * Estimates the reading time for a piece of content.
     *
     * @param string $content The content to be measured.
     * @param int $wpm Words per minute reading speed.
     * @return int The estimated reading time in minutes.
     */
    public static function readingTime(string $content, int $wpm = 200): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = floor($wordCount / $wpm);
        return ($minutes < 1) ? 1 : $minutes;
    }

    /**
     * Dump the given variable and end the script.
     *
     * @param mixed $value The variable to dump.
     * @return void
     */
    public static function dd(...$args): void
    {
        // End any previous output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        echo '<pre>';
        foreach ($args as $arg) {
            var_dump($arg);
        }
        echo '</pre>';
        exit;
    }

    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Generate a URL for an asset.
     *
     * @param string $path
     * @return string
     */
    public static function asset(string $path): string
    {
        $baseUrl = self::env('APP_URL', '');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Generate a full URL for the given path.
     *
     * @param string $path
     * @return string
     */
    public static function url(string $path): string
    {
        $baseUrl = self::env('APP_URL', '');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function slugify(string $string, string $separator = '-'): string
    {
        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';
        $string = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $string);

        // Replace @ with the word 'at'
        $string = str_replace('@', $separator . 'at' . $separator, $string);

        // Remove all characters that are not the separator, letters, numbers, or whitespace.
        $string = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($string));

        // Replace all separator characters and whitespace by a single separator
        $string = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $string);

        return trim($string, $separator);
    }

    /**
     * Get/Set a session value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function session(string $key, $default = null)
    {
        if (is_null($default)) {
            // Get session value
            return $_SESSION[$key] ?? null;
        }

        // Set session value
        $_SESSION[$key] = $default;
        return $default;
    }

    /**
     * Retrieve old input data from the session.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function old(string $key, $default = '')
    {
        return $_SESSION['_old_input'][$key] ?? $default;
    }

    /**
     * Generate a CSRF token input field.
     *
     * @return string
     */
    public static function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . self::csrf_token() . '">';
    }

    /**
     * Get the current CSRF token.
     *
     * @return string
     */
    public static function csrf_token(): string
    {
        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_token'];
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array   $array
     * @param  string|int|null  $key
     * @param  mixed   $default
     * @return mixed
     */
    public static function array_get(array $array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Get the fully qualified path to the project root.
     *
     * @param string $path
     * @return string
     */
    public static function base_path(string $path = ''): string
    {
        // Assumes this Helper.php file is in /App/Helper/
        $basePath = realpath(__DIR__ . '/../../');
        return $basePath . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
    }

    /**
     * Get the fully qualified path to the 'App' directory.
     *
     * @param string $path
     * @return string
     */
    public static function app_path(string $path = ''): string
    {
        return self::base_path('App') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
    }

    /**
     * Get the fully qualified path to the 'storage' directory.
     *
     * @param string $path
     * @return string
     */
    public static function storage_path(string $path = ''): string
    {
        // You might need to create a 'Storage' directory in your project root
        return self::base_path('Storage') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }
}