<?php
function findPhone(string $phone, object $pdo)
{
    $query = "SELECT id FROM users WHERE p_num = :p_num;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":p_num", $phone);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function findEmail(string $email, object $pdo)
{
    $query = "SELECT id FROM users WHERE email = :email;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function findCoupon(string $coupon, object $pdo)
{
    $query = "SELECT id FROM coupons WHERE coupon_number = :coupon LIMIT 1;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":coupon", $coupon);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

// function findCoupon(string $coupon, object $pdo)
// {
//     $query = "SELECT id FROM coupons WHERE coupon_number = :coupon LIMIT 1;";
//     $stmt = $pdo->prepare($query);
//     $stmt->bindParam(":coupon", $coupon);
//     $stmt->execute();
//     return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
// }


function findBankAcc(string $acc, object $pdo)
{
    $query = "SELECT acc_num FROM bank WHERE acc_num = :acc LIMIT 1;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":acc", $acc);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result !== false;
}
