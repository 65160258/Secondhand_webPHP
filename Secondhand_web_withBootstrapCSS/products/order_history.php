<?php
session_start();
include '../database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_register/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลคำสั่งซื้อทั้งหมดของผู้ใช้
$sql = "SELECT * FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>ประวัติการสั่งซื้อของคุณ</h1>
        <a href="../index.php" class="btn btn-secondary">กลับสู่หน้าหลัก</a>
    </header>
    <main class="container my-5">
        <?php if ($result->num_rows > 0): ?>
            <?php while($order = $result->fetch_assoc()): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>รหัสคำสั่งซื้อ: <?php echo htmlspecialchars($order['id']); ?></h2>
                    </div>
                    <div class="card-body">
    <p><strong>วันที่สั่งซื้อ:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
    <p><strong>ยอดรวม:</strong> <?php echo htmlspecialchars($order['total_amount']); ?> บาท</p>
    <p><strong>วิธีการจัดส่ง:</strong> <?php echo htmlspecialchars($order['shipping_method']); ?></p>
    <!-- ดึงรายการสินค้าที่อยู่ในคำสั่งซื้อ -->
    <?php
    $order_id = $order['id'];
    $sql_items = "SELECT products.name, order_items.quantity, products.price, orders.shipping_method 
                  FROM order_items 
                  JOIN products ON order_items.product_id = products.id 
                  JOIN orders ON order_items.order_id = orders.id
                  WHERE order_items.order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
    ?>
    <ul class="list-group">
        <?php while($item = $result_items->fetch_assoc()): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($item['name']); ?> 
                - จำนวน: <?php echo htmlspecialchars($item['quantity']); ?> 
                - ราคา: <?php echo htmlspecialchars($item['price']); ?> บาท
            </li>
        <?php endwhile; ?>
    </ul>
    <?php $stmt_items->close(); ?>
</div>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="alert alert-warning">คุณยังไม่มีคำสั่งซื้อ</p>
        <?php endif; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
