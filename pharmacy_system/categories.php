<?php
require "config.php";
require_login();
$page_title = "Categories";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['category_name']);
    if ($conn->query("INSERT INTO categories (category_name) VALUES ('$name')")) {
        $msg = "Category add ho gayi.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE category_id = $id");
    $msg = "Category delete ho gayi.";
}

$categories = $conn->query("SELECT * FROM categories ORDER BY category_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories - Pharmacy System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="row g-0">
    <?php include "includes/sidebar.php"; ?>
    <div class="col-md-10">
        <?php include "includes/topbar.php"; ?>
        <div class="p-4">
            <?php if ($msg): ?>
                <div class="alert alert-info"><?php echo $msg; ?></div>
            <?php endif; ?>

            <div class="card card-stat p-3 mb-4">
                <h6>Add New Category</h6>
                <form method="POST" class="row g-2 mt-1">
                    <div class="col-md-8">
                        <input type="text" name="category_name" class="form-control" placeholder="Category Name (e.g. Tablet, Syrup)" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="add_category" class="btn btn-success w-100">Add Category</button>
                    </div>
                </form>
            </div>

            <div class="card card-stat p-3">
                <h6>All Categories</h6>
                <table class="table table-striped table-bordered mt-2">
                    <tr><th>ID</th><th>Category Name</th><th>Action</th></tr>
                    <?php while ($row = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['category_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td>
                            <a href="categories.php?delete=<?php echo $row['category_id']; ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this category?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
