<?php require_once("../header.php"); ?>

<div class="main">
    <h1>Admin Sign In</h1>

    <?php if (!empty($_SESSION["errors_admin_signin"])): ?>
        <div class="errors">
            <?php foreach ($_SESSION["errors_admin_signin"] as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach;
            unset($_SESSION["errors_admin_signin"]); ?>
        </div>
    <?php endif; ?>

    <form action="adminsigninhelper.php" method="post">
        <label>Email</label>
        <input type="text" name="email" placeholder="admin@example.com" required>

        <label>Password</label>
        <input type="password" name="pwd" placeholder="Password" required>

        <div class="form-actions">
            <button type="submit">Admin Sign In</button>
            <button type="reset">Clear</button>
        </div>
    </form>

    <div class="auth-links">
        <p>Customer? <a href="signin.php">Customer Login</a></p>
    </div>
</div>

<?php require_once("../footer.php"); ?>