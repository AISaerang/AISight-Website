<?php
$host = 'localhost';
$dbname = 'u199348039_aisight_db';
$username = 'u199348039_admin'; // Ganti dengan username dari Database Management
$password = '@!$Db415'; // Ganti dengan password dari Database Management

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>