<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$recent_sql = "SELECT b.* FROM books b
              JOIN recently_viewed rv ON b.id = rv.book_id
              WHERE rv.user_id = ?
              ORDER BY rv.viewed_at DESC
              LIMIT 12";
$stmt = $conn->prepare($recent_sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$recent_books = $stmt->get_result();
?>

<section class="recent-books py-5">
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-clock"></i> Your Recent Reads</h2>
        
        <?php if ($recent_books->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($book = $recent_books->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="book-cover-container">
                                <img src="<?= getBookCover($book['cover']) ?>" 
                                     class="book-cover-img"
                                     alt="<?= htmlspecialchars($book['title']) ?>"
                                     onerror="this.onerror=null; this.src='assets/images/default-cover.jpg'">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($book['author']) ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="book.php?id=<?= $book['id'] ?>" class="btn btn-pink">View Again</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Your reading history is empty. <a href="books.php">Browse books</a> to get started!
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>