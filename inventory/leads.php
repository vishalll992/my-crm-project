<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle new lead
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_lead'])) {
    $name = $_POST['customer_name'];
    $phone = $_POST['contact_number'];
    $address = $_POST['pickup_address'];
    $service = $_POST['service_type'];
    $pickup_date = $_POST['pickup_date'];

    $stmt = $conn->prepare(
        "INSERT INTO leads (customer_name, contact_number, pickup_address, service_type, pickup_date) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssss", $name, $phone, $address, $service, $pickup_date);
    $stmt->execute();
    header("Location: leads.php");
    exit();
}

// Mark lead as completed
if (isset($_GET['complete_id'])) {
    $id = (int)$_GET['complete_id'];
    $conn->query("UPDATE leads SET status='Completed' WHERE id=$id");
    header("Location: leads.php");
    exit();
}

// Fetch all leads
$leads = $conn->query("SELECT * FROM leads ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leads</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"/>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f4f4; }
        .container { display: flex; }
        .main { flex: 1; padding: 30px; }
        .add-btn { padding: 10px 20px; background-color: #3498db; color: white; border: none; border-radius: 5px; margin-bottom: 15px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background: #2980b9; color: white; }
        .badge { padding: 5px 10px; border-radius: 6px; font-weight: bold; }
        .badge.New { background: #f39c12; color: white; }
        .badge.Scheduled { background: #27ae60; color: white; }
        .badge.Completed { background: #2c3e50; color: white; }
        .complete-btn { background: green; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        /* Popup styles */
        .popup-form { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 999; }
        .popup-inner { background: #fff; padding: 30px; border-radius: 10px; width: 400px; }
        .popup-inner input, .popup-inner textarea { width: 100%; padding: 10px; margin: 10px 0; }
        .popup-inner button { padding: 10px; margin-top: 10px; cursor: pointer; width: 100%; }
        .close-btn { background: #ccc; float: right; font-weight: bold; border: none; }
        .submit-btn { background: #2980b9; color: white; border: none; }
        /* DataTables filter positioning */
        .dataTables_wrapper .dataTables_filter { float: right; text-align: right; }
    </style>
</head>
<body>
<div class="container">
    <?php include 'sidebar.php'; ?>
    <div class="main">
        <h2>📬 Leads</h2>
        <button class="add-btn" onclick="openPopup()">➕ Add Lead</button>

        <table id="leadsTable" class="display datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Pickup Address</th>
                    <th>Service</th>
                    <th>Pickup Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($lead = $leads->fetch_assoc()): ?>
                    <tr>
                        <td><?= $lead['id'] ?></td>
                        <td><?= htmlspecialchars($lead['customer_name']) ?></td>
                        <td><?= htmlspecialchars($lead['contact_number']) ?></td>
                        <td><?= htmlspecialchars($lead['pickup_address']) ?></td>
                        <td><?= htmlspecialchars($lead['service_type']) ?></td>
                        <td><?= $lead['pickup_date'] ?></td>
                        <td><span class="badge <?= $lead['status'] ?>"><?= $lead['status'] ?></span></td>
                        <td>
                            <?php if ($lead['status'] !== 'Completed'): ?>
                                <button class="complete-btn" onclick="window.location='?complete_id=<?= $lead['id'] ?>'">Mark Completed</button>
                            <?php else: ?>
                                ✔ Done
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Lead Popup -->
<div class="popup-form" id="popupForm">
    <form method="POST" class="popup-inner">
        <button type="button" class="close-btn" onclick="closePopup()">X</button>
        <h3>Add Lead</h3>
        <input type="text" name="customer_name" placeholder="Customer Name" required>
        <input type="text" name="contact_number" placeholder="Contact Number" required>
        <textarea name="pickup_address" placeholder="Pickup Address" required></textarea>
        <input type="text" name="service_type" placeholder="Service Type" required>
        <label>Pickup Date:</label>
        <input type="date" name="pickup_date" required>
        <button type="submit" name="add_lead" class="submit-btn">Add Lead</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#leadsTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            responsive: true,
            dom: '<"top"f>rt<"bottom"lp><"clear">',
            language: { search: "_INPUT_", searchPlaceholder: "🔍 Search leads..." }
        });
    });

    function openPopup() {
        document.getElementById("popupForm").style.display = "flex";
    }
    function closePopup() {
        document.getElementById("popupForm").style.display = "none";
    }
</script>
</body>
</html>
