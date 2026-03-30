<?php require_once("../header.php"); ?>

<div class="main">
    <h1>Update Account</h1>

    <?php
    require_once("db.php");
    $user_id = $_SESSION["user_id"];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    $update_data = $_SESSION["update_data"] ?? [];
    unset($_SESSION["update_data"]);
    ?>

    <form action="updateaccounthelper.php" method="post">
        <label>First Name</label>
        <input type="text" name="f_name" placeholder="<?= htmlspecialchars($currentUser['f_name']) ?>" value="<?= htmlspecialchars($update_data['f_name'] ?? '') ?>">

        <label>Last Name</label>
        <input type="text" name="l_name" placeholder="<?= htmlspecialchars($currentUser['l_name']) ?>" value="<?= htmlspecialchars($update_data['l_name'] ?? '') ?>">

        <label>Phone Number</label>
        <input type="text" name="p_num" placeholder="<?= htmlspecialchars($currentUser['p_num']) ?>" value="<?= htmlspecialchars($update_data['p_num'] ?? '') ?>">

        <label>House Number</label>
        <input type="text" name="h_num" placeholder="<?= htmlspecialchars($currentUser['h_num']) ?>" value="<?= htmlspecialchars($update_data['h_num'] ?? '') ?>">

        <label>Password</label>
        <input type="password" name="pwd" placeholder="New Password (leave blank to keep current)">

        <label>Bank Account Number</label>
        <input type="text" name="acc_num" placeholder="<?= htmlspecialchars($currentUser['acc_num']) ?>" value="<?= htmlspecialchars($update_data['acc_num'] ?? '') ?>">

        <div class="form-actions">
            <button type="submit">Update</button>
            <button type="reset">Clear</button>
        </div>
    </form>

    <div class="auth-links" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
        <p><a href="dashboard.php">Back to Dashboard</a></p>
        <p style="margin-top: 10px;"><a href="deleteaccount.php" style="color: #ff4d4d;">Delete My Account</a></p>
    </div>
</div>

<?php require_once("../footer.php"); ?>