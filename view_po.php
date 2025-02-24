<?php
include "conn.php";
$po_id = $_GET['id'];

// Fetch order details (Add status column)
$orderQuery = "SELECT po.id, c.name, po.order_date, po.status
               FROM purchase_orders po
               JOIN customers c ON po.customer_id = c.id 
               WHERE po.id = $po_id";
$orderResult = mysqli_query($conn, $orderQuery);
$order = mysqli_fetch_assoc($orderResult);

// Fetch order items
$itemsQuery = "SELECT p.name, oi.quantity, oi.price, oi.subtotal 
               FROM purchase_order_items oi
               JOIN products p ON oi.product_id = p.id 
               WHERE oi.po_id = $po_id";
$itemsResult = mysqli_query($conn, $itemsQuery);

// Calculate total price dynamically
$total_price = 0;
while ($row = mysqli_fetch_assoc($itemsResult)) {
    $total_price += $row["subtotal"];
    $items[] = $row; // Store items in an array for reuse
}
?>

<p><strong>Status:</strong> <?= $order['status'] ?></p>

<h2>Order Details</h2>
<p><strong>Customer:</strong> <?= $order['name'] ?></p>
<p><strong>Date:</strong> <?= $order['order_date'] ?></p>
<p><strong>Total Price:</strong> <?= $total_price ?></p> <!-- Correct total price -->

<h3>Products</h3>
<table class="table">
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Subtotal</th>
    </tr>
    <?php foreach ($items as $row): ?>
        <tr>
            <td><?= $row["name"] ?></td>
            <td><?= $row["quantity"] ?></td>
            <td><?= $row["price"] ?></td>
            <td><?= $row["subtotal"] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<form action="complete_order.php" method="POST">
    <input type="hidden" name="po_id" value="<?= $po_id ?>">
    <button type="submit" class="btn btn-success">Complete Order</button>
</form>


<a href="index.php" class="btn btn-secondary">Back</a>