<?php
session_start();
include '../database/db.php'; 

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    // ตรวจสอบว่ามีสินค้านี้อยู่ในตะกร้าหรือไม่
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // ดึงข้อมูลสินค้าจากฐานข้อมูล
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $product = $result->fetch_assoc();
                $_SESSION['cart'][$product_id] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity
                ];
            }
            $stmt->close(); // ปิดการเชื่อมต่อฐานข้อมูล
        } else {
            // แจ้งเตือนหากไม่สามารถเตรียมคำสั่ง SQL ได้
            echo "Error preparing SQL statement.";
        }
    }
    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล

    // เปลี่ยนเส้นทางไปยังหน้าตะกร้าสินค้า
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Your Shopping Cart</h1>
        <a href="../index.php">Back to Home</a>
    </header>
    <main>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <form action="checkout.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_amount = 0;
                        foreach ($_SESSION['cart'] as $id => $product): 
                            $total = $product['quantity'] * $product['price'];
                            $total_amount += $total;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td>
                                    <form action="update_cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="p" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" min="1" disabled>
                                    </form>
                                </td>
                                <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                                <td>$<?php echo number_format($total, 2); ?></td>
                                <td>
                                    <form action="remove_from_cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="remove_from_cart">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3">Total Amount</td>
                            <td>$<?php echo number_format($total_amount, 2); ?></td>
                        </tr>
                    </tbody>
                </table>

                <h2>Select Shipping Method</h2>
                <select name="shipping_method" required>
                    <?php 
                    $shipping_methods = ['Standard Shipping', 'Express Shipping', 'Next-Day Delivery'];
                    foreach ($shipping_methods as $method): ?>
                        <option value="<?php echo htmlspecialchars($method); ?>"><?php echo htmlspecialchars($method); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="checkout">Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>
</body>
</html>
