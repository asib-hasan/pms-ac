@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header">
                            <h4>System Settings Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <form role="form" class="mb-4" method="POST" action="{{ '/system/settings/update' }}">
                                            @csrf
                                            <div class="form-group mt-1">
                                                <label for="store_title">Store Title<b class="required_mark">*</b></label>
                                                <input type="text" maxlength="100" class="form-control" name="store_title" value="{{ $store_info->store_title ?? '' }}" placeholder="Enter title" required/>
                                                <small><i>eg. XYZ Management System</i></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="store_name">Store Name<b class="required_mark">*</b></label>
                                                <input type="text" maxlength="25" class="form-control" name="store_name" value="{{ $store_info->store_name ?? '' }}" placeholder="Enter name" required/>
                                                <small><i>eg. Your store name (ABC or XYZ)</i></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="store_email">Store Email</label>
                                                <input type="email" maxlength="50" class="form-control" name="store_email" value="{{ $store_info->store_email ?? '' }}" placeholder="Enter email"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="store_phone">Store Phone</label>
                                                <input type="text" maxlength="11" class="form-control" name="store_phone" value="{{ $store_info->store_phone ?? '' }}" placeholder="Enter phone"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="store_address">Store Address</label>
                                                <textarea class="form-control" name="store_address" style="resize: vertical;" placeholder="Enter address">{{ $store_info->store_address ?? '' }}</textarea>
                                            </div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-check"></i> Save
                                            </button>
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

    <script>
        $('#settings').addClass('active');
    </script>
@endsection
