<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle new customer submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $company = $_POST['company'];
    $address = $_POST['address'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $created_at = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, company, address, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $email, $phone, $company, $address, $is_active, $created_at);
    $stmt->execute();
}

// Get services for a customer (AJAX call)
if (isset($_GET['get_services']) && isset($_GET['customer_id'])) {
    $customerId = (int)$_GET['customer_id'];
    $services = $conn->query("
        SELECT s.name FROM customer_services cs
        JOIN services s ON cs.service_id = s.id
        WHERE cs.customer_id = $customerId
    ");
    $serviceList = [];
    while ($row = $services->fetch_assoc()) {
        $serviceList[] = $row['name'];
    }
    echo json_encode($serviceList);
    exit;
}

// Fetch all customers
$result = $conn->query("SELECT * FROM customers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <link
  rel="stylesheet"
  href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"/>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
        }

        .container {
            display: flex;
        }

        .main {
            flex: 1;
            padding: 20px;
        }

        .add-btn {
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background: #2980b9;
            color: white;
        }

        /* Popup Form */
        .popup-form {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
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

        .popup-inner input, .popup-inner textarea {
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

        /* Service Modal */
        #serviceModal {
            display: none;
            position: fixed;
            top: 20%;
            left: 30%;
            width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            z-index: 1100;
        }

        #serviceModal h3 {
            margin-top: 0;
        }

        #serviceModal ul {
            list-style: none;
            padding-left: 0;
        }

        #serviceModal ul li {
            padding: 6px 0;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <h2>Customers</h2>
        <button class="add-btn" onclick="openPopup()">➕ Add Customer</button>

        <table class="datatable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Address</th>
                <th>Status</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['company']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= $row['is_active'] ? 'Active' : 'Inactive' ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><button onclick="viewServices(<?= $row['id'] ?>)">🛠 View Services</button></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Customer Popup -->
<div class="popup-form" id="popupForm">
    <form method="POST" class="popup-inner">
        <button type="button" class="close-btn" onclick="closePopup()">X</button>
        <h3>Add Customer</h3>
        <input type="text" name="name" placeholder="Customer Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="text" name="company" placeholder="Company Name" required>
        <textarea name="address" placeholder="Address" required></textarea>
        <label><input type="checkbox" name="is_active" checked> Active</label>
        <button type="submit" name="add_customer" class="submit-btn">Add</button>
    </form>
</div>

<!-- Service Modal -->
<div id="serviceModal">
    <h3>Customer Services</h3>
    <ul id="serviceList"></ul>
    <button onclick="document.getElementById('serviceModal').style.display='none'">Close</button>
</div>

<script>
function openPopup() {
    document.getElementById("popupForm").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupForm").style.display = "none";
}

function viewServices(customerId) {
    fetch(`customers.php?get_services=1&customer_id=${customerId}`)
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById("serviceList");
            list.innerHTML = '';
            if (data.length === 0) {
                list.innerHTML = '<li>No services taken</li>';
            } else {
                data.forEach(service => {
                    list.innerHTML += `<li>${service}</li>`;
                });
            }
            document.getElementById("serviceModal").style.display = 'block';
        });
}
</script>


<!-- jQuery (required by DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('.datatable').DataTable({
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50],
      responsive: true,
      language: {
        search: "_INPUT_",
        searchPlaceholder: "🔍 Search…"
      }
    });
  });
</script>

</body>
</html>
