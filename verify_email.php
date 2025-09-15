<?php
include 'db_connect.php';

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token']; // Asumsikan token dikirim via email

    $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE email = ? AND referral_code = ?");
    if ($stmt->execute([$email, $token])) {
        echo "Email berhasil diverifikasi. Silakan login.";
    } else {
        echo "Verifikasi gagal. Token tidak valid.";
    }
}
?>