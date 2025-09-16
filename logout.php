<?php
session_start();

// Menghancurkan semua data sesi
session_unset();
session_destroy();

// Set pesan flash (opsional, gunakan jika ada mekanisme notifikasi)
$_SESSION['message'] = "Anda telah berhasil logout.";
header("Location: index.php");
exit();
?>