
<?php
session_start();
include 'includes/db.php';
include 'includes/navbar.php';

$sql = "SELECT * FROM books WHERE audio != '' ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">🎧 Audio Books</h2>

    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by title or author...">
        </div>
    </div>

    <div class="row" id="booksList">
        <?php while ($book = $result->fetch_assoc()): ?>
        <div class="col-md-3 mb-4 book-item" data-title="<?= strtolower($book['title']) ?>" data-author="<?= strtolower($book['author']) ?>">
            <div class="card h-100 shadow-sm">
                <img src="<?= $book['cover'] ?>" class="card-img-top" alt="<?= $book['title'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $book['title'] ?></h5>
                    <p class="card-text">By <?= $book['author'] ?></p>
                    <a href="details.php?id=<?= $book['id'] ?>" class="btn btn-pink w-100">Details</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll(".book-item").forEach(item => {
        const title = item.getAttribute("data-title");
        const author = item.getAttribute("data-author");
        if (title.includes(query) || author.includes(query)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
});
</script>