<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/db.php';

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    header("Location: main.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'writer') {
                    header("Location: writer_dashboard.php");
                } else {
                
                header("Location: main.php");}
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        $stmt->close();
    }
    if ($user['role'] === 'writer') {
        header("Location: writer_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Booky</title>
    <?php include 'includes/header.php'; ?>
    <style>
        .login-container {
            max-width: 500px;
            margin: 5rem auto;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container">
        <div class="login-container bg-white">
            <h2 class="text-center mb-4">Login to Booky</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-pink w-100 py-2">Login</button>
            </form>

            <div class="text-center mt-3">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>