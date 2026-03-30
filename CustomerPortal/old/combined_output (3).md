========================================
File: index.php
========================================
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shemachoch</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="container">
        <header class="top-bar">
            <div class="top-bar-content">
                <span class="logo"><a href="index.php">
                        Shemachoch
                    </a></span>

                <div class="theme-toggle">
                    <span class="mode-text">Dark mode</span>
                    <label class="switch">
                        <input type="checkbox" id="darkToggle">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </header>

        <div class="welcome">

            <div class="img-div">
                <img src="images/groceries.jpg" alt="groceries" class="main-img">
            </div>

            <div class="right-content">
                <div class="heading-div">
                    <h1 class="heading">Welcome to Shemachoch</h1>
                </div>

                <div class="links-div">
                    <a href="includes/register.php" class="link register">Register </a>
                    <a href="includes/signin.php" class="link signin">Sign in</a>
                    <a href="includes/dashboard.php" class="link dashboard">Dashboard</a>
                </div>
            </div>

        </div>
    </div>

    <script>
        const toggle = document.getElementById("darkToggle");

        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark");
            toggle.checked = true;
        }

        toggle.addEventListener("change", () => {
            document.body.classList.toggle("dark");
            localStorage.setItem(
                "theme",
                document.body.classList.contains("dark") ? "dark" : "light"
            );
        });
    </script>

</body>

</html>
========================================
File: bank.php
========================================
```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Bank</title>
</head>

<body>

    <div class="container main">
        <h1>Signup to Bank</h1>

        <form action="bankhelper.php" method="post">
            <label>Account Number</label>
            <input type="text" name="acc_num" placeholder="Account Number"> <br>
            <label>First Name </label>
            <input type="text" name="f_name" placeholder="First Name"> <br>
            <label>Last Name</label>
            <input type="text" name="l_name" placeholder="Last Name"> <br>
            <label>Phone Number</label>
            <input type="text" name="p_num" placeholder="Phone Number"> <br>
            <label>Password</label>
            <input type="password" name="pwd" placeholder="Password"> <br>
            <label>Balance</label>
            <input type="text" name="balance" placeholder="Balance"> <br>
            <button>Signup</button>
            <button type="reset">Clear</button>
        </form>
        <?php
        session_start();   ?>
        <div class="messages">
            <?php
            if (!empty($_SESSION["success_bank"])) {
                echo "<p class='success'>" . htmlspecialchars($_SESSION["success_bank"]) . "</p>";
                unset($_SESSION["success_bank"]);
            }

            if (!empty($_SESSION["errors_bank"])) {
                echo "<div class='errors'>";
                foreach ($_SESSION["errors_bank"] as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                }
                echo "</div>";
                unset($_SESSION["errors_bank"]);
            }
            ?>
        </div>
    </div>
</body>

</html>
```
========================================

========================================
File: bankhelper.php
========================================
```php
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

```
========================================

========================================
File: buyproduct.php
========================================
```php
<?php
session_start();
require_once("db.php");

$user_id = $_SESSION["user_id"] ?? null;
$product_id = $_POST["product_id"] ?? null;
$qty = (int)($_POST["quantity"] ?? 0);

if (!$user_id || !$product_id || $qty <= 0) {
    header("Location: dashboard.php");
    exit;
}

/* Fetch product */
$stmt = $pdo->prepare("
    SELECT price, quantity, one_per_customer
    FROM products
    WHERE id = ?
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product || $product["quantity"] < $qty) {
    header("Location: dashboard.php");
    exit;
}

/* Enforce one_per_customer */
if ($product["one_per_customer"]) {
    $check = $pdo->prepare("
        SELECT 1 FROM reservations
        WHERE customer_id = ? AND product_id = ?
        AND status IN ('pending','confirmed','picked_up')
    ");
    $check->execute([$user_id, $product_id]);
    if ($check->fetch()) {
        header("Location: dashboard.php");
        exit;
    }
}

/* Fetch bank info */
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
    header("Location: dashboard.php");
    exit;
}

/* Begin purchase */
$pdo->beginTransaction();

/* Deduct balance */
$newBalance = $bank["balance"] - $total;
$pdo->prepare("
    UPDATE bank SET balance = ? WHERE acc_num = ?
")->execute([$newBalance, $bank["acc_num"]]);

/* Reduce product quantity */
$pdo->prepare("
    UPDATE products SET quantity = quantity - ?
    WHERE id = ?
")->execute([$qty, $product_id]);

/* Create reservation */
$pdo->prepare("
    INSERT INTO reservations (customer_id, product_id, quantity)
    VALUES (?, ?, ?)
")->execute([$user_id, $product_id, $qty]);

/* Record transaction */
$pdo->prepare("
    INSERT INTO transactions (user_id, acc_num, amount, balance_after, status)
    VALUES (?, ?, ?, ?, 'completed')
")->execute([$user_id, $bank["acc_num"], $total, $newBalance]);

$pdo->commit();

header("Location: dashboard.php");
exit;

```
========================================

