<?php
session_start();
require_once("db.php");

$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;

$product_id = isset($_POST["product_id"]) ? $_POST["product_id"] : null;
$qty = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 0;

if (!$user_id || !$product_id || $qty <= 0) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT price, quantity, one_per_customer, name
    FROM products
    WHERE id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product || $product["quantity"] < $qty) {
    $_SESSION["error_buy"] = "Not enough stock for " . ($product ? $product['name'] : 'this item') . ".";
    header("Location: dashboard.php");
    exit;
}

if ($product["one_per_customer"]) {
    $check = $pdo->prepare("
        SELECT SUM(quantity) as total_reserved 
        FROM reservations 
        WHERE customer_id = ? AND product_id = ? 
        AND status IN ('pending', 'confirmed', 'picked_up')
    ");
    $check->execute([$user_id, $product_id]);
    $reserved = $check->fetch(PDO::FETCH_ASSOC);

    $totalReserved = isset($reserved["total_reserved"]) ? $reserved["total_reserved"] : 0;

    if ($totalReserved + $qty > 1) {
        $_SESSION["error_buy"] = "You can only buy/reserve one of this limited item.";
        header("Location: dashboard.php");
        exit;
    }
}

$stmt = $pdo->prepare("
    SELECT b.acc_num, b.balance
    FROM users u
    JOIN bank b ON u.acc_num = b.acc_num
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$bank = $stmt->fetch(PDO::FETCH_ASSOC);

$total = $product["price"] * $qty;

if ($bank["balance"] < $total) {
    $_SESSION["error_buy"] = "Insufficient balance. Needed: " . number_format($total, 2);
    header("Location: dashboard.php");
    exit;
}

try {
    $pdo->beginTransaction();

    $newBalance = $bank["balance"] - $total;
    $pdo->prepare("UPDATE bank SET balance = ? WHERE acc_num = ?")
        ->execute([$newBalance, $bank["acc_num"]]);

    $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?")
        ->execute([$qty, $product_id]);

    $checkPending = $pdo->prepare("
        SELECT id, quantity 
        FROM reservations 
        WHERE customer_id = ? AND product_id = ? AND status = 'pending'
        LIMIT 1
    ");
    $checkPending->execute([$user_id, $product_id]);
    $existing = $checkPending->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $newQty = $existing["quantity"] + $qty;
        $pdo->prepare("
            UPDATE reservations 
            SET quantity = ?, status = 'confirmed' 
            WHERE id = ?
        ")->execute([$newQty, $existing["id"]]);
    } else {
        $pdo->prepare("
            INSERT INTO reservations (customer_id, product_id, quantity, status)
            VALUES (?, ?, ?, 'confirmed')
        ")->execute([$user_id, $product_id, $qty]);
    }

    $pdo->prepare("
        INSERT INTO transactions (user_id, acc_num, amount, balance_after, status)
        VALUES (?, ?, ?, ?, 'completed')
    ")->execute([$user_id, $bank["acc_num"], $total, $newBalance]);

    $pdo->commit();

    $_SESSION["success_buy"] = "Successfully bought {$qty} × {$product["name"]}! Total: " . number_format($total, 2);
    header("Location: dashboard.php");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Buy product failed: " . $e->getMessage());
    $_SESSION["error_buy"] = "Purchase failed. Please try again.";
    header("Location: dashboard.php");
    exit;
}
