<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query to check if token exists in database
    $sql = "SELECT * FROM users WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token valid, tampilkan form reset password
        ?>

        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Kata Sandi - Bursa Makanan</title>
            <link rel="stylesheet" href="css/styles.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        </head>
        <body>
            <div class="header-container">
                <div class="header">Bursa Makanan</div>
            </div>
            <div class="container">
                <div class="header">Reset Kata Sandi</div>
                <div class="login-form">
                    <h2>Reset Kata Sandi</h2>
                    <form id="resetPasswordForm" action="update-password.php" method="POST">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" placeholder="Kata Sandi Baru" required>
                        </div>
                        <button type="submit" class="btn">Reset Kata Sandi</button>
                    </form>
                </div>
            </div>
        </body>
        </html>

        <?php
    } else {
        echo "Token tidak valid atau telah kadaluarsa.";
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Jika tidak ada token atau bukan metode GET, redirect ke halaman forgot-password.php
header("Location: forgot-password.php");
exit();
?>
