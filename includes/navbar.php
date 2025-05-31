<nav class="navbar navbar-expand-lg navbar-light bg-pink">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/images/Booky_logo.png" alt="Booky Logo" width="40" class="me-2">
            Booky
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="main.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="books.php">All Books</a>
                </li>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'writer'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="writer_dashboard.php">
                        <i class="fas fa-pen"></i> Writer Dashboard
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a class="nav-link" href="latest.php">Latest Reads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="favorites.php">Favorites</a>
                </li>
            </ul>
            
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                    <a href="logout.php" class="btn btn-light">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light me-2">Login</a>
                    <a href="register.php" class="btn btn-light">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>