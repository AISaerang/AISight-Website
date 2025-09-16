<?php
include 'db_connect.php';

session_start();

$id = $_GET['id'] ?? null;
$project = null;

if ($id) {
    try {
        $stmt = $conn->prepare("SELECT p.name, p.url, p.description, p.insight, p.thumbnail_url, u.name as tutor FROM projects p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Detail project dari AISight."/>
  <meta property="og:title" content="AISight — Detail Project"/>
  <meta property="og:description" content="Detail project dari AISight."/>
  <meta property="og:type" content="website"/>
  <title>AISight — Detail Project</title>
  <style>
    /* CSS sama seperti index.php, tambahkan untuk detail */
    .project-detail {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .project-thumbnail img {
      width: 100%;
      border-radius: var(--radius);
      margin-bottom: 16px;
    }

    .project-description {
      margin-bottom: 16px;
    }

    .insight-dropdown {
      margin-bottom: 16px;
    }

    .insight-header {
      background: var(--surface);
      padding: 12px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      border-radius: var(--radius);
      border: 1px solid var(--border);
    }

    .insight-content {
      display: none;
      padding: 12px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-top: none;
      border-radius: 0 0 var(--radius) var(--radius);
    }

    .insight-content.show {
      display: block;
    }

    .report-container {
      position: relative;
      height: 500px; /* Adjust as needed */
      margin-bottom: 16px;
    }

    .report-container iframe {
      width: 100%;
      height: 100%;
      border: none;
      border-radius: var(--radius);
    }

    .full-screen-btn {
      background: var(--accent);
      color: var(--text);
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .full-screen-btn:hover {
      background: var(--accent-strong);
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

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', (e) => {
      const query = e.target.value.toLowerCase();
      const elements = document.querySelectorAll('.project-thumbnail p');
      elements.forEach(el => {
        const text = el.textContent.toLowerCase();
        const thumbnail = el.parentElement;
        thumbnail.style.display = text.includes(query) ? 'block' : 'none';
      });
    });

    // Click event for thumbnails
    document.querySelectorAll('.project-thumbnail').forEach(thumbnail => {
      thumbnail.addEventListener('click', () => {
        const url = thumbnail.getAttribute('data-url');
        window.location.href = url;
      });
    });

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