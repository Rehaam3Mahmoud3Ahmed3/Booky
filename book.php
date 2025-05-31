<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';
include 'includes/navbar.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$book_id = (int) $_GET['id'];
$user_id = (int) $_SESSION['user_id'];

logBookView($conn, $user_id, $book_id);

//review 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $rating = (int) $_POST['rating'];
    $comment = $conn->real_escape_string(trim($_POST['comment']));

    if ($rating < 1 || $rating > 5) {
        $review_error = "Please select a rating between 1 and 5 stars";
    } else {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, book_id, rating, comment) 
                               VALUES (?, ?, ?, ?)
                               ON DUPLICATE KEY UPDATE 
                               rating = VALUES(rating), 
                               comment = VALUES(comment),
                               reviewed_at = NOW()");
        $stmt->bind_param("iiis", $user_id, $book_id, $rating, $comment);
        $stmt->execute();
        $stmt->close();
    }
}

//book details
$book_sql = "SELECT b.id, b.title, b.author, b.cover, b.description, b.pdf_path,
            (SELECT COUNT(*) FROM favorites f WHERE f.book_id = b.id AND f.user_id = ?) as is_favorite
            FROM books b 
            WHERE b.id = ?";
$stmt = $conn->prepare($book_sql);
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$book) {
    header("Location: books.php");
    exit();
}

// Get reviews book
$reviews_sql = "SELECT r.*, u.name as user_name 
               FROM reviews r
               JOIN users u ON r.user_id = u.id
               WHERE r.book_id = ?
               ORDER BY r.reviewed_at DESC";
$stmt = $conn->prepare($reviews_sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$reviews = $stmt->get_result();

// Get existing review (if any)
$user_review_sql = "SELECT * FROM reviews WHERE book_id = ? AND user_id = ?";
$stmt = $conn->prepare($user_review_sql);
$stmt->bind_param("ii", $book_id, $user_id);
$stmt->execute();
$user_review = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<section class="book-details py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="book-cover-container">
                    <img src="<?= getBookCover($book['cover']) ?>" class="book-cover-img"
                        alt="<?= htmlspecialchars($book['title']) ?>"
                        onerror="this.onerror=null; this.src='assets/images/default-cover.jpg'">
                </div>

                <div class="actions mt-4">
                    <a href="<?= $book['is_favorite'] ? 'remove_favorite.php' : 'add_favorite.php' ?>?id=<?= $book['id'] ?>"
                        class="btn <?= $book['is_favorite'] ? 'btn-danger' : 'btn-pink' ?> w-100 mb-2">
                        <i class="fas fa-heart"></i>
                        <?= $book['is_favorite'] ? 'Remove from Favorites' : 'Add to Favorites' ?>
                    </a>
                    <a href="books.php" class="btn btn-outline-secondary w-100">Back to All Books</a>
                </div>
            </div>


            <div class="col-md-8">
                <h1><?= htmlspecialchars($book['title']) ?></h1>
                <h3 class="text-muted">By <?= htmlspecialchars($book['author']) ?></h3>

                <hr class="my-4">

                <h4>Description</h4>
                <p class="lead"><?= nl2br(htmlspecialchars($book['description'] ?? 'No description available.')) ?></p>

                <hr class="my-4">

                <?php if (!empty($book['pdf_path']) && file_exists('assets/pdfs/' . $book['pdf_path'])): ?>
                    <hr class="my-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Read This Book</h4>
                        </div>
                        <div class="card-body text-center">

                            <button class="btn btn-pink me-3" data-bs-toggle="modal" data-bs-target="#pdfModal">
                                <i class="fas fa-book-open"></i> Read Now
                            </button>

                            <a href="download.php?id=<?= $book['id'] ?>" class="btn btn-outline-pink">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>


                    <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= htmlspecialchars($book['title']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <iframe src="view_pdf.php?id=<?= $book['id'] ?>"
                                        style="width: 100%; height: 80vh; border: none;"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <a href="download.php?id=<?= $book['id'] ?>" class="btn btn-pink">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- Review Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Write a Review</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($review_error)): ?>
                            <div class="alert alert-danger"><?= $review_error ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Your Rating</label>
                                <div class="rating-stars">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>"
                                            <?= (isset($user_review) && $user_review['rating'] == $i) ? 'checked' : '' ?>>
                                        <label for="star<?= $i ?>">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Your Review</label>
                                <textarea name="comment" class="form-control" rows="3" required><?=
                                    isset($user_review) ? htmlspecialchars($user_review['comment']) : ''
                                    ?></textarea>
                            </div>
                            <button type="submit" name="submit_review" class="btn btn-pink">
                                <?= isset($user_review) ? 'Update Review' : 'Submit Review' ?>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reviews Section -->
                <h4 class="mb-3">Book Reviews</h4>

                <?php if ($reviews->num_rows > 0): ?>
                    <div class="reviews-list">
                        <?php while ($review = $reviews->fetch_assoc()): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h5><?= htmlspecialchars($review['user_name']) ?></h5>
                                        <div class="text-pink">
                                            <?= str_repeat('★', $review['rating']) ?>
                                        </div>
                                    </div>
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                    <small class="text-muted">
                                        Reviewed on <?= date('F j, Y', strtotime($review['reviewed_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No reviews yet. Be the first to review this book!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<style>
    .book-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        border: none;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        height: 100%;
        display: flex;
        flex-direction: column;
        background: white;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(214, 51, 132, 0.15);
    }

    .book-cover-container {
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .books-grid .book-cover-container {
        height: 280px;
        padding: 25px;
    }


    .book-details .book-cover-container {
        height: 400px;
        padding: 40px;
        border-radius: 12px;
    }


    .book-cover-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .book-card:hover .book-cover-img {
        transform: scale(1.03);
    }

    .book-card .card-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .books-grid .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark-color);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .book-details .card-title {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }

    .book-card .card-text {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .books-grid .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-card .card-footer {
        background-color: white;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem;
        display: flex;
        gap: 10px;
    }

    .book-card .btn {
        flex: 1;
        padding: 0.5rem;
        font-size: 0.9rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    @media (max-width: 992px) {
        .books-grid .book-cover-container {
            height: 240px;
            padding: 20px;
        }

        .book-details .book-cover-container {
            height: 350px;
            padding: 30px;
        }
    }

    @media (max-width: 768px) {
        .books-grid .book-cover-container {
            height: 220px;
        }

        .book-details .book-cover-container {
            height: 300px;
        }

        .book-card .card-body {
            padding: 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .books-grid .book-cover-container {
            height: 200px;
            padding: 15px;
        }

        .books-grid .card-title {
            font-size: 1rem;
        }
    }
</style>


<?php include 'includes/footer.php'; ?>