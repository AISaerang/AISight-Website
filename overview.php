<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview - AISight</title>
    <style>
        /* Gaya CSS seperti sebelumnya */
        :root { --accent: #14B8A6; }
        body { font-family: system-ui; background: #0F172A; color: #E6E8EC; }
        .project-grid { display: flex; gap: 16px; flex-wrap: wrap; }
        .project-thumbnail { cursor: pointer; }
    </style>
</head>
<body>
    <div class="project-grid">
        <?php
        $stmt = $conn->prepare("SELECT p.id, p.name, p.thumbnail_url, u.name as tutor FROM projects p JOIN users u ON p.user_id = u.id");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='project-thumbnail' data-url='projects.php?id=" . $row['id'] . "'>";
            echo "<img src='" . $row['thumbnail_url'] . "' alt='" . $row['name'] . "' style='width: 200px;'>";
            echo "<p>" . $row['name'] . " - " . $row['tutor'] . "</p>";
            echo "</div>";
        }
        ?>
    </div>
    <script>
        document.querySelectorAll('.project-thumbnail').forEach(thumb => {
            thumb.addEventListener('click', () => {
                window.location.href = thumb.dataset.url;
            });
        });
    </script>
</body>
</html>