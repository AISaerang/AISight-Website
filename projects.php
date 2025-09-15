<?php
include 'db_connect.php';

session_start();

// Ambil user_id dari session, default null jika belum login
$user_id = $_SESSION['user']['id'] ?? null;
$projects = [];

if ($user_id) {
    // Ambil semua proyek yang terkait dengan user_id
    $stmt = $conn->prepare("SELECT p.id, p.name, p.url, p.description, p.insight, p.thumbnail_url, u.name as tutor FROM projects p JOIN users u ON p.user_id = u.id WHERE p.user_id = ? ORDER BY p.created_at DESC");
    $stmt->execute([$user_id]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Jika belum login, tampilkan pesan atau arahkan ke login
    $error = "Anda harus login untuk melihat portofolio.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Portofolio proyek dari tutor dan murid AISight termasuk insight dan laporan interaktif."/>
  <meta property="og:title" content="AISight — Portofolio"/>
  <meta property="og:description" content="Portofolio proyek dari tutor dan murid AISight termasuk insight dan laporan interaktif."/>
  <meta property="og:type" content="website"/>
  <title>AISight — Portofolio</title>
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

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font: 16px/1.6 system-ui, -apple-system, sans-serif;
      color: var(--text);
      background: var(--bg);
      min-height: 100vh;
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
      font-weight: 500;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background: var(--accent-strong);
    }

    .btn.btn-outline {
      background: none;
      border: 1px solid var(--border);
      color: var(--text);
    }

    .btn.btn-outline:hover {
      background: var(--surface);
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

    .sub {
      font-size: 18px;
      color: var(--muted);
      margin: 0 0 24px;
    }

    .project-preview {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 24px;
      margin: 32px 0;
    }

    .project-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 16px;
      box-shadow: var(--shadow);
      cursor: pointer;
      transition: transform 0.2s;
    }

    .project-card:hover {
      transform: translateY(-5px);
    }

    .project-card img {
      width: 100%;
      height: auto;
      border-radius: var(--radius);
      margin-bottom: 8px;
    }

    .project-card h3 {
      margin: 0 0 8px;
      font-size: 18px;
    }

    .project-card .details {
      display: none;
      margin-top: 16px;
      padding: 12px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
    }

    .project-card .details.show {
      display: block;
    }

    .project-card .report-container {
      position: relative;
      height: 300px;
      margin-bottom: 16px;
    }

    .project-card iframe {
      width: 100%;
      height: 100%;
      border: none;
      border-radius: var(--radius);
    }

    .project-card .full-screen-btn {
      background: var(--accent);
      color: var(--text);
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 8px;
      transition: background-color 0.3s;
    }

    .project-card .full-screen-btn:hover {
      background: var(--accent-strong);
    }

    .error {
      color: var(--ring);
      text-align: center;
    }

    .sticky-cta {
      position: fixed;
      bottom: 24px;
      right: 24px;
      display: flex;
      gap: 12px;
      z-index: 40;
    }

    @media (max-width: 720px) {
      .project-preview {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="inner container">
      <a href="index.html" class="brand">
        <span class="dot"></span> AISight
      </a>
      <div class="nav-menu">
        <div class="dropdown">
          <a href="#" class="dropdown-toggle">Jalur Belajar</a>
          <div class="dropdown-menu">
            <a href="overview.html">Modul</a>
            <a href="projects.php">Portofolio</a>
          </div>
        </div>
        <a href="simulation.html">Biaya</a>
        <div class="dropdown">
          <a href="#" class="dropdown-toggle">Lainnya</a>
          <div class="dropdown-menu">
            <a href="contact.html">Kontak</a>
            <a href="blog.html">Blog</a>
            <a href="faq.html">FAQ</a>
          </div>
        </div>
      </div>
      <div class="auth-buttons">
        <a href="login.php" class="btn btn-outline">Masuk</a>
        <a href="signup.php" class="btn">Daftar</a>
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

  <div class="sticky-cta">
    <a id="sticky-wa" class="btn" href="#">WhatsApp</a>
    <a class="btn btn-outline" href="simulation.html">Simulasikan</a>
  </div>

  <main class="section">
    <div class="container">
      <h1>Portofolio Proyek</h1>
      <p class="sub">Jelajahi proyek Anda dengan preview thumbnail. Klik untuk melihat detail.</p>
      <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
      <?php elseif (empty($projects)): ?>
        <p class="error">Belum ada proyek yang ditambahkan.</p>
      <?php else: ?>
        <div class="project-preview">
          <?php foreach ($projects as $project): ?>
            <div class="project-card" onclick="toggleDetail(this)">
              <img src="<?php echo htmlspecialchars($project['thumbnail_url'] ?: 'assets/img/default-thumbnail.jpg'); ?>" alt="<?php echo htmlspecialchars($project['name']); ?>" style="width: 100%; border-radius: var(--radius); margin-bottom: 8px;">
              <h3><?php echo htmlspecialchars($project['name']); ?></h3>
              <div class="details">
                <div class="report-container">
                  <iframe src="<?php echo htmlspecialchars($project['url']); ?>" frameborder="0"></iframe>
                </div>
                <button class="btn full-screen-btn" onclick="toggleFullScreen(this.previousElementSibling.querySelector('iframe'))">Full Screen</button>
                <div class="insight-dropdown">
                  <div class="insight-header" onclick="toggleInsight(this)">
                    <span>Insight & Deskripsi</span>
                    <span>+</span>
                  </div>
                  <div class="insight-content">
                    <p><?php echo htmlspecialchars($project['description'] ?: 'Deskripsi belum tersedia.'); ?></p>
                    <h3>Insight</h3>
                    <p><?php echo htmlspecialchars($project['insight'] ?: 'Tidak ada insight'); ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer class="section">
    <div class="container center muted">&copy; 2025 AISight. Semua hak dilindungi.</div>
  </footer>

  <script>
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

    function initStickyWA() {
      try {
        const n = window.AISIGHT_CONFIG?.WHATSAPP_NUMBER || '';
        const url = n ? `https://wa.me/${n}?text=Halo%20AISight%2C%20saya%20ingin%20info%20pelatihan` : '#';
        const a = document.getElementById('sticky-wa');
        if (a) a.href = url;
      } catch (e) {}
    }

    function toggleDetail(card) {
      const details = card.querySelector('.details');
      details.classList.toggle('show');
    }

    function toggleInsight(header) {
      const content = header.nextElementSibling;
      content.classList.toggle('show');
      header.querySelector('span:last-child').textContent = content.classList.contains('show') ? '-' : '+';
    }

    function toggleFullScreen(iframe) {
      if (iframe.requestFullscreen) {
        iframe.requestFullscreen();
      } else if (iframe.mozRequestFullScreen) { /* Firefox */
        iframe.mozRequestFullScreen();
      } else if (iframe.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
        iframe.webkitRequestFullscreen();
      } else if (iframe.msRequestFullscreen) { /* IE/Edge */
        iframe.msRequestFullscreen();
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      restorePreferences();
      initStickyWA();

      const hamburger = document.querySelector('.hamburger');
      const navMenu = document.querySelector('.nav-menu');
      const dropdowns = document.querySelectorAll('.dropdown');

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

      document.addEventListener('click', (e) => {
        if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
          navMenu.classList.remove('active');
          hamburger.classList.remove('active');
          dropdowns.forEach(d => d.classList.remove('active'));
        }
      });
    });
  </script>
</body>
</html>