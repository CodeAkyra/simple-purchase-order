<?php
include "conn.php";
$sql = "SELECT po.id, c.name, po.order_date, po.status,
               COALESCE((SELECT SUM(oi.subtotal) FROM purchase_order_items oi WHERE oi.po_id = po.id), 0) AS total_price
        FROM purchase_orders po
        JOIN customers c ON po.customer_id = c.id";

$result = mysqli_query($conn, $sql);
?>

<h2>Purchase Orders</h2>
<a href="create_po.php" class="btn btn-success">Create PO</a>

<table class="table" style="text-align: center;">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Date</th>
        <th>Total Price</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= $row["name"] ?></td>
            <td><?= $row["order_date"] ?></td>
            <td><?= $row["total_price"] ?></td>
            <td><?= $row["status"] ?></td>
            <td><a href="view_po.php?id=<?= $row["id"] ?>" class="btn btn-primary">View</a></td>
        </tr>
    <?php endwhile; ?>
</table>