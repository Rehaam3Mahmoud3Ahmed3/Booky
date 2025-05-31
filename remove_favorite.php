<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$book_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();

header("Location: main.php");
exit();
?>