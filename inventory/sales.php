<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle sale submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sale'])) {
    $sales_number = $_POST['sales_number'];
    $customer_name = $_POST['customer_name'];
    $product_name = $_POST['product_name'];
    $total_amount = floatval($_POST['total_amount']);
    $payment_received = floatval($_POST['payment_received']);
    $payment_remaining = $total_amount - $payment_received;

    if ($payment_received <= 0) {
        $payment_status = 'Unpaid';
    } elseif ($payment_received < $total_amount) {
        $payment_status = 'Partial';
    } else {
        $payment_status = 'Paid';
    }

    $created_at = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO sales (sales_number, customer_name, product_name, total_amount, payment_received, payment_remaining, payment_status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdddss", $sales_number, $customer_name, $product_name, $total_amount, $payment_received, $payment_remaining, $payment_status, $created_at);
    $stmt->execute();

    header("Location: sales.php");
    exit();
}

$sales = $conn->query("SELECT * FROM sales ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"/>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .container { display: flex; }
        .main { flex: 1; padding: 30px; }
        .add-btn {
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
            cursor: pointer;
        }
        table.dataTable {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        table.dataTable th,
        table.dataTable td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }
        table.dataTable th {
            background: #2980b9;
            color: white;
        }

        /* Popup */
        .popup-form {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup-inner {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
        }
        .popup-inner input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .popup-inner button {
            padding: 10px;
            margin-top: 10px;
            cursor: pointer;
        }
        .close-btn {
            background: #ccc;
            color: black;
            float: right;
            font-weight: bold;
            border: none;
        }
        .submit-btn {
            background-color: #2980b9;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <h2>📊 Sales</h2>
        <button class="add-btn" onclick="openPopup()">➕ Add Sale</button>

        <table id="salesTable" class="display">
            <thead>
                <tr>
                    <th>Sales #</th>
                    <th>Customer</th>
                    <th>Part</th>
                    <th>Total (₹)</th>
                    <th>Received (₹)</th>
                    <th>Remaining (₹)</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sales->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['sales_number']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                        <td>₹<?= number_format($row['payment_received'], 2) ?></td>
                        <td>₹<?= number_format($row['payment_remaining'], 2) ?></td>
                        <td><?= $row['payment_status'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Sale Popup -->
<div class="popup-form" id="popupForm">
    <form method="POST" class="popup-inner">
        <button type="button" class="close-btn" onclick="closePopup()">X</button>
        <h3>Add Sale</h3>
        <input type="text" name="sales_number" placeholder="Sales Number" required>
        <input type="text" name="customer_name" placeholder="Customer Name" required>
        <input type="text" name="product_name" placeholder="Product Name" required>
        <input type="number" step="0.01" name="total_amount" placeholder="Total Amount" required>
        <input type="number" step="0.01" name="payment_received" placeholder="Payment Received" required>
        <button type="submit" name="add_sale" class="submit-btn">Add Sale</button>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
function openPopup() {
    document.getElementById("popupForm").style.display = "flex";
}
function closePopup() {
    document.getElementById("popupForm").style.display = "none";
}

$(document).ready(function() {
    $('#salesTable').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Search sales..."
        }
    });
});
</script>

</body>
</html>
