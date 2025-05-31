

<?php
require_once 'includes/db.php';

$books = [
    ["title" => "Almost Adulting", "author" => "Arden Rose", "image" => "Almost Adulting.jpg"],
    ["title" => "Art of Feminist", "author" => "Helena Reckitt", "image" => "Art of Feminist.jpg"],
    ["title" => "Beauty Food", "author" => "Maria Ahlgren", "image" => "Beauty food.jpg"],
    ["title" => "Call it What You Want", "author" => "Brigid Kemmerer", "image" => "Call it what you want.jpg"],
    ["title" => "Day Dreams", "author" => "Heather Neill", "image" => "Day Dreams.jpg"],
    ["title" => "Flower Expert", "author" => "Thames & Hudson", "image" => "Flower Expert.jpg"],
    ["title" => "Fresh Face", "author" => "Mandi Nyambi", "image" => "Fresh Face.jpg"],
    ["title" => "Humans Emotions", "author" => "Tiffany Watt", "image" => "Humans Emotions.jpg"],
    ["title" => "It's not like It's a secret", "author" => "Misa Sugiura", "image" => "It's not like it's a secret.jpg"],
    ["title" => "Joyful", "author" => "Ingrid Fetell", "image" => "Joyful.jpg"],
    ["title" => "Know Yourself", "author" => "Nicolayoon", "image" => "Know yourself.jpg"],
    ["title" => "Life Hacks", "author" => "Yumi Sakugawa", "image" => "Life hacks.jpg"],
    ["title" => "My Heart and Black Holes", "author" => "Jasmin Wanga", "image" => "My heart and black holes.jpg"],
    ["title" => "Nice Girls", "author" => "Lois Frankel", "image" => "Nice Girls.jpg"],
    ["title" => "Picking Daisies on Sundays", "author" => "Liana Cincotti", "image" => "Picking Daisies on Sundays.jpg"],
    ["title" => "She Minds Her Own Business", "author" => "Lauren Martin", "image" => "she minds her own business.jpg"],
    ["title" => "Skin Care Bible", "author" => "Anjali Mahto", "image" => "Skin care bible.jpg"],
    ["title" => "Skin Deep", "author" => "Bobbi Brown", "image" => "Skin deep.jpg"],
    ["title" => "Solitaire", "author" => "Alice Oseman", "image" => "Solitaire.jpg"],
    ["title" => "The Book of Moods", "author" => "Lauren Martin", "image" => "The book of moods.jpg"],
    ["title" => "The Right Swipe", "author" => "Alisha Rai", "image" => "The Right Swipe.jpg"],
    ["title" => "The Sun is Also a Star", "author" => "Nicola Yoon", "The sun is also a star.jpg"],
    ["title" => "Things Art", "author" => "Adam J. Kurtz", "image" => "Things art.jpg"],
    ["title" => "Things We Hide from the Light", "author" => "Lucy Score", "image" => "Things we hide from the light.jpg"],
    ["title" => "Too Fat", "author" => "Anne Helen Petersen", "image" => "Too Fat.jpg"],
    ["title" => "Where Stylists Shop", "author" => "Booth Moore", "image" => "Where Stylists Shop.jpg"],
    ["title" => "Your Glow", "author" => "Latham Thomas", "image" => "Your Glow.jpg"],
    ["title" => "استشارة المرأة", "author" => "مشعل بن محمد", "image" => "استشارة المرأة.png"],
    ["title" => "النسوية الإسلامية", "author" => "سامي عامري", "image" => "النسوية الاسلامية.jpg"],
    ["title" => "  أنوثة طاغية ", "author" => " هالة محمد غبان", "image" => "انوثة طاغية.jpg"],
    ["title" => "تنمية مهارات المرأة", "author" => "مشعل بن محمد", "image" => "تنمية مهارات المرأة.jpg"], 
    ["title" => "كلي ونامي وابهري الآخرين", "author" => "سلمي محمود", "image" => "كلي ونامي وابهري الآخرين .jpg"],
    ["title" => "نحو أنوثة واثقة ", "author" => "مايا طارق الهواري", "image" => "نحو أنوثة واثقة.jpg"], 
    ["title" => " نفس تواقة ", "author" => "  صفاء عبدالله باعيسي", "image" => "نفس تواقة.png"],
    ["title" => "همسة", "author" => "حسان شمني باشا", "image" => "همسة.png"]
];


$stmt = $conn->prepare("INSERT INTO books (title, author, cover) VALUES (?, ?, ?)");
$imported = 0;

foreach ($books as $book) {
    try {
       
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/Booky/assets/images/' . $book['image'];
        
        if (!file_exists($imagePath)) {
            throw new Exception("Cover image missing: " . $book['image']);
        }
        
        $stmt->bind_param("sss", $book['title'], $book['author'], $book['image']);
        $stmt->execute();
        $imported++;
    } catch (Exception $e) {
        echo "Error with '{$book['title']}': " . $e->getMessage() . "<br>";
    }
}

echo "Imported $imported books successfully.";

$stmt->close();
$conn->close();