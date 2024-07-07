<?php
session_start();
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['reset_email'])) {
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];
        

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $email = $_SESSION['reset_email'];

            // Update kata sandi di database
            $sql_update_password = "UPDATE users SET password = ? WHERE email = ?";
            $stmt_update_password = $conn->prepare($sql_update_password);
            $stmt_update_password->bind_param("ss", $hashed_password, $email);
            if ($stmt_update_password->execute()) {
                // Hapus sesi dan arahkan ke halaman login setelah berhasil mengubah kata sandi
                unset($_SESSION['reset_email']);
                header("Location: index.html?message=Kata+sandi+berhasil+diubah.+Silakan+masuk+dengan+kata+sandi+baru+Anda.");
                exit();
            } else {
                echo "Terjadi kesalahan saat mengubah kata sandi: " . $stmt_update_password->error;
            }

            $stmt_update_password->close();
        } else {
            echo "Konfirmasi kata sandi baru tidak cocok.";
        }
    } else {
        echo "Token reset tidak valid.";
    }
} else {
    header("Location: index.html");
    exit();
}

$conn->close();
?>
