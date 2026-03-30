<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        header("Location: signin.php");
        exit();
    }

    $user_id = $_SESSION["user_id"];

    try {
        require_once("db.php");

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE coupons SET user_id = NULL, state = 'unassigned' WHERE user_id = :id");
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM reservations WHERE customer_id = :id");
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM transactions WHERE user_id = :id");
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();

        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();

        $pdo->commit();

        session_unset();
        session_destroy();
        
        header("Location: ../index.php");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
}
