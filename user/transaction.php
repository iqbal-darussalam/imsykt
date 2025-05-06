<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM ims_transaction ORDER BY transaction_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction - YKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="../css/sidebar.css" rel="stylesheet">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content p-4">
    <h2>Transaction</h2>

    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa fa-plus"></i> Add Transaction
    </button>

    <div class="d-flex justify-content-between align-items-center mb-3">
    <div style="max-width: 250px;">
        <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Search by product ID or type...">
        </div>
    </div>
    <a href="../export_excell.php" class="btn btn-secondary btn-sm">
        <i class="fa fa-file-alt"></i> Report
    </a>
</div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
        <thead>
    <tr>
        <th>Transaction ID</th>
        <th>Product ID</th>
        <th>Type</th>
        <th>Quantity</th>
        <th>Transaction Date</th>
        <th>Warehouse ID</th>
    </tr>
</thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr data-id="<?= $row['transaction_id'] ?>" data-product="<?= $row['product_id'] ?>" data-type="<?= $row['type'] ?>" data-qty="<?= $row['quantity'] ?>" data-date="<?= $row['transaction_date'] ?>" data-warehouse="<?= $row['warehouse_id'] ?>">
                    <td><?= $row['transaction_id'] ?></td>    
                    <td><?= $row['product_id'] ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= $row['transaction_date'] ?></td>
                    <td><?= $row['warehouse_id'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="crud_transaction.php">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Transaction</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Product ID</label><input type="number" class="form-control" name="product_id" required></div>
          <div class="mb-3"><label>Type</label>
              <select class="form-control" name="type" required>
                <option value="in">In</option>
                <option value="out">Out</option>
              </select>
          </div>
          <div class="mb-3"><label>Quantity</label><input type="number" class="form-control" name="quantity" required></div>
          <div class="mb-3"><label>Transaction Date</label><input type="date" class="form-control" name="transaction_date" required></div>
          <div class="mb-3"><label>Warehouse ID</label><input type="number" class="form-control" name="warehouse_id" required></div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add" class="btn btn-success">Add</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="crud_transaction.php">
      <input type="hidden" name="transaction_id" id="edit-id">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Transaction</h5></div>
        <div class="modal-body">
          <div class="mb-3"><label>Product ID</label><input type="number" class="form-control" name="product_id" id="edit-product" required></div>
          <div class="mb-3"><label>Type</label>
              <select class="form-control" name="type" id="edit-type" required>
                <option value="in">In</option>
                <option value="out">Out</option>
              </select>
          </div>
          <div class="mb-3"><label>Quantity</label><input type="number" class="form-control" name="quantity" id="edit-qty" required></div>
          <div class="mb-3"><label>Transaction Date</label><input type="date" class="form-control" name="transaction_date" id="edit-date" required></div>
          <div class="mb-3"><label>Warehouse ID</label><input type="number" class="form-control" name="warehouse_id" id="edit-warehouse" required></div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-warning">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/sidebar.js"></script>
<script src="../js/search.js"></script>
<script>
document.querySelectorAll('.editBtn').forEach(btn => {
  btn.addEventListener('click', function () {
    const row = this.closest('tr');
    document.getElementById('edit-id').value = row.dataset.id;
    document.getElementById('edit-product').value = row.dataset.product;
    document.getElementById('edit-type').value = row.dataset.type;
    document.getElementById('edit-qty').value = row.dataset.qty;
    document.getElementById('edit-date').value = row.dataset.date;
    document.getElementById('edit-warehouse').value = row.dataset.warehouse;
  });
});
</script>
</body>
</html>
