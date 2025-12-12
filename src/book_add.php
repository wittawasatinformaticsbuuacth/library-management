<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $publication_year = $_POST['publication_year'];
    $category = $_POST['category'];
    $total_copies = $_POST['total_copies'];
    $shelf_location = $_POST['shelf_location'];
    
    $sql = "INSERT INTO books (isbn, title, author, publisher, publication_year, category, total_copies, available_copies, shelf_location) 
            VALUES ('$isbn', '$title', '$author', '$publisher', $publication_year, '$category', $total_copies, $total_copies, '$shelf_location')";
    
    mysqli_query($conn, $sql);
    
    header('Location: books.php');
    exit();
}
?>