========================================
File: dashboard.php
========================================
```php
<?php
$title = "Dashboard";
require_once("../header.php");
require_once("db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("
    SELECT u.f_name, b.balance, b.acc_num
    FROM users u
    JOIN bank b ON u.acc_num = b.acc_num
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$productsStmt = $pdo->query("
    SELECT *
    FROM products
");
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
?>


<h1>Welcome, <?= $user["f_name"] ?></h1>
<p><strong>Balance:</strong> <?= number_format($user["balance"], 2) ?></p>

<h2>Products</h2>

<?php if (empty($products)): ?>
    <p>No products available.</p>
<?php else: ?>

    <table border="0" cellpadding="10">
        <tr>
            <th>Item</th>
            <th>Name</th>
            <th>Price</th>
            <th>Available</th>
            <th>Action</th>
        </tr>

        <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="<?= $product["image_url"] ?>" alt="item image"> </td>
                <td><?= $product["name"] ?></td>
                <td><?= number_format($product["price"], 2) ?></td>
                <td><?= $product["quantity"] ?></td>
                <td>
                    <form action="buyproduct.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product["id"] ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product["quantity"] ?>">
                        <button type="submit">Buy</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<br> <a href="updateaccount.php">Edit Profile</a>
<br> <a href="deleteaccount.php">Delete Account</a>
</div>
<?php require_once("../footer.php"); ?>
```
========================================

========================================
File: db.php
========================================
```php
<?php
$dsn = "mysql:host=localhost;dbname=shemachoch3_db";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

```
========================================

========================================
File: deleteaccount.php
========================================
```php
<?php require_once("../header.php"); ?>
<h1>Delete Account</h1>
<form action="deleteaccounthelper.php" method="post">
    <label>First Name </label>
    <input type="text" name="f_name" placeholder="First Name" required> <br>
    <label>Last Name</label>
    <input type="text" name="l_name" placeholder="Last Name" required> <br>
    <label>Phone Number</label>
    <input type="text" name="p_num" placeholder="Phone Number" required> <br>
    <label>Password</label>
    <input type="password" name="pwd" placeholder="Password" required> <br>
    <button>Delete</button>
</form>
</div>
<?php require_once("../footer.php"); ?>
```
========================================

========================================
File: deleteaccounthelper.php
========================================
```php
<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $f_name = $_POST["f_name"];
    $l_name = $_POST["l_name"];
    $phone_no = $_POST["phone_no"];
    $pwd = $_POST["pwd"];

    try {
        require_once("db.php");

        $query = "SELECT pwd FROM users WHERE id = :id AND l_name = :l_name AND phone_no = :phone_no;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":f_name", $f_name);
        $stmt->bindParam(":l_name", $l_name);
        $stmt->bindValue(":phone_no", $phone_no);
        $stmt->execute();
        $user_found = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_found && password_verify($pwd, $user_found['pwd'])) {
            $query = "DELETE FROM users WHERE phone_no = :phone_no";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":phone_no", $phone_no);
            $stmt->execute();
        } else {
            echo "User Not Found!<br>";
        }
        $pdo = null;
        $stmt = null;
        header("Location: ../index.php");
        die();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
}

```
========================================

========================================
File: errorhandlers.php
========================================
```php
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

```
========================================

========================================
File: errorhandling.php
========================================
```php
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

```
========================================

========================================
File: register.php
========================================
```php
<?php require_once("../header.php"); ?>

<h1>Sign Up</h1>

<?php

if (!empty($_SESSION["success_signup"])) {
    echo "<p class='success'>" . htmlspecialchars($_SESSION["success_signup"]) . "</p>";
    unset($_SESSION["success_signup"]);
}

if (!empty($_SESSION["errors_signup"])) {
    echo "<div class='errors'>";
    foreach ($_SESSION["errors_signup"] as $error) {
        echo "<p>" . htmlspecialchars($error) . "</p>";
    }
    echo "</div>";
    unset($_SESSION["errors_signup"]);
}
?>

<form action="registerhelper.php" method="post">
    <label>First Name</label>
    <input type="text" name="f_name" placeholder="First Name" required> <br>

    <label>Last Name</label>
    <input type="text" name="l_name" placeholder="Last Name" required> <br>

    <label>Phone Number</label>
    <input type="text" name="p_num" placeholder="Phone Number" required> <br>

    <label>House Number</label>
    <input type="text" name="h_num" placeholder="House Number" required> <br>

    <label>Password</label>
    <input type="password" name="pwd" placeholder="Password" required> <br>

    <label>Bank Account Number</label>
    <input type="text" name="acc_num" placeholder="Bank Account Number" required> <br>

    <label>Coupon Code</label>
    <input type="text" name="coupon" placeholder="Enter your coupon code" required> <br>

    <button type="submit">Sign Up</button>
    <button type="reset">Clear</button>
</form>

<p>Already have an account? <a href="signin.php">Sign In</a></p>
</div>
<?php require_once("../footer.php"); ?>
```
========================================

