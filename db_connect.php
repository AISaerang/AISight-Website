<?php
$host = 'localhost';
$dbname = 'aisight_db';
$username = 'your_db_username'; // Ganti dengan username dari Database Management
$password = 'your_db_password'; // Ganti dengan password dari Database Management

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>