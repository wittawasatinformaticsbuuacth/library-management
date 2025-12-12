<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get statistics
$stats = [];

// Total books
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM books");
$stats['total_books'] = mysqli_fetch_assoc($result)['total'];

// Available books
$result = mysqli_query($conn, "SELECT SUM(available_copies) as total FROM books");
$stats['available_books'] = mysqli_fetch_assoc($result)['total'];

// Total members
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM members WHERE status = 'active'");
$stats['active_members'] = mysqli_fetch_assoc($result)['total'];

// Currently borrowed
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM borrowing WHERE status = 'borrowed' OR status = 'overdue'");
$stats['borrowed_books'] = mysqli_fetch_assoc($result)['total'];

$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM borrowing WHERE status = 'borrowed' AND due_date < CURDATE()");
$stats['overdue_books'] = mysqli_fetch_assoc($result)['total'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">📚 Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Dashboard</a>
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
                        <a class="nav-link" href="reports.php">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?php echo $_SESSION['full_name']; ?> (<?php echo $_SESSION['role']; ?>)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2 class="mb-4">Dashboard</h2>
        
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Books</h5>
                        <h2><?php echo $stats['total_books']; ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Available</h5>
                        <h2><?php echo $stats['available_books']; ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Active Members</h5>
                        <h2><?php echo $stats['active_members']; ?></h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Borrowed</h5>
                        <h2><?php echo $stats['borrowed_books']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($stats['overdue_books'] > 0): ?>
        <div class="alert alert-danger">
            <strong>Warning!</strong> There are <?php echo $stats['overdue_books']; ?> overdue books.
        </div>
        <?php endif; ?>
        
        <!-- Recent Borrowing -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Recent Borrowing Activity</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Book</th>
                            <th>Borrow Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT b.*, m.full_name, bk.title 
                                FROM borrowing b, members m, books bk
                                WHERE b.member_id = m.member_id 
                                AND b.book_id = bk.book_id 
                                ORDER BY b.borrow_date DESC 
                                LIMIT 10";
                        $result = mysqli_query($conn, $sql);
                        
                        while ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['borrow_date']; ?></td>
                            <td><?php echo $row['due_date']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'overdue'): ?>
                                    <span class="badge bg-danger">Overdue</span>
                                <?php elseif ($row['status'] == 'borrowed'): ?>
                                    <span class="badge bg-warning">Borrowed</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Returned</span>
                                <?php endif; ?>
                            </td>
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
