<?php require_once("../header.php"); ?>

<div class="main">
    <h1>Sign In</h1>

    <?php
    if (!empty($_SESSION["success_signin"])): ?>
        <p class="success"><?= htmlspecialchars($_SESSION["success_signin"]) ?></p>
        <?php unset($_SESSION["success_signin"]); ?>
    <?php endif; ?>

    <?php
    if (!empty($_SESSION["errors_signin"])): ?>
        <div class="errors">
            <?php foreach ($_SESSION["errors_signin"] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION["errors_signin"]); ?>
    <?php endif; ?>

    <?php
    $signin_data = $_SESSION["signin_data"] ?? [];
    unset($_SESSION["signin_data"]);
    ?>

    <form action="signinhelper.php" method="post">
        <label>Coupon Number</label>
        <input type="text" name="coupon" placeholder="Coupon Number" required value="<?= htmlspecialchars($signin_data['coupon'] ?? '') ?>">

        <label>Password</label>
        <input type="password" name="pwd" placeholder="Password" required>

        <div class="form-actions">
            <button type="submit">Sign In</button>
            <button type="reset">Clear</button>
        </div>
    </form>

    <div class="auth-links">
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
        <p>Are you an Admin? <a href="adminsignin.php" class="link admin">Admin Page</a></p>
    </div>
</div>

<?php require_once("../footer.php"); ?>