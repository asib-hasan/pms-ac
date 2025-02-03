@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">System Users Information <a href="javascript:void(0)" data-toggle="modal" data-target="#add_user" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-plus-circle"></i> Add New</a>&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#search" class="btn btn-success btn-xs btn-rounded" style="background: black;border: 1px solid black"><i class="fa fa-search"></i> Advance Search</a></div>
                        <div id="search" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form method="GET" action="{{ url('user') }}">
                                        <div class="card">
                                            <div class="card-header font-weight-bold">Advance Search For System Users <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Search Keyword<b class="required_mark">*</b></label>
                                                            <input type="text" class="form-control" name="sk" placeholder="Enter system user name or type any word" required />
                                                        </div>
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search Now</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr style="background-color: #ddd">
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($user_list as $user)
                                                <tr>
                                                    <td>{{ $user->admin_name }}</td>
                                                    <td>{{ $user->admin_phone }}</td>
                                                    <td>{{ $user->admin_email }}</td>
                                                    <td>{{ $user->admin_type == 1 ? 'Admin':'Employee' }}</td>
                                                    <td>{{ $user->admin_status }}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_{{ $user->admin_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                                        <div id="edit_{{ $user->admin_id }}" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content" style="border: 0px;">
                                                                    <form method="POST" action="{{ url('user/update') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="admin_id" value="{{ $user->admin_id }}" />
                                                                        <div class="card">
                                                                            <div class="card-header font-weight-bold">Update User Information <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label>Name<b class="required_mark">*</b></label>
                                                                                                <input type="text" class="form-control" name="admin_name" value="{{ $user->admin_name }}" placeholder="Enter name" required />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label>Email</label>
                                                                                                <input type="email" class="form-control" name="admin_email" value="{{ $user->admin_email }}" placeholder="Enter email" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label>Phone<b class="required_mark">*</b></label>
                                                                                                <input type="text" maxlength="11" class="form-control" required name="admin_phone" value="{{ $user->admin_phone }}" placeholder="Enter phone" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="admin_type">Type<b class="required_mark">*</b></label>
                                                                                                <select class="form-control" name="admin_type" required>
                                                                                                    <option value="">-- Choose Type --</option>
                                                                                                    <option value="1" @selected($user->admin_type == 1)>Admin</option>
                                                                                                    <option value="2" @selected($user->admin_type == 2)>Employee</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label for="admin_status">Status<b class="required_mark">*</b></label>
                                                                                                <select class="form-control" name="admin_status" required>
                                                                                                    <option value="">-- Choose Status --</option>
                                                                                                    <option value="Active" @selected($user->admin_status=='Active')>Active</option>
                                                                                                    <option value="Inactive" @selected($user->admin_status=='Inactive')>Inactive</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                                                                                        </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td>No Records</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        {{ $user_list->links() }}
                                    </div>
                                    <div id="add_user" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ url('user/store') }}">
                                                    @csrf
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add New User Information <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Name<b class="required_mark">*</b></label>
                                                                            <input type="text" class="form-control" name="admin_name" value="{{ old('name') }}" placeholder="Enter name" required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="admin_email">Email</label>
                                                                            <input type="email" class="form-control" name="admin_email" value="{{ old('email') }}" placeholder="Enter email" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="phone">Phone<b class="required_mark">*</b></label>
                                                                            <input type="text" maxlength="11" class="form-control" required name="admin_phone" value="{{ old('phone') }}" placeholder="Enter phone" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="password">Password<b class="required_mark">*</b></label>
                                                                            <input type="password" maxlength="50" minlength="6" class="form-control" name="admin_password" value="" placeholder="Enter password" required />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label>Type<b class="required_mark">*</b></label>
                                                                            <select class="form-control" name="admin_type" required>
                                                                                <option value="">-- Select --</option>
                                                                                <option value="1" @selected(old('type') == 1)>Admin</option>
                                                                                <option value="2" @selected(old('type') == 2)>Employee</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="admin_status">Status<b class="required_mark">*</b></label>
                                                                            <select class="form-control" name="admin_status" required>
                                                                                <option value="">-- Select --</option>
                                                                                <option value="Active" @selected(old('status') == 'Active')>Active</option>
                                                                                <option value="Inactive" @selected(old('status') == 'Inactive')>Inactive</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
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
    </div>

    <script>
        $('#userstx').addClass('active');
    </script>
@endsection
