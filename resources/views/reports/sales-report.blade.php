<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>

    @include('layouts.header')

    <style>
    /* Print header — only visible when printing */
    .report-header {
        display: none;
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #333;
    }
    .report-header h2 {
        margin: 0 0 5px 0;
        font-weight: bold;
        color: #333;
    }
    .report-header .report-date {
        font-size: 14px;
        color: #555;
        margin: 0;
    }
    @media print {
        .report-header {
            display: block !important;
        }
        .content-header,
        .card:first-of-type,
        form,
        .breadcrumb,
        .main-header,
        .main-sidebar,
        .main-footer,
        .btn {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        body {
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
    }
    </style>
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
                <select class="form-control" id="mode_of_payment" name="mode_of_payment">
                  <option value="">All</option>
                  <option value="1" {{ request('mode_of_payment') == '1' ? 'selected' : '' }}>Cash</option>
                  <option value="2" {{ request('mode_of_payment') == '2' ? 'selected' : '' }}>Fonepay</option>
                  <option value="3" {{ request('mode_of_payment') == '3' ? 'selected' : '' }}>Credit Card</option>
                  <option value="4" {{ request('mode_of_payment') == '4' ? 'selected' : '' }}>Debit Card</option>
                  <option value="5" {{ request('mode_of_payment') == '5' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
              </div>
              <div class="col-md">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id">
                  <option value="">All</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2">
                <input type="submit" value="Create Report" class="btn btn-primary mt-4 form-control">
              </div>
              <div class="col-md-2">
                <button onclick="window.print()" class="btn btn-secondary mt-4 form-control">Print Report</button>
              </div>
            </div>
            </form>
          </div>
        </div>
        <!-- Print header with company name and date -->
        <div class="report-header">
            <h2>{{ $company ? $company->company_name : 'Sales Report' }}</h2>
            <p class="report-date">
                Report Period:
                {{ request('start_date') ? date('F j, Y', strtotime(request('start_date'))) : date('F j, Y', strtotime('-30 days')) }}
                —
                {{ request('end_date') ? date('F j, Y', strtotime(request('end_date'))) : date('F j, Y') }}
            </p>
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
                      <td>{{ $sale->mode_of_payment }}</td>
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

<!-- Override DataTable with print customization for company name + date -->
<script>
$(function() {
    if ($.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable().destroy();
    }
    $('#example1').DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf',
            {
                extend: 'print',
                customize: function (win) {
                    $(win.document.body).prepend(
                        '<div style="text-align:center;margin-bottom:20px;padding-bottom:15px;border-bottom:2px solid #333;">' +
                            '<h2>{{ $company ? $company->company_name : "Sales Report" }}</h2>' +
                            '<p style="font-size:14px;color:#555;margin:5px 0 0;">Report Period: {{ request("start_date") ? date("F j, Y", strtotime(request("start_date"))) : date("F j, Y", strtotime("-30 days")) }} &mdash; {{ request("end_date") ? date("F j, Y", strtotime(request("end_date"))) : date("F j, Y") }}</p>' +
                        '</div>'
                    );
                }
            },
            'colvis'
        ]
    });
});
</script>
</body>
</html>