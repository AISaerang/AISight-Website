<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; // Enkripsi password di sini (misalnya md5($password))

    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, md5($password)]); // Gunakan enkripsi yang sama seperti saat input data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: overview.php");
    } else {
        echo "<p>Login gagal!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AISight</title>
    <style>
        /* Gaya CSS seperti sebelumnya */
        :root { --accent: #14B8A6; --surface: #0F172A; --text: #E6E8EC; --radius: 16px; }
        body { font-family: system-ui; background: var(--surface); color: var(--text); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: #111827; padding: 20px; border-radius: var(--radius); box-shadow: 0 4px 12px rgba(0,0,0,.3); }
        .login-container input { width: 100%; padding: 8px; margin: 10px 0; border: 1px solid #1F2937; border-radius: var(--radius); background: #0F172A; color: var(--text); }
        .login-container button { width: 100%; padding: 10px; background: var(--accent); border: none; border-radius: var(--radius); color: var(--text); cursor: pointer; }
        .login-container button:hover { background: #0F766E; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>