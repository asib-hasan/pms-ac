@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bolder">Companies Information  <a href="javascript:void(0)" data-toggle="modal" data-target="#add_company" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-plus-circle"></i> Add New</a>&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#search" style="background: black;border: 1px solid black" class="btn btn-success btn-xs btn-rounded"><i class="fa fa-search"></i> Advance Search</a></div>
                        <div id="search" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form method="GET" action="{{ url('companies') }}">
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
                                                <th>Company Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($companies_list as $company)
                                                <tr>
                                                    <td>{{ $company->item_company_name }}</td>
                                                    <td>{{ $company->item_company_status }}</td>
                                                    <td>
                                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_{{ $company->item_company_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                                        <a href="{{ url('/company/users?id=' . $company->item_company_id) }}" style="color: blue"><i class="fa fa-user"></i> Contacts</a>
                                                        <a href="{{ url('company/purchase?id=' . $company->item_company_id) }}" style="color: green"><i class="a fa-bitcoin"></i> Purchase & Billing</a>
                                                        <div id="edit_{{ $company->item_company_id }}" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-md">
                                                                <div class="modal-content" style="border: 0px;">
                                                                    <form method="POST" action="{{ url('companies/update') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="item_company_id" value="{{ $company->item_company_id }}" />
                                                                        <div class="card">
                                                                            <div class="card-header font-weight-bold">Update Company Information <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                                                            <div class="card-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">
                                                                                            <label for="item_company_name">Name<b class="required_mark">*</b></label>
                                                                                            <input type="text" class="form-control" name="item_company_name" value="{{ $company->item_company_name }}" placeholder="Enter name" required />
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="item_company_status">Status<b class="required_mark">*</b></label>
                                                                                            <select class="form-control" name="item_company_status" id="item_company_status" required>
                                                                                                <option value="Active" @selected($company->item_company_status == 'Active')>Active</option>
                                                                                                <option value="Inactive" @selected($company->item_company_status == 'Inactive')>Inactive</option>
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
                                        {{ $companies_list->links() }}
                                    </div>

                                    <div id="add_company" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px;">
                                                <form method="POST" action="{{ url('companies/store') }}">
                                                    @csrf
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold"><i class="fa fa-plus"></i> Add New Company <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="item_company_name">Name<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="item_company_name" placeholder="Enter name" required />
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
        $('#companies').addClass('active');
    </script>
@endsection
