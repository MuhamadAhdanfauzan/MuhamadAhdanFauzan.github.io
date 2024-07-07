<?php
include 'includes/db.php';

session_start(); // Mulai session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_fullname'] = $user['name'];
            header('Location: home.php'); // Redirect ke halaman home setelah login berhasil
            exit();
        } else {
            $error_message = "Email atau kata sandi salah";
        }
    } else {
        $error_message = "Email atau kata sandi salah";
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
    <title>Foodshare Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="header-container">
        <div class="header">Foodshare Log in</div>
    </div>
    <div class="container">
        <div class="login-form">
            <h2>Log in</h2>
            <form id="loginForm" action="login.php" method="POST">
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-phone"></i></span>
                    <input type="text" id="email" name="email" placeholder="No.Handphone/Email" required>
                </div>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Masuk</button>
            </form>
            <p><a href="forgot-password.php">Lupa Kata Sandi?</a></p>
            <p>Belum punya akun? <a href="register.php">Klik buat akun baru</a></p>
            
            <?php
            if (isset($error_message)) {
                echo '<p style="color: red;">' . $error_message . '</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
