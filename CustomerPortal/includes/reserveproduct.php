<?php
session_start();
require_once("db.php");

$user_id = $_SESSION["user_id"] ?? null;
$product_id = $_POST["product_id"] ?? null;

if (!$user_id || !$product_id) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM products 
    WHERE id = ? 
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product || $product["status"] !== 'Coming Soon') {
    header("Location: dashboard.php");
    exit;
}

if ($product["one_per_customer"]) {
    $check = $pdo->prepare("
        SELECT 1 FROM reservations 
        WHERE customer_id = ? AND product_id = ? 
        AND status IN ('pending', 'confirmed', 'picked_up')
    ");
    $check->execute([$user_id, $product_id]);
    if ($check->fetch()) {
        $_SESSION["error_reserve"] = "You can only reserve one of this item.";
        header("Location: dashboard.php");
        exit;
    }
}

// Check if already pending reservation
$checkPending = $pdo->prepare("
    SELECT 1 FROM reservations 
    WHERE customer_id = ? AND product_id = ? AND status = 'pending'
");
$checkPending->execute([$user_id, $product_id]);
if ($checkPending->fetch()) {
    $_SESSION["error_reserve"] = "You have already reserved this item.";
    header("Location: dashboard.php");
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO reservations (customer_id, product_id, quantity, status, notes)
        VALUES (?, ?, 1, 'pending', 'Reserved for future availability')
    ");
    $stmt->execute([$user_id, $product_id]);

    $pdo->commit();

    $_SESSION["success_reserve"] = "Successfully reserved '{$product["name"]}'! We'll notify you when it's available.";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION["error_reserve"] = "Failed to reserve item. Please try again.";
}

header("Location: dashboard.php");
exit;
