<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];

    $stmt = $pdo->prepare("SELECT * FROM files WHERE token = ?");
    $stmt->execute([$token]);
    $file = $stmt->fetch();

    if ($file) {
        $filepath = $file['filepath'];
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file['filename']));
            readfile($filepath);
            exit;
        }
    } else {
        $error = 'Invalid token or file not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive - TumaFiles</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Receive a File</h1>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form action="receive.php" method="post">
            <input type="text" name="token" placeholder="Enter file token" required>
            <button type="submit">Download</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
