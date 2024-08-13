<?php
session_start();
include '../database/db.php'; 

if (!isset($_SESSION['user_id'])) {
    die("You need to be logged in to place an order.");
}

if (isset($_POST['checkout'])) {
    $user_id = $_SESSION['user_id'];
    $shipping_method = $_POST['shipping_method']; // รับข้อมูลวิธีการจัดส่ง
    $total_amount = 0;
    
    foreach ($_SESSION['cart'] as $product) {
        $total_amount += $product['price'] * $product['quantity'];
    }
    
    $sql = "INSERT INTO orders (user_id, total_amount, shipping_method, order_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ids", $user_id, $total_amount, $shipping_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    foreach ($_SESSION['cart'] as $id => $product) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $order_id, $id, $product['quantity']);
        $stmt->execute();
    }

    unset($_SESSION['cart']);
    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการสั่งซื้อ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>ยืนยันการสั่งซื้อ</h1>
        <a href="../index.php" class="btn btn-secondary">กลับสู่หน้าหลัก</a>
    </header>
    <main class="container my-5">
        <div class="alert alert-success">
            <p>คำสั่งซื้อสำเร็จ! หมายเลขคำสั่งซื้อของคุณคือ: <?php echo htmlspecialchars($order_id); ?></p>
            <p>วิธีการจัดส่ง: <?php echo htmlspecialchars($shipping_method); ?></p>
        </div>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
            <a href="order_history.php" class="btn btn-primary">ประวัติการสั่งซื้อ</a>
        <?php endif; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
}
?>
