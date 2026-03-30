<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db_connect.php';
$active_page = 'manageGoods';

$edit_mode = false;
$product_to_edit = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $image_path = $_POST['existing_image'] ?? null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
       $image_path = $upload_dir . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path); //? move the upload impage from temporary location - save it permanetly in /uploads folder
        $image_path = 'uploads/' . $image_name;
    }

    if ($action === 'add') {
        $name = $_POST['name'];
        $price = (float)$_POST['price'];
        $quantity = (int)$_POST['quantity']; 
        $one_per_customer = isset($_POST['one_per_customer']) ? 1 : 0;
        $status = $_POST['status'];
        
        if (empty($name) || empty($price) || empty($quantity) || empty($status) || empty($image_path)) {
            $_SESSION['message'] = "Error: All fields including the image are required!";
        } else {
            $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, image_url, one_per_customer, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdisis", $name, $price, $quantity, $image_path, $one_per_customer, $status);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Product added successfully!";
            }
        }
    } elseif ($action === 'update') {
        $id = $_POST['product_id'];
        $name = $_POST['name'];
        $price = (float)$_POST['price'];
        $quantity = (int)$_POST['quantity'];
        $one_per_customer = isset($_POST['one_per_customer']) ? 1 : 0;
        $status = $_POST['status'];

        if (empty($name) || empty($price) || empty($quantity) || empty($status) || empty($image_path)) {
            $_SESSION['message'] = "Error: All fields including the image are required!";
        } else {
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ?, image_url = ?, one_per_customer = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sdisisi", $name, $price, $quantity, $image_path, $one_per_customer, $status, $id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Product updated successfully!";
            }
        }
    } elseif ($action === 'delete') {
        $id = $_POST['product_id'];

        $name_stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
        
        $name_stmt->bind_param("i", $id);
        $product_name = 'The product';
        if ($name_stmt->execute()) {
            $result = $name_stmt->get_result();
            if ($product = $result->fetch_assoc()) {
                $product_name = $product['name'];
            }
        }

        $stmt = $conn->prepare("UPDATE products SET status = 'Archived' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "'" . htmlspecialchars($product_name) . "' deleted successfully.";
        } else {
            $_SESSION['message'] = "Failed to archive the product.";
        }
    }
    header("Location: manageGoods.php");
    exit();
}

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product_to_edit = $result->fetch_assoc();
}

$products = $conn->query("SELECT id, name, quantity, price, status, image_url, one_per_customer FROM products WHERE status != 'Archived' ORDER BY created_at DESC");

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - Manage Goods</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <h1>Shemachoch Admin Panel</h1>
    </header>
    <div class="main-layout">
      <?php include 'sidebar.php'; ?>
      <main>
        <div class="container">
          <h2>Manage Goods</h2>
          <p>Add, edit, and view your goods.</p>

          <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
          <?php endif; ?>

          <div class="two-column-layout">
            <div class="form-column">
              <section class="card">
                <h3><?php echo $edit_mode ? 'Edit Good' : 'Add Good'; ?></h3>
                <form method="post" action="manageGoods.php" enctype="multipart/form-data">
                  <input type="hidden" name="product_id" value="<?php echo $product_to_edit['id'] ?? ''; ?>">
                  <input type="hidden" name="existing_image" value="<?php echo $product_to_edit['image_url'] ?? ''; ?>">
                  <div>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Name" required value="<?php echo $product_to_edit['name'] ?? ''; ?>" />
                  </div>
                  <div>
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" placeholder="0" min="0" required value="<?php echo $product_to_edit['quantity'] ?? ''; ?>" />
                  </div>
                  <div>
                    <label for="price">Price</label>
                    <input type="number" step="0.01" id="price" name="price" placeholder="0.00" min="0" required value="<?php echo $product_to_edit['price'] ?? ''; ?>" />
                  </div>
                   <div>
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="Available" <?php echo (($product_to_edit['status'] ?? '') === 'Available') ? 'selected' : ''; ?>>Available</option>
                        <option value="Unavailable" <?php echo (($product_to_edit['status'] ?? '') === 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                        <option value="Coming Soon" <?php echo (($product_to_edit['status'] ?? '') === 'Coming Soon') ? 'selected' : ''; ?>>Coming Soon</option>
                    </select>
                  </div>
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" id="one_per_customer" name="one_per_customer" <?php echo ($product_to_edit['one_per_customer'] ?? 0) ? 'checked' : ''; ?> style="width: auto;">
                    <label for="one_per_customer" style="font-weight: normal;">Restrict to one per customer</label>
                  </div>
                  <div>
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*" <?php echo $edit_mode ? '' : 'required'; ?>>
                    <?php if ($edit_mode && !empty($product_to_edit['image_url'])): ?>
                        <img src="../<?php echo htmlspecialchars($product_to_edit['image_url']); ?>" alt="Current Image" style="max-width: 100px; margin-top: 10px;">
                    <?php endif; ?>
                  </div>
                  <div>
                    <div class="actions">
                      <?php if ($edit_mode): ?>
                        <button type="submit" name="action" value="update" class="btn btn-primary">Update</button>
                        <a href="manageGoods.php" class="btn">Cancel</a>
                      <?php else: ?>
                        <button type="submit" name="action" value="add" class="btn btn-primary">Add</button>
                      <?php endif; ?>
                    </div>
                  </div>
                </form>
              </section>
            </div>
            <div class="table-column">
              <section class="card">
                <h3>Goods List</h3>
                <div class="responsive-table">
                <table>
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Status</th>
                      <th>Restriction</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($product = $products->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo $product['id']; ?></td>
                      <td><img src="../<?php echo htmlspecialchars($product['image_url'] ?? 'uploads/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image" style="width: 50px; height: 50px; object-fit: cover;"></td>
                      <td><?php echo htmlspecialchars($product['name']); ?></td>
                      <td><?php echo $product['quantity']; ?></td>
                      <td><?php echo number_format($product['price'], 2) . ' Birr'; ?></td>
                      <?php
                        $status = htmlspecialchars($product['status']);
                        $status_class = '';
                        if ($status === 'Available') {
                            $status_class = 'status-available';
                        } elseif ($status === 'Unavailable') {
                            $status_class = 'status-unavailable';
                        } elseif ($status === 'Coming Soon') {
                            $status_class = 'status-coming-soon';
                        }
                      ?>
                      <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                      <td><?php echo $product['one_per_customer'] ? 'One Per Customer' : 'None'; ?></td>
                      <td class="actions">
                        <a href="manageGoods.php?edit_id=<?php echo $product['id']; ?>" class="btn">Edit</a>
                        <form method="post" action="manageGoods.php" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="action" value="delete" class="btn btn-danger">Delete</button>
                        </form>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
                </div>
              </section>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div id="imageModal" class="modal">
      <span class="close">&times;</span>
      <img class="modal-content" id="modalImage">
    </div>

    <script>
      var modal = document.getElementById("imageModal");
      var modalImg = document.getElementById("modalImage");
      
      var images = document.getElementsByClassName("product-image");
      for (var i = 0; i < images.length; i++) {
        images[i].onclick = function(){
          modal.style.display = "block";
          modalImg.src = this.src;
        }
      }

      var span = document.getElementsByClassName("close")[0];

      span.onclick = function() { 
        modal.style.display = "none";
      }
      
      modal.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }
    </script>
  </body>
</html>
