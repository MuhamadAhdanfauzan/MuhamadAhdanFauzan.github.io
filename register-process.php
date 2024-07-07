<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Cek apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email sudah terdaftar
        $stmt->close();
        $conn->close();
        header("Location: register.php?error=email_exists");
        exit();
    }

    // Jika email belum terdaftar, lakukan proses pendaftaran
    // Simpan password dalam format plaintext
    $plaintext_password = $password;

    // Encrypt password menggunakan PHP password_hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, plaintext_password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $plaintext_password);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    // Redirect ke halaman login setelah berhasil mendaftar
    header("Location: index.html");
    exit();
} else {
    // Jika bukan metode POST, redirect ke halaman register
    header("Location: register.php");
    exit();
}
?>
