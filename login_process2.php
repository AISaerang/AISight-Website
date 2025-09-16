<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "db_connect.php";

// Check if connection is valid
if (!isset($conn) || !$conn instanceof PDO) {
    die("Koneksi database tidak terdefinisi atau tidak valid.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Email dan kata sandi wajib diisi.";
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        if (!$stmt) {
            die("Prepare gagal: " . print_r($conn->errorInfo(), true));
        }

        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Authentication successful
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];

            // Initialize session in database (optional)
            $session_id = session_id();
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $stmt2 = $conn->prepare("INSERT INTO sessions (id, user_id, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE expires_at = ?");
            if ($stmt2) {
                $stmt2->execute([$session_id, $user['id'], $expires_at, $expires_at]);
                // No need to close PDO statement explicitly
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Email atau kata sandi salah.";
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $error = "Terjadi kesalahan. Coba lagi.";
    }

    // No need for $stmt->close() in PDO
    if (isset($error)) {
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>