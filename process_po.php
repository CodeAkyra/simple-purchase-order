<?php
include "conn.php";
if (isset($_POST['create_po'])) {
    $customer_id = $_POST['customer_id'];
    $total_price = $_POST['total_price'];
    $products = $_POST['products'];
    $quantities = $_POST['quantities'];
    $prices = $_POST['prices'];
    $subtotals = $_POST['subtotals'];

    mysqli_query($conn, "INSERT INTO purchase_orders (customer_id, total_price) VALUES ($customer_id, $total_price)");
    $po_id = mysqli_insert_id($conn);

    for ($i = 0; $i < count($products); $i++) {
        mysqli_query($conn, "INSERT INTO purchase_order_items (po_id, product_id, quantity, price, subtotal) 
            VALUES ($po_id, {$products[$i]}, {$quantities[$i]}, {$prices[$i]}, {$subtotals[$i]})");
    }

    header("Location: index.php");
}
