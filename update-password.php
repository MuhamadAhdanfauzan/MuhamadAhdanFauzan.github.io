<?php
session_start();
include 'includes/db.php'; // Pastikan file ini memuat koneksi database yang benar

// Periksa jika ada permintaan POST untuk mengubah kata sandi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['reset_email'])) {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        // Ambil email dari sesi
        $email = $_SESSION['reset_email'];

        // Query untuk mendapatkan password terenkripsi dari database
        $sql_get_password = "SELECT password FROM users WHERE email = ?";
        $stmt_get_password = $conn->prepare($sql_get_password);
        $stmt_get_password->bind_param("s", $email);
        $stmt_get_password->execute();
        $result_get_password = $stmt_get_password->get_result();

        if ($result_get_password->num_rows > 0) {
            $row = $result_get_password->fetch_assoc();
            $current_password_hash = $row['password'];

            // Verifikasi password lama
            if (password_verify($old_password, $current_password_hash)) {
                // Konfirmasi kata sandi baru
                if ($new_password === $confirm_password) {
                    // Hash password baru
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Simpan password dalam format plaintext (opsional)
                    $plaintext_password = $new_password;

                    // Update kata sandi di database
                    $sql_update_password = "UPDATE users SET password = ?, plaintext_password = ? WHERE email = ?";
                    $stmt_update_password = $conn->prepare($sql_update_password);
                    $stmt_update_password->bind_param("sss", $hashed_password, $plaintext_password, $email);
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
                echo "Password lama yang Anda masukkan salah.";
            }
        } else {
            echo "Email tidak ditemukan.";
        }

        $stmt_get_password->close();
    } else {
        echo "Token reset tidak valid.";
    }
    exit(); // Keluar dari skrip setelah proses POST selesai
}

// Jika token disediakan melalui parameter GET
if (!isset($_GET['token']) || empty($_GET['token'])) {
    header("Location: forgot-password.php");
    exit();
}

$token = $_GET['token'];

// Query untuk mencari pengguna berdasarkan token reset
$sql = "SELECT * FROM users WHERE reset_token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Token valid, tampilkan form ubah kata sandi
    $user = $result->fetch_assoc();
    $_SESSION['reset_email'] = $user['email']; // Simpan email untuk digunakan setelah ubah kata sandi

    // Hapus token reset setelah digunakan
    $sql_update_token = "UPDATE users SET reset_token = NULL WHERE email = ?";
    $stmt_update_token = $conn->prepare($sql_update_token);
    $stmt_update_token->bind_param("s", $user['email']);
    $stmt_update_token->execute();
    $stmt_update_token->close();
} else {
    // Token tidak valid atau tidak ditemukan
    echo "Token reset tidak valid atau sudah kadaluarsa.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi - Bursa Makanan</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="header-container">
        <div class="header">Ubah Kata Sandi</div>
    </div>
    <div class="container">
        <div class="login-form">
            <h2>Ubah Kata Sandi</h2>
            <form id="updatePasswordForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="old_password" placeholder="Password Lama" required>
                </div>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="new_password" placeholder="Kata Sandi Baru" required>
                </div>
                <div class="input-group">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Kata Sandi Baru" required>
                </div>
                <button type="submit" class="btn">Simpan Kata Sandi Baru</button>
            </form>
        </div>
    </div>
</body>
</html>
