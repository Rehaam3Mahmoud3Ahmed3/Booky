<?php
require_once 'includes/db.php';

$books = $conn->query("SELECT id, title, cover FROM books");

echo "<h1>Book Cover Verification</h1>";
echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Cover File</th><th>Exists?</th></tr>";

while ($book = $books->fetch_assoc()) {
    $path = __DIR__ . '/uploads/covers/' . $book['cover'];
    $exists = file_exists($path) ? 'YES' : 'NO';
    
    echo "<tr>
        <td>{$book['id']}</td>
        <td>{$book['title']}</td>
        <td>{$book['cover']}</td>
        <td>$exists</td>
    </tr>";
}

echo "</table>";
?>