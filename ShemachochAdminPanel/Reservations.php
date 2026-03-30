<?php
session_start();
require 'db_connect.php';
$active_page = 'reservations';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    $action = $_POST['action'];

  if ($action === 'confirm') {
    $stmt = $conn->prepare("UPDATE reservations SET status = 'confirmed', confirmed_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
  } elseif ($action === 'pickup') {

    $conn->begin_transaction();
    try {
      $sel = $conn->prepare("SELECT customer_id, product_id, quantity FROM reservations WHERE id = ? FOR UPDATE");
      $sel->bind_param("i", $reservation_id);
      $sel->execute();
      $res = $sel->get_result();
      if ($res->num_rows === 0) {
        // nothing to do
        $conn->commit();
      } else {
        $row = $res->fetch_assoc();
        $customer_id = (int)$row['customer_id'];
        $product_id = (int)$row['product_id'];
        $quantity = (int)$row['quantity'];

        $chk = $conn->prepare("SELECT id, quantity FROM reservations WHERE customer_id = ? AND product_id = ? AND status = 'picked_up' LIMIT 1 FOR UPDATE");
        $chk->bind_param("ii", $customer_id, $product_id);
        $chk->execute();
        $chkRes = $chk->get_result();

        if ($chkRes && $chkRes->num_rows > 0) {
          $existing = $chkRes->fetch_assoc();
          $newQty = (int)$existing['quantity'] + $quantity;
          $upd = $conn->prepare("UPDATE reservations SET quantity = ?, pickup_date = NOW() WHERE id = ?");
          $upd->bind_param("ii", $newQty, $existing['id']);
          $upd->execute();

          $del = $conn->prepare("DELETE FROM reservations WHERE id = ?");
          $del->bind_param("i", $reservation_id);
          $del->execute();
        } else {
          $stmt = $conn->prepare("UPDATE reservations SET status = 'picked_up', pickup_date = NOW() WHERE id = ?");
          $stmt->bind_param("i", $reservation_id);
          $stmt->execute();
        }
        $conn->commit();
      }
    } catch (Exception $e) {
      $conn->rollback();
      error_log('Failed to mark reservation picked up: ' . $e->getMessage());
    }
  }
    header("Location: Reservations.php");
    exit();
}

$reservationsResult = $conn->query("
    SELECT r.id, u.f_name, u.l_name, p.name as product_name, r.status
    FROM reservations r
    JOIN users u ON r.customer_id = u.id
    JOIN products p ON r.product_id = p.id
    WHERE r.status IN ('pending', 'confirmed')
    ORDER BY r.reservation_date DESC
");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - Reservations</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <h1>Shemachoch Admin Panel</h1>
    </header>
    <div class="main-layout">
      <?php include 'sidebar.php'; ?>
      <main>
        <h2>Manage Reservations</h2>
        <p class="muted">Confirm or mark reservations as picked up.</p>

        <section class="card" style="margin-top:16px;">
          <h3>Current Reservations</h3>
          <div class="responsive-table">
            <table style="width:100%; border-collapse: collapse;">
              <thead>
                <tr>
                  <th data-label="Order ID">Order ID</th>
                  <th data-label="Customer">Customer</th>
                  <th data-label="Item">Item</th>
                  <th data-label="Status">Status</th>
                  <th data-label="Actions">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = $reservationsResult->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['id']); ?></td>
                  <td><?php echo htmlspecialchars($row['f_name'] . ' ' . $row['l_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                  <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
                  <td class="actions">
                      <?php if ($row['status'] === 'pending'): ?>
                          <form method="post" action="Reservations.php" style="display:inline;">
                              <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                              <button type="submit" name="action" value="confirm" class="btn btn-confirm">Confirm</button>
                          </form>
                      <?php elseif ($row['status'] === 'confirmed'): ?>
                          <form method="post" action="Reservations.php" style="display:inline;">
                              <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                              <button type="submit" name="action" value="pickup" class="btn btn-pickup">Mark as Picked Up</button>
                          </form>
                      <?php else: ?>
                          <span class="muted">Completed</span>
                      <?php endif; ?>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </body>
</html>
