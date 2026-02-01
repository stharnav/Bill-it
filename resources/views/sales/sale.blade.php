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
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sales</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
      <div class="container-fluid">
        <button class="btn btn-primary" onclick="window.location='{{ route('sales.new-sales') }}'">New Sale</button><br><br>
        <div class="card">
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Rank</th>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Mode of Payment</th>
                    <th>Payment Details</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($sales as $sale)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->invoice_no }}</td>
                    <td>{{ $sale->created_at }}</td>
                    <td>{{ $sale->mode_of_payment == 1 ? 'Cash' : ($sale->mode_of_payment == 2 ? 'Fonepay' : 'Other') }}</td>
                    <td>{{ $sale->payment_details == '' ? 'N/A' : $sale->payment_details }}</td>
                    <td></td>
                  </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Rank</th>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Mode of Payment</th>
                    <th>Payment Details</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
      </div><!-- /.container-fluid -->
    </section>

</div>
@include('layouts.footer')
@include('layouts.script')
<!-- @include('layouts.datatable-script') -->
</body>
</html>