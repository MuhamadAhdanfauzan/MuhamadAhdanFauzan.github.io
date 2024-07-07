<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Arahkan pengguna ke halaman login
header('Location: index.html');
exit();
?>
