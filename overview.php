<?php
include 'db_connect.php';

session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Daftar project terbaru dari tutor dan murid AISight."/>
  <meta property="og:title" content="AISight — Daftar Projects"/>
  <meta property="og:description" content="Daftar project terbaru dari tutor dan murid AISight."/>
  <meta property="og:type" content="website"/>
  <title>AISight — Daftar Projects</title>
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

    .hero {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 32px;
      align-items: center;
    }

    .hero-visual {
      max-width: 100%;
    }

    .hero-visual svg {
      width: 100%;
      height: auto;
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

    .badges {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 24px;
    }

    .badge {
      background: var(--card);
      border: 1px solid var(--border);
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 14px;
    }

    .cta {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
    }

    .sticky-cta {
      position: fixed;
      bottom: 24px;
      right: 24px;
      display: flex;
      gap: 12px;
      z-index: 40;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .divider {
      border: none;
      border-top: 1px solid var(--border);
      margin: 24px 0;
    }

    .metric-row {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }

    .metric {
      text-align: center;
    }

    .metric b {
      display: block;
      font-size: 18px;
    }

    .list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .list li {
      position: relative;
      padding-left: 24px;
      margin-bottom: 12px;
    }

    .list li::before {
      content: '•';
      position: absolute;
      left: 0;
      color: var(--accent);
      font-size: 20px;
    }

    .testimonials {
      position: relative;
      overflow: hidden;
      margin: 24px 0;
    }

    .testimonials-inner {
      display: flex;
      transition: transform 0.5s ease;
    }

    .testimonial {
      flex: 0 0 33.33%;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: var(--accent);
      color: var(--text);
      border: none;
      padding: 10px;
      cursor: pointer;
      z-index: 10;
      border-radius: 50%;
      font-size: 20px;
    }

    #prev-test {
      left: 10px;
    }

    #next-test {
      right: 10px;
    }

    .testimonial p {
      font-size: 16px;
      line-height: 1.5;
      margin: 0 0 16px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--border);
    }

    .t-author {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-top: 16px;
    }

    .t-author img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--accent);
    }

    .t-author div {
      font-size: 14px;
    }

    .t-author b {
      font-size: 16px;
      font-weight: 600;
    }

    .tutors {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
      margin-top: 24px;
    }

    .tutor {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow);
      text-align: center;
    }

    .tutor img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--accent);
      margin-bottom: 16px;
    }

    .tutor h3 {
      font-size: 18px;
      margin: 0 0 8px;
      color: var(--text);
    }

    .tutor p {
      font-size: 14px;
      color: var(--muted);
      margin: 0;
      line-height: 1.5;
    }

    .center {
      text-align: center;
    }

    @media (max-width: 960px) {
      .hero {
        grid-template-columns: 1fr;
      }
      .grid-2 {
        grid-template-columns: 1fr;
      }
      .metric-row {
        grid-template-columns: 1fr;
      }
      .testimonial {
        flex: 0 0 100%;
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
      .dropdown-menu {
        position: static;
        display: none;
        width: 100%;
        box-shadow: none;
        border: none;
      }
      .dropdown.active .dropdown-menu {
        display: block;
      }
      .auth-buttons {
        flex-direction: column;
        gap: 10px;
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
      .controls {
        gap: 6px;
        flex-direction: column;
        align-items: flex-end;
      }
      .nav {
        gap: 10px;
        font-size: 14px;
      }
      .tutor img {
        width: 60px;
        height: 60px;
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
            <img src="<?php echo htmlspecialchars($_SESSION['user']['profile_image'] ?: 'assets/img/Andi.jpg'); ?>" alt="Profile" class="profile-img" id="profileImg">
            <div class="dropdown-menu">
              <a href="profile.php">Pengaturan Profil</a>
              <a href="courses.php">Kursus Saya</a>
              <a href="portfolio.php">Portofolio</a>
              <a href="logout.php">Keluar</a>
            </div>
          </div>
        <?php else: ?>
          <a href="#" class="btn btn-outline" id="loginBtn">Masuk</a>
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

  <div class="sticky-cta">
    <a id="sticky-wa" class="btn" href="#">WhatsApp</a>
    <a class="btn btn-outline" href="simulation.php">Simulasikan</a>
  </div>

  <main class="section">
    <div class="container">
      <h1>Daftar Projects Terbaru</h1>
      <p class="sub">Jelajahi project terbaru dari tutor dan murid AISight. Klik thumbnail untuk detail.</p>

      <?php
      try {
        $stmt = $conn->prepare("SELECT p.id, p.name, p.thumbnail_url, u.name as tutor FROM projects p JOIN users u ON p.user_id = u.id");
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($projects && count($projects) > 0) {
          echo '<div class="project-grid">';
          foreach ($projects as $project) {
            echo "<div class='project-thumbnail' data-url='project.php?id=" . htmlspecialchars($project['id']) . "'>";
            echo "<img src='" . htmlspecialchars($project['thumbnail_url']) . "' alt='" . htmlspecialchars($project['name']) . "'>";
            echo "<p>" . htmlspecialchars($project['name']) . " - " . htmlspecialchars($project['tutor']) . "</p>";
            echo "</div>";
          }
          echo '</div>';
        } else {
          echo '<div class="no-results">Tidak ada project yang ditemukan.</div>';
        }
      } catch (PDOException $e) {
        echo '<div class="no-results">Terjadi kesalahan saat memuat project. Coba lagi nanti.</div>';
      }
      ?>
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
      } else {
        document.body.classList.remove('theme-light');
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

    const testimonialsData = [
      {
        text: "“Materinya terkurasi sesuai kebutuhan industri. Setelah kelas, aku membangun dashboard KPI marketing yang langsung dipakai tim.”",
        author: "Rina",
        role: "Marketing Analyst",
        avatar: "assets/img/rina.jpg"
      },
      {
        text: "“DAX yang tadinya bikin pusing jadi kebuka karena contoh kasusnya realistis. Review tugasnya detail dan actionable.”",
        author: "Fajar",
        role: "Business Intelligence",
        avatar: "assets/img/fajar.jpg"
      },
      {
        text: "“Portfolio web jadi nilai jual saat apply kerja. Skillset-nya langsung kepakai di tim data kami. Highly recommended.”",
        author: "Andi",
        role: "Fresh Graduate",
        avatar: "assets/img/andi.jpg"
      },
      {
        text: "“Pelatihan ini sangat membantu dalam meningkatkan kemampuan analisis data saya. Mentornya sangat berpengalaman.”",
        author: "Siti",
        role: "Data Analyst",
        avatar: "assets/img/siti.jpg"
      },
      {
        text: "“Sangat puas dengan kurikulum yang relevan dengan industri. Bonus domain portfolio sangat berguna.”",
        author: "Budi",
        role: "Professional",
        avatar: "assets/img/budi.jpg"
      },
      {
        text: "“Rekomendasi tinggi untuk fresh grad. Mentoring review portfolio membuat CV saya lebih menonjol.”",
        author: "Dewi",
        role: "Fresh Graduate",
        avatar: "assets/img/dewi.jpg"
      }
    ];

    function initTestimonials() {
      const inner = document.querySelector('.testimonials-inner');
      testimonialsData.forEach(data => {
        const testimonial = document.createElement('div');
        testimonial.classList.add('testimonial');
        testimonial.innerHTML = `
          <p>${data.text}</p>
          <div class="t-author">
            <img src="${data.avatar}" alt="${data.author}, ${data.role}" />
            <div>
              <b>${data.author}</b><br>
              <span class="muted">${data.role}</span>
            </div>
          </div>
        `;
        inner.appendChild(testimonial);
      });

      const container = document.getElementById('testimonials-carousel');
      const items = inner.querySelectorAll('.testimonial');
      if (items.length === 0) return;

      const itemWidth = items[0].offsetWidth;
      let current = 0;
      const perView = window.innerWidth <= 960 ? 1 : 3;
      const max = items.length - perView;

      document.getElementById('prev-test').addEventListener('click', () => {
        if (current > 0) {
          current--;
          inner.style.transform = `translateX(-${current * itemWidth}px)`;
        }
      });

      document.getElementById('next-test').addEventListener('click', () => {
        if (current < max) {
          current++;
          inner.style.transform = `translateX(-${current * itemWidth}px)`;
        }
      });
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
      initStickyWA();
      initTestimonials();

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