<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        
         <a class="navbar-brand" href="index.php">
            <img src="assets/images/Booky_logo.png" alt="Booky Logo" width="40" class="me-2">
            Booky
        </a>
        
        <div class="d-flex align-items-center">
            <a href="login.php" class="btn btn-outline-pink me-2" title="Login">
                <i class="fas fa-sign-in-alt"></i> <span class="d-none d-sm-inline">Login</span>
            </a>
            <a href="register.php" class="btn btn-pink" title="Register">
                <i class="fas fa-user-plus"></i> <span class="d-none d-sm-inline">Register</span>
            </a>
        </div>
    </div>
</nav>


<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 pe-lg-5">
                <h1 class="display-4 fw-bold mb-4">Welcome, Bookish Girls!</h1>
                <p class="lead mb-4">At Booky, we celebrate your love for reading with a community that understands the magic of getting lost in a good book.</p>
                
                <div class="motivational-message p-4 mb-4 rounded">
                    <h3 class="h4 mb-3"><i class="fas fa-quote-left me-2"></i>Dear Reader,</h3>
                    <p>Every book you open is a new adventure waiting to happen. Whether you're curled up with a classic or exploring new worlds, remember that each page turns you into a more wonderful version of yourself.</p>
                    <p class="mb-0">With love,<br>The Booky Team</p>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="assets/images/Booky_logo.png" alt="Booky logo" class="img-fluid rounded-4 shadow" style="max-height: 500px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>


<section class="reading-tips-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 ps-lg-5">
                <h2 class="mb-4">Your Reading Companion</h2>
                <p class="lead mb-4">Discover personalized reading recommendations and track your literary journey.</p>
                
                <div class="reading-tips p-4 bg-white rounded mb-4">
                    <h3 class="h4 mb-3"><i class="fas fa-lightbulb me-2"></i>Reading Tips</h3>
                    <ul class="mb-0">
                        <li>Set aside 20 minutes daily for reading</li>
                        <li>Keep a reading journal</li>
                        <li>Join our monthly challenges</li>
                        <li>Explore different genres</li>
                    </ul>
                </div>
                
                <div class="d-flex gap-3">
                    <a href="login.php" class="btn btn-pink btn-lg">Get Started</a>
                    <a href="register.php" class="btn btn-outline-pink btn-lg">Learn More</a>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1 d-none d-lg-block">
                <img src="assets/images/books2.jpg" alt="Happy reader" class="img-fluid rounded-4 shadow" style="max-height: 500px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<section class="why-join py-5">
    <div class="container">
        <h2 class="text-center mb-5">Why Join Booky?</h2>
        <div class="row g-4">
            <div class="col-md-4 d-flex">
                <div class="card border-0 shadow-sm flex-fill">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="h5">Track Your Reading</h3>
                        <p class="mb-0">Keep a beautiful digital record of all the books you've read and want to read.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex">
                <div class="card border-0 shadow-sm flex-fill">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="h5">Join a Community</h3>
                        <p class="mb-0">Connect with other book-loving girls who share your passion for literature.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex">
                <div class="card border-0 shadow-sm flex-fill">
                    <div class="card-body text-center p-4">
                        <div class="icon-circle mb-3">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h3 class="h5">Exclusive Perks</h3>
                        <p class="mb-0">Get access to reading challenges, giveaways, and special recommendations.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.navbar {
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    padding: 15px 0;
}

.brand-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: #d63384;
    font-family: 'Playfair Display', serif;
}

.icon-circle {
    width: 70px;
    height: 70px;
    background-color: #f8e1e7;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #d63384;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.reading-tips {
    border-left: 4px solid #d63384;
    background-color: #fff;
}

.btn-pink {
    background-color: #d63384;
    border-color: #d63384;
    color: white;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
}

.btn-outline-pink {
    border-color: #d63384;
    color: #d63384;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
}

.btn-outline-pink:hover {
    background-color: #d63384;
    color: white;
}

.motivational-message {
    background-color: #f8f9fa;
    border-left: 4px solid #d63384;
}

.hero-section, 
.reading-tips-section,
.why-join {
    padding: 5rem 0;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

/* Ensure sections equal */
@media (min-width: 992px) {
    .hero-section, 
    .reading-tips-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
    }
}
</style>

<?php include 'includes/footer.php'; ?>