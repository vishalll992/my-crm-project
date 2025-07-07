<?php
include 'components/connections.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)$_POST['product_id'];
    $imagePath = $_POST['image_url'];

    // Delete the product from database
    $delete = $conn->query("DELETE FROM products WHERE id = $productId");

    if ($delete) {
        // Delete image file if it exists
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Redirect with success message
        header("Location: products.php?msg=deleted");
        exit();
    } else {
        echo "Failed to delete product.";
    }
} else {
    echo "Invalid request.";
}
?>
