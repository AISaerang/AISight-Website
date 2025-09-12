<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $referral = $_POST['referral'] ?? null;

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, referral_code) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $referral]);
    echo "<p>Pendaftaran berhasil!</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - AISight</title>
  <style>
    :root {
      --bg: #0B1220;
      --surface: #0F172A;
      --card: #111827;
      --border: #1F2937;
      --text: #E6E8EC;
      --muted: #9CA3AF;
      --accent: #14B8A6;
      --accent-strong: #0F766E;
      --ring: #22D3EE;
      --shadow: 0 10px 30px rgba(0, 0, 0, .35);
      --radius: 16px;
      --pad: clamp(16px, 4vw, 32px);
    }

    body.theme-light {
      --bg: #F6F7FB;
      --surface: #FFFFFF;
      --card: #FFFFFF;
      --border: #E5E7EB;
      --text: #0B1220;
      --muted: #6B7280;
      --shadow: 0 8px 24px rgba(2, 6, 23, .08);
    }

    * { box-sizing: border-box; }
    body { margin: 0; font: 16px/1.6 system-ui, -apple-system, sans-serif; color: var(--text); background: var(--bg); min-height: 100vh; }

    /* Navbar */
    .navbar {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(15, 23, 42, .6);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid var(--border);
    }
    body.theme-light .navbar { background: rgba(255, 255, 255, .8); }
    .navbar .inner { max-width: var(--maxw); margin: 0 auto; display: flex; align-items: center; justify-content: space-between; padding: 14px var(--pad); gap: 16px; }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; letter-spacing: .2px; }
    .brand .dot { width: 10px; height: 10px; border-radius: 999px; background: linear-gradient(135deg, var(--accent), var(--ring)); }
    .nav { display: flex; gap: 18px; align-items: center; margin-left: auto; position: relative; }
    .nav a { color: var(--text); opacity: .9; text-decoration: none; }
    .nav a:hover { color: var(--accent); }
    .nav .dropdown-menu { display: none; position: absolute; top: 100%; left: 0; background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); padding: 8px 0; min-width: 150px; }
    .nav .dropdown-menu a { display: block; padding: 8px 16px; }
    .nav:hover .dropdown-menu { display: block; } /* Hover untuk dropdown */
    .controls { display: flex; gap: 10px; align-items: center; margin-left: 12px; }

    /* Form Signup */
    .form-container { max-width: 400px; margin: 50px auto; background: var(--card); padding: 24px; border-radius: var(--radius); box-shadow: var(--shadow); }
    .form-container label { display: block; margin-bottom: 8px; font-weight: 500; }
    .form-container input, .form-container button { width: 100%; padding: 12px; margin-bottom: 16px; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); color: var(--text); }
    .form-container button { background: var(--accent); border: none; color: var(--text); cursor: pointer; }
    .form-container button:hover { background: var(--accent-strong); }
    .form-container .google-btn { background: #4285F4; color: white; margin-top: 16px; }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="inner container">
      <div class="brand"><span class="dot"></span> AISight</div>
      <div class="nav">
        <a href="#">Menu</a>
        <div class="dropdown-menu">
          <a href="index.html">Beranda</a>
          <a href="overview.html">Kurikulum</a>
          <a href="simulation.html">Kalkulator</a>
          <a href="contact.html">Kontak</a>
        </div>
      </div>
      <div class="controls">
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/>
          </svg>
          <svg viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="12" r="5"/>
            <line x1="12" y1="1" x2="12" y2="3" stroke="currentColor" stroke-width="2"/>
            <line x1="12" y1="21" x2="12" y2="23" stroke="currentColor" stroke-width="2"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke="currentColor" stroke-width="2"/>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke="currentColor" stroke-width="2"/>
            <line x1="1" y1="12" x2="3" y2="12" stroke="currentColor" stroke-width="2"/>
            <line x1="21" y1="12" x2="23" y2="12" stroke="currentColor" stroke-width="2"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke="currentColor" stroke-width="2"/>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke="currentColor" stroke-width="2"/>
          </svg>
          <span class="thumb"></span>
        </button>
        <button class="palette-btn" id="paletteBtn" aria-label="Change accent color">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
            <path d="M14.7 3.3a1 1 0 0 1 1.4 0l4.6 4.6a1 1 0 0 1 0 1.4l-6.8 6.8a5 5 0 0 1-2.95 1.43c-.66.07-1.3.36-1.77.83l-.9.9a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.9-.9c.47-.47.76-1.11.83-1.77A5 5 0 0 1 8.9 10.1l6.8-6.8a1 1 0 0 1 1.4 0z"/>
            <circle cx="19" cy="19" r="2"/>
          </svg>
        </button>
      </div>
    </div>
  </nav>

  <main>
    <div class="form-container">
      <h2>Daftar Akun Dicoding</h2>
      <form method="POST">
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" placeholder="Masukkan nama asli Anda, nama akan digunakan pada sertifikat" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Gunakan alamat email aktif Anda" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Gunakan minimal 8 karakter dengan kombinasi huruf dan angka" required>

        <label for="referral">Kode Referral (Opsional)</label>
        <input type="text" id="referral" name="referral" placeholder="Masukkan kode referral pengguna lain jika ada">

        <button type="submit">Daftar</button>
      </form>
      <button class="google-btn" id="googleSignup">Daftar dengan Google</button>
    </div>
  </main>

  <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-auth.js"></script>
  <script>
    const firebaseConfig = {
      apiKey: "YOUR_API_KEY",
      authDomain: "your-project.firebaseapp.com",
      projectId: "your-project",
      storageBucket: "your-project.appspot.com",
      messagingSenderId: "YOUR_SENDER_ID",
      appId: "YOUR_APP_ID"
    };
    firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();

    const provider = new firebase.auth.GoogleAuthProvider();

    document.getElementById('googleSignup').addEventListener('click', () => {
      auth.signInWithPopup(provider)
        .then((result) => {
          // User signed in with Google
          const user = result.user;
          // Kirim data ke PHP atau database untuk simpan
          window.location.href = 'overview.html';
        })
        .catch((error) => {
          console.error(error);
        });
    });
  </script>
</body>
</html>