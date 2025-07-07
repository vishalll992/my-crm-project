<?php
session_start();
include 'components/connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check for existing user
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            // Hash & insert
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'employee')");
            $ins->bind_param("ss", $username, $hash);
            if ($ins->execute()) {
                header("Location: login.php?registered=1");
                exit();
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Registration</title>
    <style>
        body { font-family:'Segoe UI',sans-serif; background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .form-box { background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); width:320px; }
        h2 { text-align:center; margin-bottom:20px; }
        input { width:100%; padding:12px; margin:8px 0; border:1px solid #ccc; border-radius:4px; }
        button { width:100%; padding:12px; background:#0066cc; border:none; color:#fff; border-radius:4px; cursor:pointer; }
        button:hover { background:#005bb5; }
        .error { color:#e74c3c; text-align:center; margin-bottom:10px; }
        .info { text-align:center; margin-top:10px; font-size:14px; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Register as Employee</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" novalidate>
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <div class="info">
            <a href="login.php">Already have an account? Log in</a>
        </div>
    </div>
</body>
</html>
