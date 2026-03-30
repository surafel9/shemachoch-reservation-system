<?php
session_start();
require 'db_connect.php';
$active_page = 'reports';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear_reports') {
    $stmt = $conn->prepare("DELETE FROM reservations WHERE status = 'picked_up'");
    if ($stmt->execute()) {
        $_SESSION['report_message'] = "All reports have been cleared successfully!";
    } else {
        $_SESSION['report_error'] = "Failed to clear reports.";
    }
    header("Location: Reports.php");
    exit();
}

$pickedReservationsResult = $conn->query("
    SELECT r.id, u.f_name, u.l_name, p.name as product_name, r.pickup_date
    FROM reservations r
    JOIN users u ON r.customer_id = u.id
    JOIN products p ON r.product_id = p.id
    WHERE r.status = 'picked_up'
    ORDER BY r.pickup_date DESC
");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - Reports</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <h1>Shemachoch Admin Panel</h1>
    </header>
    <div class="main-layout">
      <?php include 'sidebar.php'; ?>
      <main>
        <?php if (isset($_SESSION['report_message'])): ?>
            <div class="alert alert-success" style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #bbf7d0;">
                <?php echo $_SESSION['report_message']; unset($_SESSION['report_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['report_error'])): ?>
            <div class="alert alert-danger" style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #fecaca;">
                <?php echo $_SESSION['report_error']; unset($_SESSION['report_error']); ?>
            </div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Reports</h2>
                <p class="muted">A detailed report of all completed and picked-up reservations.</p>
            </div>
            <?php if ($pickedReservationsResult->num_rows > 0): ?>
                <form method="post" onsubmit="return confirm('Are you sure you want to clear all reports? This will permanently delete current picked-up records.');">
                    <input type="hidden" name="action" value="clear_reports">
                    <button type="submit" class="btn btn-danger">Clear All Reports</button>
                </form>
            <?php endif; ?>
        </div>

        <section class="card" style="margin-top:16px;">
          <h3>Picked-Up Reservation Details</h3>
          <p class="muted">Listing all reservations that have been successfully picked up.</p>
          <div class="responsive-table">
            <table style="width:100%; border-collapse: collapse;">
              <thead>
                <tr>
                  <th style="border:1px solid #e5e7eb; padding:10px; background:#f3f4f6; text-align:left;">Order ID</th>
                  <th style="border:1px solid #e5e7eb; padding:10px; background:#f3f4f6; text-align:left;">Customer</th>
                  <th style="border:1px solid #e5e7eb; padding:10px; background:#f3f4f6; text-align:left;">Item</th>
                  <th style="border:1px solid #e5e7eb; padding:10px; background:#f3f4f6; text-align:left;">Date</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($pickedReservationsResult->num_rows > 0): ?>
                    <?php while($row = $pickedReservationsResult->fetch_assoc()): ?>
                    <tr>
                      <td style="border:1px solid #e5e7eb; padding:10px;"><?php echo htmlspecialchars($row['id']); ?></td>
                      <td style="border:1px solid #e5e7eb; padding:10px;"><?php echo htmlspecialchars($row['f_name'] . ' ' . $row['l_name']); ?></td>
                      <td style="border:1px solid #e5e7eb; padding:10px;"><?php echo htmlspecialchars($row['product_name']); ?></td>
                      <td style="border:1px solid #e5e7eb; padding:10px;"><?php echo htmlspecialchars($row['pickup_date']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px; color: #6b7280;">No reports found.</td>
                    </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </body>
</html>
