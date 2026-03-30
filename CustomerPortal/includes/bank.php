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
    <?php
    $title = "Bank Signup";
    require_once("../header.php");
    ?>

    <div class="main">
        <h1 >Signup to Bank</h1>

        <form action="bankhelper.php" method="post">
            <label>Account Number</label>
            <input type="text" name="acc_num" placeholder="Account Number">
            <label>First Name </label>
            <input type="text" name="f_name" placeholder="First Name">
            <label>Last Name</label>
            <input type="text" name="l_name" placeholder="Last Name">
            <label>Phone Number</label>
            <input type="text" name="p_num" placeholder="Phone Number">
            <label>Password</label>
            <input type="password" name="pwd" placeholder="Password">
            <label>Balance</label>
            <input type="text" name="balance" placeholder="Balance">
            <div class="form-actions">
                <button type="submit">Signup</button>
                <button type="reset">Clear</button>
            </div>
        </form>

        <div class="messages">
            <?php
            session_start();
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

    <?php require_once("../footer.php"); ?>
</body>

</html>