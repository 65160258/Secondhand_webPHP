<?php
session_start();
include '../database/db.php';

// ตรวจสอบว่าผู้ใช้เป็นผู้ขาย
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../index.php");
    exit();
}

// ตรวจสอบว่ามีการส่งข้อมูลฟอร์มมา
if (isset($_POST['add_product'])) {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];
    $seller_id = $_SESSION['user_id']; // ใช้ user_id ของผู้ขาย

    // ตรวจสอบข้อมูลก่อนทำการเพิ่ม
    if (empty($name) || empty($price) || empty($description)) {
        echo "Please fill in all required fields.";
        exit();
    }

    // เตรียมคำสั่ง SQL สำหรับเพิ่มสินค้า
    $sql = "INSERT INTO products (name, description, price, image_url, seller_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssi", $name, $description, $price, $image_url, $seller_id);
        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error executing SQL statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing SQL statement: " . $conn->error;
    }

    $conn->close();
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
</head>
<body>
    <h1>ประกาศขายสินค้า</h1>
    <a href="../index.php">Back to Homepage</a>
    <form action="add_product.php" method="POST">
        <label for="name">Product Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>
        
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" required><br><br>
        
        <label for="image_url">Image URL:</label><br>
        <input type="text" id="image_url" name="image_url"><br><br>
        
        <input type="submit" name="add_product" value="Add Product">
    </form>
</body>
</html>
