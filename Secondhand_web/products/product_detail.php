<?php
session_start();
include '../database/db.php'; 

$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Product Detail</h1>
        <a href="../index.php">Back to Homepage</a>
    </header>
    <main>
        <?php if ($product): ?>
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <?php if (!empty($product['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 400px;">
            <?php endif; ?>
            <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
            <p>Description: <?php echo htmlspecialchars($product['description']); ?></p>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
                <form action="cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>        
            <?php endif; ?>
           
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </main>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
