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

//add/remove favorite
if (isset($_GET['action']) && isset($_GET['id'])) {
    $book_id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user_id'];
    
    if ($_GET['action'] === 'add') {
        $stmt = $conn->prepare("INSERT IGNORE INTO favorites (user_id, book_id, added_at) VALUES (?, ?, NOW())");
    } else {
        $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");
    }
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: favorites.php");
    exit();
}

// Get favorite 
$favorites_sql = "SELECT b.id, b.title, b.author, b.cover 
                 FROM books b 
                 JOIN favorites f ON b.id = f.book_id 
                 WHERE f.user_id = ? 
                 ORDER BY f.added_at DESC";
$stmt = $conn->prepare($favorites_sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$favorite_books = $stmt->get_result();
?>

<section class="favorites-section py-5">
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-heart me-2"></i>Your Favorite Books</h2>
    
        <?php if ($favorite_books->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($book = $favorite_books->fetch_assoc()): ?>
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
                                <a href="book.php?id=<?= $book['id'] ?>" class="btn btn-pink">View</a>
                                <a href="favorites.php?action=remove&id=<?= $book['id'] ?>" 
                                   class="btn btn-outline-danger">Remove</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No favorites yet. <a href="books.php">Browse books</a> to add some!
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>