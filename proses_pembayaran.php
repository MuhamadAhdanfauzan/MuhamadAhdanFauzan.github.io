<?php
session_start();
include 'includes/db.php'; // Memuat koneksi database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_email'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: index.html');
    exit();
}

// Periksa apakah data yang diperlukan ada dalam $_POST
if (!isset($_POST['product_id']) || !isset($_POST['total_tagihan'])) {
    echo "Data tidak lengkap!";
    exit();
}

$product_id = $_POST['product_id'];
$total_tagihan = $_POST['total_tagihan'];
$user_email = $_SESSION['user_email'];

// Ambil ID pengguna berdasarkan email
$sql_user = "SELECT id FROM users WHERE email = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$stmt_user->bind_result($user_id);
$stmt_user->fetch();
$stmt_user->close();

// Update saldo dompet pengguna
$sql_update_balance = "UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?";
$stmt_update = $conn->prepare($sql_update_balance);
$stmt_update->bind_param("di", $total_tagihan, $user_id);
$stmt_update->execute();
$stmt_update->close();

// Simpan detail pembayaran ke tabel orders (buat tabel jika belum ada)
$sql_create_orders_table = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    amount DECIMAL(10, 2),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";
$conn->query($sql_create_orders_table);

// Insert order details into orders table
$sql_insert_order = "INSERT INTO orders (user_id, product_id, amount, status) VALUES (?, ?, ?, 'Sedang Diproses')";
$stmt_insert = $conn->prepare($sql_insert_order);
$stmt_insert->bind_param("iid", $user_id, $product_id, $total_tagihan);
$stmt_insert->execute();
$stmt_insert->close();

// Update total_orders for the product
$sql_update_total_orders = "UPDATE products SET total_orders = total_orders + 1 WHERE product_id = ?";
$stmt_update_total = $conn->prepare($sql_update_total_orders);
$stmt_update_total->bind_param("i", $product_id);
$stmt_update_total->execute();
$stmt_update_total->close();

$conn->close();

// Redirect to payment success page
header('Location: pembayaran_berhasil.php');
exit();
?>
