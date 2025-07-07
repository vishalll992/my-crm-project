<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle new employee submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $last_active = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO employees (name, role, mobile, email, gender, is_active, last_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $role, $mobile, $email, $gender, $is_active, $last_active);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM employees ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employees</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"/>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
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
        table.dataTable {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        table.dataTable th,
        table.dataTable td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table.dataTable th {
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

        .popup-inner input, .popup-inner select {
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
        <h2>👥 Employees</h2>
        <button class="add-btn" onclick="openPopup()">➕ Add Employee</button>

        <table id="employeesTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Last Active</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= htmlspecialchars($row['mobile']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= $row['is_active'] ? 'Active' : 'Inactive' ?></td>
                        <td><?= $row['last_active'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Employee Popup -->
<div class="popup-form" id="popupForm">
    <form method="POST" class="popup-inner">
        <button type="button" class="close-btn" onclick="closePopup()">X</button>
        <h3>Add Employee</h3>
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="role" placeholder="Role" required>
        <input type="text" name="mobile" placeholder="Mobile No" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
        </select>
        <label><input type="checkbox" name="is_active" checked> Active</label>
        <button type="submit" name="add_employee" class="submit-btn">Add</button>
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
    $('#employeesTable').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "🔍 Search employees..."
        }
    });
});
</script>

</body>
</html>
