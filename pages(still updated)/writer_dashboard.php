<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is a writer
if (!isset($_SESSION['user_id']) || !$_SESSION['is_writer']) {
    header("Location: login.php");
    exit();
}

// Get writer stats
$writer_id = $_SESSION['user_id'];
$stats = [
    'total_books' => $conn->query("SELECT COUNT(*) FROM books WHERE created_by = $writer_id")->fetch_row()[0],
    'published_books' => $conn->query("SELECT COUNT(*) FROM books WHERE created_by = $writer_id AND status = 'published'")->fetch_row()[0],
    'draft_books' => $conn->query("SELECT COUNT(*) FROM books WHERE created_by = $writer_id AND status = 'draft'")->fetch_row()[0]
];

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container py-5">
    <h2 class="mb-4">Writer Dashboard</h2>
    
    <div class="row mb-4">
        <!-- Stats Cards -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Books</h5>
                    <p class="card-text display-4"><?= $stats['total_books'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Published</h5>
                    <p class="card-text display-4"><?= $stats['published_books'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Drafts</h5>
                    <p class="card-text display-4"><?= $stats['draft_books'] ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Quick Actions</h5>
        </div>
        <div class="card-body">
            <a href="writer_add_book.php" class="btn btn-pink me-2">
                <i class="fas fa-plus"></i> Add New Book
            </a>
            <a href="writer_books.php" class="btn btn-outline-pink">
                <i class="fas fa-book"></i> Manage Books
            </a>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <h5>Recent Activity</h5>
        </div>
        <div class="card-body">
            <?php
            $activity = $conn->query("
                SELECT b.title, b.status, b.updated_at 
                FROM books b 
                WHERE b.created_by = $writer_id 
                ORDER BY b.updated_at DESC 
                LIMIT 5
            ");
            
            if ($activity->num_rows > 0) {
                while ($row = $activity->fetch_assoc()) {
                    echo '<div class="mb-2">';
                    echo '<strong>'.htmlspecialchars($row['title']).'</strong> - ';
                    echo '<span class="badge bg-'.($row['status']=='published'?'success':'warning').'">';
                    echo ucfirst($row['status']);
                    echo '</span> (last updated: '.date('M j, Y', strtotime($row['updated_at'])).')';
                    echo '</div>';
                }
            } else {
                echo '<p>No recent activity</p>';
            }
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>