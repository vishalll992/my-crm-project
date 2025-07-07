<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle delete
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $deleteId");
    header("Location: products.php");
    exit();
}

// Handle customer order
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_purchase'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $name = $_POST['customer_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check stock
    $check = $conn->query("SELECT stock FROM products WHERE id = $productId");
    $product = $check->fetch_assoc();

    if ($product && $product['stock'] >= $quantity) {
        $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $productId");

        $stmt = $conn->prepare("INSERT INTO customer_orders (customer_name, email, phone, product_id, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $name, $email, $phone, $productId, $quantity);
        $stmt->execute();

        echo "<script>alert('Order placed successfully!');</script>";
    } else {
        echo "<script>alert('Not enough stock available.');</script>";
    }
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"/>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f9f9f9; }
        .container { display: flex; }
        .main { flex: 1; padding: 20px; }
        .logout-bar { position: fixed; top: 10px; right: 10px; background-color: #2980b9; color: white; padding: 10px 20px; border-radius: 8px; display: flex; align-items: center; gap: 10px; z-index: 999; }
        .logout-bar .logout-btn { background: white; color: #2980b9; padding: 6px 12px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .logout-bar .logout-btn:hover { background: #ecf0f1; }
        .products-table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        .products-table th, .products-table td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        .products-table th { background-color: #f2f2f2; }
        .products-table img { width: 80px; height: 80px; object-fit: contain; }
        /* Unified action button style */
        .action-btn {
            padding: 6px 12px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 3px;
            font-size: 14px;
        }
        .buy-btn { background-color: #2980b9; }
        .buy-btn:hover { background-color: #3498db; }
        .edit-btn { background-color: #f39c12; }
        .edit-btn:hover { background-color: #d35400; }
        .delete-btn { background-color: #e74c3c; }
        .delete-btn:hover { background-color: #c0392b; }
        .popup-form { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .popup-inner { background: #fff; padding: 30px; border-radius: 10px; width: 300px; }
        .popup-inner input { margin-bottom: 15px; padding: 10px; font-size: 14px; width: 100%; }
        .popup-inner button { padding: 10px; margin-top: 10px; cursor: pointer; width: 100%; }
    </style>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>
    <div class="main">
        <h1>All Products</h1>
        <table id="productsTable" class="products-table display datatable">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price (₹)</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= $row['name'] ?>"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td>
                            <button class="action-btn buy-btn" type="button" onclick="openPopup(<?= $row['id'] ?>)">Buy</button>
                            <button class="action-btn edit-btn" type="button" onclick="window.location='edit_product.php?id=<?= $row['id'] ?>'">Edit</button>
                            <button class="action-btn delete-btn" type="button" onclick="if(confirm('Are you sure?')) window.location='products.php?delete=<?= $row['id'] ?>';">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Purchase Popup -->
<div class="popup-form" id="popupForm">
    <form method="POST" class="popup-inner">
        <h3>Complete Your Purchase</h3>
        <input type="hidden" name="product_id" id="popupProductId">
        <input type="hidden" name="quantity" value="1">
        <input type="text" name="customer_name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Email ID" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>
        <button type="submit" name="confirm_purchase" class="action-btn buy-btn">Submit Order</button>
        <button type="button" class="action-btn delete-btn" onclick="closePopup()">Cancel</button>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        responsive: true,
        language: { search: "_INPUT_", searchPlaceholder: "🔍 Search products..." }
    });
});

function openPopup(productId) {
    document.getElementById("popupProductId").value = productId;
    document.getElementById("popupForm").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupForm").style.display = "none";
}
</script>

</body>
</html>
