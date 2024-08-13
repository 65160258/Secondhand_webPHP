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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Your Order History</h1>
        <a href="../index.php">Back to Homepage</a>
    </header>
    <main>
        <?php if ($result->num_rows > 0): ?>
            <?php while($order = $result->fetch_assoc()): ?>
                <div>
                    <h2>Order ID: <?php echo htmlspecialchars($order['id']); ?></h2>
                    <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
                    <p>Total Amount: $<?php echo htmlspecialchars($order['total_amount']); ?></p>

                    <!-- ดึงรายการสินค้าที่อยู่ในคำสั่งซื้อ -->
                    <?php
                    $order_id = $order['id'];
                    $sql_items = "SELECT products.name, order_items.quantity, products.price 
                                  FROM order_items 
                                  JOIN products ON order_items.product_id = products.id 
                                  WHERE order_items.order_id = ?";
                    $stmt_items = $conn->prepare($sql_items);
                    $stmt_items->bind_param("i", $order_id);
                    $stmt_items->execute();
                    $result_items = $stmt_items->get_result();
                    ?>

                    <ul>
                        <?php while($item = $result_items->fetch_assoc()): ?>
                            <li><?php echo htmlspecialchars($item['name']); ?> - Quantity: <?php echo htmlspecialchars($item['quantity']); ?> - Price: $<?php echo htmlspecialchars($item['price']); ?></li>
                        <?php endwhile; ?>
                    </ul>

                    <?php $stmt_items->close(); ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have no orders.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
