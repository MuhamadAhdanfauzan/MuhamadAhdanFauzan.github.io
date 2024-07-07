<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Baru</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .header {
            text-align: left;
            font-size: 24px;
            margin-bottom: 0px; /* Tambahkan margin-bottom di sini */
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header">Bursa Makanan Register</div>
    </div>
    <div class="container">
        <div class="header">Buat Akun Baru</div>
        <div class="login-form">
            <h2>Buat Akun Baru</h2>
            <form id="registerForm" action="register-process.php" method="POST">
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="text" name="name" placeholder="Nama Lengkap" required>
                </div>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" placeholder="Kata Sandi" required>
                </div>
                <button type="submit" class="btn">Buat Akun</button>
                
                <?php
                if (isset($_GET['error']) && $_GET['error'] == 'email_exists') {
                    echo '<p style="color: red;">Email sudah terdaftar. Gunakan email lain atau <a href="index.html">masuk</a>.</p>';
                }
                ?>
                
                <p>Sudah punya akun? <a href="index.html">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>
