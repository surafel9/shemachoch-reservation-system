<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["user_id"])) {
        header("Location: signin.php");
        exit();
    }

    $user_id = $_SESSION["user_id"];
    
    try {
        require_once("db.php");

        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User not found.");
        }

        $f_name   = !empty($_POST["f_name"]) ? $_POST["f_name"] : $user["f_name"];
        $l_name   = !empty($_POST["l_name"]) ? $_POST["l_name"] : $user["l_name"];
        $p_num    = !empty($_POST["p_num"]) ? $_POST["p_num"] : $user["p_num"];
        $h_num    = !empty($_POST["h_num"]) ? $_POST["h_num"] : $user["h_num"];
        $acc_num  = !empty($_POST["acc_num"]) ? $_POST["acc_num"] : $user["acc_num"];
        
        if (!empty($_POST["pwd"])) {
            $pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
        } else {
            $pwd = $user["pwd"];
        }

        $query = "UPDATE users SET f_name = :f_name, l_name = :l_name, p_num = :p_num, h_num = :h_num, pwd = :pwd, acc_num = :acc_num WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":f_name", $f_name);
        $stmt->bindValue(":l_name", $l_name);
        $stmt->bindValue(":p_num", $p_num);
        $stmt->bindValue(":h_num", $h_num);
        $stmt->bindValue(":pwd", $pwd);
        $stmt->bindValue(":acc_num", $acc_num);
        $stmt->bindValue(":id", $user_id);

        $stmt->execute();

      
        $_SESSION["user_name"] = $f_name . " " . $l_name;
        unset($_SESSION["update_data"]);

        header("Location: dashboard.php?update=success");
        exit();
    } catch (PDOException $e) {
        $_SESSION["update_data"] = [
            "f_name" => $_POST["f_name"] ?? '',
            "l_name" => $_POST["l_name"] ?? '',
            "p_num" => $_POST["p_num"] ?? '',
            "h_num" => $_POST["h_num"] ?? '',
            "acc_num" => $_POST["acc_num"] ?? ''
        ];
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
