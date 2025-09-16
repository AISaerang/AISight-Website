<?php
include 'db_connect.php';

session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AISight — Masuk</title>
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

    .login-card {
      max-width: 400px;
      margin: 0 auto;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow);
      text-align: center;
    }

    .login-card h2 {
      margin-top: 0;
      font-size: 24px;
      color: var(--text);
    }

    .login-card .form-group {
      margin-bottom: 16px;
      text-align: left;
    }

    .login-card label {
      display: block;
      margin-bottom: 4px;
      color: var(--muted);
    }

    .login-card input {
      width: 100%;
      padding: 8px;
      border: 1px solid var(--border);
      border-radius: var(--radius);
      background: var(--surface);
      color: var(--text);
      font-size: 16px;
    }

    .login-card .btn {
      width: 100%;
      background: var(--accent);
      color: var(--text);
      padding: 10px;
      border-radius: var(--radius);
      text-decoration: none;
      display: inline-block;
      cursor: pointer;
    }

    .login-card .btn:disabled {
      background: var(--border);
      cursor: not-allowed;
    }

    .login-card .google-btn {
      width: 100%;
      background: #DB4437;
      color: white;
      padding: 10px;
      border-radius: var(--radius);
      text-decoration: none;
      display: inline-block;
      margin-top: 8px;
      opacity: 0.7; /* Disabled visual cue */
      cursor: not-allowed;
    }

    .login-card .google-btn:hover {
      opacity: 0.8;
    }

    .login-card .forgot {
      display: block;
      margin-top: 12px;
      color: var(--accent);
      text-decoration: none;
      font-size: 14px;
    }

    .login-card .signup-link {
      margin-top: 16px;
      color: var(--muted);
    }

    .login-card .signup-link a {
      color: var(--accent);
      text-decoration: none;
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 16px;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="inner container">
      <a href="index.php" class="brand">
        <span class="dot"></span> AISaerang
      </a>
      <div class="search-bar">
        <input type="text" placeholder="Cari proyek atau tutor..." disabled>
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
      <div class="auth-buttons"></div>
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

  <div class="container">
    <div class="login-card">
      <h2>Masuk ke AISight</h2>
      <form action="login_process.php" method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required placeholder="Masukkan email Anda">
        </div>
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi">
        </div>
        <button type="submit" class="btn">Masuk</button>
      </form>
      <a href="#" class="forgot">Lupa Kata Sandi?</a>
      <button class="google-btn" disabled>Login dengan Google</button>
      <div class="signup-link">
        Belum punya akun? <a href="signup.php">Daftar di sini</a>
      </div>
    </div>
  </div>

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
      } else {
        document.body.classList.remove('theme-light');
      }
      const savedPalette = localStorage.getItem('palette') || 'teal';
      applyPalette(savedPalette);
    }

    document.addEventListener('DOMContentLoaded', restorePreferences);
  </script>
</body>
</html>