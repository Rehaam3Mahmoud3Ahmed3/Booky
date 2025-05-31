<?php


function getBookCover($coverFilename) {
    $coverPath = 'assets/images/covers/' . $coverFilename;
    $defaultPath = 'assets/images/default-cover.jpg';
    
    
    return file_exists($coverPath) ? $coverPath : $defaultPath;
}

function logBookView($conn, $user_id, $book_id) {
  
    $stmt = $conn->prepare("INSERT INTO recently_viewed (user_id, book_id, viewed_at) 
                          VALUES (?, ?, NOW())
                          ON DUPLICATE KEY UPDATE viewed_at = NOW()");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $stmt->close();
}
function uploadFile($file, $folder) {
    $targetDir = "assets/images/$folder/";
    $fileName = uniqid() . '-' . basename($file['name']);
    $targetPath = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return 'default-cover.jpg';
}

function isWriter() {
    return isset($_SESSION['is_writer']) && $_SESSION['is_writer'] == 1;
}
function checkWriterRole() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'writer') {
        header("Location: login.php");
        exit();
    }
}
?>