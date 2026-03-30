<?php

require_once("errorhandling.php");
function dupePhone($phone, $pdo)
{
    if (findPhone($phone, $pdo)) {
        return true;
    } else {
        return false;
    }
}

// function dupeCoupon($coupon, $pdo)
// {
//     if (findCoupon($coupon, $pdo)) {
//         return true;
//     } else {
//         return false;
//     }
// }


function dupeAcc($acc, $pdo)
{
    if (findBankAcc($acc, $pdo)) {
        return true;
    } else {
        return false;
    }
}

function invalidBankacc($acc_num, $pdo)
{
    return !findBankAcc($acc_num, $pdo);
}

// function isValidCoupon($coupon, $pdo)
// {
//     $query = "SELECT id FROM coupons 
//               WHERE coupon_number = :coupon 
//               AND state = 'unassigned' 
//               AND (expiry_date IS NULL OR expiry_date >= CURDATE())
//               LIMIT 1";
//     $stmt = $pdo->prepare($query);
//     $stmt->bindParam(":coupon", $coupon);
//     $stmt->execute();
//     return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
// }

function isValidCoupon($coupon, $pdo)
{
    $query = "SELECT id FROM coupons 
              WHERE coupon_number = :coupon 
              AND state = 'unassigned'
              AND (expiry_date IS NULL OR expiry_date = '0000-00-00' OR expiry_date >= CURDATE())
              LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":coupon", $coupon);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
