@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header">Categories Information  <a href="javascript:void(0)" data-toggle="modal" data-target="#add_category" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-plus-circle"></i> Add New</a>&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#search" style="background: black;border: 1px solid black" class="btn btn-success btn-xs btn-rounded"><i class="fa fa-search"></i> Advance Search</a></div>
                        <div id="search" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form method="GET" action="{{ url('categories') }}">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header font-weight-bold">Advance Search For Category <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Search Keyword<b class="required_mark">*</b></label>
                                                            <input type="text" class="form-control" name="sk" placeholder="Enter category name or type any word" required />
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
                                                <th>Category Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($categories_list as $category)
                                            <tr>
                                                <td>{{ $category->item_category_name }}</td>
                                                <td>{{ $category->item_category_status }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_{{ $category->item_category_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                                    <div id="edit_{{ $category->item_category_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <form method="POST" action="{{ url('categories/update') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="item_category_id" value="{{ $category->item_category_id }}" />
                                                                    <div class="card">
                                                                        <div class="card-header font-weight-bold">Update Item Category <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="item_category_name">Name<b class="required_mark">*</b></label>
                                                                                        <input type="text" class="form-control" name="item_category_name" value="{{ $category->item_category_name}}" placeholder="Enter name" required />
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="item_category_status">Status<b class="required_mark">*</b></label>
                                                                                        <select class="form-control" name="item_category_status" id="item_category_status" required>
                                                                                            <option value="Active" @selected($category->item_category_status === 'Active')>Active</option>
                                                                                            <option value="Inactive" @selected($category->item_category_status === 'Inactive')>Inactive</option>
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
                                                <td colspan="3">No records found</td>
                                            </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div>
                                        {{ $categories_list->links() }}
                                    </div>

                                    <div id="add_category" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px">
                                                <form method="POST" action="{{ url('categories/store') }}">
                                                    @csrf
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add New Item Category <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="item_category_name">Name<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="item_category_name" value="{{ old('item_category_name') }}" placeholder="Enter name" required />
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary" name="btn_save_category"><i class="fa fa-check"></i> Submit</button>
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
        $('#categories').addClass('active');
    </script>
@endsection
