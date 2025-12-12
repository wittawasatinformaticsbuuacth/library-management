<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Most borrowed books
$popular_sql = "SELECT b.title, b.author, COUNT(*) as borrow_count 
                FROM borrowing br
                JOIN books b ON br.book_id = b.book_id
                GROUP BY br.book_id
                ORDER BY borrow_count DESC
                LIMIT 10";
$popular_result = mysqli_query($conn, $popular_sql);

// Active members with most borrows
$active_members_sql = "SELECT m.member_code, m.full_name, m.member_type, COUNT(*) as total_borrows
                       FROM borrowing br
                       JOIN members m ON br.member_id = m.member_id
                       GROUP BY br.member_id
                       ORDER BY total_borrows DESC
                       LIMIT 10";
$active_members_result = mysqli_query($conn, $active_members_sql);

// Overdue books with fines
$overdue_sql = "SELECT b.borrow_id, m.member_code, m.full_name, bk.title, 
                       b.borrow_date, b.due_date, b.fine_amount,
                       DATEDIFF(CURDATE(), b.due_date) as days_overdue
                FROM borrowing b
                JOIN members m ON b.member_id = m.member_id
                JOIN books bk ON b.book_id = bk.book_id
                WHERE b.status = 'borrowed' AND b.due_date < CURDATE()
                ORDER BY days_overdue DESC";
$overdue_result = mysqli_query($conn, $overdue_sql);

// Books by category
$category_sql = "SELECT category, COUNT(*) as book_count, SUM(total_copies) as total_copies
                 FROM books
                 GROUP BY category
                 ORDER BY book_count DESC";
$category_result = mysqli_query($conn, $category_sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
                        <a class="nav-link" href="return.php">Return</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="reports.php">Reports</a>
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
        <h2 class="mb-4">Reports & Statistics</h2>
        
        <div class="row">
            <!-- Popular Books -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Most Borrowed Books</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Times</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($popular_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                                    <td><span class="badge bg-primary"><?php echo $row['borrow_count']; ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Active Members -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>Most Active Members</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Borrows</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($active_members_result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['member_code']); ?></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo ucfirst($row['member_type']); ?></td>
                                    <td><span class="badge bg-info"><?php echo $row['total_borrows']; ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Overdue Books -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5>Overdue Books</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Member Code</th>
                                <th>Member Name</th>
                                <th>Book Title</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Fine (Baht)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($overdue_result)): 
                                $calculated_fine = $row['days_overdue'] * 5;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['member_code']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo $row['borrow_date']; ?></td>
                                <td><?php echo $row['due_date']; ?></td>
                                <td>
                                    <span class="badge bg-danger"><?php echo $row['days_overdue']; ?> days</span>
                                </td>
                                <td>
                                    <?php echo number_format($calculated_fine, 2); ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Books by Category -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Books by Category</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Number of Titles</th>
                            <th>Total Copies</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($category_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo $row['book_count']; ?></td>
                            <td><?php echo $row['total_copies']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
