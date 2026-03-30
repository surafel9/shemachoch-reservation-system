<script>
    if (localStorage.getItem("theme") === "dark") {
        document.documentElement.classList.add("dark");
    }
</script>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Shemachoch') ?></title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <header class="top-bar">
            <div class="top-bar-content">
                <span class="logo">
                    <a href="../index.php">Shemachoch</a>
                </span>
                <div class="theme-toggle">
                    <span class="mode-text">Dark mode</span>
                    <label class="switch">
                        <input type="checkbox" id="darkToggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </header>
        <main class="main">