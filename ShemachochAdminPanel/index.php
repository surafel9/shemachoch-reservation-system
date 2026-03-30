<?php
session_start();
$active_page = 'dashboard';
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Admin Panel - Dashboard</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
	<?php
	require 'db_connect.php';

	// Fetch data from the database
	$totalGoodsResult = $conn->query("SELECT COUNT(*) as count FROM products WHERE status != 'Archived'");
	$totalGoods = $totalGoodsResult->fetch_assoc()['count'];

	$availableGoodsResult = $conn->query("SELECT COUNT(*) as count FROM products WHERE status = 'Available'");
	$availableGoods = $availableGoodsResult->fetch_assoc()['count'];

	$activeReservationsResult = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE status = 'confirmed'");
	$activeReservations = $activeReservationsResult->fetch_assoc()['count'];

	$reservationsPickedResult = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE status = 'picked_up'");
	$reservationsPicked = $reservationsPickedResult->fetch_assoc()['count'];

	$conn->close();
	?>
		<header>
			<h1>Shemachoch Admin Panel</h1>
		</header>
		<div class="main-layout">
			<?php include 'sidebar.php'; ?>
			<main>
			<h2>Dashboard</h2>
			<p>Overview of goods and reservations.</p>

			<div class="card-grid">
				<div class="card total-goods">
					<div class="icon-wrapper">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
					</div>
					<h3>Total Goods</h3>
					<p class="value"><?php echo $totalGoods; ?></p>
					<a href="manageGoods.php" class="link">Manage Goods →</a>
				</div>
				<div class="card available-goods">
					<div class="icon-wrapper">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .962-.343 1.087-.835l1.823-6.44a1.125 1.125 0 00-.142-1.082A1.125 1.125 0 0020.25 6H5.636a1.125 1.125 0 00-1.087.835L3.217 9.5M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c.51 0 .962-.343 1.087-.835l1.823-6.44a1.125 1.125 0 00-.142-1.082A1.125 1.125 0 0020.25 6H5.636a1.125 1.125 0 00-1.087.835L3.217 9.5" /></svg>
					</div>
					<h3>Available Goods</h3>
					<p class="value"><?php echo $availableGoods; ?></p>
					<p class="muted">In stock and ready</p>
				</div>
				<div class="card active-reservations">
					<div class="icon-wrapper">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.5 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" /></svg>
					</div>
					<h3>Active Reservations</h3>
					<p class="value"><?php echo $activeReservations; ?></p>
					<a href="Reservations.php" class="link">Review Reservations →</a>
				</div>
				<div class="card completed-pickups">
					<div class="icon-wrapper">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
					</div>
					<h3>Completed Pickups</h3>
					<p class="value"><?php echo $reservationsPicked; ?></p>
					<a href="Reports.php" class="link">View Reports →</a>
				</div>
			</div>
			</main>
		</div>
	</body>
</html>