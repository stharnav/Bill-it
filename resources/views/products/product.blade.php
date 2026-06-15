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
            <h1 class="m-0">Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
        <button onclick="window.location.href='{{ route('products.add-product') }}'" class="btn btn-primary">Add Product</button>
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Rank</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @forelse ($products as $product)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $product->name }}</td>
                          <td>{{ $product->price }}</td>
                          <td>
                              @if ($product->stock > 5)
                                  <span class="badge badge-success">{{ $product->stock }}</span>
                              @elseif ($product->stock > 0)
                                  <span class="badge badge-warning">{{ $product->stock }}</span>
                              @else
                                  <span class="badge badge-danger">Out of Stock</span>
                              @endif
                          </td>
                          <td>{{ $product->sku_number ?? 'N/A' }}</td>
                          <td>{{ $product->category->name ?? 'N/A' }}</td>
                          <td>
                              <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                              <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display:inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                              </form>
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="7">No products found.</td>
                      </tr>
                  @endforelse
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Rank</th>
                    <th>Product name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
            </div>
      </div>
    </section>

</div>
@include('layouts.footer')
@include('layouts.script')
@include('layouts.datatable-script')

</body>
</html>