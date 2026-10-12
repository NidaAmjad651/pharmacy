<?php
require "config.php";
require_login();
$page_title = "Suppliers";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_supplier'])) {
    $name    = $conn->real_escape_string($_POST['supplier_name']);
    $company = $conn->real_escape_string($_POST['company_name']);
    $contact = $conn->real_escape_string($_POST['contact_no']);
    $email   = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);

    $sql = "INSERT INTO suppliers (supplier_name, company_name, contact_no, email, address)
            VALUES ('$name', '$company', '$contact', '$email', '$address')";
    if ($conn->query($sql)) {
        $msg = "Supplier add ho gaya.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM suppliers WHERE supplier_id = $id");
    $msg = "Supplier delete ho gaya.";
}

$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY supplier_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suppliers - Pharmacy System</title>
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
                <h6>Add New Supplier</h6>
                <form method="POST" class="row g-2 mt-1">
                    <div class="col-md-3">
                        <input type="text" name="supplier_name" class="form-control" placeholder="Supplier Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="company_name" class="form-control" placeholder="Company Name">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="contact_no" class="form-control" placeholder="Contact No">
                    </div>
                    <div class="col-md-2">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="address" class="form-control" placeholder="Address">
                    </div>
                    <div class="col-12">
                        <button type="submit" name="add_supplier" class="btn btn-success">Add Supplier</button>
                    </div>
                </form>
            </div>

            <div class="card card-stat p-3">
                <h6>All Suppliers</h6>
                <table class="table table-striped table-bordered mt-2">
                    <tr><th>ID</th><th>Name</th><th>Company</th><th>Contact</th><th>Email</th><th>Address</th><th>Action</th></tr>
                    <?php while ($row = $suppliers->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['supplier_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td>
                            <a href="suppliers.php?delete=<?php echo $row['supplier_id']; ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this supplier?');">Delete</a>
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
