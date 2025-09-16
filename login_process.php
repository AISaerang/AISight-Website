<?php
session_start();

require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Autentikasi berhasil
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];

            // Inisialisasi sesi di database (opsional, sesuaikan dengan signup.php)
            $session_id = session_id();
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt = $conn->prepare("INSERT INTO sessions (id, user_id, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE expires_at = ?");
            $stmt->execute([$session_id, $user['id'], $expires_at, $expires_at]);

            header("Location: overview.php");
            exit();
        } else {
            $error = "Email atau kata sandi salah.";
        }
    } else {
        $error = "Email atau kata sandi salah.";
    }

    $stmt->close();
    $conn->close();

    if (isset($error)) {
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }
}
?>