========================================
File: registerhelper.php
========================================
```php
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $u_role        = "customer";
    $f_name        = trim($_POST["f_name"] ?? '');
    $l_name        = trim($_POST["l_name"] ?? '');
    $p_num         = trim($_POST["p_num"] ?? '');
    $h_num         = trim($_POST["h_num"] ?? '');
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
            header("Location: register.php");
            exit;
        }

        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 12]);

        $pdo->beginTransaction();

        $query = "INSERT INTO users (role, f_name, l_name, p_num, h_num, pwd, acc_num, coupon) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        // $stmt = $pdo->prepare($query);
        // $stmt->execute([$u_role, $f_name, $l_name, $p_num, $h_num, $hashedPwd, $acc_num, $coupon]);
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

        $_SESSION["success_signup"] = "Registration successful! You can now sign in.";
        header("Location: signin.php");
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        error_log("Signup error: " . $e->getMessage());

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

```
========================================

========================================
File: searchitems.php
========================================
```php
<?php
require_once("../header.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usersearch = $_POST["usersearch"];

    try {
        require_once("db.php");

        $query = "SELECT * FROM users WHERE f_name = :usersearch;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":usersearch", $usersearch);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
}


echo "<h3>Search Results for '$usersearch':</h3>";

if (empty($results)) {
    echo "<p>No Such User!</p><br>";
} else {
    foreach ($results as $result) {
        echo "<p>First Name: {$result['f_name']} </p>";
        echo "<p>Last Name: {$result['l_name']} </p>";
        echo "<p>Phone Number: {$result['p_num']} </p>";
        echo "<p>House Number: {$result['h_num']} </p>";
        echo "<p>Password: {$result['pwd']} </p>";
        echo "<p>Account Number: {$result['acc_num']} </p>";
        echo "<p>Account Created At: {$result['created_at']} </p>";
    }
}
require_once("../footer.php");

```
========================================

========================================
File: signin.php
========================================
```php
<?php require_once("../header.php"); ?>
<h1>Sign In</h1>

<?php

if (!empty($_SESSION["success_signin"])) {
    echo "<p class='success'>" . htmlspecialchars($_SESSION["success_signin"]) . "</p>";
    unset($_SESSION["success_signin"]);
}

if (!empty($_SESSION["errors_signin"])) {
    echo "<div class='errors'>";
    foreach ($_SESSION["errors_signin"] as $error) {
        echo "<p>" . htmlspecialchars($error) . "</p>";
    }
    echo "</div>";
    unset($_SESSION["errors_signin"]);
}
?>

<form action="signinhelper.php" method="post">
    <label>Phone Number</label>
    <input type="text" name="p_num" placeholder="Phone Number" required> <br>

    <label>Password</label>
    <input type="password" name="pwd" placeholder="Password" required> <br>

    <button type="submit">Sign In</button>
    <button type="reset">Clear</button>
</form>

<p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>
<?php require_once("../footer.php"); ?>
```
========================================

========================================
File: signinhelper.php
========================================
```php
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: signin.php");
    exit;
}

$p_num = trim($_POST["p_num"] ?? '');
$pwd   = $_POST["pwd"] ?? '';

$errors = [];

if (empty($p_num)) {
    $errors[] = "Please enter your phone number.";
}
if (empty($pwd)) {
    $errors[] = "Please enter your password.";
}

if (!empty($errors)) {
    $_SESSION["errors_signin"] = $errors;
    header("Location: signin.php");
    exit;
}

try {
    require_once "db.php";

    // $clean_phone = preg_replace("/[^\d+]/", "", $p_num);
    // if (strlen($clean_phone) > 1 && $clean_phone[0] !== '+') {
    // }

    $query = "SELECT id, f_name, l_name, pwd FROM users WHERE p_num = ? LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$p_num]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($pwd, $user['pwd'])) {

        $_SESSION["user_id"]   = $user["id"];
        $_SESSION["user_name"] = $user["f_name"] . " " . $user["l_name"];

        $_SESSION["success_signin"] = "Welcome back, " . $user["f_name"] . "!";

        header("Location: dashboard.php");
        exit;
    } else {
        $errors[] = "Invalid phone number or password.";
        $_SESSION["errors_signin"] = $errors;
        header("Location: signin.php");
        exit;
    }
} catch (PDOException $e) {
    error_log("Signin error: " . $e->getMessage() . " | IP: " . $_SERVER['REMOTE_ADDR']);
    $_SESSION["errors_signin"] = ["Login failed. Please try again later."];
    header("Location: signin.php");
    exit;
}

```
========================================

