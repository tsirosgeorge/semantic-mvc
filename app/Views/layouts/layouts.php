<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard'; ?></title>
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/js/dashboard.js"></script>
</head>

<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="/dashboard">Home</a></li>
                <li><a href="/profile">Profile</a></li>
                <li><a href="#" id="logoutButton">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?= $content; ?> <!-- Inject the view content here -->
    </main>

    <footer>
        <p>&copy; <?= date('Y'); ?> My Company</p>
    </footer>
</body>

</html>