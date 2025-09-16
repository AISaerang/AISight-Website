<?php
session_start();

// Sertakan file koneksi database
require_once "db_connect.php";

// Periksa apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // cegah SQL Injection dengan prepared statement
    $stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Autentikasi berhasil, set session
            $_SESSION['user'] = [
                'email' => $user['email'],
                'profile_image' => 'assets/img/default-avatar.jpg' // Sesuaikan jika ada kolom profile_image
            ];
            // Redirect ke halaman utama
            header("Location: index.php");
            exit();
        } else {
            // Password salah
            $error = "Email atau kata sandi salah.";
        }
    } else {
        // Email tidak ditemukan
        $error = "Email atau kata sandi salah.";
    }

    $stmt->close();
    $conn->close();
}

// Jika ada error atau form tidak disubmit, redirect kembali ke login dengan pesan error
if (isset($error)) {
    header("Location: login.php?error=" . urlencode($error));
    exit();
}
?>