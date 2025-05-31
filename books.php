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


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
$search_term = "%$search%";

if (!empty($search)) {
    $where = " WHERE title LIKE ? OR author LIKE ?";
}

// Pagination
$booksPerPage = 12;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $booksPerPage;


$sql = "SELECT id, title, author, cover FROM books $where ORDER BY title LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $stmt->bind_param("ssii", $search_term, $search_term, $booksPerPage, $offset);
} else {
    $stmt->bind_param("ii", $booksPerPage, $offset);
}

$stmt->execute();
$books = $stmt->get_result();


$count_sql = "SELECT COUNT(*) FROM books $where";
$count_stmt = $conn->prepare($count_sql);

if (!empty($search)) {
    $count_stmt->bind_param("ss", $search_term, $search_term);
}

$count_stmt->execute();
$count = $count_stmt->get_result()->fetch_row()[0];
$totalPages = ceil($count / $booksPerPage);
?>


<div class="container py-5">
    <h1 class="text-center mb-5">Our Book Collection</h1>
    
    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <form action="books.php" method="get" class="search-box">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or author..." 
                           value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-pink" type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="books.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    
    <?php if (!empty($search)): ?>
        <div class="alert alert-info mb-4">
            Found <?= $count ?> book(s) matching "<?= htmlspecialchars($search) ?>"
        </div>
    <?php endif; ?>
    
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php while ($book = $books->fetch_assoc()): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="book-cover-container">
                        <img src="<?= getBookCover($book['cover']) ?>" 
                             class="book-cover-img"
                             alt="<?= htmlspecialchars($book['title']) ?>"
                             onerror="this.onerror=null; this.src='assets/images/default-cover.jpg'">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($book['author']) ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="book.php?id=<?= $book['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page-1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>">Previous</a>
                </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page+1 ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>