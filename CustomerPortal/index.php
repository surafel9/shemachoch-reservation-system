<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shemachoch</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
    <script>
        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>
</head>

<body>

    <div class="container">
        <header class="top-bar">
            <div class="top-bar-content">
                <span class="logo"><a href="index.php">
                        Shemachoch
                    </a></span>

                <div class="theme-toggle">
                    <span class="mode-text">Dark mode</span>
                    <label class="switch">
                        <input type="checkbox" id="darkToggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </header>

        <div class="welcome">

            <div class="img-div">
                <img src="images/groceries.png" alt="groceries" class="main-img">
            </div>

            <div class="right-content">
                <div class="heading-div">
                    <h1 class="heading">Welcome to Shemachoch</h1>
                </div>

                <div class="links-div">
                    <a href="includes/register.php" class="link register">Register</a>
                    <a href="includes/signin.php" class="link signin">Sign In</a>
                    <a href="includes/dashboard.php" class="link dashboard">Dashboard</a>
                </div>
            </div>

        </div>
    </div>

    <script>
        const toggle = document.getElementById("darkToggle");

        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
            if (toggle) toggle.checked = true;
        }

        if (toggle) {
            toggle.addEventListener("change", () => {
                if (toggle.checked) {
                    document.documentElement.classList.add("dark");
                    localStorage.setItem("theme", "dark");
                } else {
                    document.documentElement.classList.remove("dark");
                    localStorage.setItem("theme", "light");
                }
            });
        }
    </script>

</body>

</html>