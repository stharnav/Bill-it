<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    
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
            <h1 class="m-0">Sales Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Sales Report</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
      <div class="container-fluid">
        @if(session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
        @endif
        <div class="card">
          <div class="card-body">
            <form action="{{ route('reports.sales-report.search') }}" method="GET">
            <div class="row">
              <div class="col-md">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
              </div>
              <div class="col-md">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date" value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-md">
                <label for="mode_of_payment">Mode of Payment</label>
                <select class="form-control" id="mode_of_payment" name="mode_of_payment" disabled>
                  <option value="all">All</option>
                  <option value="cash">Cash</option>
                  <option value="credit_card">Credit Card</option>
                  <option value="debit_card">Debit Card</option>
                  <option value="mobile_payment">Mobile Payment</option>
                </select>
              </div>
              <div class="col-md">
                <label for="category">Category</label>
                <select class="form-control" id="category" name="category" disabled>
                  <option value="all">All</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2">
                <input type="submit" value="Create Report" class="btn btn-primary mt-4 form-control">
              </div>
            </div>
            </form>
          </div>
        </div>
        <div class="card">
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Rank</th>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Mode of Payment</th>
                    <th>Customer Name</th>
                    <th>Sales Amount</th>
                  </tr>
                  </thead>
                  <tbody>
                  @if(isset($sales) && count($sales) > 0)
                  @php
                  $total_sales = 0;
                  @endphp
                  @foreach($sales as $index => $sale)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $sale->bill_no }}</td>
                      <td>{{ date('Y-m-d', strtotime($sale->created_at)) }}</td>
                      <td>{{ ucfirst(str_replace('_', ' ', $sale->mode_of_payment)) }}</td>
                      <td>{{ $sale->customer_name }}</td>
                      <td>{{ number_format(($sale->total_price - $sale->discount/100 * $sale->total_price), 2) }}</td>
                      @php
                      $amount = $sale->total_price - ($sale->discount / 100 * $sale->total_price);
                      $total_sales += $sale->is_refund ? -$amount : $amount;
                      @endphp
                    </tr>
                  @endforeach
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td><strong>Total:</strong></td>
                      <td>{{ number_format($total_sales, 2) }}</td>          
                    </tr>
                  @else
                    <tr>
                      <td colspan="6" class="text-center">No sales found for the selected criteria.</td>
                    </tr>
                  @endif
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Rank</th>
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Mode of Payment</th>
                    <th>Customer Name</th>
                    <th>Sales Amount</th>
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