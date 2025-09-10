<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id']; // Akan diisi dari session login nanti
    $name = $_POST['name'];
    $url = $_POST['url'];
    $description = $_POST['description'];
    $insight = $_POST['insight'] ?? null;
    $thumbnail_url = $_POST['thumbnail_url'] ?? null;

    $stmt = $conn->prepare("INSERT INTO projects (user_id, name, url, description, insight, thumbnail_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $url, $description, $insight, $thumbnail_url]);

    echo "<p>Proyek berhasil ditambahkan!</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Project - AISight</title>
    <style>
        :root { --accent: #14B8A6; --surface: #0F172A; --text: #E6E8EC; --radius: 16px; }
        body { font-family: system-ui; background: var(--surface); color: var(--text); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: #111827; padding: 20px; border-radius: var(--radius); box-shadow: 0 4px 12px rgba(0,0,0,.3); width: 400px; }
        .form-container input, .form-container textarea { width: 100%; padding: 8px; margin: 10px 0; border: 1px solid #1F2937; border-radius: var(--radius); background: #0F172A; color: var(--text); }
        .form-container button { width: 100%; padding: 10px; background: var(--accent); border: none; border-radius: var(--radius); color: var(--text); cursor: pointer; }
        .form-container button:hover { background: #0F766E; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Submit Project</h2>
        <form method="POST">
            <input type="hidden" name="user_id" value="1"> <!-- Ganti dengan session login nanti -->
            <input type="text" name="name" placeholder="Nama Proyek" required>
            <input type="url" name="url" placeholder="URL Power BI" required>
            <textarea name="description" placeholder="Deskripsi" required></textarea>
            <textarea name="insight" placeholder="Insight (Opsional)"></textarea>
            <input type="text" name="thumbnail_url" placeholder="URL Thumbnail (Opsional)">
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>