<?php require_once("../header.php"); ?>

<div class="main">
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

    $signup_data = $_SESSION["signup_data"] ?? [];
    unset($_SESSION["signup_data"]);
    ?>

    <form action="registerhelper.php" method="post">
        <label>First Name</label>
        <input type="text" name="f_name" placeholder="First Name" required value="<?= htmlspecialchars($signup_data['f_name'] ?? '') ?>">

        <label>Last Name</label>
        <input type="text" name="l_name" placeholder="Last Name" required value="<?= htmlspecialchars($signup_data['l_name'] ?? '') ?>">

        <label>Phone Number</label>
        <input type="text" name="p_num" placeholder="Phone Number" required value="<?= htmlspecialchars($signup_data['p_num'] ?? '') ?>">

        <label>Password</label>
        <input type="password" name="pwd" placeholder="Password" required>

        <label>Bank Account Number</label>
        <input type="text" name="acc_num" placeholder="Bank Account Number" required value="<?= htmlspecialchars($signup_data['acc_num'] ?? '') ?>">

        <label>Coupon Code</label>
        <input type="text" name="coupon" placeholder="Enter your coupon code" required value="<?= htmlspecialchars($signup_data['coupon'] ?? '') ?>">

        <div class="form-actions">
            <button type="submit">Sign Up</button>
            <button type="reset">Clear</button>
        </div>
    </form>

    <div class="auth-links">
        <p>Already have an account? <a href="signin.php">Sign In</a></p>
    </div>
</div>

<?php require_once("../footer.php"); ?>