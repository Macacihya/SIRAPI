<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-light: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.025em;
        }

        .logo-accent {
            color: var(--primary);
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 2rem;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-item:hover {
            color: var(--primary);
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
            flex: 1;
        }

        main {
            background: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        h1 {
            font-size: 2rem;
            margin: 3rem auto 0;
            max-width: 1200px;
            padding: 0 2rem;
            color: var(--text);
        }

        /* Footer Styles */
        footer {
            background: var(--white);
            border-top: 1px solid var(--border);
            padding: 4rem 2rem 2rem;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 3rem;
        }

        .footer-brand p {
            color: var(--text-light);
            line-height: 1.6;
            margin-top: 1rem;
        }

        .footer-nav h4, .footer-legal h4 {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        .footer-nav ul, .footer-legal ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-nav li, .footer-legal li {
            margin-bottom: 0.75rem;
        }

        .footer-nav a, .footer-legal a {
            text-decoration: none;
            color: var(--text-light);
            transition: color 0.2s;
        }

        .footer-nav a:hover, .footer-legal a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            border-top: 1px solid var(--border);
            padding-top: 2rem;
            text-align: center;
            color: var(--text-light);
            font-size: 0.875rem;
        }

        /* Table Styles (for list_product) */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th {
            text-align: left;
            padding: 1rem;
            background: var(--bg);
            color: var(--text-light);
            font-weight: 600;
            font-size: 0.875rem;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }

        tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <header>
        @include('components.header')
    </header>

    <h1>List Produk</h1>
    <div class="container">
        <main>
            @yield('content')
        </main>
    </div>

    <footer>
        @include('components.footer')
    </footer>
</body>
</html>
