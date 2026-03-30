<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_signin.php");
    exit;
}

$email = trim($_POST["email"] ?? '');
$pwd   = $_POST["pwd"] ?? '';

$errors = [];

if (empty($email)) {
    $errors[] = "Please enter your email.";
}
if (empty($pwd)) {
    $errors[] = "Please enter your password.";
}

if (!empty($errors)) {
    $_SESSION["errors_admin_signin"] = $errors;
    header("Location: adminsignin.php");
    exit;
}

try {
    require_once "db.php";

    $query = "SELECT id, f_name, l_name, pwd, role 
              FROM users 
              WHERE email = ? AND role = 'admin'
              LIMIT 1";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && $pwd == $user['pwd']) {
        $_SESSION["user_id"]   = $user["id"];
        $_SESSION["user_name"] = $user["f_name"] . " " . $user["l_name"];
        $_SESSION["user_role"] = $user["role"];

        $_SESSION["success_signin"] = "Welcome back, Admin " . htmlspecialchars($user["f_name"]) . "!";

        header("Location: ../../ShemachochAdminPanel/index.php");
        exit;
    } else {
        $errors[] = "Invalid email or password, or not an admin account.";
        $_SESSION["errors_admin_signin"] = $errors;
        header("Location: adminsignin.php");
        exit;
    }
} catch (PDOException $e) {
    error_log("Admin signin error: " . $e->getMessage());
    $_SESSION["errors_admin_signin"] = ["Login failed. Please try again later."];
    header("Location: adminsignin.php");
    exit;
}
