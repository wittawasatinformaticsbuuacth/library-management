<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrow_id = $_POST['borrow_id'];
    
    // Get borrowing info
    $sql = "SELECT * FROM borrowing WHERE borrow_id = $borrow_id";
    $result = mysqli_query($conn, $sql);
    $borrow = mysqli_fetch_assoc($result);
    
    if (!$borrow) {
        $error = "Borrowing record not found!";
    } elseif ($borrow['status'] == 'returned') {
        $error = "This book has already been returned!";
    } else {
        $return_date = date('Y-m-d');
        $due_date = $borrow['due_date'];
        
        // Calculate fine
        $fine = 0;
        if ($return_date > $due_date) {
            $days_late = (strtotime($return_date) - strtotime($due_date)) / 86400;
            $fine = $days_late * 5; // 5 Baht per day
        }
        
        // Update borrowing record
        $update_sql = "UPDATE borrowing 
                       SET return_date = '$return_date', 
                           fine_amount = $fine, 
                           status = 'returned' 
                       WHERE borrow_id = $borrow_id";
        mysqli_query($conn, $update_sql);
        
        // Update book available copies
        $book_sql = "UPDATE books 
                     SET available_copies = available_copies + 1 
                     WHERE book_id = {$borrow['book_id']}";
        mysqli_query($conn, $book_sql);
        
        if ($fine > 0) {
            $message = "Book returned successfully! Fine: " . number_format($fine, 2) . " Baht";
        } else {
            $message = "Book returned successfully! No fine.";
        }
    }
}

// Get currently borrowed books
$borrowed_sql = "SELECT b.*, m.member_code, m.full_name, bk.title, bk.isbn 
                 FROM borrowing b
                 JOIN members m ON b.member_id = m.member_id
                 JOIN books bk ON b.book_id = bk.book_id
                 WHERE b.status IN ('borrowed', 'overdue')
                 ORDER BY b.due_date";
$borrowed_result = mysqli_query($conn, $borrowed_sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
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
                        <a class="nav-link" href="borrow.php">Borrow</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="return.php">Return</a>
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
        <h2 class="mb-4">Return Book</h2>
        
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
        
        <div class="card">
            <div class="card-header">
                <h5>Currently Borrowed Books</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Book Title</th>
                                <th>ISBN</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($borrowed_result)): 
                                // Calculate days overdue
                                $today = strtotime(date('Y-m-d'));
                                $due = strtotime($row['due_date']);
                                $days_diff = floor(($today - $due) / 86400);
                                $is_overdue = $days_diff > 0;
                            ?>
                            <tr class="<?php echo $is_overdue ? 'table-danger' : ''; ?>">
                                <td>
                                    <?php echo htmlspecialchars($row['member_code']); ?><br>
                                    <small><?php echo htmlspecialchars($row['full_name']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                                <td><?php echo $row['borrow_date']; ?></td>
                                <td><?php echo $row['due_date']; ?></td>
                                <td>
                                    <?php if ($is_overdue): ?>
                                        <span class="badge bg-danger">
                                            <?php echo $days_diff; ?> days late
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <?php echo abs($days_diff); ?> days left
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($is_overdue): ?>
                                        <span class="badge bg-danger">Overdue</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Borrowed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="borrow_id" value="<?php echo $row['borrow_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                onclick="return confirm('Confirm return this book?')">
                                            Return
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5>Fine Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Fine Rate:</strong> 5 Baht per day</p>
                <p><strong>Grace Period:</strong> None</p>
                <p class="text-muted">
                    <!-- BUG 29: Documentation doesn't match implementation -->
                    Note: Fine is calculated from the day after due date
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
