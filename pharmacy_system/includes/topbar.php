<div class="topbar d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><?php echo $page_title ?? 'Dashboard'; ?></h5>
    <div>
        Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>
        (<?php echo htmlspecialchars($_SESSION['role']); ?>)
    </div>
</div>
