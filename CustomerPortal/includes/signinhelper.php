<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: signin.php");
    exit;
}

$coupon = trim($_POST["coupon"] ?? '');
$pwd    = $_POST["pwd"] ?? '';

$errors = [];

if (empty($coupon)) {
    $errors[] = "Please enter your coupon number.";
}
if (empty($pwd)) {
    $errors[] = "Please enter your password.";
}

if (!empty($errors)) {
    $_SESSION["errors_signin"] = $errors;
    $_SESSION["signin_data"] = [
        "coupon" => $coupon
    ];
    header("Location: signin.php");
    exit;
}

try {
    require_once "db.php";

    $query = "SELECT id, f_name, l_name, pwd, role 
              FROM users 
              WHERE coupon = ? 
              LIMIT 1";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$coupon]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && $pwd) {
        unset($_SESSION["signin_data"]);
        $_SESSION["user_id"]   = $user["id"];
        $_SESSION["user_name"] = $user["f_name"] . " " . $user["l_name"];
        $_SESSION["user_role"] = $user["role"]; 

        $_SESSION["success_signin"] = "Welcome back, " . htmlspecialchars($user["f_name"]) . "!";

    
        if ($user["role"] === "admin") {
            header("Location: admin_dashboard.php"); 
        } else {
            header("Location: dashboard.php"); 
        }
        exit;
    } else {
        $errors[] = "Invalid coupon number or password.";
        $_SESSION["errors_signin"] = $errors;
        $_SESSION["signin_data"] = [
            "coupon" => $coupon
        ];
        header("Location: signin.php");
        exit;
    }
} catch (PDOException $e) {
    error_log("Signin error: " . $e->getMessage() . " | IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    $_SESSION["signin_data"] = [
        "coupon" => $coupon
    ];
    $_SESSION["errors_signin"] = ["Login failed. Please try again later."];
    header("Location: signin.php");
    exit;
}
