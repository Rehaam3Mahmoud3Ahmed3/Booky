<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';
include 'includes/navbar.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    
    $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $name, $email, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
    $stmt->close();
}


$user_sql = "SELECT name, email, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="assets/images/profile.jpg" class="rounded-circle mb-3" width="150" alt="Profile">
                    <h3><?php echo htmlspecialchars($_SESSION['name']); ?></h3>
                    <p class="text-muted">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">Profile Information</h3>
                    
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php elseif (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-pink">Update Profile</button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <h4>Account Actions</h4>
                    <a href="change_password.php" class="btn btn-outline-secondary me-2">Change Password</a>
                    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>