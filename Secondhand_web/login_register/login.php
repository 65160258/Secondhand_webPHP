<?php
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูลตะกร้าจากฐานข้อมูล
    $sql = "SELECT carts.product_id, products.name, products.price, carts.quantity FROM carts JOIN products ON carts.product_id = products.id WHERE carts.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $_SESSION['cart'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['cart'][$row['product_id']] = [
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity']
        ];
    }

    $stmt->close();
}
?>
