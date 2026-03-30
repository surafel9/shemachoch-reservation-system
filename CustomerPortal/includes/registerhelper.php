<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $u_role        = "customer";
    $f_name        = trim($_POST["f_name"] ?? '');
    $l_name        = trim($_POST["l_name"] ?? '');
    $p_num         = trim($_POST["p_num"] ?? '');
    $h_num         = "House";
    $pwd           = $_POST["pwd"] ?? '';
    $acc_num       = (int) trim($_POST["acc_num"] ?? '0');
    $coupon = trim($_POST["coupon"] ?? '');

    $errors = [];

    try {
        require_once("db.php");
        require_once("errorhandlers.php");

        if (empty($f_name) || empty($l_name)) {
            $errors[] = "Please enter both First and Last Name";
        }

        if (empty($p_num)) {
            $errors[] = "Please enter your Phone Number";
        } elseif (dupePhone($p_num, $pdo)) {
            $errors[] = "This Phone Number is already registered";
        }

        if (empty($h_num)) {
            $errors[] = "Please enter your House Number";
        }

        if (empty($pwd)) {
            $errors[] = "Please enter a Password";
        } elseif (strlen($pwd) < 6) {
            $errors[] = "Password must be at least 6 characters";
        }

        if ($acc_num <= 0) {
            $errors[] = "Invalid Bank Account Number";
        } elseif (invalidBankacc($acc_num, $pdo)) {
            $errors[] = "This Bank Account Number does not exist in our bank system";
        }

        if (empty($coupon)) {
            $errors[] = "Please enter your coupon code";
        } elseif (!isValidCoupon($coupon, $pdo)) {
            $errors[] = "Invalid or expired coupon code";
        }

        if (!empty($errors)) {
            $_SESSION["errors_signup"] = $errors;
            $_SESSION["signup_data"] = [
                "f_name" => $f_name,
                "l_name" => $l_name,
                "p_num" => $p_num,
                "acc_num" => $acc_num,
                "coupon" => $coupon
            ];
            header("Location: register.php");
            exit;
        }

        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);

        $pdo->beginTransaction();

        $query = "INSERT INTO users (role, f_name, l_name, p_num, h_num, pwd, acc_num, coupon) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        // $stmt = $pdo->prepare($query);
        // $stmt->execute([$u_role, $f_name, $l_name, $p_num, $hashedPwd, $acc_num, $coupon]);
        $stmt = $pdo->prepare($query);
        $stmt->execute([$u_role, $f_name, $l_name, $p_num, $h_num, $hashedPwd, $acc_num, $coupon]);

        $user_id = $pdo->lastInsertId();

        $updateCoupon = "UPDATE coupons 
                         SET state = 'assigned', user_id = ? 
                         WHERE coupon_number = ? AND state = 'unassigned'";
        $stmt2 = $pdo->prepare($updateCoupon);
        $stmt2->execute([$user_id, $coupon]);

        if ($stmt2->rowCount() === 0) {
            $pdo->rollBack();
            $_SESSION["errors_signup"] = ["This coupon is no longer available. It may have been used by someone else."];
            header("Location: register.php");
            exit;
        }

        $pdo->commit();

        unset($_SESSION["signup_data"]);
        $_SESSION["success_signup"] = "Registration successful! You can now sign in.";
        header("Location: signin.php");
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        error_log("Signup error: " . $e->getMessage());

        $_SESSION["signup_data"] = [
            "f_name" => $f_name,
            "l_name" => $l_name,
            "p_num" => $p_num,
            "acc_num" => $acc_num,
            "coupon" => $coupon
        ];
        $_SESSION["errors_signup"] = [
            "Database error: " . htmlspecialchars($e->getMessage())
        ];
        header("Location: register.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
