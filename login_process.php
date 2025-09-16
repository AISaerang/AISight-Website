<?php
session_start();
// Contoh sederhana (ganti dengan logika autentikasi nyata)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Misal: Cek terhadap database
    if ($email === "user@example.com" && $password === "password") {
        $_SESSION['user'] = ['profile_image' => 'assets/img/default-avatar.jpg', 'email' => $email];
        header("Location: index.php");
    } else {
        echo "<script>alert('Email atau kata sandi salah.');</script>";
    }
}
?>