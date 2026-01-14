<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    @include('layouts.header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    {{$currentPage = 'edit-category'}}
    @include('layouts.sidbar')
    
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1>Edit Category</h1>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Category</h3>
                    </div>

                    <form method="POST" action="{{ route('category.update', $category->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ $category->name }}"
                                       required>
                            </div>

                            <div class="form-group">
                                <textarea name="description"
                                          class="form-control"
                                          placeholder="Description">{{ $category->description }}</textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Category</button>
                            <a href="{{ route('categories.category') }}" class="btn btn-secondary">Cancel</a>
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
