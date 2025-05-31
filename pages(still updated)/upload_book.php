
<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Writer') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $uploaded_by = $_SESSION['user']['id'];

    $cover = "";
    $pdf = "";
    $audio = "";

    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $cover = "uploads/books/covers/" . basename($_FILES["cover"]["name"]);
        move_uploaded_file($_FILES["cover"]["tmp_name"], $cover);
    }

    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $pdf = "uploads/books/pdfs/" . basename($_FILES["pdf"]["name"]);
        move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdf);
    }

    if (isset($_FILES['audio']) && $_FILES['audio']['error'] == 0) {
        $audio = "uploads/books/audios/" . basename($_FILES["audio"]["name"]);
        move_uploaded_file($_FILES["audio"]["tmp_name"], $audio);
    }

    $stmt = $conn->prepare("INSERT INTO books (title, author, description, cover, pdf, audio, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $title, $author, $description, $cover, $pdf, $audio, $uploaded_by);

    if ($stmt->execute()) {
        echo "<script>alert('Book uploaded successfully!'); window.location.href='main.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Book - Booky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap @5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Upload a New Book</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Author</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label>Cover Image</label>
                <input type="file" name="cover" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label>PDF File</label>
                <input type="file" name="pdf" class="form-control" accept=".pdf">
            </div>
            <div class="mb-3">
                <label>Audio File</label>
                <input type="file" name="audio" class="form-control" accept="audio/*">
            </div>
            <button type="submit" class="btn btn-pink w-100">Upload Book</button>
        </form>
    </div>
</body>
</html>