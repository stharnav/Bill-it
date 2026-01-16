<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @include('layouts.header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('layouts.sidbar')
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sales</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">Add Sale</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
                  <br>
                    <div class="row">
                      <a href="{{ route('sales.sale') }}" class="btn btn-info">View Sales</a>
                    </div>
              </div>
             
          @endif
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Sale</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="">
                @csrf
                <div class="card-body">
                 <div class="row">
                    <div class="col">
                      <input type="text" class="form-control" placeholder="Invoice No" name="invoice_no" required>
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" placeholder="Product Name" name="product_name" required>
                    </div>
                    <div class="col">
                      <button class="btn btn-primary">Add</button>
                    </div>
                 </div>
                 <div class="row">
                  <div class="col">
                    <select name="mode_of_transport" id="" class="form-control" required>
                      <option value="1">Cash</option>
                      <option value="2">Fonepay</option>
                      <option value="3">Credit Card</option>
                      <option value="4">Debit Card</option>
                      <option value="5">Bank Transfer</option>
                    </select>
                  </div>
                  
                 </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
              </form>
            </div>
      </div><!-- /.container-fluid -->
    </section>

</div>
@include('layouts.footer')
@include('layouts.script')
</body>
</html>