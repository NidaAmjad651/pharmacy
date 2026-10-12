<?php
require "config.php";
require_login();
$page_title = "Sale";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_sale'])) {
    $customer_id = intval($_POST['customer_id']);
    $medicine_id = intval($_POST['medicine_id']);
    $qty         = intval($_POST['quantity']);
    $price       = floatval($_POST['sale_price']);
    $discount    = floatval($_POST['discount']);
    $user_id     = $_SESSION['user_id'];
    $subtotal    = $qty * $price;
    $total       = $subtotal - $discount;

    // Pehle available stock check karein
    $check = $conn->query("SELECT quantity, medicine_name FROM medicines WHERE medicine_id = $medicine_id")->fetch_assoc();

    if ($check['quantity'] < $qty) {
        $msg = "Error: '" . $check['medicine_name'] . "' ka stock sirf " . $check['quantity'] . " bacha hai.";
    } else {
        $conn->begin_transaction();
        try {
            $conn->query("INSERT INTO sales (customer_id, user_id, sale_date, total_amount, discount, paid_amount)
                           VALUES ($customer_id, $user_id, CURDATE(), $total, $discount, $total)");
            $sale_id = $conn->insert_id;

            $conn->query("INSERT INTO sale_items (sale_id, medicine_id, quantity, sale_price, subtotal)
                           VALUES ($sale_id, $medicine_id, $qty, $price, $subtotal)");
            // Stock automatically kam ho jayegi trigger ki wajah se

            $conn->commit();
            $msg = "Sale successfully record ho gayi. Invoice #$sale_id";
        } catch (Exception $e) {
            $conn->rollback();
            $msg = "Error: " . $e->getMessage();
        }
    }
}

$customers = $conn->query("SELECT * FROM customers");
$medicines = $conn->query("SELECT * FROM medicines WHERE quantity > 0 ORDER BY medicine_name");

$sales = $conn->query("SELECT s.sale_id, s.sale_date, c.customer_name, m.medicine_name,
                        si.quantity, si.sale_price, si.subtotal, s.discount, s.total_amount
                        FROM sales s
                        JOIN sale_items si ON s.sale_id = si.sale_id
                        LEFT JOIN customers c ON s.customer_id = c.customer_id
                        JOIN medicines m ON si.medicine_id = m.medicine_id
                        ORDER BY s.sale_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sale - Pharmacy System</title>
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
                <h6>Record New Sale</h6>
                <form method="POST" class="row g-2 mt-1">
                    <div class="col-md-3">
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Customer --</option>
                            <?php while($c = $customers->fetch_assoc()): ?>
                                <option value="<?php echo $c['customer_id']; ?>"><?php echo $c['customer_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="medicine_id" class="form-select" required>
                            <option value="">-- Medicine (in stock) --</option>
                            <?php while($m = $medicines->fetch_assoc()): ?>
                                <option value="<?php echo $m['medicine_id']; ?>">
                                    <?php echo $m['medicine_name']; ?> (Qty: <?php echo $m['quantity']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="sale_price" class="form-control" placeholder="Price per Unit" required>
                    </div>
                    <div class="col-md-1">
                        <input type="number" step="0.01" name="discount" class="form-control" placeholder="Discount" value="0">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" name="add_sale" class="btn btn-success w-100">Sell</button>
                    </div>
                </form>
            </div>

            <div class="card card-stat p-3">
                <h6>Sales History</h6>
                <table class="table table-striped table-bordered mt-2">
                    <tr>
                        <th>Sale ID</th><th>Date</th><th>Customer</th><th>Medicine</th>
                        <th>Qty</th><th>Price/Unit</th><th>Discount</th><th>Total</th>
                    </tr>
                    <?php while ($row = $sales->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['sale_id']; ?></td>
                        <td><?php echo $row['sale_date']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name'] ?? 'Walk-in'); ?></td>
                        <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo number_format($row['sale_price'],2); ?></td>
                        <td><?php echo number_format($row['discount'],2); ?></td>
                        <td><?php echo number_format($row['total_amount'],2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
