<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

$sql = "SELECT customer_orders.id, customer_orders.customer_name, products.name AS product_name, customer_orders.quantity, customer_orders.email, customer_orders.phone
        FROM customer_orders
        JOIN products ON customer_orders.product_id = products.id
        ORDER BY customer_orders.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #ecf0f1;
        }
        .container {
            display: flex;
        }
        .main {
            flex: 1;
            padding: 30px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        .orders-table th, .orders-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .orders-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="container">
<?php include 'sidebar.php'; ?>

    <div class="main">
        <h1>Order History</h1>
        <table class="orders-table">
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No orders yet.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
