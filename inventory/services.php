<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle add service
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_service'])) {
    if (isset($_POST['name'], $_POST['description'], $_POST['price'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $created_at = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("INSERT INTO services (name, description, price, is_active, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $description, $price, $is_active, $created_at);
        $stmt->execute();

        header("Location: services.php");
        exit();
    }
}

// Handle update service
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_service'])) {
    if (isset($_POST['service_id'], $_POST['name'], $_POST['description'], $_POST['price'])) {
        $id = $_POST['service_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE services SET name=?, description=?, price=?, is_active=? WHERE id=?");
        $stmt->bind_param("ssdii", $name, $description, $price, $is_active, $id);
        $stmt->execute();

        header("Location: services.php");
        exit();
    }
}

// Get all services
$services = $conn->query("SELECT * FROM services ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Services</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; }
        .container { display: flex; }
        .main { flex: 1; padding: 20px; }
        .add-btn {
            padding: 10px 20px;
            background: #2980b9;
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
        th { background: #2980b9; color: white; }
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
            background: #2980b9;
            color: white;
            border: none;
        }
        /* Move search bar to top-right */
        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <h2>Services</h2>
        <button class="add-btn" onclick="openAddPopup()">➕ Add Service</button>

        <table class="datatable" id="servicesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Date Added</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($service = $services->fetch_assoc()): ?>
                    <tr>
                        <td><?= $service['id'] ?></td>
                        <td><?= htmlspecialchars($service['name']) ?></td>
                        <td><?= htmlspecialchars($service['description']) ?></td>
                        <td>₹<?= number_format($service['price'], 2) ?></td>
                        <td><?= $service['is_active'] ? 'Active' : 'Inactive' ?></td>
                        <td><?= $service['created_at'] ?></td>
                        <td><button class="edit-btn" onclick='openEditPopup(<?= json_encode($service) ?>)'>Edit</button></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Service Popup -->
<div class="popup-form" id="addPopup">
    <form method="POST" class="popup-inner">
        <button type="button" class="close-btn" onclick="closePopup()">X</button>
        <h3>Add Service</h3>
        <input type="text" name="name" placeholder="Service Name" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="price" placeholder="Price" step="0.01">
        <label><input type="checkbox" name="is_active" checked> Active</label>
        <button type="submit" name="add_service" class="submit-btn">Add</button>
    </form>
</div>

<!-- Edit Service Popup -->
<div class="popup-form" id="editPopup">
    <form method="POST" class="popup-inner">
        <button type="button" class="close-btn" onclick="closePopup()">X</button>
        <h3>Edit Service</h3>
        <input type="hidden" name="service_id" id="edit_id">
        <input type="text" name="name" id="edit_name" placeholder="Service Name" required>
        <textarea name="description" id="edit_description" placeholder="Description"></textarea>
        <input type="number" name="price" id="edit_price" step="0.01">
        <label><input type="checkbox" name="is_active" id="edit_active"> Active</label>
        <button type="submit" name="update_service" class="submit-btn">Update</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#servicesTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            responsive: true,
            dom: '<"top"f>rt<"bottom"lp><"clear">',
            language: { search: "_INPUT_", searchPlaceholder: "🔍 Search services..." }
        });
    });

    function openAddPopup() {
        document.getElementById("addPopup").style.display = "flex";
    }
    function openEditPopup(service) {
        document.getElementById("edit_id").value = service.id;
        document.getElementById("edit_name").value = service.name;
        document.getElementById("edit_description").value = service.description;
        document.getElementById("edit_price").value = service.price;
        document.getElementById("edit_active").checked = service.is_active == 1;
        document.getElementById("editPopup").style.display = "flex";
    }
    function closePopup() {
        document.getElementById("addPopup").style.display = "none";
        document.getElementById("editPopup").style.display = "none";
    }
</script>

</body>
</html>
