<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header("Location: overview.html");
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
    /* Sama seperti signup.php */
  </style>
</head>
<body>
  <nav class="navbar">
    <!-- Sama seperti signup.php -->
  </nav>

  <main>
    <div class="form-container">
      <h2>Login</h2>
      <form method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Gunakan alamat email aktif Anda" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan password" required>

        <button type="submit">Login</button>
      </form>
      <button class="google-btn" id="googleLogin">Login dengan Google</button>
    </div>
  </main>

  <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-auth.js"></script>
  <script>
    // Sama seperti signup.php, tapi ganti signInWithPopup
    document.getElementById('googleLogin').addEventListener('click', () => {
      auth.signInWithPopup(provider)
        .then((result) => {
          window.location.href = 'overview.html';
        })
        .catch((error) => {
          console.error(error);
        });
    });
  </script>
</body>
</html>