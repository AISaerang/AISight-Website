<?php
include 'db_connect.php';
session_start();
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . addslashes($_SESSION['message']) . "');</script>";
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta name="description" content="Pelatihan modern, kurikulum relevan industri, durasi 16–42 jam, sertifikat lifetime, Power BI Pro 1 bulan, domain portfolio 1 tahun."/>
  <meta property="og:title" content="AISight — Pelatihan Data Analytics & Power BI"/>
  <meta property="og:description" content="Pelatihan modern, kurikulum relevan industri, durasi 16–42 jam, sertifikat lifetime, Power BI Pro 1 bulan, domain portfolio 1 tahun."/>
  <meta property="og:type" content="website"/>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AISight — Pelatihan Data Analytics & Power BI</title>
  <style>
    :root{
      --bg:#0B1220;--surface:#0F172A;--card:#111827;--border:#1F2937;--text:#E6E8EC;--muted:#9CA3AF;
      --accent:#14B8A6;--accent-strong:#0F766E;--ring:#22D3EE;--shadow:0 10px 30px rgba(0,0,0,.35);
      --radius:16px;--maxw:1120px;--pad:clamp(16px,4vw,32px);
    }
    body.theme-light{
      --bg:#F6F7FB;--surface:#FFFFFF;--card:#FFFFFF;--border:#E5E7EB;--text:#0B1220;--muted:#6B7280;
      --shadow:0 8px 24px rgba(2,6,23,.08);
    }

    *{box-sizing:border-box}
    body{margin:0;font:16px/1.6 system-ui,-apple-system,sans-serif;color:var(--text);background:var(--bg);min-height:100vh}

    /* ====== NAVBAR ====== */
    .navbar{position:sticky;top:0;z-index:50;background:rgba(15,23,42,.6);backdrop-filter:blur(8px);border-bottom:1px solid var(--border)}
    body.theme-light .navbar{background:rgba(255,255,255,.8)}
    .navbar .inner{max-width:var(--maxw);margin:0 auto;display:flex;align-items:center;justify-content:space-between;padding:12px var(--pad);gap:12px}

    .brand{display:flex;align-items:center;gap:8px;font-weight:700;letter-spacing:.2px;text-decoration:none;color:var(--text);font-size:20px}
    .brand .dot{width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--accent),var(--ring))}

    .search-bar{margin-left:20px}
    .search-bar input{
      padding:8px;border:1px solid var(--border);border-radius:999px;background:var(--surface);color:var(--text);width:250px
    }

    .nav-menu{display:flex;gap:12px;align-items:center}
    .nav-menu a{color:var(--text);opacity:.9;text-decoration:none;padding:4px 8px;font-size:14px}
    .nav-menu a:hover{color:var(--accent)}

    .dropdown{position:relative}
    .dropdown-toggle{cursor:pointer;font-size:14px}
    .dropdown-menu{
      display:none;position:absolute;top:100%;left:0;background:var(--card);border:1px solid var(--border);border-radius:var(--radius);
      box-shadow:var(--shadow);padding:8px 0;min-width:140px
    }
    .dropdown:hover .dropdown-menu{display:block}
    .dropdown-menu a{display:block;padding:6px 12px;color:var(--text);text-decoration:none;font-size:13px}
    .dropdown-menu a:hover{background:var(--surface)}

    .auth-buttons{display:flex;gap:8px}
    .btn{background:var(--accent);color:var(--text);padding:6px 12px;border-radius:999px;text-decoration:none;font-size:14px}
    .btn-outline{background:none;border:1px solid var(--border)}

    .profile-dropdown{position:relative}
    .profile-dropdown .profile-img{width:32px;height:32px;border-radius:50%;object-fit:cover;cursor:pointer;border:2px solid var(--accent)}
    .profile-dropdown .dropdown-menu{top:calc(100% + 5px);right:0;left:auto;min-width:130px}

    .controls{display:flex;gap:8px;align-items:center}

    /* Toggle berwarna mengikuti palette */
    .theme-toggle{
      position:relative;width:64px;height:32px;border-radius:999px;border:1px solid var(--border);background:var(--card);
      cursor:pointer;padding:0 6px;display:flex;align-items:center;justify-content:space-between;transition:background-color .25s
    }
    .theme-toggle:hover{background:var(--surface)}
    .theme-toggle svg{width:18px;height:18px;opacity:.8;color:var(--muted);z-index:2}
    .theme-toggle .thumb{
      position:absolute;top:3px;left:3px;width:26px;height:26px;border-radius:999px;background:var(--accent);
      transition:transform .25s cubic-bezier(.4,0,.2,1), background-color .25s;box-shadow:inset 0 0 0 2px rgba(0,0,0,.12)
    }
    /* Saat light mode, tombol geser ke kanan – tetap warna accent */
    body.theme-light .theme-toggle .thumb{transform:translateX(32px)}
    /* Jejak warna track tipis sesuai tema */
    .theme-toggle::after{
      content:"";position:absolute;inset:0;border-radius:999px;background:linear-gradient(90deg,rgba(0,0,0,.06),rgba(0,0,0,0));
      opacity:.25;pointer-events:none
    }

    .palette-btn{
      width:36px;height:36px;border-radius:10px;background:var(--card);border:1px solid var(--border);
      cursor:pointer;padding:0;display:grid;place-items:center;transition:background-color .25s, transform .2s
    }
    .palette-btn:hover{background:var(--surface);transform:scale(1.05)}
    .palette-btn:active{transform:scale(.95)}
    .palette-btn svg{width:22px;height:22px;color:var(--text)}

    /* ====== LAYOUT ====== */
    .container{max-width:var(--maxw);margin:0 auto;padding:48px var(--pad)}
    .section{padding:32px 0}
    .hero{display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center}
    .hero-visual{max-width:100%}
    .hero-visual svg{width:100%;height:auto}
    h1{font-size:clamp(28px,5vw,40px);line-height:1.2;margin:0 0 16px}
    .sub{font-size:18px;color:var(--muted);margin:0 0 24px}
    .badges{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:24px}
    .badge{background:var(--card);border:1px solid var(--border);padding:6px 12px;border-radius:8px;font-size:13px}
    .cta{display:flex;gap:12px;flex-wrap:wrap}
    .sticky-cta{position:fixed;bottom:24px;right:24px;display:flex;gap:8px;z-index:40}
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:24px}
    .card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:20px;box-shadow:var(--shadow)}
    .divider{border:none;border-top:1px solid var(--border);margin:20px 0}
    .metric-row{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
    .metric{text-align:center}.metric b{font-size:16px}
    .list{list-style:none;padding:0;margin:0}.list li{padding-left:20px;margin-bottom:10px}.list li::before{content:"•";position:absolute;left:0;color:var(--accent);font-size:18px}

    /* ====== TESTI ====== */
    .testimonials{position:relative;overflow:hidden;margin:20px 0}
    .testimonials-inner{display:flex;transition:transform .5s ease}
    .testimonial{flex:0 0 33.33%;background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:20px;box-shadow:var(--shadow)}
    .carousel-btn{position:absolute;top:50%;transform:translateY(-50%);background:var(--accent);color:var(--text);border:none;padding:8px;cursor:pointer;z-index:10;border-radius:50%;font-size:18px}
    #prev-test{left:8px} #next-test{right:8px}
    .testimonial p{font-size:14px;margin:0 0 12px;padding-bottom:12px;border-bottom:1px solid var(--border)}
    .t-author{display:flex;align-items:center;gap:10px;margin-top:10px}
    .t-author img{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--accent)}
    .t-author div{font-size:12px}.t-author b{font-size:14px}
    .tutors{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px}
    .tutor{padding:20px;text-align:center}
    .tutor img{width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--accent);margin-bottom:12px}
    .tutor h3{font-size:16px;margin:0 0 6px}.tutor p{font-size:13px}
    .center{text-align:center}

    /* ====== LOGIN MODAL ====== */
    .modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);justify-content:center;align-items:center;z-index:1000}
    .modal.active{display:flex}
    .modal-content{
      position:relative;background:var(--card);border:1px solid var(--border);border-radius:var(--radius);
      padding:20px;width:92%;max-width:420px;box-shadow:var(--shadow);animation:fadeIn .25s ease
    }
    .modal-content h2{font-size:20px;margin:0 0 12px}
    .modal-content .form-group{margin-bottom:12px;text-align:left}
    .modal-content label{display:block;font-size:14px;color:var(--muted);margin-bottom:4px}
    .modal-content input{width:100%;padding:10px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text);font-size:14px}
    .modal-content input:focus{outline:none;border-color:var(--accent);background:var(--card)}
    .modal-content button{width:100%;padding:10px;background:var(--accent);border:none;color:var(--text);border-radius:8px;cursor:pointer;font-weight:600}
    .modal-content button:hover{background:var(--accent-strong)}
    .close-btn{
      position:absolute;top:8px;right:8px;width:36px;height:36px;border-radius:10px;background:var(--surface);
      border:1px solid var(--border);color:var(--text);font-size:22px;line-height:32px;text-align:center;cursor:pointer
    }
    .modal-content .error{color:var(--ring);font-size:12px;margin-top:8px;min-height:16px}
    .forgot{display:block;margin-top:10px;font-size:12px;color:var(--muted);text-decoration:none}
    .forgot:hover{color:var(--accent)}

    /* ====== RESPONSIVE ====== */
    @media (max-width:960px){
      .hero{grid-template-columns:1fr}
      .grid-2{grid-template-columns:1fr}
      .metric-row{grid-template-columns:1fr}
      .testimonial{flex:0 0 100%}
    }

    /* --- MOBILE NAV LAYOUT persis sesuai permintaan --- */
    @media (max-width:720px){
      /* Ubah layout navbar jadi grid 2 kolom 2 baris:
         baris1: brand | auth (pojok kanan atas)
         baris2: search | controls (ikon di bawah auth) */
    /* Susunan 2 baris, 2 kolom:
   baris-1: brand | auth
   baris-2: search | controls (sejajar satu baris) */
.navbar .inner{
  display:grid;
  grid-template-columns: 1fr auto;
  grid-template-areas:
    "brand auth"
    "search controls";
  row-gap: 8px;
  align-items: center;          /* samakan baseline */
}

/* Search bar jadi satu baris dengan controls */
.search-bar{ grid-area: search; margin-left:0; width:100%; }
.search-bar input{ width:100%; max-width:none; padding:8px 12px; }

/* Controls horizontal (hamburger → toggle → palette) */
.controls{
  grid-area: controls;
  justify-self: end;
  display:flex;
  flex-direction: row;          /* <— ini kuncinya */
  align-items: center;
  gap: 10px;
}

/* Hamburger tetap tampil di mobile */
.hamburger{
  display:block;
  width:28px; height:18px; position:relative; cursor:pointer; background:none; border:none;
}
.hamburger span{
  position:absolute; height:2px; width:100%; background:var(--text); transition:all .3s ease;
}
.hamburger span:nth-child(1){ top:0; }
.hamburger span:nth-child(2){ top:50%; transform:translateY(-50%); }
.hamburger span:nth-child(3){ bottom:0; }
.hamburger.active span:nth-child(1){ transform:rotate(45deg) translate(5px,5px); }
.hamburger.active span:nth-child(2){ opacity:0; }
.hamburger.active span:nth-child(3){ transform:rotate(-45deg) translate(7px,-7px); }

/* (opsional) rapikan menu link di mobile */
.nav-menu{ display:none; }


    @keyframes fadeIn{from{opacity:0;transform:scale(.98)}to{opacity:1;transform:scale(1)}}
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="inner container">
      <a href="index.php" class="brand"><span class="dot"></span> AISight</a>

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
          <!-- moon -->
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/></svg>
          <!-- sun -->
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

  <!-- Sticky CTA -->
  <div class="sticky-cta">
    <a id="sticky-wa" class="btn" href="#">WhatsApp</a>
    <a class="btn btn-outline" href="simulation.php">Simulasikan</a>
  </div>

  <!-- LOGIN MODAL -->
  <div class="modal" id="loginModal" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="loginTitle">
      <button class="close-btn" id="closeModal" aria-label="Tutup modal">&times;</button>
      <h2 id="loginTitle">Masuk</h2>
      <form id="loginForm" method="POST" action="login_process.php" novalidate>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required placeholder="Masukkan email Anda" autocomplete="email">
        </div>
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi" autocomplete="current-password">
        </div>
        <button type="submit">Masuk</button>
        <p class="error" id="loginError"></p>
      </form>
      <a href="#" class="forgot">Lupa Kata Sandi?</a>
    </div>
  </div>

  <!-- HERO + CONTENT (tidak diubah) -->
  <header class="section">
    <div class="container hero">
      <div>
        <h1>Belajar Power BI & Data Analytics yang <span style="color:var(--accent)">kompetitif</span> dan <span style="color:var(--ring)">terjangkau</span></h1>
        <p class="sub">Materi terstruktur, praktik proyek nyata, dan dukungan mentor untuk mahasiswa, fresh grad, hingga profesional.</p>
        <div class="badges">
          <span class="badge">16–42 jam intensif</span>
          <span class="badge">Sertifikat lifetime</span>
          <span class="badge">Power BI Pro 1 bulan</span>
          <span class="badge">Domain portfolio 1 tahun</span>
        </div>
        <div class="cta">
          <a class="btn" href="overview.php">Lihat Kurikulum</a>
          <a class="btn btn-outline" href="simulation.php">Simulasikan Biaya</a>
        </div>
      </div>
      <div class="hero-visual" aria-hidden="true">
        <svg viewBox="0 0 600 400" role="img" aria-label="Animasi grafik">
          <defs>
            <linearGradient id="grad" x1="0" y1="0" x2="1" y2="1">
              <stop stop-color="var(--accent)" offset="0"/>
              <stop stop-color="var(--ring)" offset="1"/>
            </linearGradient>
          </defs>
          <g opacity=".18">
            <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
              <path d="M20 0H0V20" fill="none" stroke="currentColor" stroke-width="1"/>
            </pattern>
            <rect width="600" height="400" fill="url(#grid)"/>
          </g>
          <g fill="url(#grad)">
            <rect x="80" y="240" width="50" height="120">
              <animate attributeName="y" values="260;240;260" dur="3s" repeatCount="indefinite"/>
              <animate attributeName="height" values="100;120;100" dur="3s" repeatCount="indefinite"/>
            </rect>
            <rect x="160" y="200" width="50" height="160">
              <animate attributeName="y" values="220;200;220" dur="3.4s" repeatCount="indefinite"/>
              <animate attributeName="height" values="140;160;140" dur="3.4s" repeatCount="indefinite"/>
            </rect>
            <rect x="240" y="260" width="50" height="100">
              <animate attributeName="y" values="280;260;280" dur="2.7s" repeatCount="indefinite"/>
              <animate attributeName="height" values="80;100;80" dur="2.7s" repeatCount="indefinite"/>
            </rect>
            <rect x="320" y="220" width="50" height="140">
              <animate attributeName="y" values="240;220;240" dur="3.2s" repeatCount="indefinite"/>
              <animate attributeName="height" values="120;140;120" dur="3.2s" repeatCount="indefinite"/>
            </rect>
          </g>
          <polyline points="80,280 160,240 240,300 320,260 400,220 480,260" fill="none" stroke="url(#grad)" stroke-width="4" stroke-linecap="round">
            <animate attributeName="points"
              values="80,300 160,260 240,320 320,280 400,240 480,280;
                      80,280 160,240 240,300 320,260 400,220 480,260;
                      80,300 160,260 240,320 320,280 400,240 480,280" dur="4s" repeatCount="indefinite"/>
            <animate attributeName="stroke-opacity" values="1;0.7;1" dur="4s" repeatCount="indefinite"/>
          </polyline>
          <polyline points="80,280 160,240 240,300 320,260 400,220 480,260" fill="none" stroke="var(--card)" stroke-width="6" stroke-linecap="round" opacity="0.5">
            <animate attributeName="points"
              values="80,300 160,260 240,320 320,280 400,240 480,280;
                      80,280 160,240 240,300 320,260 400,220 480,260;
                      80,300 160,260 240,320 320,280 400,240 480,280" dur="4s" repeatCount="indefinite"/>
          </polyline>
        </svg>
      </div>
    </div>
  </header>

  <main>
    <section class="section">
      <div class="container">
        <div class="grid-2">
          <div class="card">
            <h3>Kenapa AISight lebih bernilai?</h3>
            <p class="muted">
              Harga sertifikasi Power BI Data Analyst Associate dari Microsoft sekitar Rp 2,5 juta untuk 1 exam saja, tanpa pelatihan.
              <a href="https://learn.microsoft.com/en-us/credentials/certifications/data-analyst-associate/?practice-assessment-type=certification" target="_blank" class="citation"><span class="badge">learn.microsoft.com</span></a>
              Course eksternal seperti Nexacu mulai dari Rp 6 juta untuk 1 hari training dasar.
              <a href="https://nexacu.com/microsoft-power-bi-training-courses/" target="_blank" class="citation"><span class="badge">nexacu.com</span></a>
              Disini kalian bisa hemat hingga 50% dan dapat hasil maksimal!
            </p>
            <hr class="divider">
            <div class="metric-row">
              <div class="metric"><b>1–4 jt</b><span class="muted">mengikuti modul pilihan</span></div>
              <div class="metric"><b>16–42 jam</b><span class="muted">durasi belajar</span></div>
              <div class="metric"><b>+Bonus</b><span class="muted">sertifikat, domain, PBI Pro</span></div>
              <div class="metric"><b>Mentoring</b><span class="muted">review portfolio</span></div>
            </div>
          </div>
          <div class="card">
            <h3>Fokus Hasil</h3>
            <ul class="list">
              <li>Proyek mini tiap modul + studi kasus industri.</li>
              <li>DAX & Dashboard yang efektif dan komunikatif.</li>
              <li>Storytelling dan penyajian insight bisnis.</li>
              <li>Portfolio web untuk pamer karya kamu.</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <h2>Yang Mereka Katakan</h2>
        <p class="sub">Beberapa pengalaman peserta setelah mengikuti pelatihan AISight.</p>
        <div class="testimonials" id="testimonials-carousel">
          <div class="testimonials-inner"></div>
          <button id="prev-test" class="carousel-btn">&lt;</button>
          <button id="next-test" class="carousel-btn">&gt;</button>
        </div>
        <hr class="divider" />
        <h3>Tim Pengajar Kami</h3>
        <div class="tutors">
          <div class="tutor">
            <img src="assets/img/tutor1.jpg" alt="Tutor Sarah, Data Analyst" />
            <h3>Sarah</h3>
            <p>Sejak 2022, Sarah memimpin proyek analitik di startup teknologi, fokus pada visualisasi data dengan Power BI. Ia juga mentor untuk bootcamp data sejak 2023.</p>
          </div>
          <div class="tutor">
            <img src="assets/img/tutor2.jpg" alt="Tutor Bima, BI Consultant" />
            <h3>Bima</h3>
            <p>Bima telah bekerja sebagai konsultan BI selama 3 tahun, membantu perusahaan retail mengoptimalkan KPI dengan DAX. Ia aktif berbagi insight di komunitas data sejak 2022.</p>
          </div>
          <div class="tutor">
            <img src="assets/img/tutor3.jpg" alt="Tutor Livia, Data Scientist" />
            <h3>Livia</h3>
            <p>Livia mengembangkan dashboard interaktif untuk fintech selama 2 tahun terakhir. Ia juga mengajar storytelling data di universitas lokal sejak 2023.</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="section">
    <div class="container center muted">&copy; 2025 AISight. Semua hak dilindungi.</div>
  </footer>

  <script>
    /* ====== Palette & Theme ====== */
    const PALETTES = {
      teal:{accent:'#14B8A6',strong:'#0F766E',ring:'#22D3EE'},
      violet:{accent:'#7C3AED',strong:'#5B21B6',ring:'#8B5CF6'},
      orange:{accent:'#FB923C',strong:'#C2410C',ring:'#FDBA74'}
    };
    const PALETTE_ORDER = ['teal','violet','orange'];
    let currentPaletteIndex = 0;
    function applyPalette(name){
      const p = PALETTES[name]; if(!p) return;
      const r = document.documentElement;
      r.style.setProperty('--accent',p.accent);
      r.style.setProperty('--accent-strong',p.strong);
      r.style.setProperty('--ring',p.ring);
      localStorage.setItem('palette',name);
      currentPaletteIndex = PALETTE_ORDER.indexOf(name); if(currentPaletteIndex===-1) currentPaletteIndex=0;
    }
    const themeToggle = document.getElementById('themeToggle');
    themeToggle.addEventListener('click', () => {
      const isLight = document.body.classList.toggle('theme-light');
      localStorage.setItem('theme', isLight ? 'light' : 'dark');
    });
    const paletteBtn = document.getElementById('paletteBtn');
    paletteBtn.addEventListener('click', () => {
      currentPaletteIndex = (currentPaletteIndex + 1) % PALETTE_ORDER.length;
      applyPalette(PALETTE_ORDER[currentPaletteIndex]);
    });
    function restorePreferences(){
      const t = localStorage.getItem('theme'); if(t==='light') document.body.classList.add('theme-light');
      applyPalette(localStorage.getItem('palette') || 'teal');
    }

    /* ====== Sticky WA ====== */
    function initStickyWA(){
      try{
        const n = window.AISIGHT_CONFIG?.WHATSAPP_NUMBER || '';
        const url = n ? `https://wa.me/${n}?text=Halo%20AISight%2C%20saya%20ingin%20info%20pelatihan` : '#';
        const a = document.getElementById('sticky-wa'); if(a) a.href = url;
      }catch(e){}
    }

    /* ====== Testimonials ====== */
    const testimonialsData = [
      {text:"“Materinya terkurasi sesuai kebutuhan industri. Setelah kelas, aku membangun dashboard KPI marketing yang langsung dipakai tim.”",author:"Rina",role:"Marketing Analyst",avatar:"assets/img/rina.jpg"},
      {text:"“DAX yang tadinya bikin pusing jadi kebuka karena contoh kasusnya realistis. Review tugasnya detail dan actionable.”",author:"Fajar",role:"Business Intelligence",avatar:"assets/img/fajar.jpg"},
      {text:"“Portfolio web jadi nilai jual saat apply kerja. Skillset-nya langsung kepakai di tim data kami. Highly recommended.”",author:"Andi",role:"Fresh Graduate",avatar:"assets/img/andi.jpg"},
      {text:"“Pelatihan ini sangat membantu dalam meningkatkan kemampuan analisis data saya. Mentornya sangat berpengalaman.”",author:"Siti",role:"Data Analyst",avatar:"assets/img/siti.jpg"},
      {text:"“Sangat puas dengan kurikulum yang relevan dengan industri. Bonus domain portfolio sangat berguna.”",author:"Budi",role:"Professional",avatar:"assets/img/budi.jpg"},
      {text:"“Rekomendasi tinggi untuk fresh grad. Mentoring review portfolio membuat CV saya lebih menonjol.”",author:"Dewi",role:"Fresh Graduate",avatar:"assets/img/dewi.jpg"}
    ];
    function initTestimonials(){
      const inner = document.querySelector('.testimonials-inner');
      testimonialsData.forEach(d=>{
        const el = document.createElement('div'); el.className='testimonial';
        el.innerHTML = `<p>${d.text}</p>
          <div class="t-author"><img src="${d.avatar}" alt="${d.author}, ${d.role}" />
          <div><b>${d.author}</b><br><span class="muted">${d.role}</span></div></div>`;
        inner.appendChild(el);
      });
      const items = inner.querySelectorAll('.testimonial'); if(!items.length) return;
      const itemWidth = items[0].offsetWidth; let current = 0;
      const perView = window.innerWidth <= 960 ? 1 : 3; const max = items.length - perView;
      document.getElementById('prev-test').addEventListener('click', () => { if(current>0){ current--; inner.style.transform=`translateX(-${current*itemWidth}px)`;} });
      document.getElementById('next-test').addEventListener('click', () => { if(current<max){ current++; inner.style.transform=`translateX(-${current*itemWidth}px)`;} });
    }

    function toggleProfileDropdown(){
      const profileImg = document.getElementById('profileImg'); if(!profileImg) return;
      const menu = profileImg.nextElementSibling; menu.style.display = (menu.style.display==='block') ? 'none' : 'block';
    }

    document.addEventListener('DOMContentLoaded', () => {
      restorePreferences(); initStickyWA(); initTestimonials();

      const hamburger = document.querySelector('.hamburger');
      const navMenu = document.querySelector('.nav-menu');
      const dropdowns = document.querySelectorAll('.dropdown');
      const profileImg = document.getElementById('profileImg');

      hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active'); navMenu.classList.toggle('active');
      });

      dropdowns.forEach(d => {
        const t = d.querySelector('.dropdown-toggle');
        t.addEventListener('click', (e) => { e.preventDefault(); d.classList.toggle('active'); });
      });

      if (profileImg) profileImg.addEventListener('click', toggleProfileDropdown);

      document.addEventListener('click', (e) => {
        if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
          navMenu.classList.remove('active'); hamburger.classList.remove('active');
          dropdowns.forEach(dd => dd.classList.remove('active'));
        }
        const m = document.querySelector('.profile-dropdown .dropdown-menu');
        if (m && !m.contains(e.target) && !profileImg?.contains(e.target)) m.style.display = 'none';
      });

      /* ===== Modal Login ===== */
      const loginBtn = document.getElementById('loginBtn');
      const loginModal = document.getElementById('loginModal');
      const closeModal = document.getElementById('closeModal');
      const loginForm = document.getElementById('loginForm');
      const loginError = document.getElementById('loginError');

      if (loginBtn){
        loginBtn.addEventListener('click', () => {
          loginModal.classList.add('active');
          setTimeout(()=>document.getElementById('email').focus(), 50);
          document.body.style.overflow='hidden';
        });
      }
      closeModal.addEventListener('click', () => { loginModal.classList.remove('active'); document.body.style.overflow=''; });
      window.addEventListener('click', (e) => { if (e.target === loginModal){ loginModal.classList.remove('active'); document.body.style.overflow=''; } });

      loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        loginError.textContent = '';
        const formData = new FormData(loginForm);
        fetch('login_process.php', { method:'POST', body:formData })
          .then(r => r.json())
          .then(d => {
            if (d.success) { window.location.href = 'overview.php'; }
            else { loginError.textContent = d.error || 'Terjadi kesalahan.'; document.getElementById('password').value=''; }
          })
          .catch(()=>{ loginError.textContent='Terjadi kesalahan. Coba lagi.'; });
      });
    });
  </script>
</body>
</html>
