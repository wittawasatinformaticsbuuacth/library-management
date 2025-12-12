<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR isbn LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM books ORDER BY title";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books Management</title>
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
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="books.php">Books</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Books Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
                Add New Book
            </button>
        </div>

        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search by title, author, or ISBN..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Books Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Total</th>
                                <th>Available</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($book = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                                <td><?php echo htmlspecialchars($book['title']); ?></td>
                                <td><?php echo htmlspecialchars($book['author']); ?></td>
                                <td><?php echo htmlspecialchars($book['category']); ?></td>
                                <td><?php echo $book['total_copies']; ?></td>
                                <td>
                                    <!-- BUG 13: No validation if available > total -->
                                    <span class="badge <?php echo $book['available_copies'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $book['available_copies']; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($book['shelf_location']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewBook(<?php echo $book['book_id']; ?>)">
                                        View
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editBook(<?php echo $book['book_id']; ?>)">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="book_add.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <input type="text" class="form-control" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Publisher</label>
                            <input type="text" class="form-control" name="publisher">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Publication Year</label>
                            <!-- BUG 14: No validation for future years -->
                            <input type="number" class="form-control" name="publication_year">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" name="category">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Copies</label>
                            <!-- BUG 15: No minimum value validation (can be negative) -->
                            <input type="number" class="form-control" name="total_copies" value="1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shelf Location</label>
                            <input type="text" class="form-control" name="shelf_location">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewBook(id) {
            window.location.href = 'book_view.php?id=' + id;
        }
        
        function editBook(id) {
            window.location.href = 'book_edit.php?id=' + id;
        }
    </script>
</body>
</html>
