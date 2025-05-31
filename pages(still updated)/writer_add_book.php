<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_writer']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string(trim($_POST['title']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $status = 'draft'; // Default to draft
    
    // Handle file upload
    $cover = 'default-cover.jpg';
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $cover = uploadFile($_FILES['cover'], 'covers');
    }
    
    $stmt = $conn->prepare("
        INSERT INTO books (title, description, cover, status, created_by) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssi", $title, $description, $cover, $status, $_SESSION['user_id']);
    $stmt->execute();
    
    header("Location: writer_books.php");
    exit();
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">
    <h2 class="mb-4">Add New Book</h2>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Book Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="cover" class="form-label">Cover Image</label>
                    <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                </div>
                
                <button type="submit" class="btn btn-pink">Save as Draft</button>
                <a href="writer_books.php" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>