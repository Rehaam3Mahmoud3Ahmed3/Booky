<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$book_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

// Check book exists
$check_book = $conn->prepare("SELECT id FROM books WHERE id = ?");
$check_book->bind_param("i", $book_id);
$check_book->execute();
if ($check_book->get_result()->num_rows == 0) {
    $_SESSION['error'] = "Book not found";
    header("Location: main.php");
    exit();
}


$stmt = $conn->prepare("INSERT IGNORE INTO favorites (user_id, book_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();

header("Location: main.php");
exit();
?>