========================================
File: updateaccount.php
========================================
```php
<?php require_once("../header.php"); ?>
<h1>Update Account</h1>

<form action="updateaccounthelper.php" method="post">
    <label>First Name </label>
    <input type="text" name="f_name" placeholder="First Name"> <br>
    <label>Last Name</label>
    <input type="text" name="l_name" placeholder="Last Name"> <br>
    <label>Phone Number</label>
    <input type="text" name="p_num" placeholder="Phone Number"> <br>
    <label>House Number</label>
    <input type="text" name="h_num" placeholder="House Number"> <br>
    <label>Password</label>
    <input type="text" name="pwd" placeholder="Password"> <br>
    <label>Bank Account Number</label>
    <input type="text" name="acc_num" placeholder="Account Number"> <br>
    <button>Update</button>
</form>
</div>
<?php require_once("../footer.php"); ?>
```
========================================

========================================
File: updateaccounthelper.php
========================================
```php
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $f_name   = $_POST["f_name"];
    $l_name   = $_POST["l_name"];
    $phone_no = $_POST["phone_no"];
    $house_no = $_POST["house_no"];
    $acc_no   = $_POST["acc_no"];
    $pwd = $_POST["pwd"];
    $id  = $_SESSION["id"];

    if (empty($f_name)) {
        $f_name = $_SESSION["f_name"];
        $_SESSION["f_name"] = $f_name;
    }
    if (empty($l_name)) {
        $l_name = $_SESSION["l_name"];
        $_SESSION["l_name"] = $l_name;
    }
    if (empty($phone_no)) {
        $phone_no = $_SESSION["phone_no"];
        $_SESSION["phone_no"] = $phone_no;
    }
    if (empty($house_no)) {
        $house_no = $_SESSION["house_no"];
        $_SESSION["house_no"] = $house_no;
    }
    if (empty($acc_no)) {
        $acc_no = $_SESSION["acc_no"];
        $_SESSION["acc_no"] = $acc_no;
    }
    if (empty($pwd)) {
        $pwd = $_SESSION["pwd"];
    } else {
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $_SESSION["pwd"] = $pwd;
    }

    try {
        require_once("db.php");

        $query = "UPDATE users SET f_name = :f_name, l_name = :l_name, phone_no = :phone_no, house_no = :house_no, pwd = :pwd, acc_no = :acc_no WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":f_name", $f_name);
        $stmt->bindValue(":l_name", $l_name);
        $stmt->bindValue(":phone_no", $phone_no);
        $stmt->bindValue(":house_no", $house_no);
        $stmt->bindValue(":pwd", $pwd);
        $stmt->bindValue(":acc_no", $acc_no);
        $stmt->bindValue(":id", $_SESSION["id"]);

        $stmt->execute();

        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}

```
========================================

========================================
File: style.css
========================================
:root {
    --primary-color: #0d6efd;
    --card-border: #ddd;
    --accent-color: #FDCEC0;
    --greenish: #c8f8e2;
    --violetish: #dbe4fe;
}

body,
html {
    height: 100%;
    transition: opacity 0.5s ease-in-out;
    scroll-behavior: smooth;
    /* background-color: var(--card-border); */
}

@media (max-width: 768px) {
    .welcome {
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .right-content,
    .img-div {
        width: 90%;
        padding: 1rem;
    }
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    /* height: 100%; */
    display: flex;
    flex-direction: column;
    /* min-height: 100vh; */
    /* max-height: 100vh; */
}

/* .welcome {
    display: flex;
    align-items: center;
    justify-content: space-evenly;
    background-image: linear-gradient(to right, #FFF, var(--card-border), #FFF);
    height: 100%;
    min-height: 0;
} */

.right-content {
    width: 50%;
    padding: 2rem;
    display: flex;
    flex-direction: column;
}

.links-div {
    padding: 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    align-items: stretch;
}

.dashboard {
    grid-column: 1 / -1;
}

.links-div a {
    display: block;
    text-align: center;
    padding: 1rem;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--card-border);

    background-color: white;
    border-radius: 8px;
    font-weight: 600;
}

