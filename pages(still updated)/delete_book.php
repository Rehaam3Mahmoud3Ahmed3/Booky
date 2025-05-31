<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Writer') {
    header("Location: login.php");
    exit();
}

$book_id = intval($_GET['id']);
$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND uploaded_by = ?");
$stmt->bind_param("ii", $book_id, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Book deleted successfully!'); window.history.back();</script>";
} else {
    echo "Error deleting book.";
}
?>