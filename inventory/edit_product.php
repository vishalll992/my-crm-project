<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'components/connections.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = (int)$_GET['id'];

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $category = $_POST['category'];
    $price    = (float)$_POST['price'];
    $stock    = (int)$_POST['stock'];
    
    // Optional: handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $filename = basename($_FILES['image']['name']);
        $targetFile = $targetDir . time() . "_" . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        
        // Update with new image URL
        $stmt = $conn->prepare("
            UPDATE products 
            SET name=?, category=?, price=?, stock=?, image_url=? 
            WHERE id=?
        ");
        $stmt->bind_param("ssdisi", $name, $category, $price, $stock, $targetFile, $id);
    } else {
        // Update without changing image
        $stmt = $conn->prepare("
            UPDATE products 
            SET name=?, category=?, price=?, stock=? 
            WHERE id=?
        ");
        $stmt->bind_param("ssdii", $name, $category, $price, $stock, $id);
    }
    
    $stmt->execute();
    header("Location: products.php");
    exit();
}

// Fetch existing product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows !== 1) {
    header("Location: products.php");
    exit();
}
$product = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
    <style>
      body { font-family:'Segoe UI',sans-serif; background:#f4f4f4; margin:0; }
      .container { max-width:500px; margin:50px auto; background:#fff; padding:30px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
      h2 { text-align:center; margin-bottom:20px; }
      label { display:block; margin-top:15px; font-weight:500; }
      input[type="text"], input[type="number"], input[type="file"] {
        width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:4px;
      }
      button { margin-top:20px; width:100%; padding:12px; background:#0066cc; color:white; border:none; border-radius:4px; cursor:pointer; }
      button:hover { background:#005bb5; }
      .back { margin-top:10px; display:inline-block; text-decoration:none; color:#0066cc; }
    </style>
</head>
<body>
  <div class="container">
    <h2>Edit Product #<?= $product['id'] ?></h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

      <label>Category</label>
      <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>

      <label>Price (₹)</label>
      <input type="number" name="price" step="0.01" value="<?= number_format($product['price'],2,'.','') ?>" required>

      <label>Stock</label>
      <input type="number" name="stock" value="<?= $product['stock'] ?>" required>

      <label>Image (leave blank to keep current)</label>
      <input type="file" name="image" accept="image/*">
      <?php if ($product['image_url']): ?>
        <p>Current: <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="" style="height:50px;"></p>
      <?php endif; ?>

      <button type="submit">Update Product</button>
    </form>
    <a href="products.php" class="back">← Back to Products</a>
  </div>
</body>
</html>
