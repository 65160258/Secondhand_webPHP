<?php
session_start();
include 'database/db.php';

$sql = "SELECT * FROM products";
if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
    $seller_id = $_SESSION['user_id'];
    $sql .= " WHERE seller_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seller_id);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
            <h1>หน้าประกาศขายสินค้า</h1>
        <?php endif; ?>

        <h1>ร้านค้ามือสอง</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="login_register/logout.php">Logout</a></p>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
                <p><a href="products/add_product.php">ประกาศขายสินค้า</a></p>
            <?php endif; ?>
            
        <?php else: ?>
            <p><a href="login_register/login.html">Login</a> | <a href="login_register/register.html">Register</a></p>
        <?php endif; ?>
        <form action="search.php" method="get">
            <input type="text" name="query" placeholder="Search products..." required>
            <button type="submit">Search</button>
        </form>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
            <a href="products/cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>
        <?php endif; ?>
    </header>
    <main>
        <h2>Products</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 200px;">
                    <?php endif; ?>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <a href="products/product_detail.php?id=<?php echo $product['id']; ?>">View Details</a>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
        <form action="products/delete_product.php" method="post" style="display:inline;">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <button type="submit" name="delete_product">Delete</button>
        </form>
        <?php endif; ?>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
