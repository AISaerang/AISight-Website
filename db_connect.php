<?php
$host = 'localhost';
$dbname = 'u199348039_aisight_db';
$username = 'u199348039_admin'; // Ganti dengan username dari Database Management
$password = '@!$Db415'; // Ganti dengan password dari Database Management

try {
    // Membuat koneksi PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mengaktifkan error handling
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Memastikan prepared statements asli
} catch (PDOException $e) {
    // Log error untuk produksi, tampilkan pesan ramah
    error_log("Koneksi gagal: " . $e->getMessage());
    die("Maaf, terjadi kesalahan. Silakan coba lagi nanti.");
}
?>