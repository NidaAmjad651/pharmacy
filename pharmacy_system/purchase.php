<?php
require "config.php";
require_login();
$page_title = "Purchase";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_purchase'])) {
    $supplier_id = intval($_POST['supplier_id']);
    $medicine_id = intval($_POST['medicine_id']);
    $qty         = intval($_POST['quantity']);
    $price       = floatval($_POST['purchase_price']);
    $user_id     = $_SESSION['user_id'];
    $subtotal    = $qty * $price;

    $conn->begin_transaction();
    try {
        $conn->query("INSERT INTO purchases (supplier_id, user_id, purchase_date, total_amount)
                       VALUES ($supplier_id, $user_id, CURDATE(), $subtotal)");
        $purchase_id = $conn->insert_id;

        $conn->query("INSERT INTO purchase_items (purchase_id, medicine_id, quantity, purchase_price, subtotal)
                       VALUES ($purchase_id, $medicine_id, $qty, $price, $subtotal)");
        // Stock automatically update ho jayegi trigger ki wajah se

        $conn->commit();
        $msg = "Purchase successfully record ho gayi. Stock update ho gaya.";
    } catch (Exception $e) {
        $conn->rollback();
        $msg = "Error: " . $e->getMessage();
    }
}

$suppliers = $conn->query("SELECT * FROM suppliers");
$medicines = $conn->query("SELECT * FROM medicines ORDER BY medicine_name");

$purchases = $conn->query("SELECT p.purchase_id, p.purchase_date, s.supplier_name, m.medicine_name,
                            pi.quantity, pi.purchase_price, pi.subtotal
                            FROM purchases p
                            JOIN purchase_items pi ON p.purchase_id = pi.purchase_id
                            JOIN suppliers s ON p.supplier_id = s.supplier_id
                            JOIN medicines m ON pi.medicine_id = m.medicine_id
                            ORDER BY p.purchase_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase - Pharmacy System</title>
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
                <h6>Record New Purchase</h6>
                <form method="POST" class="row g-2 mt-1">
                    <div class="col-md-3">
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Supplier --</option>
                            <?php while($s = $suppliers->fetch_assoc()): ?>
                                <option value="<?php echo $s['supplier_id']; ?>"><?php echo $s['supplier_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="medicine_id" class="form-select" required>
                            <option value="">-- Medicine --</option>
                            <?php while($m = $medicines->fetch_assoc()): ?>
                                <option value="<?php echo $m['medicine_id']; ?>"><?php echo $m['medicine_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="purchase_price" class="form-control" placeholder="Price per Unit" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add_purchase" class="btn btn-success w-100">Add Purchase</button>
                    </div>
                </form>
            </div>

            <div class="card card-stat p-3">
                <h6>Purchase History</h6>
                <table class="table table-striped table-bordered mt-2">
                    <tr>
                        <th>Purchase ID</th><th>Date</th><th>Supplier</th><th>Medicine</th>
                        <th>Qty</th><th>Price/Unit</th><th>Subtotal</th>
                    </tr>
                    <?php while ($row = $purchases->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['purchase_id']; ?></td>
                        <td><?php echo $row['purchase_date']; ?></td>
                        <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo number_format($row['purchase_price'],2); ?></td>
                        <td><?php echo number_format($row['subtotal'],2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
