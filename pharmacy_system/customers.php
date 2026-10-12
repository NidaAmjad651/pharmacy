<?php
require "config.php";
require_login();
$page_title = "Customers";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_customer'])) {
    $name    = $conn->real_escape_string($_POST['customer_name']);
    $contact = $conn->real_escape_string($_POST['contact_no']);
    $address = $conn->real_escape_string($_POST['address']);

    $sql = "INSERT INTO customers (customer_name, contact_no, address) VALUES ('$name', '$contact', '$address')";
    if ($conn->query($sql)) {
        $msg = "Customer add ho gaya.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM customers WHERE customer_id = $id");
    $msg = "Customer delete ho gaya.";
}

$customers = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customers - Pharmacy System</title>
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
                <h6>Add New Customer</h6>
                <form method="POST" class="row g-2 mt-1">
                    <div class="col-md-4">
                        <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="contact_no" class="form-control" placeholder="Contact No">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="address" class="form-control" placeholder="Address">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add_customer" class="btn btn-success w-100">Add</button>
                    </div>
                </form>
            </div>

            <div class="card card-stat p-3">
                <h6>All Customers</h6>
                <table class="table table-striped table-bordered mt-2">
                    <tr><th>ID</th><th>Name</th><th>Contact</th><th>Address</th><th>Action</th></tr>
                    <?php while ($row = $customers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['customer_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td>
                            <a href="customers.php?delete=<?php echo $row['customer_id']; ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this customer?');">Delete</a>
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
