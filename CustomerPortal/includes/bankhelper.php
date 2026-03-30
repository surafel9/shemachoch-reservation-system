<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $acc_num = trim($_POST["acc_num"] ?? '');
    $f_name  = trim($_POST["f_name"] ?? '');
    $l_name  = trim($_POST["l_name"] ?? '');
    $p_num   = trim($_POST["p_num"] ?? '');
    $pwd     = $_POST["pwd"] ?? '';
    $balance = trim($_POST["balance"] ?? '');

    $errors = [];

    try {
        require_once "db.php";
        require_once "errorhandlers.php";

        if (empty($acc_num)) {
            $errors["acc_num"] = "Please enter an Account Number";
        } elseif (dupeAcc($acc_num, $pdo)) {
            $errors["duplicate_acc"] = "This Account Number is already registered";
        }

        if (empty($f_name)) {
            $errors["f_name"] = "Please enter First Name";
        }
        if (empty($l_name)) {
            $errors["l_name"] = "Please enter Last Name";
        }

        if (empty($p_num)) {
            $errors["p_num"] = "Please enter your Phone Number";
        } elseif (dupePhone($p_num, $pdo)) {
            $errors["duplicate_phone"] = "This Phone Number is already registered";
        }

        if (empty($pwd)) {
            $errors["pwd"] = "Please enter a Password";
        }

        if (empty($balance) || !is_numeric($balance) || $balance < 0) {
            $errors["balance"] = "Please enter a valid Balance (number >= 0)";
        }

        if (!empty($errors)) {
            $_SESSION["errors_bank"] = $errors;
            header("Location: bank.php");
            exit;
        }

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        $query = "INSERT INTO bank (acc_num, f_name, l_name, p_num, pwd, balance)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$acc_num, $f_name, $l_name, $p_num, $hashedPwd, $balance]);

        $_SESSION["success_bank"] = "Account created successfully! You can now log in.";

        unset($_SESSION["errors_bank"]);

        header("Location: bank.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $errors[] = "Account or Phone Number already exists.";
        } else {
            $errors[] = "Signup failed. Please try again later.";
            error_log("Bank signup error: " . $e->getMessage());
        }
        $_SESSION["errors_bank"] = $errors;
        header("Location: bank.php");
        exit;
    }
}
