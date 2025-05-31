<?php
session_start();
require_once 'includes/db.php';


$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = isset($_POST['role']) ? $conn->real_escape_string($_POST['role']) : 'Reader';

    // Validation
    
$role = isset($_POST['role']) && $_POST['role'] === 'writer' ? 'writer' : 'reader';

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } else {
       
        $check_sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email already exists.';
        } else {
            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
            
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                header("Location: main.php");
                exit();
            } else {
                $error = 'Registration failed. Please try again. Error: ' . $conn->error;
            }
        }
        $stmt->close();
    }
}


include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Register</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="Reader" <?php echo (isset($_POST['role']) && $_POST['role'] === 'Reader') ? 'selected' : ''; ?>>Reader</option>
                <option value="Writer" <?php echo (isset($_POST['role']) && $_POST['role'] === 'Writer') ? 'selected' : ''; ?>>Writer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-pink w-100">Register</button>
    </form>
    <div class="text-center mt-3">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>
<!-- writer role 
<div class="mb-3">
    <label class="form-label">Register as:</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="role" id="readerRole" value="reader" checked>
        <label class="form-check-label" for="readerRole">Reader</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="role" id="writerRole" value="writer">
        <label class="form-check-label" for="writerRole">Writer</label>
    </div>
</div>-->

<?php include 'includes/footer.php'; ?>