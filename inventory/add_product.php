<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $stock = (int)$_POST['stock'];
    $price = (float)$_POST['price'];

    // Handle image upload
    $targetDir = "images/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    $imageType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO products (name, category, stock, price, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $name, $category, $stock, $price, $targetFilePath);
        $stmt->execute();
        $success = "Product added successfully!";
    } else {
        $error = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
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

        .logout-bar {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 999;
        }

        .logout-bar .logout-btn {
            background: white;
            color: #2980b9;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .logout-bar .logout-btn:hover {
            background: #ecf0f1;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
        }

        input, select {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3498db;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="container">
<?php include 'sidebar.php'; ?>


    <div class="main">
        <h1>Add New Product</h1>

        <?php if (isset($success)): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="category" placeholder="Category" required>
            <input type="text" name="name" placeholder="Product Name" required>
            <input type="number" name="stock" placeholder="Stock Quantity" required min="1">
            <input type="number" step="0.01" name="price" placeholder="Price (₹)" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Add Product</button>
        </form>
    </div>
</div>

</body>
</html>
