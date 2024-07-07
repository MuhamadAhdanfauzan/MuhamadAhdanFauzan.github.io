<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bursa Makanan - Daftar Menu</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        width: 80%;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        min-height: 100vh; /* agar konten bisa di-scroll ke bawah */
        display: flex;
        flex-direction: column;
    }
    .welcome-message {
        font-size: 24px;
        text-align: center;
        margin-bottom: 20px;
    }
    .welcome-text {
        color: #45a049; /* Ubah warna teks di sini */
    }
    .wallet-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f5f5f5;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .wallet-balance {
        font-size: 20px;
        color: #4CAF50;
    }
    .top-up-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
    }
    .top-up-btn:hover {
        background-color: #45a049;
    }
    .content {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* grid responsive dengan lebar minimum 200px */
        gap: 20px; /* jarak antar grid */
    }
    .content-item {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 2px 10px 12px rgba(0, 0, 0, 0.1); /* shadow untuk menonjolkan konten */
        transition: box-shadow 0.3s ease;
        min-height: 300px; /* tinggi minimum konten */
        display: flex;
        flex-direction: column;
        border: 1px solid #45a049; /* garis hijau sebagai batas konten */
    }

    .content-item:hover {
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.2); /* Shadow lebih besar saat hover */
    }

    .content-item p {
        line-height: 1.6; /* spasi antarbaris pada paragraf */
        margin-bottom: 15px; /* margin bawah setiap paragraf */
        flex: 1; /* agar teks konten bisa mengisi seluruh ruang */
    }
    .content-item .buy-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        margin-top: auto; /* agar tombol "Beli" selalu berada di bagian bawah */
        align-self: center; /* posisi tombol "Beli" di tengah */
    }
    .buy-btn:hover {
        background-color: #45a049;
    }
    .logout-btn {
        background-color: #f44336;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }
    .logout-btn:hover {
        background-color: #d32f2f;
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
                <a href="menu.php">Pesanan</a> <!-- Tautan Pesanan diarahkan ke menu.php -->
                <a href="add-menu.php">Jualan</a>
                <a href="#profil">Profil</a>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="welcome-message">
            <p class="welcome-text">Selamat datang di Bursa Makanan, Ahdan<?php  ?></p>
        </div>

        <div class="wallet-container">
            <span class="wallet-balance">Saldo: Rp 200.000<?php  ?></span>
            <a href="topup.php" class="top-up-btn">Top Up</a>
        </div>
        
        <div class="content">
            <!-- Manual content item -->
            <div class="content-item">
                <img src="uploads/produk_manual/image1.jpg" alt="Donat J.CO">
                <div class="details">
                    <p>J.CO Donuts</p>
                    <p class="price">Rp 12.000</p>
                </div>
                <button class="buy-btn" onclick="beliProduk(1)">Beli</button>
            </div>

            <!-- Konten menu yang di-generate dari database -->
            <?php
            // Query untuk mengambil data produk
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="content-item">';
                    // Menampilkan gambar produk dari direktori yang sesuai
                    $img_path = glob("uploads/produk_" . $row['product_id'] . "/*.*")[0];
                    echo '<img src="' . $img_path . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<div class="details">';
                    echo '<p>' . htmlspecialchars($row['name']) . '</p>';
                    echo '<p class="price">Rp ' . htmlspecialchars(number_format($row['price'], 0, ',', '.')) . '</p>';
                    echo '</div>';
                    // Tombol "Beli" dengan aksi JavaScript sederhana
                    echo '<button class="buy-btn" onclick="beliProduk(' . $row['product_id'] . ')">Beli</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>Tidak ada menu yang tersedia.</p>';
            }

            $conn->close();
            ?>

            <!-- Script JavaScript untuk aksi "Beli" -->
            <script>
                function beliProduk(id_produk) {
                    // Implementasi aksi pembelian, bisa berupa redirect atau tindakan lain
                    console.log('Membeli produk dengan ID: ' + id_produk);
                    // Contoh redirect ke halaman pembelian
                    window.location.href = 'product.php?id=' + id_produk;
                }
            </script>
        </div>
    </div>
</body>
</html>
