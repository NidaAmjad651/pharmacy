<?php
require "config.php";
require_login();
$page_title = "Medicines";
$msg = "";

// Add new medicine
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_medicine'])) {
    $name       = $conn->real_escape_string($_POST['medicine_name']);
    $category   = intval($_POST['category_id']);
    $supplier   = intval($_POST['supplier_id']);
    $batch      = $conn->real_escape_string($_POST['batch_no']);
    $unit       = $conn->real_escape_string($_POST['unit']);
    $p_price    = floatval($_POST['purchase_price']);
    $s_price    = floatval($_POST['sale_price']);
    $qty        = intval($_POST['quantity']);
    $expiry     = $conn->real_escape_string($_POST['expiry_date']);

    $sql = "INSERT INTO medicines (medicine_name, category_id, supplier_id, batch_no, unit, purchase_price, sale_price, quantity, expiry_date)
            VALUES ('$name', $category, $supplier, '$batch', '$unit', $p_price, $s_price, $qty, '$expiry')";

    if ($conn->query($sql)) {
        $msg = "Medicine successfully add ho gayi.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}

$categories = $conn->query("SELECT * FROM categories");
$suppliers  = $conn->query("SELECT * FROM suppliers");
$medicines  = $conn->query("SELECT m.*, c.category_name, s.supplier_name FROM medicines m
                             LEFT JOIN categories c ON m.category_id = c.category_id
                             LEFT JOIN suppliers s ON m.supplier_id = s.supplier_id
                             ORDER BY m.medicine_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicines - Pharmacy System</title>
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
                <h6>Add New Medicine</h6>
                <form method="POST" class="row g-2 mt-1">
                    <div class="col-md-4">
                        <input type="text" name="medicine_name" class="form-control" placeholder="Medicine Name" required>
                    </div>
                    <div class="col-md-4">
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Category --</option>
                            <?php while($c = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $c['category_id']; ?>"><?php echo $c['category_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Supplier --</option>
                            <?php while($s = $suppliers->fetch_assoc()): ?>
                                <option value="<?php echo $s['supplier_id']; ?>"><?php echo $s['supplier_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="batch_no" class="form-control" placeholder="Batch No">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="unit" class="form-control" placeholder="Unit (Strip/Bottle)" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="purchase_price" class="form-control" placeholder="Purchase Price" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="sale_price" class="form-control" placeholder="Sale Price" required>
                    </div>
                    <div class="col-md-1">
                        <input type="number" name="quantity" class="form-control" placeholder="Qty" value="0">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="expiry_date" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="add_medicine" class="btn btn-success">Add Medicine</button>
                    </div>
                </form>
            </div>

            <div class="card card-stat p-3">
                <h6>All Medicines</h6>
                <table class="table table-striped table-bordered mt-2">
                    <tr>
                        <th>ID</th><th>Name</th><th>Category</th><th>Supplier</th><th>Batch</th>
                        <th>Unit</th><th>Purchase Price</th><th>Sale Price</th><th>Qty</th><th>Expiry</th>
                    </tr>
                    <?php while ($row = $medicines->fetch_assoc()): ?>
                    <tr class="<?php echo $row['quantity'] <= 10 ? 'table-danger' : ''; ?>">
                        <td><?php echo $row['medicine_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['batch_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['unit']); ?></td>
                        <td><?php echo number_format($row['purchase_price'],2); ?></td>
                        <td><?php echo number_format($row['sale_price'],2); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['expiry_date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
