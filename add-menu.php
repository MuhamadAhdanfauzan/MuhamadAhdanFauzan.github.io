<?php
session_start();
include 'includes/db.php'; // Memuat koneksi database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_email'])) {
    // Jika belum login, arahkan ke halaman login
    header('Location: index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $photo = $_FILES['photo'];

    // Validasi dan upload gambar
    if ($photo['error'] == 0) {
        // Ambil ID produk terakhir untuk penamaan direktori
        $result = $conn->query("SELECT MAX(id) AS max_id FROM products");
        $row = $result->fetch_assoc();
        $next_id = $row['max_id'] + 1;
        $dir_name = 'uploads/produk_' . $next_id;

        // Buat direktori baru
        if (!is_dir($dir_name)) {
            mkdir($dir_name, 0777, true);
        }

        $target_file = $dir_name . '/' . basename($photo["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah gambar
        $check = getimagesize($photo["tmp_name"]);
        if ($check !== false) {
            // Cek ukuran file
            if ($photo["size"] <= 5000000) { // 5MB
                // Hanya izinkan format tertentu
                if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                    // Pindahkan file ke folder uploads
                    if (move_uploaded_file($photo["tmp_name"], $target_file)) {
                        // Simpan informasi menu ke database
                        $image_url = $dir_name . '/' . basename($photo["name"]);
                        $sql = "INSERT INTO products (name, category, description, price, image) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssss", $name, $category, $description, $price, $image_url);

                        if ($stmt->execute()) {
                            echo "Menu baru berhasil ditambahkan.";
                            header('Location: menu.php'); // Arahkan kembali ke halaman daftar menu setelah sukses menambahkan
                            exit();
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                    } else {
                        echo "Maaf, terjadi kesalahan saat mengupload gambar.";
                    }
                } else {
                    echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
                }
            } else {
                echo "Maaf, ukuran file terlalu besar.";
            }
        } else {
            echo "File bukan gambar.";
        }
    } else {
        echo "Tidak ada file yang diupload.";
    }

    // Tutup statement dan koneksi database
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .header-container {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            height: 60px; /* Atur tinggi header sesuai kebutuhan */
        }
        .header {
            text-align: left;
            font-size: 30px;
            margin-bottom: 50px; /* Tambahkan margin-bottom di sini */
        }

        .container {
            width: 50%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 70px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-height: 100vh; /* agar konten bisa di-scroll ke bawah */
            display: flex;
            flex-direction: column;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 16px;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            padding: 30px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <a href="home.php" class="header" style="text-decoration: none; color: #ffffff;">Bursa Makanan</a>
    </div>
    <div class="container">
        <h1>Tambah Menu</h1>
        <form action="add-menu.php" method="POST" enctype="multipart/form-data">
            <label for="photo">Foto Menu</label>
            <input type="file" id="photo" name="photo" required>
            <label for="name">Nama Menu</label>
            <textarea id="name" name="name" required></textarea>
            <label for="category">Kategori Menu</label>
            <select id="category" name="category">
                <option value="Roti">Roti</option>
                <!-- Tambahkan opsi kategori lainnya sesuai kebutuhan -->
            </select>
            <label for="description">Deskripsi menu</label>
            <textarea id="description" name="description" required></textarea>
            <label for="price">Atur Harga (Rp)</label>
            <input type="number" id="price" name="price" required>
            <button type="submit">Tambahkan</button>
        </form>
    </div>
</body>
</html>
