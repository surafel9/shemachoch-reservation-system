<?php require_once("../header.php"); ?>

<div class="main">
    <h1>Delete Account</h1>

    <p style="color: #ff4d4d; margin-bottom: 20px;">Are you sure you want to delete your account? This action cannot be undone.</p>

    <form action="deleteaccounthelper.php" method="post" onsubmit="return confirm('Are you absolutely sure? All your data will be permanently removed.');">
        <div class="form-actions">
            <button type="submit" class="btn-danger" style="background-color: #ff4d4d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Yes, Delete My Account</button>
            <a href="dashboard.php" class="btn-secondary" style="margin-left: 10px; text-decoration: none; background-color: #ccc; color: #333; padding: 10px 20px; border-radius: 4px;">Cancel</a>
        </div>
    </form>

    <div class="auth-links">
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</div>

<?php require_once("../footer.php"); ?>