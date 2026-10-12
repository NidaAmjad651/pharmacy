<?php
require "config.php";
require_login();
$page_title = "Users";
$msg = "";

// Sirf Admin hi is page ko access kar sakta hai
if ($_SESSION['role'] != 'Admin') {
    die("<h3 style='font-family:Arial;color:red;text-align:center;margin-top:50px;'>Access Denied: Sirf Admin ye page dekh sakta hai.</h3>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $username  = $conn->real_escape_string($_POST['username']);
    $password  = $conn->real_escape_string($_POST['password']);
    $role      = $conn->real_escape_string($_POST['role']);
    $contact   = $conn->real_escape_string($_POST['contact_no']);

    $sql = "INSERT INTO users (full_name, username, password, role, contact_no)
            VALUES ('$full_name', '$username', '$password', '$role', '$contact')";
    if ($conn->query($sql)) {
        $msg = "User add ho gaya.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE user_id = $id");
        $msg = "User delete ho gaya.";
    } else {
        $msg = "Aap apna khud ka account delete nahi kar sakte.";
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY user_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users - Pharmacy System</title>
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
                <h6>Add New User</h6>
                <form method="POST" class="row g-2 mt-1">
                    <div class="col-md-3">
                        <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select" required>
                            <option value="Cashier">Cashier</option>
                            <option value="Manager">Manager</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="contact_no" class="form-control" placeholder="Contact No">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" name="add_user" class="btn btn-success w-100">Add</button>
                    </div>
                </form>
            </div>

            <div class="card card-stat p-3">
                <h6>All Users</h6>
                <table class="table table-striped table-bordered mt-2">
                    <tr><th>ID</th><th>Full Name</th><th>Username</th><th>Role</th><th>Contact</th><th>Action</th></tr>
                    <?php while ($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                        <td>
                            <?php if ($row['user_id'] != $_SESSION['user_id']): ?>
                            <a href="users.php?delete=<?php echo $row['user_id']; ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this user?');">Delete</a>
                            <?php else: ?>
                                <span class="text-muted">(You)</span>
                            <?php endif; ?>
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
