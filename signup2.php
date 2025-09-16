<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $referral = trim($_POST['referral']) ?? null;

    // Validasi input
    if (!$name || !$email || !$password || strlen($password) < 8) {
        $error = "Nama, email, dan password (minimal 8 karakter) wajib diisi.";
    } elseif (!preg_match("/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/", $password)) {
        $error = "Password harus mengandung kombinasi huruf dan angka.";
    } else {
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email sudah terdaftar.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, referral_code, is_verified, created_at, updated_at) VALUES (?, ?, ?, ?, 0, NOW(), NOW())");
            if ($stmt->execute([$name, $email, $hashed_password, $referral])) {
                $user_id = $conn->lastInsertId();
                $_SESSION['user'] = ['id' => $user_id, 'name' => $name, 'email' => $email, 'profile_image' => 'assets/img/default-avatar.jpg'];

                // Inisialisasi sesi (opsional)
                $session_id = session_id();
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $stmt = $conn->prepare("INSERT INTO sessions (id, user_id, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$session_id, $user_id, $expires_at]);

                // Inisialisasi modul default (opsional)
                $stmt = $conn->prepare("INSERT INTO user_modules (user_id, module_id, status) SELECT ?, id, 'not_started' FROM modules WHERE package_type = 'Regular' LIMIT 1");
                $stmt->execute([$user_id]);

                header("Location: index.php");
                exit();
            } else {
                $error = "Terjadi kesalahan saat mendaftar. Coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Daftar akun untuk mengakses pelatihan AISight."/>
  <meta property="og:title" content="AISight — Daftar"/>
  <meta property="og:description" content="Daftar akun untuk mengakses pelatihan AISight."/>
  <meta property="og:type" content="website"/>
  <title>AISight — Daftar</title>
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
      --maxw: 1120px;
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

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font: 16px/1.6 system-ui, -apple-system, sans-serif;
      color: var(--text);
      background: var(--bg);
      min-height: 100vh;
    }

    .search-bar { margin-left: 20px; }
    .search-bar input {
      padding: 8px;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: var(--surface);
      color: var(--text);
      width: 250px;
    }

    .navbar {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(15, 23, 42, .6);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid var(--border);
    }

    body.theme-light .navbar {
      background: rgba(255, 255, 255, .8);
    }

    .navbar .inner {
      max-width: var(--maxw);
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px var(--pad);
      gap: 16px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      letter-spacing: .2px;
      text-decoration: none;
      color: var(--text);
    }

    .brand .dot {
      width: 10px;
      height: 10px;
      border-radius: 999px;
      background: linear-gradient(135deg, var(--accent), var(--ring));
    }

    .nav-menu {
      display: flex;
      gap: 18px;
      align-items: center;
    }

    .nav-menu a {
      color: var(--text);
      opacity: .9;
      text-decoration: none;
      padding: 4px 8px;
    }

    .nav-menu a:hover {
      color: var(--accent);
    }

    .dropdown {
      position: relative;
    }

    .dropdown-toggle {
      cursor: pointer;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 8px 0;
      min-width: 150px;
    }

    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu a {
      display: block;
      padding: 8px 16px;
      color: var(--text);
      text-decoration: none;
    }

    .dropdown-menu a:hover {
      background: var(--surface);
    }

    .auth-buttons {
      display: flex;
      gap: 8px;
    }

    .btn {
      background: var(--accent);
      color: var(--text);
      padding: 8px 16px;
      border-radius: var(--radius);
      text-decoration: none;
    }

    .btn-outline {
      background: none;
      border: 1px solid var(--border);
    }

    .profile-dropdown {
      position: relative;
    }

    .profile-dropdown .profile-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      cursor: pointer;
      border: 2px solid var(--accent);
    }

    .profile-dropdown .dropdown-menu {
      top: calc(100% + 5px);
      right: 0;
      left: auto;
    }

    .controls {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .theme-toggle {
      position: relative;
      width: 64px;
      height: 32px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: var(--card);
      cursor: pointer;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 6px;
      transition: background-color 0.3s;
    }

    .theme-toggle:hover {
      background: var(--surface);
    }

    .theme-toggle svg {
      width: 18px;
      height: 18px;
      opacity: 0.7;
      z-index: 2;
      position: relative;
    }

    .theme-toggle .thumb {
      position: absolute;
      top: 3px;
      left: 3px;
      width: 26px;
      height: 26px;
      border-radius: 999px;
      background: var(--accent);
      transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 1;
    }

    body.theme-light .theme-toggle .thumb {
      transform: translateX(32px);
      background: linear-gradient(180deg, #333, #555);
    }

    .palette-btn {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: var(--card);
      border: 1px solid var(--border);
      cursor: pointer;
      padding: 0;
      display: grid;
      place-items: center;
      transition: background-color 0.3s, transform 0.2s;
    }

    .palette-btn:hover {
      background: var(--surface);
      transform: scale(1.05);
    }

    .palette-btn:active {
      transform: scale(0.95);
    }

    .palette-btn svg {
      width: 22px;
      height: 22px;
      color: var(--text);
    }

    .palette-btn::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 50%;
      transform: translateX(-50%);
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--accent);
      border: 2px solid var(--bg);
    }

    .container {
      max-width: var(--maxw);
      margin: 0 auto;
      padding: 48px var(--pad);
    }

    .section {
      padding: 32px 0;
    }

    h1 {
      font-size: clamp(28px, 5vw, 40px);
      line-height: 1.2;
      margin: 0 0 16px;
    }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .card label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }

    .card input {
      width: 100%;
      padding: 12px;
      margin-bottom: 16px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--surface);
      color: var(--text);
      font-size: 16px;
      transition: border-color 0.3s, background-color 0.3s;
    }

    .card input:focus {
      outline: none;
      border-color: var(--accent);
      background: var(--card);
    }

    .card button {
      width: 100%;
      padding: 12px;
      background: var(--accent);
      border: none;
      color: var(--text);
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      transition: background-color 0.3s;
    }

    .card button:hover {
      background: var(--accent-strong);
    }

    .card .google-btn {
      background: #4285F4;
      color: white;
      margin-top: 16px;
    }

    .card .error {
      color: var(--ring);
      margin-top: 8px;
      font-size: 14px;
    }

    @media (max-width: 960px) {
      .navbar .inner {
        flex-direction: column;
        align-items: flex-start;
      }
      .nav-menu, .auth-buttons, .controls {
        width: 100%;
        margin-top: 10px;
      }
      .nav-menu {
        flex-direction: column;
        align-items: flex-start;
      }
      .dropdown-menu {
        position: static;
        width: 100%;
        box-shadow: none;
        border: none;
      }
      .dropdown.active .dropdown-menu {
        display: block;
      }
    }

    @media (max-width: 720px) {
      .nav-menu {
        display: none;
        position: absolute;
        top: 60px;
        right: 0;
        flex-direction: column;
        background: var(--card);
        padding: 20px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        width: 200px;
      }
      .nav-menu.active {
        display: flex;
      }
      .hamburger {
        display: block;
        width: 30px;
        height: 20px;
        position: relative;
        cursor: pointer;
      }
      .hamburger span {
        position: absolute;
        height: 3px;
        width: 100%;
        background: var(--text);
        display: block;
        transition: all 0.3s ease;
      }
      .hamburger span:nth-child(1) { top: 0; }
      .hamburger span:nth-child(2) { top: 50%; transform: translateY(-50%); }
      .hamburger span:nth-child(3) { bottom: 0; }
      .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
      .hamburger.active span:nth-child(2) { opacity: 0; }
      .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(7px, -7px); }
      .search-bar {
        margin-left: 0;
        width: 100%;
        margin-bottom: 10px;
      }
      .search-bar input {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="inner container">
      <a href="index.php" class="brand">
        <span class="dot"></span> AISight
      </a>
      <div class="search-bar">
        <input type="text" placeholder="Cari proyek atau tutor...">
      </div>
      <div class="nav-menu">
        <div class="dropdown">
          <a href="#" class="dropdown-toggle">Jalur Belajar</a>
          <div class="dropdown-menu">
            <a href="overview.php">Modul</a>
            <a href="projects.php">Portofolio</a>
          </div>
        </div>
        <a href="simulation.php">Biaya</a>
        <div class="dropdown">
          <a href="#" class="dropdown-toggle">Lainnya</a>
          <div class="dropdown-menu">
            <a href="contact.php">Kontak</a>
            <a href="blog.php">Blog</a>
            <a href="faq.php">FAQ</a>
          </div>
        </div>
      </div>
      <div class="auth-buttons">
        <?php if (isset($_SESSION['user'])): ?>
          <div class="profile-dropdown">
            <img src="<?php echo htmlspecialchars($_SESSION['user']['profile_image'] ?: 'assets/img/default-avatar.jpg'); ?>" alt="Profile" class="profile-img" id="profileImg">
            <div class="dropdown-menu">
              <a href="profile.php">Pengaturan Profil</a>
              <a href="courses.php">Kursus Saya</a>
              <a href="portfolio.php">Portofolio</a>
              <a href="logout.php">Keluar</a>
            </div>
          </div>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline">Masuk</a>
          <a href="signup.php" class="btn">Daftar</a>
        <?php endif; ?>
      </div>
      <div class="controls">
        <button class="hamburger" aria-label="Toggle menu">
          <span></span><span></span><span></span>
        </button>
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

  <main class="section">
    <div class="container">
      <div class="card">
        <h1>Daftar Akun</h1>
        <?php if (isset($error)): ?>
          <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name" placeholder="Masukkan nama asli Anda" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>

          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Gunakan alamat email aktif" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>

          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Gunakan minimal 8 karakter dengan kombinasi huruf dan angka" required>

          <label for="referral">Kode Referral (Opsional)</label>
          <input type="text" id="referral" name="referral" placeholder="Masukkan kode referral pengguna lain" value="<?php echo isset($_POST['referral']) ? htmlspecialchars($_POST['referral']) : ''; ?>">

          <button type="submit">Daftar</button>
        </form>
        <button class="google-btn" id="googleSignup">Daftar dengan Google</button>
      </div>
    </div>
  </main>

  <footer class="section">
    <div class="container center muted">&copy; 2025 AISight. Semua hak dilindungi.</div>
  </footer>

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
    const app = firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();
    const provider = new firebase.auth.GoogleAuthProvider();

    document.getElementById('googleSignup').addEventListener('click', () => {
      auth.signInWithPopup(provider)
        .then((result) => {
          const user = result.user;
          fetch('signup_firebase.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              name: user.displayName,
              email: user.email,
              googleId: user.uid
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              window.location.href = 'overview.php';
            } else {
              alert('Gagal menyimpan data. Coba lagi.');
            }
          })
          .catch(error => console.error('Error:', error));
        })
        .catch((error) => {
          console.error('Google Sign-In Error:', error);
          alert('Gagal login dengan Google. Coba lagi.');
        });
    });

    const PALETTES = {
      teal: { accent: '#14B8A6', strong: '#0F766E', ring: '#22D3EE' },
      violet: { accent: '#7C3AED', strong: '#5B21B6', ring: '#8B5CF6' },
      orange: { accent: '#FB923C', strong: '#C2410C', ring: '#FDBA74' }
    };
    const PALETTE_ORDER = ['teal', 'violet', 'orange'];
    let currentPaletteIndex = 0;

    function applyPalette(name) {
      const palette = PALETTES[name];
      if (!palette) return;
      const root = document.documentElement;
      root.style.setProperty('--accent', palette.accent);
      root.style.setProperty('--accent-strong', palette.strong);
      root.style.setProperty('--ring', palette.ring);
      localStorage.setItem('palette', name);
      currentPaletteIndex = PALETTE_ORDER.indexOf(name);
      if (currentPaletteIndex === -1) currentPaletteIndex = 0;
    }

    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', function() {
      const isLight = document.body.classList.toggle('theme-light');
      localStorage.setItem('theme', isLight ? 'light' : 'dark');
    });

    const paletteBtn = document.getElementById('paletteBtn');
    paletteBtn.addEventListener('click', function() {
      currentPaletteIndex = (currentPaletteIndex + 1) % PALETTE_ORDER.length;
      const newPalette = PALETTE_ORDER[currentPaletteIndex];
      applyPalette(newPalette);
    });

    function restorePreferences() {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'light') {
        document.body.classList.add('theme-light');
      }
      const savedPalette = localStorage.getItem('palette') || 'teal';
      applyPalette(savedPalette);
    }

    function toggleProfileDropdown() {
      const profileImg = document.getElementById('profileImg');
      const menu = profileImg.nextElementSibling;
      if (menu.style.display === 'block') {
        menu.style.display = 'none';
      } else {
        menu.style.display = 'block';
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      restorePreferences();

      const hamburger = document.querySelector('.hamburger');
      const navMenu = document.querySelector('.nav-menu');
      const dropdowns = document.querySelectorAll('.dropdown');
      const profileImg = document.getElementById('profileImg');

      hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
      });

      dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        toggle.addEventListener('click', (e) => {
          e.preventDefault();
          dropdown.classList.toggle('active');
        });
      });

      if (profileImg) {
        profileImg.addEventListener('click', toggleProfileDropdown);
      }

      document.addEventListener('click', (e) => {
        if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
          navMenu.classList.remove('active');
          hamburger.classList.remove('active');
          dropdowns.forEach(d => d.classList.remove('active'));
        }
        const profileMenu = document.querySelector('.profile-dropdown .dropdown-menu');
        if (profileMenu && !profileMenu.contains(e.target) && !profileImg?.contains(e.target)) {
          profileMenu.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>