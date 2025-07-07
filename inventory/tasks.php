<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle new task submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_task'])) {
    $employee_id = $_POST['employee_id'];
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $due_date = $_POST['due_date'];
    $assigned_date = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO tasks (employee_id, task_title, task_description, assigned_date, due_date, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("issss", $employee_id, $task_title, $task_description, $assigned_date, $due_date);
    $stmt->execute();
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    if (isset($_POST['mark_completed'])) {
        $conn->query("UPDATE tasks SET status = 'Completed' WHERE id = $task_id");
    } elseif (isset($_POST['mark_pending'])) {
        $conn->query("UPDATE tasks SET status = 'Pending' WHERE id = $task_id");
    }
}

// Fetch employees
$employees = $conn->query("SELECT id, name FROM employees");

// Fetch tasks
$tasks = $conn->query("SELECT t.*, e.name AS employee_name FROM tasks t JOIN employees e ON t.employee_id = e.id ORDER BY t.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Assignment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; margin: 0; background: #f4f4f4; }

        .main { padding: 20px; }

        .form-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .form-section input, .form-section textarea, .form-section select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #2980b9;
            color: white;
        }

        button {
            padding: 6px 10px;
            margin: 2px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .completed {
            background-color: #27ae60;
            color: white;
        }

        .pending {
            background-color: #c0392b;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <h2>Assign Task</h2>

            <div class="form-section">
                <form method="POST">
                    <label>Select Employee:</label>
                    <select name="employee_id" required>
                        <option value="">-- Select Employee --</option>
                        <?php while ($emp = $employees->fetch_assoc()): ?>
                            <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
                        <?php endwhile; ?>
                    </select>

                    <input type="text" name="task_title" placeholder="Task Title" required>
                    <textarea name="task_description" placeholder="Task Description" required></textarea>
                    <label>Due Date:</label>
                    <input type="date" name="due_date" required>

                    <button type="submit" name="add_task">Assign Task</button>
                </form>
            </div>

            <h3>All Tasks</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Assigned</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php while ($task = $tasks->fetch_assoc()): ?>
                    <tr>
                        <td><?= $task['id'] ?></td>
                        <td><?= htmlspecialchars($task['employee_name']) ?></td>
                        <td><?= htmlspecialchars($task['task_title']) ?></td>
                        <td><?= htmlspecialchars($task['task_description']) ?></td>
                        <td><?= $task['assigned_date'] ?></td>
                        <td><?= $task['due_date'] ?></td>
                        <td><?= $task['status'] ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <button type="submit" name="mark_completed" class="completed">✅</button>
                                <button type="submit" name="mark_pending" class="pending">❌</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
