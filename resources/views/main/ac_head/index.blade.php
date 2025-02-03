@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Expense Head Information  <a href="javascript:void(0)" data-toggle="modal" data-target="#add_head" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-plus-circle"></i> Add New</a>&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#search" style="background: black;border: 1px solid black" class="btn btn-success btn-xs btn-rounded"><i class="fa fa-search"></i> Advance Search</a></div>
                        <div id="search" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form method="GET" action="{{ url('achead') }}">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header font-weight-bold">Advance Search For Expense Head <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Search Keyword<b class="required_mark">*</b></label>
                                                            <input type="text" class="form-control" name="sk" placeholder="Enter head name or type any word" required />
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
                                                <th>Head Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($expense_head_list as $achead)
                                            <tr>
                                                <td>{{ $achead->name }}</td>
                                                <td>{{ $achead->status }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_{{ $achead->id }}"><i class="fa fa-pencil"></i> Edit</a>
                                                    <div id="edit_{{ $achead->id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <form method="POST" action="{{ url('achead/update') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $achead->id }}" />
                                                                    <div class="card">
                                                                        <div class="card-header font-weight-bold">Update Expense Head Name <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="item_category_name">Head Name <b class="required_mark">*</b></label>
                                                                                        <input type="text" class="form-control" name="name" value="{{ $achead->name }}" placeholder="Enter name" required />
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="status">Status<b class="required_mark">*</b></label>
                                                                                        <select class="form-control" name="status" required>
                                                                                            <option value="Active" @selected($achead->status === 'Active')>Active</option>
                                                                                            <option value="Inactive" @selected($achead->status === 'Inactive')>Inactive</option>
                                                                                        </select>
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
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4">No records found</td>
                                            </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div>
                                        {{ $expense_head_list->links() }}
                                    </div>

                                    <div id="add_head" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ url('achead/store') }}">
                                                    @csrf
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add New Expense Head Information <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="item_category_name">Head Name<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="item_category_status">Status<b class="required_mark">*</b></label>
                                                                        <select class="form-control" name="status" required>
                                                                            <option value="Active" @selected(old('status') == 'Active')>Active</option>
                                                                            <option value="Inactive" @selected(old('status') == 'Inactive')>Inactive</option>
                                                                        </select>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#achead').addClass('active');
    </script>
@endsection
