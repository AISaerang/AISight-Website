<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "db_connect.php";

// Cek koneksi
if (!isset($conn) || $conn->connect_error) {
    die("Koneksi database gagal: " . ($conn->connect_error ?? 'Koneksi tidak terdefinisi'));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email dan kata sandi wajib diisi.";
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }

    // Prepared statement
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    if (!$stmt) {
        die("Prepare gagal: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        die("Execute gagal: " . $stmt->error);
    }

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

            // Inisialisasi sesi di database (opsional)
            $session_id = session_id();
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt2 = $conn->prepare("INSERT INTO sessions (id, user_id, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE expires_at = ?");
            if ($stmt2) {
                $stmt2->bind_param("siss", $session_id, $user['id'], $expires_at, $expires_at);
                $stmt2->execute();
                $stmt2->close();
            }

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
} else {
    header("Location: login.php");
    exit();
}
?>