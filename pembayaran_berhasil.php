<?php
session_start();
include 'includes/db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header('Location: index.html');
    exit();
}

// Handle successful payment and order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process payment and order details...
    
    // Update total_orders for the product in the database
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        // Update total_orders in products table
        $sql_update_orders = "UPDATE products SET total_orders = total_orders + 1 WHERE product_id = '$product_id'";
        if ($conn->query($sql_update_orders) === TRUE) {
            // Successfully updated total_orders
        } else {
            echo "Error updating total_orders: " . $conn->error;
        }
    }
}?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Bursa Makanan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
       .header-container {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
        }
        .header {
            text-align: left;
            font-size: 24px;
        }
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            margin-top: 10px;
        }
        .nav-menu {
            display: flex;
            gap: 10px;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            font-size: 18px;          
            padding: 5px 10px;
        }
        .nav-menu a:hover {
            text-decoration: underline;
        }
        .search-bar {
            flex: 1;
            display: flex;
            justify-content: flex-start;
        }
        .search-bar input {
            width: 50%;
            padding: 10px;
            font-size: 16px;
        }
        .container {
            max-width: 500px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .success-message {
            text-align: center;
            padding: 20px;
            border: 2px solid #4CAF50;
            border-radius: 8px;
            color: #4CAF50;
            background-color: #e9f5e9;
            margin-bottom: 20px;
        }
        .back-button {
            display: block;
            width: 95%;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header">Bursa Makanan</div>
        <div class="nav-container">
            <div class="search-bar">
                <input type="text" placeholder="Cari di Bursa Makanan...">
            </div>
            <div class="nav-menu">
                <a href="#notifikasi">Notifikasi</a>
                <a href="#pesanan">Pesanan</a>
                <a href="#jualan">Jualan</a>
                <a href="#profil">Profil</a>
            </div>
        </div>
    </div>
    <div class="container">
        <h2>Pembayaran Berhasil</h2>
        <div class="success-message">
            <p>Terima kasih atas pembayaran Anda! Pesanan Anda sedang diproses dan akan segera dikirim.</p>
        </div>
        <a href="home.php" class="back-button">Kembali ke Menu</a>
    </div>
</body>
</html>
