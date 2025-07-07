<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'components/connections.php';

// Count summary
$totalCustomers = $conn->query("SELECT COUNT(*) as total FROM customers")->fetch_assoc()['total'];
$totalEmployees = $conn->query("SELECT COUNT(*) as total FROM employees")->fetch_assoc()['total'];
$totalSales = $conn->query("SELECT COUNT(*) as total FROM sales")->fetch_assoc()['total'];
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];

// Recent activity
$recentSales = $conn->query("SELECT * FROM sales ORDER BY id DESC LIMIT 5");
$recentCustomers = $conn->query("SELECT * FROM customers ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f4f4; }
        .main { padding: 20px; flex: 1; }
        .cards { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px; }
        .card {
            background: white; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1); flex: 1; min-width: 200px;
        }
        .card h3 { margin: 0 0 10px; color: #2980b9; }
        .section {
            margin-bottom: 30px;
            background: white; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        table {
            width: 100%; border-collapse: collapse; background: white;
        }
        th, td {
            padding: 10px; text-align: left; border-bottom: 1px solid #ddd;
        }
        th { background: #2980b9; color: white; }
        .quick-actions {
            display: flex; gap: 10px; margin-top: 10px;
        }
        .quick-actions a {
            background: #3498db; color: white; padding: 10px 15px;
            text-decoration: none; border-radius: 5px; display: inline-block;
        }
    </style>
</head>
<script>
// Apply dark mode from localStorage
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('darkModeToggle');
    const isDark = localStorage.getItem('darkMode') === 'true';
    
    if (isDark) {
        document.body.classList.add('dark-mode');
        toggle.checked = true;
    }

    toggle.addEventListener('change', () => {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', toggle.checked);
    });
});
</script>

<body>
<div class="container">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <h2>Dashboard</h2>

        <!-- Summary Cards -->
        <div class="cards">
            <div class="card">
                <h3>Total Customers</h3>
                <p><?= $totalCustomers ?></p>
            </div>
            <div class="card">
                <h3>Total Employees</h3>
                <p><?= $totalEmployees ?></p>
            </div>
            <div class="card">
                <h3>Total Sales</h3>
                <p><?= $totalSales ?></p>
            </div>
            <div class="card">
                <h3>Total Products</h3>
                <p><?= $totalProducts ?></p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section">
            <h3>Quick Actions</h3>
            <div class="quick-actions">
                <a href="add_product.php">➕ Add Product</a>
                <a href="sales.php">➕ New Sale</a>
                <a href="tasks.php">📝 Assign Task</a>
                <a href="customers.php">➕ Add Customer</a>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="section">
            <h3>Recent Sales</h3>
            <table>
                <tr>
                    <th>Sale No</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
                <?php while ($sale = $recentSales->fetch_assoc()): ?>
                <tr>
                    <td><?= $sale['sales_number'] ?></td>
                    <td><?= htmlspecialchars($sale['customer_name']) ?></td>
                    <td>₹<?= number_format($sale['total_amount'], 2) ?></td>
                    <td><?= $sale['payment_status'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Recent Customers -->
        <div class="section">
            <h3>Recent Customers</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Phone</th>
                </tr>
                <?php while ($cust = $recentCustomers->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($cust['name']) ?></td>
                    <td><?= htmlspecialchars($cust['email']) ?></td>
                    <td><?= htmlspecialchars($cust['company']) ?></td>
                    <td><?= htmlspecialchars($cust['phone']) ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
