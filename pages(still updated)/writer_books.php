<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_writer']) {
    header("Location: login.php");
    exit();
}

$writer_id = $_SESSION['user_id'];
$books = $conn->query("
    SELECT id, title, cover, status, created_at 
    FROM books 
    WHERE created_by = $writer_id
    ORDER BY created_at DESC
");

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Your Books</h2>
        <a href="writer_add_book.php" class="btn btn-pink">
            <i class="fas fa-plus"></i> Add New Book
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($book = $books->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="<?= getBookCover($book['cover']) ?>" 
                             style="width: 50px; height: 70px; object-fit: cover;" 
                             alt="<?= htmlspecialchars($book['title']) ?>">
                    </td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td>
                        <span class="badge bg-<?= $book['status']=='published'?'success':'warning' ?>">
                            <?= ucfirst($book['status']) ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y', strtotime($book['created_at'])) ?></td>
                    <td>
                        <a href="writer_edit_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <?php if ($book['status'] == 'draft'): ?>
                        <a href="writer_publish.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-upload"></i> Publish
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>