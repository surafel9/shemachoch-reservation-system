<?php
require_once("../header.php");
$title = "Dashboard";

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
    WHERE status != 'Archived'
    ORDER BY created_at DESC
");
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
if (!empty($_SESSION["success_reserve"])): ?>
    <p style="color:green; background:#e0ffe0; padding:15px; border-radius:5px;">
        <?= htmlspecialchars($_SESSION["success_reserve"]) ?>
    </p>
    <?php unset($_SESSION["success_reserve"]); ?>
<?php endif; ?>

<?php
if (!empty($_SESSION["error_reserve"])): ?>
    <p style="color:red; background:#ffe0e0; padding:15px; border-radius:5px;">
        <?= htmlspecialchars($_SESSION["error_reserve"]) ?>
    </p>
    <?php unset($_SESSION["error_reserve"]); ?>
<?php endif; ?>
<?php if (!empty($_SESSION["success_buy"])): ?>
    <p style="color:green; background:#e0ffe0; padding:15px; border-radius:5px; margin:20px 0;">
        <?= htmlspecialchars($_SESSION["success_buy"]) ?>
    </p>
    <?php unset($_SESSION["success_buy"]); ?>
<?php endif; ?>

<?php if (!empty($_SESSION["error_buy"])): ?>
    <p style="color:red; background:#ffe0e0; padding:15px; border-radius:5px; margin:20px 0;">
        <?= htmlspecialchars($_SESSION["error_buy"]) ?>
    </p>
    <?php unset($_SESSION["error_buy"]); ?>
<?php endif; ?>

<script>
    // Add wide-layout class to the container for the dashboard
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.container');
        if (container) {
            container.classList.add('wide-layout');
        }
    });
</script>

<h1 class="welcome-msg">Welcome, <?= htmlspecialchars($user["f_name"]) ?></h1>

<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;">Products</h2>
    
    <div class="profile-header">
        <a href="updateaccount.php" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; gap: 5px;">
            <div class="profile-icon" style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #e0e0e0; color: #333; border: 2px solid #4a90e2;">
                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="width: 30px; height: 30px;">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
            </div>
            <!-- <p style="margin: 0; font-weight: bold; font-size: 0.9rem;"><?= htmlspecialchars($user["f_name"]) ?></p> -->
        </a>
    </div>
</div>

<?php if (empty($products)): ?>
    <p>No products available.</p>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image-container">
                    <?php 
                    $imagePath = $product["image_url"] ?? 'images/placeholder.jpg';
                    // If it's a real image (not placeholder), it's stored as 'uploads/filename.jpg'
                    // Since dashboard.php is in CustomerPanel/includes/, we go up two levels to reach the root
                    $displayPath = ($imagePath === 'images/placeholder.jpg') ? '../' . $imagePath : '../../' . $imagePath;
                    ?>
                    <img src="<?= htmlspecialchars($displayPath) ?>" alt="<?= htmlspecialchars($product["name"]) ?>">
                </div>
                <div class="product-info">
                    <h3 class="product-name"><?= htmlspecialchars($product["name"]) ?></h3>
                    <p class="price"><?= number_format($product["price"], 2) ?> Birr</p>
                    <?php if ($product["one_per_customer"]): ?>
                        <p class="availability-status" style="color: #ff9800; font-weight: bold;">Limited Item</p>
                    <?php endif; ?>
                    <p class="availability-status <?= strtolower(str_replace(' ', '-', $product["status"])) ?><?= ($product["status"] === 'Available' && $product["quantity"] <= 0) ? ' out-of-stock' : '' ?>">
                        <?php
                        if ($product["status"] === 'Available') {
                            echo $product["quantity"] > 0 ? 'Available (' . $product["quantity"] . ')' : 'Out of Stock';
                        } else {
                            echo htmlspecialchars($product["status"]);
                        }
                        ?>
                    </p>
                    <div class="product-actions">
                        <?php if ($product["status"] === 'Available' && $product["quantity"] > 0): ?>
                            <?php
                            $canBuy = true;
                            if ($product["one_per_customer"]) {
                                $checkBought = $pdo->prepare("SELECT 1 FROM reservations WHERE customer_id = ? AND product_id = ? AND status IN ('pending', 'confirmed', 'picked_up')");
                                $checkBought->execute([$user_id, $product["id"]]);
                                if ($checkBought->fetch()) {
                                    $canBuy = false;
                                }
                            }
                            ?>
                            <?php if ($canBuy): ?>
                                <form action="buyproduct.php" method="post" class="buy-form">
                                    <input type="hidden" name="product_id" value="<?= $product["id"] ?>">
                                    <?php if (!$product["one_per_customer"]): ?>
                                        <div class="quantity-selector">
                                            <label for="qty-<?= $product["id"] ?>">Qty:</label>
                                            <input type="number" id="qty-<?= $product["id"] ?>" name="quantity" value="1" min="1" max="<?= $product["quantity"] ?>" class="qty-input">
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" name="quantity" value="1">
                                    <?php endif; ?>
                                    <button type="submit" class="buy-btn">Buy Now</button>
                                </form>
                            <?php else: ?>
                                <button class="buy-btn reserved" disabled title="This item is restricted to one per customer. You have already purchased or reserved it.">Limit Reached</button>
                            <?php endif; ?>
                        <?php elseif ($product["status"] === 'Coming Soon'): ?>
                            <button class="buy-btn reserved" disabled title="This item is not yet prepared for reservation. Please wait for further updates.">Coming Soon</button>
                            <p class="availability-status coming-soon" style="font-size: 0.8rem; margin-top: 5px;">Reservations opening soon</p>
                        <?php else: ?>
                            <button class="buy-btn out-of-stock" disabled>Out of Stock</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once("../footer.php"); ?>