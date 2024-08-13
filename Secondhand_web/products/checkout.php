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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Order Confirmation</h1>
        <a href="../index.php">Back to Homepage</a>
    </header>
    <main>
        <p>Order placed successfully! Your Order ID is: <?php echo htmlspecialchars($order_id); ?></p>
        <p>Shipping Method: <?php echo htmlspecialchars($shipping_method); ?></p>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
                <p><a href="order_history.php">Order History</a></p>
            <?php endif; ?>
    </main>
</body>
</html>

<?php
}
?>
