@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper pl-0">
            <div class="col-lg-12">
                @include('partials.alerts')
                <div class="card">
                    <div class="card-header font-weight-bold">Account Settings Information</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $auth_info->admin_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email Address</th>
                                            <td>{{ $auth_info->admin_email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td>{{ $auth_info->admin_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account Type</th>
                                            <td>{{ $auth_info->admin_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account Status</th>
                                            <td>{{ $auth_info->admin_status }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account Created On</th>
                                            <td>{{ $auth_info->created_at }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <br>
                                <a href="javascript:void(0);" style="font-weight: bold;" data-toggle="modal" data-target="#edit_info"><i class="fa fa-pencil"></i> Edit Information</a>
                                <a href="javascript:void(0);" style="color: maroon;font-weight: bold;" data-toggle="modal" data-target="#edit_password"><i class="fa fa-lock"></i> Change Password</a>
                                <div id="edit_info" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content" style="border: 0px;">
                                            <form method="POST" action="{{ url('account/settings/update') }}">
                                                @csrf
                                                <input type="hidden" name="admin_id" value="{{ $auth_info->admin_id }}"/>
                                                <div class="card">
                                                    <div class="card-header font-weight-bold">Update Account Information
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="name">Name<b class="required_mark">*</b></label>
                                                                    <input type="text" class="form-control" name="name" value="{{ $auth_info->admin_name }}" placeholder="Enter name" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email">Email</label>
                                                                    <input type="email" class="form-control" name="email" value="{{ $auth_info->admin_email }}" placeholder="Enter email"/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="phone">Phone<b class="required_mark">*</b></label>
                                                                    <input type="text" maxlength="11" class="form-control" required name="phone" value="{{ $auth_info->admin_phone }}" placeholder="Enter phone"/>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="edit_password" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ url('account/settings/change/password') }}">
                                                @csrf
                                                <div class="card">
                                                    <div class="card-header font-weight-bold">Change Password
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="current_password">Current Password <b style="color: red">*</b></label>
                                                                    <input name="current_password" class="form-control" type="password" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="new_password">New Password <b style="color: red">*</b></label>
                                                                    <input maxlength="30" minlength="6" name="new_password" class="form-control" type="password" value="" required/>
                                                                </div>
                                                                <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
