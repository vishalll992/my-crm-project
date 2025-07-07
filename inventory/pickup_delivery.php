<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Mark as Delivered
if (isset($_GET['deliver_id'])) {
    $id = (int)$_GET['deliver_id'];
    $conn->query("UPDATE pickup_delivery SET status='Delivered', delivery_date=NOW() WHERE id=$id");
    header("Location: pickup_delivery.php");
    exit();
}

// Handle assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign'])) {
    $order_id = $_POST['order_id'];
    $employee_id = $_POST['employee_id'];
    $notes = $_POST['notes'] ?? '';

    $stmt = $conn->prepare("INSERT INTO pickup_delivery (order_id, employee_id, status, pickup_date, notes) VALUES (?, ?, 'Assigned', NOW(), ?)");
    $stmt->bind_param("iis", $order_id, $employee_id, $notes);
    $stmt->execute();
    header("Location: pickup_delivery.php");
    exit();
}

// Fetch all assignments
$assignments = $conn->query("
    SELECT pd.*, e.name AS employee_name, o.id AS order_id
    FROM pickup_delivery pd
    JOIN employees e ON pd.employee_id = e.id
    JOIN orders o ON pd.order_id = o.id
    ORDER BY pd.id DESC
");

// Fetch employees & orders for form
$employees = $conn->query("SELECT id, name FROM employees");
$orders = $conn->query("SELECT id FROM orders");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pickup & Delivery</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { margin: 0; font-family: Arial; background: #f4f4f4; }
        .main { padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        th { background: #2980b9; color: white; }
        .form-box { background: white; padding: 20px; border-radius: 10px; }
        select, button, textarea { padding: 10px; margin-top: 10px; width: 100%; }
        .deliver-btn { background: green; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <?php include 'sidebar.php'; ?>
    <div class="main">
        <h2>Assign Pickup</h2>
        <div class="form-box">
            <form method="POST">
                <label>Order:</label>
                <select name="order_id" required>
                    <option value="">-- Select Order --</option>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <option value="<?= $order['id'] ?>">Order #<?= $order['id'] ?></option>
                    <?php endwhile; ?>
                </select>

                <label>Assign to Employee:</label>
                <select name="employee_id" required>
                    <option value="">-- Select Employee --</option>
                    <?php while ($emp = $employees->fetch_assoc()): ?>
                        <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label>Notes:</label>
                <textarea name="notes" placeholder="Optional notes for this delivery..."></textarea>

                <button type="submit" name="assign">Assign Pickup</button>
            </form>
        </div>

        <h3>Pickup & Delivery Status</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Employee</th>
                <th>Status</th>
                <th>Pickup Date</th>
                <th>Delivery Date</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $assignments->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                    <td><?= htmlspecialchars($row['employee_name']) ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['pickup_date'] ?></td>
                    <td><?= $row['delivery_date'] ?? '-' ?></td>
                    <td><?= htmlspecialchars($row['notes'] ?? '-') ?></td>
                    <td>
                        <?php if ($row['status'] !== 'Delivered'): ?>
                            <a href="?deliver_id=<?= $row['id'] ?>" class="deliver-btn">Mark as Delivered</a>
                        <?php else: ?>
                            ✔ Delivered
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
