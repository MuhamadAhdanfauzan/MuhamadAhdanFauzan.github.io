<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate token reset password
        $token = bin2hex(random_bytes(50));

        // Update token reset di database
        $sql = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        if ($stmt->execute()) {
            // Kirim email reset password (pseudo kode, perlu pengaturan server email)
            $resetLink = "http://localhost/bursa-makanan/reset-password.php?token=" . $token;
            echo "Link reset kata sandi: " . $resetLink;
            
            // Redirect ke halaman update-password.php dengan menyertakan email dan token
            header("Location: update-password.php?email=" . urlencode($email) . "&token=" . urlencode($token));
            exit();
        } else {
            echo "Terjadi kesalahan: " . $stmt->error;
        }
    } else {
        echo "Email tidak ditemukan";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Bursa Makanan</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .header {
            text-align: left;
            font-size: 24px;
            margin-bottom: 20px; /* Tambahkan margin-bottom di sini */
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header">Bursa Makanan</div>
    </div>
    <div class="container">
        <div class="header">Lupa Kata Sandi</div>
        <div class="login-form">
            <h2>Lupa Kata Sandi</h2>
            <form id="forgotPasswordForm" action="forgot-password.php" method="POST">
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <button type="submit" class="btn">Kirim Permintaan Reset Kata Sandi</button>
            </form>
        </div>
    </div>
</body>
</html>
