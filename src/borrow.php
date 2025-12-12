<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_code = $_POST['member_code'];
    $book_id = $_POST['book_id'];
    
    // Get member info
    $member_sql = "SELECT * FROM members WHERE member_code = '$member_code'";
    $member_result = mysqli_query($conn, $member_sql);
    $member = mysqli_fetch_assoc($member_result);
    
    if (!$member) {
        $error = "Member not found!";
    } else {
        // Check current borrowed books
        $check_sql = "SELECT COUNT(*) as count FROM borrowing 
                      WHERE member_id = {$member['member_id']} 
                      AND (status = 'borrowed' OR status = 'overdue')";
        $check_result = mysqli_query($conn, $check_sql);
        $current_borrowed = mysqli_fetch_assoc($check_result)['count'];
        
        if ($current_borrowed >= $member['max_books']) {
            $error = "Member has reached maximum borrowing limit!";
        } else {
            // Check book availability
            $book_sql = "SELECT * FROM books WHERE book_id = $book_id";
            $book_result = mysqli_query($conn, $book_sql);
            $book = mysqli_fetch_assoc($book_result);
            
            if ($book['available_copies'] > 0) {
                // Calculate due date
                $borrow_date = date('Y-m-d');
                $due_date = date('Y-m-d', strtotime('+14 days'));
                
                // Insert borrowing record
                $insert_sql = "INSERT INTO borrowing (member_id, book_id, borrow_date, due_date, status) 
                              VALUES ({$member['member_id']}, $book_id, '$borrow_date', '$due_date', 'borrowed')";
                mysqli_query($conn, $insert_sql);
                
                // Update available copies
                $update_sql = "UPDATE books SET available_copies = available_copies - 1 
                              WHERE book_id = $book_id";
                mysqli_query($conn, $update_sql);
                
                $message = "Book borrowed successfully! Due date: $due_date";
            } else {
                $error = "Book is not available!";
            }
        }
    }
}

// Get available books
$books_sql = "SELECT * FROM books WHERE available_copies > 0 ORDER BY title";
$books_result = mysqli_query($conn, $books_sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">📚 Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="books.php">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="members.php">Members</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="borrow.php">Borrow</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="return.php">Return</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <?php echo $_SESSION['full_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mb-4">Borrow Book</h2>
        
        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Borrow Form</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Member Code</label>
                                <input type="text" class="form-control" name="member_code" 
                                       placeholder="Enter member code (e.g., M001)" required>
                                <small class="form-text text-muted">
                                    Example: M001, M002, M003
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Select Book</label>
                                <select class="form-select" name="book_id" required>
                                    <option value="">-- Select Book --</option>
                                    <?php while ($book = mysqli_fetch_assoc($books_result)): ?>
                                    <option value="<?php echo $book['book_id']; ?>">
                                        <?php echo htmlspecialchars($book['title']); ?> 
                                        (Available: <?php echo $book['available_copies']; ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Borrow Book</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Borrowing Rules</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Students:</strong> Maximum 3 books, 14 days
                            </li>
                            <li class="list-group-item">
                                <strong>Teachers:</strong> Maximum 5 books, 30 days
                            </li>
                            <li class="list-group-item">
                                <strong>Public:</strong> Maximum 2 books, 7 days
                            </li>
                            <li class="list-group-item">
                                <strong>Fine:</strong> 5 Baht per day for overdue books
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
