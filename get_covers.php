<?php
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

$book_id = (int)$_GET['id'];

//book info
$stmt = $conn->prepare("SELECT cover FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

$coverPath = __DIR__ . '/assets/images/covers/' . $book['cover'];
$defaultPath = __DIR__ . '/assets/images/default-cover.jpg';

$file = file_exists($coverPath) ? $coverPath : $defaultPath;

$finfo = finfo_open(FILEINFO_MIME_TYPE);
header('Content-Type: ' . finfo_file($finfo, $file));
readfile($file);
?>