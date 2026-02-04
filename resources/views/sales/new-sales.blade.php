<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sale</title>
    @include('layouts.header')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
.product-item.active {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
}
</style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
@include('layouts.sidbar')

<div class="content-wrapper">
<section class="content pt-3">
<div class="container-fluid">

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card card-primary">
<div class="card-header">
<h3 class="card-title">Add Sale</h3>
</div>

<div class="card-body">

<!-- PRODUCT INPUT -->
<div class="row mb-3 position-relative">
    <div class="col-md-4">
        <input type="text" id="product_name" class="form-control" placeholder="Product Name" autocomplete="off">
<input type="hidden" id="product_id">

<ul id="productList" class="list-group position-absolute w-100" style="z-index:1000;"></ul>

    </div>

    <div class="col-md-2">
        <input type="number" id="price" class="form-control" placeholder="Price" readonly>
    </div>

    <div class="col-md-2">
        <input type="number" id="qty" class="form-control" value="1" min="1">
    </div>

    <div class="col-md-2">
        <button type="button" class="btn btn-primary" id="addProduct">Add</button>
    </div>
</div>

<form method="POST" action="{{ route('sales.store') }}">
@csrf



<!-- SALE TABLE -->
<table class="table table-bordered" id="saleTable">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- PAYMENT MODE -->
<div class="row mt-3">
    <div class="col-md-2">
        <select name="mode_of_payment" class="form-control" required>
            <option value="1">Cash</option>
            <option value="2">Fonepay</option>
            <option value="3">Credit Card</option>
            <option value="4">Debit Card</option>
            <option value="5">Bank Transfer</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="number" name="discount" class="form-control" placeholder="Discount (%)" min="0" max="100">
    </div>
    <div class="col-md-4">
        <textarea name="description" id="" class="form-control" placeholder="Description"></textarea>
    </div>
    <div class="col-md-4">
        <textarea name="payment_details" id="" class="form-control" placeholder="Payment Details"></textarea>
    </div>
    <div class="col">
        <input type="text" name="customer_name" class="form-control" placeholder="Customer Name">
    </div>
</div>

</div>

<div class="card-footer">
<button type="submit" class="btn btn-success">Save Sale</button>
</div>

</form>
</div>

</div>
</section>
</div>

@include('layouts.footer')
@include('layouts.script')

<script>
let products = [];
let selectedIndex = -1;

/* =========================
   SEARCH PRODUCTS
========================= */
$('#product_name').on('input', function () {
    let query = $(this).val();

    selectedIndex = -1;

    if (query.length < 2) {
        $('#productList').hide();
        return;
    }

    $.ajax({
        url: "{{ route('sales.search') }}",
        type: "GET",
        data: { term: query },
        success: function (data) {
            products = data;
            let list = '';

            data.forEach((product, index) => {
                list += `
                    <li class="list-group-item product-item"
                        data-index="${index}">
                        ${product.name}
                    </li>
                `;
            });

            $('#productList').html(list).show();
        }
    });
});

/* =========================
   KEYBOARD NAVIGATION
========================= */
$('#product_name').on('keydown', function (e) {
    let items = $('#productList .product-item');
    if (!items.length) return;

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        selectedIndex = (selectedIndex + 1) % items.length;
    }

    else if (e.key === 'ArrowUp') {
        e.preventDefault();
        selectedIndex = (selectedIndex - 1 + items.length) % items.length;
    }

    else if (e.key === 'Enter') {
        e.preventDefault();
        if (selectedIndex >= 0) {
            selectProduct(products[selectedIndex]);
        }
        return;
    }

    items.removeClass('active');
    items.eq(selectedIndex).addClass('active');
});

/* =========================
   MOUSE CLICK SELECTION
========================= */
$(document).on('click', '.product-item', function () {
    let index = $(this).data('index');
    selectProduct(products[index]);
});

/* =========================
   SELECT PRODUCT FUNCTION
========================= */
function selectProduct(product) {
    $('#product_id').val(product.id);
    $('#product_name').val(product.name);
    $('#price').val(product.price);

    $('#productList').hide();
    selectedIndex = -1;
}

/* =========================
   HIDE DROPDOWN ON OUTSIDE CLICK
========================= */
$(document).on('click', function (e) {
    if (!$(e.target).closest('#product_name, #productList').length) {
        $('#productList').hide();
    }
});
</script>


<script>
$('#addProduct').on('click', function () {

    let productId   = $('#product_id').val();
    let productName = $('#product_name').val();
    let price       = parseFloat($('#price').val());
    let qty         = parseInt($('#qty').val());

    if (!productId) {
        alert('Please select a product');
        return;
    }

    // Prevent duplicate product
    if ($('#row-' + productId).length) {
        alert('Product already added');
        return;
    }

    let total = price * qty;

    let row = `
        <tr id="row-${productId}">
            <td>
                ${productName}
                <input type="hidden" name="products[${productId}][id]" value="${productId}">
            </td>

            <td>
                ${price}
                <input type="hidden" name="products[${productId}][price]" value="${price}">
            </td>

            <td>
                <input type="number"
                       class="form-control qty-input"
                       name="products[${productId}][qty]"
                       value="${qty}"
                       min="1"
                       data-price="${price}">
            </td>

            <td class="row-total">${total}</td>

            <td>
                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
            </td>
        </tr>
    `;

    $('#saleTable tbody').append(row);

    // Reset inputs
    $('#product_id').val('');
    $('#product_name').val('');
    $('#price').val('');
    $('#qty').val(1);
});
</script>
<script>
$(document).on('input', '.qty-input', function () {
    let qty   = parseInt($(this).val());
    let price = parseFloat($(this).data('price'));

    if (qty < 1 || isNaN(qty)) qty = 1;

    let total = qty * price;
    $(this).closest('tr').find('.row-total').text(total);
});
</script>

<script>
$(document).on('click', '.removeRow', function () {
    $(this).closest('tr').remove();
});
</script>


</body>
</html>
