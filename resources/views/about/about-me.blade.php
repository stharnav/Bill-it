<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill it | {{ Auth::user()->name }}</title>
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
            <h1 class="m-0">About Me</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active">About Me</li>
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
          </div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif
        <div class="row">
          <div class="col-md-6">
            <div class="card card-primary">     
              <div class="m-2 p-2">
                      <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="form-group row">
                          <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName" placeholder="Name" name="name" value="{{ Auth::user()->name }}">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                          <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email" value="{{ Auth::user()->email }}">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputName2" class="col-sm-2 col-form-label">Username</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputName2" placeholder="Username" name="username" value="{{ Auth::user()->username }}">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputName2" class="col-sm-2 col-form-label">Profile</label>
                          <div class="col-sm-10">
                            <div class="row">
                              <div class="col-md-2 border rounded">
                                @if (Auth::user()->user_logo)
                                  <img src="{{ asset('uploads/user/' . Auth::user()->user_logo) }}" width="100" height="100" alt="User Logo">
                                @else
                                  <i class="fas fa-user"></i>
                                @endif
                              </div>
                              <div class="col">
                                <input type="file" class="" id="inputName2" name="user_logo">
                              </div>
                            </div>
                            
                           
                          </div>
                        </div>
                        
                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                          </div>
                        </div>
                      </form>
                    </div>
              </div> 
        </div>
        <div class="col-md-6">
          <div class="card card-primary">     
            <div class="m-2 p-2">
              <h3>Change Password</h3><hr>
                    <form class="form-horizontal" method="post" action="{{ route('password.update') }}">
                      @csrf
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Old Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" placeholder="Old Password" name="old_password">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">New Password</label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="inputName2" placeholder="New Password" name="new_password">
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-default">Change Password</button>
                        </div>
                      </div>
                    </form>
                  </div>
            </div>
            
      </div>
        </div>
          </div>
        </div>
        <!-- /.container-fluid -->
    </section>

</div>
@include('layouts.footer')
@include('layouts.script')
</body>
</html>