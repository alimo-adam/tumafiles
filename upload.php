<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['file'];
    $filename = $file['name'];
    $filepath = 'uploads/' . basename($filename);
    $token = bin2hex(random_bytes(3)); // Generate 6-character alphanumeric token

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $stmt = $pdo->prepare("INSERT INTO files (user_id, filename, filepath, token) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $filename, $filepath, $token])) {
            echo "<p>File uploaded successfully. Use this token to share your file: <strong>$token</strong></p>";
        } else {
            echo "<p>Failed to save file information to the database.</p>";
        }
    } else {
        echo "<p>Failed to upload file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload - TumaFiles</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Send a File</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="file-input">Choose a file</label>
            <input type="file" name="file" id="file-input" required>
            <button type="submit">Upload</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
