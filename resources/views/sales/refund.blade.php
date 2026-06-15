<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request</title>
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
<h3 class="card-title">Refund Request</h3>
</div>

<div class="card-body">
<h3>{{ $sale->bill_no }}</h3>
<p>*Select product that is not being refunded</p>
<form method="POST" action="{{ route('sales.processRefund') }}">
<div class="row mb-3 position-relative">


    
    @csrf
    <div class="col-md">
         <!-- REFUND TABLE -->
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
            <tbody>
                @foreach($sale_items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                        <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                    </td>

                    <td>
                        {{ $item->price }}
                        <input type="hidden" name="products[{{ $item->product_id }}][price]" value="{{ $item->price }}">
                    </td>

                    <td>
                        {{ $item->quantity }}
                        <input type="hidden"
                            name="products[{{ $item->product_id }}][qty]"
                            value="{{ $item->quantity }}"
                            data-price="{{ $item->price }}">
                    </td>

                    <td class="row-total">{{ $item->quantity * $item->price }}</td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm removeRow">Remove Product</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col">
        <b>Customer name: </b>{{ $sale->customer_name }}
        <br>
        <textarea name="description" class="form-control" placeholder="Reason for refund" required></textarea>
        <input type="hidden" name="is_refund" value="1">
        <input type="hidden" name="mode_of_payment" value="{{ $sale->mode_of_payment }}">
        <input type="hidden" name="payment_details" value="{{ $sale->payment_details }}">
        <input type="hidden" name="discount" value="{{ $sale->discount }}">
        <input type="hidden" name="tax" value="{{ $sale->tax }}">
        <input type="hidden" name="customer_name" value="{{ $sale->customer_name }}">
    </div>


    <div class="card-footer">
    <button type="submit" class="btn btn-success">Save Refund</button>
    </div>

    
</div>
</form>

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
