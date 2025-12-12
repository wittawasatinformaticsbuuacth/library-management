<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_code = $_POST['member_code'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $member_type = $_POST['member_type'];
    $registration_date = date('Y-m-d');
    
    $max_books = 3;
    if ($member_type == 'teacher') {
        $max_books = 3;
    } elseif ($member_type == 'public') {
        $max_books = 2;
    }
    
    $sql = "INSERT INTO members (member_code, full_name, email, phone, member_type, registration_date, max_books) 
            VALUES ('$member_code', '$full_name', '$email', '$phone', '$member_type', '$registration_date', $max_books)";
    
    mysqli_query($conn, $sql);
    
    header('Location: members.php');
    exit();
}
?>
