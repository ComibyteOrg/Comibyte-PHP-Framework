<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Comibyte</title>
    <style>
        body {
            background-color: #1a202c;
            color: #a0aec0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            line-height: 1.6;
        }
        .container {
            text-align: center;
            max-width: 800px;
            padding: 2rem;
        }
        .title {
            font-size: 3rem;
            font-weight: 300;
            color: #fff;
            letter-spacing: -0.025em;
            margin-bottom: 1rem;
        }
        .title span {
            font-weight: 700;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
            margin-top: 3rem;
            text-align: left;
        }
        @media (min-width: 640px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .card {
            display: block;
            padding: 1.5rem;
            background-color: #2d3748;
            border-radius: 0.75rem;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            text-decoration: none;
            color: inherit;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .card h3 {
            margin: 0 0 0.5rem 0;
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .card p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        .footer {
            margin-top: 4rem;
            font-size: 0.875rem;
            color: #4a5568;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="title">
            <span>Comibyte</span> PHP Framework
        </h1>

        <div class="grid">
            <a href="#" class="card">
                <h3>Documentation</h3>
                <p>Dive into the framework's architecture, helper functions, and core concepts. Get started on your next project.</p>
            </a>

            <a href="#" class="card">
                <h3>Routing</h3>
                <p>Learn how to define routes, handle request methods, and assign middleware to protect your application.</p>
            </a>

            <a href="#" class="card">
                <h3>Views &amp; Helpers</h3>
                <p>Discover how to render dynamic views, pass data, and use the built-in helpers to speed up your development.</p>
            </a>

            <a href="#" class="card">
                <h3>Community</h3>
                <p>Join the community, ask questions, and contribute to the future of the Comibyte framework.</p>
            </a>
        </div>

        <div class="footer">
            PHP Version <?php echo phpversion(); ?>
        </div>
    </div>

</body>
</html>