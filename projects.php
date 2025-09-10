<?php include 'db_connect.php';
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT p.name, p.url, p.description, p.insight, u.name as tutor FROM projects p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Detail - AISight</title>
    <style>
        /* Gaya CSS seperti sebelumnya */
        :root { --accent: #14B8A6; }
        body { font-family: system-ui; background: #0F172A; color: #E6E8EC; }
        .report-container { width: 100%; height: 500px; }
    </style>
</head>
<body>
    <h1>Project Detail: <?php echo $project['name'] . ' - ' . $project['tutor']; ?></h1>
    <div class="report-container">
        <iframe src="<?php echo $project['url']; ?>" frameborder="0"></iframe>
    </div>
    <div>
        <h3>Deskripsi</h3>
        <p><?php echo $project['description']; ?></p>
        <h3>Insight</h3>
        <p><?php echo $project['insight'] ?: 'Tidak ada insight'; ?></p>
    </div>
</body>
</html>