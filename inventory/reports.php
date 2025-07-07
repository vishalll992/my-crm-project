<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Total Sales
$totalSales = $conn->query("
    SELECT SUM(co.quantity * p.price) AS total_sales 
    FROM customer_orders co 
    JOIN products p ON co.product_id = p.id
")->fetch_assoc()['total_sales'] ?? 0;

// Total Orders
$totalOrders = $conn->query("SELECT COUNT(*) AS total_orders FROM customer_orders")
                    ->fetch_assoc()['total_orders'] ?? 0;

// Stock Value
$stockValue = $conn->query("SELECT SUM(stock * price) AS stock_value FROM products")
                    ->fetch_assoc()['stock_value'] ?? 0;

// Low Stock Items
$lowStockCount = $conn->query("SELECT COUNT(*) AS low_stock FROM products WHERE stock < 5")
                      ->fetch_assoc()['low_stock'] ?? 0;

// Top Selling Products
$topSelling = $conn->query("
    SELECT p.name, SUM(co.quantity) AS total_sold 
    FROM customer_orders co 
    JOIN products p ON co.product_id = p.id 
    GROUP BY co.product_id 
    ORDER BY total_sold DESC 
    LIMIT 5
");

// Recent Orders
$recentOrders = $conn->query("
    SELECT co.id, co.customer_name, p.name AS product_name, co.quantity, p.price, 
           (co.quantity * p.price) AS total 
    FROM customer_orders co 
    JOIN products p ON co.product_id = p.id 
    ORDER BY co.id DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; margin: 0; background: #ecf0f1; }
        .container { display: flex; }
        .main { flex: 1; padding: 20px; }
        h1 { margin-bottom: 20px; }
        .card { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .card h3 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; background: white; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: center; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
<div class="container">
<?php include 'sidebar.php'; ?>


    <div class="main">
        <h1>Reports</h1>

        <div class="card">
            <h3>📊 Summary</h3>
            <p><strong>₹<?= number_format($totalSales, 2) ?></strong> Total Sales</p>
            <p><strong><?= $totalOrders ?></strong> Orders Placed</p>
            <p><strong>₹<?= number_format($stockValue, 2) ?></strong> Current Stock Value</p>
            <p><strong><?= $lowStockCount ?></strong> Low Stock Items</p>
        </div>

        <div class="card">
            <h3>🔝 Top Selling Products</h3>
            <table>
                <tr><th>Product</th><th>Units Sold</th></tr>
                <?php while ($row = $topSelling->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['total_sold'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="card">
            <h3>🕓 Recent Orders</h3>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php while ($row = $recentOrders->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>₹<?= number_format($row['price'], 2) ?></td>
                        <td>₹<?= number_format($row['total'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
