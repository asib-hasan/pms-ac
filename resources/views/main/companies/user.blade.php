@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Contact Information For <span style="color: maroon">{{ $company_info->item_company_name }}</span> <a href="javascript:void(0)" data-toggle="modal" data-target="#add_company_user" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-plus-circle"></i> Add New</a></div>
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
                                                <th>Fax</th>
                                                <th>Designation</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($contact_list AS $contact)
                                            <tr>
                                                <td>{{ $contact->contact_info_name }}</td>
                                                <td>{{ $contact->contact_info_phone }}</td>
                                                <td>{{ $contact->contact_info_email }}</td>
                                                <td>{{ $contact->contact_info_fax }}</td>
                                                <td>{{ $contact->contact_info_designation }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_{{ $contact->contact_info_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                                    <a href="javascript:void(0);" style="color: maroon" data-toggle="modal" data-target="#delete_{{ $contact->contact_info_id }}"><i class="fa fa-trash"></i> Delete</a>
                                                    <div id="delete_{{ $contact->contact_info_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <form method="POST" action="{{ url('/company/user/delete') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="contact_info_id" value="{{ $contact->contact_info_id }}" />
                                                                    <div class="card">
                                                                        <div class="card-header font-weight-bold"><i class="fa fa-warning"></i> Delete Company User <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="alert alert-warning">
                                                                                        Are you sure want to delete this company user? Click "Yes" to delete.
                                                                                    </div>
                                                                                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Yes, I Want</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="edit_{{ $contact->contact_info_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content" style="border: 0px;">
                                                                <form method="POST" action="{{ url('/company/user/update') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="contact_info_id" value="{{ $contact->contact_info_id }}" />
                                                                    <div class="card">
                                                                    <div class="card-header font-weight-bold">Update Company's Contact Person Information <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="contact_info_name">Name<b class="required_mark">*</b></label>
                                                                                        <input type="text" maxlength="100" class="form-control" name="contact_info_name" value="{{ $contact->contact_info_name }}" placeholder="Enter name" required />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="contact_info_phone">Phone<b class="required_mark">*</b></label>
                                                                                        <input type="text" maxlength="11" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="contact_info_phone" value="{{ $contact->contact_info_phone }}" placeholder="Enter phone" required />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="contact_info_email">Email</label>
                                                                                        <input type="email" maxlength="100" class="form-control" name="contact_info_email" value="{{ $contact->contact_info_email }}" placeholder="Enter email" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="contact_info_fax">Fax</label>
                                                                                        <input type="text" maxlength="100" class="form-control" name="contact_info_fax" value="{{ $contact->contact_info_fax }}" placeholder="Enter fax" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="contact_info_designation">Designation</label>
                                                                                        <input type="text" maxlength="100" class="form-control" name="contact_info_designation" value="{{ $contact->contact_info_designation }}" placeholder="Enter designation" />
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
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        {{ $contact_list->links() }}
                                    </div>

                                    <div id="add_company_user" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px">
                                                <form method="POST" action="{{ url('/company/user/store') }}">
                                                    @csrf
                                                    <input type="hidden" name="contact_info_company_id" value="{{ $company_info->item_company_id }}" />
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold"><i class="fa fa-plus"></i> Add New Company User For <span style="color: maroon">{{ $company_info->item_company_name }}</span> <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contact_info_name">Name<b class="required_mark">*</b></label>
                                                                        <input type="text" maxlength="100" class="form-control" name="contact_info_name" value="{{ old('contact_info_name') }}" placeholder="Enter name" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contact_info_phone">Phone<b class="required_mark">*</b></label>
                                                                        <input type="text" maxlength="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" name="contact_info_phone" value="{{ old('contact_info_phone') }}" placeholder="Enter phone" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contact_info_email">Email</label>
                                                                        <input type="email" maxlength="100" class="form-control" name="contact_info_email" value="{{ old('contact_info_email') }}" placeholder="Enter email" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contact_info_fax">Fax</label>
                                                                        <input type="text" maxlength="20" class="form-control" name="contact_info_fax" value="{{ old('contact_info_fax') }}" placeholder="Enter fax" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="contact_info_designation">Designation</label>
                                                                        <input type="text" maxlength="100" class="form-control" name="contact_info_designation" value="{{ old('contact_info_designation') }}" placeholder="Enter designation" />
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
        $('#companies').addClass('active');
    </script>
@endsection
