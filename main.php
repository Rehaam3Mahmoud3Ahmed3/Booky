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
?>

<section class="welcome-section py-5 bg-pink-light">
    <div class="container text-center">
        <h1 class="display-4">Welcome Back, <?= htmlspecialchars($_SESSION['name']) ?>!</h1>
        <p class="lead">Continue your reading journey</p>
    </div>
</section>


<section class="quick-links py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <a href="books.php" class="card link-card h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-book-open fa-3x mb-3"></i>
                        <h3>Browse Books</h3>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="latest.php" class="card link-card h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-clock fa-3x mb-3"></i>
                        <h3>Recent Reads</h3>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="favorites.php" class="card link-card h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-heart fa-3x mb-3"></i>
                        <h3>Your Favorites</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>