.main-img {
    max-width: 100%;
    border-radius: 8px;
}

.heading-div {
    font-size: 1.5rem;
}

.img-div {
    padding: 2rem;
    width: 50%;
}

a {
    color: #333;
    text-decoration: none;
    font-size: large;
    /* transition: color 0.3s ease, transform 0.2s ease; */
    transition: transform 0.3s ease, background-color 0.3s ease;
}

a:hover {
    text-decoration: underline;
    color: #34c300;
    /* transform: scale(1.2); */
}

.links-div a:hover {
    text-decoration: underline;
    color: #34c300;
    transform: scale(1.2);
}

.top-bar {
    width: 100%;
    height: 60px;
    background-color: white;
    border-bottom: 1px solid var(--card-border);
    position: sticky;
    top: 0;
    z-index: 100;
}

.top-bar-content {
    /* max-width: 1200px; */
    height: 100%;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    font-size: 1.2rem;
    font-weight: bold;
}

.theme-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
}

.switch {
    position: relative;
    width: 50px;
    height: 26px;
}

.switch input {
    opacity: 0;
}

.slider {
    position: absolute;
    inset: 0;
    background-color: #ccc;
    border-radius: 26px;
    transition: 0.3s;
    cursor: pointer;
}

.slider::before {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: 0.3s;
}

input:checked+.slider {
    background-color: var(--primary-color);
}

input:checked+.slider::before {
    transform: translateX(24px);
}

/* Forms - make them look modern and consistent */
.main {
    padding: 2rem;
    max-width: 600px;
    margin: 2rem auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

body.dark .main {
    background: #2a2a2a;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
}

form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

label {
    font-weight: 600;
    color: #333;
}

body.dark label {
    color: #f1f1f1;
}

input[type="text"],
input[type="password"],
input[type="number"] {
    padding: 0.8rem 1rem;
    border: 1px solid var(--card-border);
    border-radius: 8px;
    font-size: 1rem;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}

input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
}

button {
    padding: 0.8rem 1.5rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}

button:hover {
    background: #0b5ed7;
    transform: translateY(-2px);
}

button[type="reset"] {
    background: #6c757d;
}

button[type="reset"]:hover {
    background: #5a6268;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

body.dark table {
    background: #2a2a2a;
}

th,
td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--card-border);
}

body.dark th,
body.dark td {
    border-bottom-color: #444;
}

th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
}

tr:hover {
    background: #f8f9fa;
}

body.dark tr:hover {
    background: #333;
}

td img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.welcome {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4rem;
    padding: 4rem 2rem;
    min-height: calc(100vh - 60px);
    flex-wrap: wrap;
}

@media (max-width: 992px) {
    .welcome {
        flex-direction: column;
        text-align: center;
        gap: 2rem;
    }

    .img-div,
    .right-content {
        width: 100%;
        max-width: 600px;
    }
}

.heading {
    font-size: 3rem;
    font-weight: bold;
    color: #222;
    margin-bottom: 1.5rem;
}

body.dark .heading {
    color: #f1f1f1;
}

.success {
    color: green;
    background: #e0ffe0;
    padding: 15px;
    border-radius: 5px;
    margin: 15px 0;
    font-weight: bold;
}

.errors {
    color: red;
    background: #ffe0e0;
    padding: 15px;
    border-radius: 5px;
    margin: 15px 0;
}

.errors p {
    margin: 5px 0;
}

/******************** Dark mode ********************/
body.dark {
    background-color: #1e1e1e;
    color: #f1f1f1;
}

body.dark .top-bar {
    background-color: #1e1e1e;
    border-bottom-color: #333;
}

body.dark .welcome {
    background-color: #1e1e1e;
}

body.dark .links-div a {
    background-color: #2a2a2a;
    border-color: #444;
    color: whitesmoke;
}

body.dark .links-div a:hover {
    text-decoration: underline;
    color: #34c300;
    transform: scale(1.2);
    background-color: #3a3a3a;
}

body.dark a {
    color: #a0d8ff;
}

body.dark a:hover {
    color: #34c300;
}

body.dark input[type="text"],
body.dark input[type="password"],
body.dark input[type="number"] {
    background: #333;
    border-color: #444;
    color: #f1f1f1;
}

body.dark input::placeholder {
    color: #aaa;
}

body,
.top-bar,
.welcome {
    transition: background-color 0.3s ease, color 0.3s ease;
}