<?php
include 'db_connect.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header("Location: overview.html");
    exit;
  } else {
    $error = "Login gagal! Periksa email atau password.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Masuk ke akun AISight untuk mengakses modul dan portofolio."/>
  <meta property="og:title" content="AISight — Login"/>
  <meta property="og:description" content="Masuk ke akun AISight untuk mengakses modul dan portofolio."/>
  <meta property="og:type" content="website"/>
  <title>AISight — Login</title>
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
    }

    .btn-outline {
      background: none;
      border: 1px solid var(--border);
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
      max-width: 400px;
      margin: 50px auto;
      padding: 0 var(--pad);
    }

    .form-container {
      background: var(--card);
      padding: 24px;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .form-container label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }

    .form-container input {
      width: 100%;
      padding: 12px;
      margin-bottom: 16px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--surface);
      color: var(--text);
    }

    .form-container button {
      width: 100%;
      padding: 12px;
      background: var(--accent);
      border: none;
      border-radius: 8px;
      color: var(--text);
      cursor: pointer;
    }

    .form-container button:hover {
      background: var(--accent-strong);
    }

    .google-btn {
      width: 100%;
      padding: 12px;
      background: #4285F4;
      border: none;
      border-radius: 8px;
      color: white;
      cursor: pointer;
    }

    .google-btn:hover {
      background: #3267D6;
    }

    .error {
      color: var(--ring);
      margin-top: 8px;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="inner container">
      <a href="index.html" class="brand">
        <span class="dot"></span> AISaerang
      </a>
      <div class="nav-menu">
        <div class="dropdown">
          <a href="#" class="dropdown-toggle">Jalur Belajar</a>
          <div class="dropdown-menu">
            <a href="overview.html">Modul</a>
            <a href="projects.html">Portofolio</a>
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
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/>
          </svg>
          <svg viewBox="0 0 24 24" fill="currentColor">
            <circle cx="12" cy="12" r="5"/>
            <line x1="12" y1="1" x2="12" y2="3" stroke="currentColor" stroke-width="2"/>
            <line x1="12" y1="21" x2="12" y2="23" stroke="currentColor" stroke-width="2"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke="currentColor" stroke-width="2"/>
            <line x1="