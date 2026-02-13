<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    @include('layouts.header')

    <style>
      @media print {
          body * {
              visibility: hidden;
          }

          #print-area,
          #print-area * {
              visibility: visible;
          }

          #print-area {
              position: absolute;
              left: 0;
              top: 0;
              width: 100%;
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
            <h1 class="m-0">Bill</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Bill</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <a href="javascript:window.print()" class="btn btn-default">
        <i class="fas fa-print"></i> Print
    </a>
    <!-- /.content-header -->
    <section class="content" id="print-area">
      <div class="container-fluid">
        <div class="bill p-3 mb-3">
              <!-- title row -->
              <div class="row ">
                <div class="col-12 d-flex align-items-center justify-content-center flex-column">
                  <h2>
                    <i class="fas fa-globe"></i> {{ $about->company_name }}
                  </h2>
                    {{ $about->company_website }}<br>
                    {{ $about->company_address}}<br>
                    {{ $about->company_phone_no }}<br>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row bill-info">
                <div class="col-sm-4 bill-col ">
                  <address>
                    @if ($about->company_email)
                      <b>Email:</b> {{ $about->company_email }}<br>
                    @endif
                    @if ($about->company_pan)
                      <b>PAN:</b> {{ $about->company_pan }}<br>
                    @endif
                    @if ($about->company_registration_no)
                      <b>Registration No:</b> {{ $about->company_registration_no }}<br>
                    @endif
                    <b>Payment Due:</b>{{@$sale->created_at}}<br>
                    <b>Printed Date:</b>{{ now()->format('Y/m/d') }}<br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                  <b>Employee name:</b> {{ Auth::user()->name }}<br>
                  @if ($sale->customer_name)
                    <b>Customer Name:</b> {{@$sale->customer_name}}<br>
                  @endif
                  <b>Bill No:</b> {{@$sale->bill_no}}<br>
                  
                  <b>Payment Method:</b> {{ @$sale->mode_of_payment == 1 ? 'Cash' : (@$sale->mode_of_payment == 2 ? 'Fonepay' : (@$sale->mode_of_payment == 3 ? 'Credit Card' : (@$sale->mode_of_payment == 4 ? 'Debit Card' : 'Bank Transfer') ))}}<br>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                      {{$subtotal = 0}}
                    @foreach($sale_items as $item)
                    <tr>
                      <td>{{ $item->product->name }}</td>
                      <td>{{ number_format($item->quantity, 0) }}</td>
                      <td>{{@$about->currency}} {{ number_format($item->price, 2) }}</td>
                    </tr>
                    {{$subtotal += $item->price * $item->quantity}}
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p>{{@$sale->description}}</p><hr>
                  <p>{{@$sale->payment_details}}</p>
                </div>
                <!-- /.col -->
                <div class="col-6">

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>{{@$about->currency}} {{$subtotal}}</td>
                      </tr>
                      <tr>
                        <th>Tax ({{number_format($sale->tax ?? 0, 0)}}%)</th>
                        <td>{{@$about->currency}} {{ number_format($subtotal * ($sale->tax ?? 0) / 100, 2) }}
                        </td>
                      </tr>
                      <tr>
                        <th>Discount({{number_format($sale->discount ?? 0, 0)}}%)</th>
                        <td>{{@$about->currency}} {{ number_format($subtotal * ($sale->discount ?? 0) / 100, 2) }}</td>
                      </tr>
                      <tr>
                        <th>Shipping:</th>
                        <td>{{@$about->currency}} 5.80</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>{{@$about->currency}} {{ number_format($subtotal + ($subtotal * ($sale->tax ?? 0) / 100) - ($subtotal * ($sale->discount ?? 0) / 100), 2) }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

            
      </div><!-- /.container-fluid -->
    </section>

</div>
@include('layouts.footer')
@include('layouts.script')
<!-- @include('layouts.datatable-script') -->
</body>
</html>