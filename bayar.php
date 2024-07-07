<?php
session_start();

// Periksa apakah data yang diperlukan ada dalam $_POST
if (!isset($_POST['product_id']) || !isset($_POST['total_tagihan'])) {
    echo "Data tidak lengkap!";
    exit();
}

$product_id = $_POST['product_id'];
$total_tagihan = $_POST['total_tagihan'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bursa Makanan - Pembayaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Your existing CSS styles */
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
            max-width: 500px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .payment-options {
            margin-bottom: 20px;
        }
        .payment-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            cursor: pointer; /* Added cursor pointer for clickable effect */
        }
        .payment-option:last-child {
            border-bottom: none;
        }
        .radio-button {
            width: 20px;
            height: 20px;
            border: 2px solid #4CAF50;
            border-radius: 50%;
            display: inline-block;
        }
        .radio-button.active {
            background-color: #4CAF50;
        }
        .summary {
            margin-top: 20px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .pay-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .link {
            color: #4CAF50;
            text-decoration: none;
            font-size: 14px;
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
                <a href="#pesanan">Pesanan</a>
                <a href="#jualan">Jualan</a>
                <a href="#profil">Profil</a>
            </div>
        </div>
    </div>
    <div class="container">
        <h2>Pembayaran <a href="#" class="link" style="float: right;">Lihat Semua</a></h2>
        <div class="payment-options">
            <div class="payment-option" id="mandiri-option">
                <span>Mandiri Virtual Account</span>
                <span class="radio-button active"></span>
            </div>
            <div class="payment-option" id="bca-option">
                <span>BCA Virtual Account</span>
                <span class="radio-button"></span>
            </div>
            <div class="payment-option" id="wallet-option">
                <span>Saldo Wallet</span>
                <span class="radio-button"></span>
            </div>
        </div>
        <div class="summary">
            <h3>Ringkasan Pembayaran</h3>
            <div class="summary-item">
                <span>Total Tagihan</span>
                <span id="total-tagihan">Rp <?php echo number_format($total_tagihan, 2); ?></span>
            </div>
        </div>
        <form id="payment-form" method="POST" action="proses_pembayaran.php">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <input type="hidden" name="total_tagihan" value="<?php echo $total_tagihan; ?>">
            <input type="hidden" id="metode_pembayaran" name="metode_pembayaran" value="mandiri"> <!-- Default to Mandiri VA -->
            <button type="submit" class="pay-button">Bayar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua opsi pembayaran
            const paymentOptions = document.querySelectorAll('.payment-option');

            // Tambahkan event listener untuk setiap opsi pembayaran
            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Hapus kelas active dari semua tombol radio
                    paymentOptions.forEach(opt => {
                        opt.querySelector('.radio-button').classList.remove('active');
                    });

                    // Tambahkan kelas active hanya pada tombol radio yang dipilih
                    this.querySelector('.radio-button').classList.add('active');

                    // Set nilai metode pembayaran di form sesuai opsi yang dipilih
                    const selectedMethod = this.id.split('-')[0]; // Mendapatkan bagian pertama dari id (mandiri, bca, wallet)
                    document.getElementById('metode_pembayaran').value = selectedMethod;
                });
            });
        });
    </script>
</body>
</html>
