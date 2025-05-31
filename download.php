<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) ){
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$book_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

// Verify  access to book
$stmt = $conn->prepare("SELECT pdf_path FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book || empty($book['pdf_path'])) {
    header("Location: book.php?id=$book_id");
    exit();
}

$filepath = 'assets/pdfs/' . $book['pdf_path'];

if (file_exists($filepath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
} else {
    header("Location: book.php?id=$book_id");
    exit();
}