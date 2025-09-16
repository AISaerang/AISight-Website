<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, username, name, email, profile_image FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]); // Note: In production, use password_hash() for security
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login ke AISight untuk mengakses portofolio dan fitur lainnya."/>
    <meta property="og:title" content="AISight — Login"/>
    <meta property="og:description" content="Login ke AISight untuk mengakses portofolio dan fitur lainnya."/>
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: var(--maxw);
            width: 100%;
            padding: var(--pad);
        }

        .login-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow);
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
        }

        .login-card h1 {
            margin: 0 0 16px;
            font-size: 24px;
        }

        .login-card .error {
            color: var(--ring);
            margin-bottom: 16px;
        }

        .login-card input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            color: var(--text);
            font-size: 16px;
        }

        .login-card input:focus {
            outline: none;
            border-color: var(--accent);
            background: var(--card);
        }

        .login-card button {
            width: 100%;
            padding: 12px;
            background: var(--accent);
            border: none;
            border-radius: 8px;
            color: var(--text);
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-card button:hover {
            background: var(--accent-strong);
        }

        .profile-section {
            display: none;
            text-align: left;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .profile-section.active {
            display: block;
        }

        .profile-section .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 16px;
        }

        .profile-section h2 {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .profile-section p {
            margin: 0 0 12px;
            color: var(--muted);
        }

        .profile-section .nav-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .profile-section .nav-links a {
            color: var(--text);
            text-decoration: none;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .profile-section .nav-links a:hover {
            background: var(--surface);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <h1>Login AISight</h1>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <?php if (isset($_SESSION['user'])): ?>
                <div class="profile-section active">
                    <img src="<?php echo htmlspecialchars($_SESSION['user']['profile_image'] ?: 'assets/img/default-avatar.jpg'); ?>" alt="Profile Image" class="profile-img">
                    <h2><?php echo htmlspecialchars($_SESSION['user']['name'] ?: $_SESSION['user']['username']); ?></h2>
                    <p><?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
                    <div class="nav-links">
                        <a href="dashboard.php">Dashboard</a>
                        <a href="projects.php">Portofolio</a>
                        <a href="profile.php">Edit Profil</a>
                        <a href="logout.php">Keluar</a>
                    </div>
                </div>
            <?php endif; ?>
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

        const themeToggle = document.querySelector('.theme-toggle');
        themeToggle?.addEventListener('click', function() {
            const isLight = document.body.classList.toggle('theme-light');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        });

        const paletteBtn = document.querySelector('.palette-btn');
        paletteBtn?.addEventListener('click', function() {
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

        document.addEventListener('DOMContentLoaded', restorePreferences);
    </script>
</body>
</html>