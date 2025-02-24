<?php
include "conn.php";
$customers = mysqli_query($conn, "SELECT * FROM customers");
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<h2>Create Purchase Order</h2>
<form action="process_po.php" method="POST">
    <label>Customer:</label>
    <select name="customer_id" required>
        <option value="">Select Customer</option>
        <?php while ($c = mysqli_fetch_assoc($customers)): ?>
            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
        <?php endwhile; ?>
    </select>

    <h3>Products</h3>
    <table id="productTable">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </table>
    <button type="button" onclick="addProduct()">Add Product</button>

    <input type="hidden" name="total_price" id="totalPrice" value="0">
    <button type="submit" name="create_po">Submit Order</button>
</form>

<script>
    function addProduct() {
        var row = document.createElement("tr");

        var productSelect = `<select name="products[]" onchange="updatePrice(this)">
        <option value="">Select</option>
        <?php while ($p = mysqli_fetch_assoc($products)): ?>
        <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>"><?= $p['name'] ?></option>
        <?php endwhile; ?>
    </select>`;

        row.innerHTML = `
        <td>${productSelect}</td>
        <td><input type="number" name="quantities[]" min="1" oninput="updateSubtotal(this)"></td>
        <td><input type="text" name="prices[]" readonly></td>
        <td><input type="text" name="subtotals[]" readonly></td>
        <td><button type="button" onclick="this.parentElement.parentElement.remove()">Remove</button></td>
    `;
        document.getElementById("productTable").appendChild(row);
    }

    function updatePrice(select) {
        var price = select.options[select.selectedIndex].getAttribute("data-price");
        var row = select.parentElement.parentElement;
        row.querySelector('input[name="prices[]"]').value = price;
    }

    function updateSubtotal(input) {
        var row = input.parentElement.parentElement;
        var price = row.querySelector('input[name="prices[]"]').value;
        row.querySelector('input[name="subtotals[]"]').value = input.value * price;
    }
</script>