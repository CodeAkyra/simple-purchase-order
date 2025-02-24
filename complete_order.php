<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $po_id = $_POST["po_id"];

    // Get ordered items
    $itemsQuery = "SELECT product_id, quantity FROM purchase_order_items WHERE po_id = $po_id";
    $itemsResult = mysqli_query($conn, $itemsQuery);

    while ($item = mysqli_fetch_assoc($itemsResult)) {
        $product_id = $item['product_id'];
        $ordered_quantity = $item['quantity'];

        // Deduct stock from inventory
        $updateStockQuery = "UPDATE products SET stock = stock - $ordered_quantity WHERE id = $product_id";
        mysqli_query($conn, $updateStockQuery);
    }

    // Mark order as completed
    $updateOrderQuery = "UPDATE purchase_orders SET status = 'Completed' WHERE id = $po_id";
    mysqli_query($conn, $updateOrderQuery);

    // Redirect back to view_po.php
    header("Location: view_po.php?id=$po_id&status=completed");
    exit();
}
