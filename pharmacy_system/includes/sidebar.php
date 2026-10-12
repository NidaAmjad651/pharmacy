<?php $current = basename($_SERVER['PHP_SELF']); ?>
<div class="col-md-2 sidebar p-0">
    <h4>💊 Pharmacy</h4>
    <a href="dashboard.php" class="<?php echo $current=='dashboard.php'?'active':''; ?>">🏠 Dashboard</a>
    <a href="medicines.php" class="<?php echo $current=='medicines.php'?'active':''; ?>">💊 Medicines</a>
    <a href="categories.php" class="<?php echo $current=='categories.php'?'active':''; ?>">🏷️ Categories</a>
    <a href="suppliers.php" class="<?php echo $current=='suppliers.php'?'active':''; ?>">🚚 Suppliers</a>
    <a href="customers.php" class="<?php echo $current=='customers.php'?'active':''; ?>">🧑‍🤝‍🧑 Customers</a>
    <a href="purchase.php" class="<?php echo $current=='purchase.php'?'active':''; ?>">🛒 Purchase</a>
    <a href="sale.php" class="<?php echo $current=='sale.php'?'active':''; ?>">💰 Sale</a>
    <?php if (($_SESSION['role'] ?? '') == 'Admin'): ?>
    <a href="users.php" class="<?php echo $current=='users.php'?'active':''; ?>">👤 Users</a>
    <?php endif; ?>
    <a href="logout.php">🚪 Logout</a>
</div>
