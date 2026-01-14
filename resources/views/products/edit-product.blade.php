<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    @include('layouts.header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    {{ $currentPage = 'edit-product' }}
    @include('layouts.sidbar')

    <div class="content-wrapper">

        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Edit Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <br>
                        <a href="{{ route('products.product') }}" class="btn btn-info mt-2">
                            View Products
                        </a>
                    </div>
                @endif

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Product</h3>
                    </div>

                    <form method="POST" action="{{ route('product.update', $product->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            <div class="row">
                                <div class="col">
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ $product->name }}"
                                           placeholder="Product Name"
                                           required>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col">
                                    <input type="number"
                                           name="price"
                                           class="form-control"
                                           value="{{ $product->price }}"
                                           placeholder="Price"
                                           required>
                                </div>

                                <div class="col">
                                    <input type="text"
                                           name="sku_number"
                                           class="form-control"
                                           value="{{ $product->sku_number }}"
                                           placeholder="SKU Number">
                                </div>

                                <div class="col">
                                    <select name="category_id" class="form-control">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <select class="form-control">
                                        <option>Sub Category</option>
                                        <option>Category 1</option>
                                        <option>Category 2</option>
                                        <option>Category 3</option>
                                    </select>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col">
                                    <textarea name="description"
                                              class="form-control"
                                              placeholder="Description">{{ $product->description }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Update Product
                            </button>
                            <a href="{{ route('products.product') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>

            </div>
        </section>
    </div>

    @include('layouts.footer')
    @include('layouts.script')
</div>
</body>
</html>
