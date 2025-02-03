<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Step 2 - Store Information</title>
        <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    </head>
    <body>
    <div class="container">
        <div class="card">
            <div class="row p-5">
                <div class="col-lg-12 col-lg-offset-3">
                    <div class="ibox float-e-margins">
                        @include('partials.alerts')
                        <div class="ibox-title">
                            <h2><span style="color: blue">SETUP STEP 2</span> <span style="font-size: 13px;font-weight: bold">PROVIDE STORE INFORMATION</span></h2>
                        </div>
                        <div class="ibox-content">
                            <small style="font-weight: bold;color: maroon">1. This information will be use in invoices.</small>
                            <br><small style="font-weight: bold;color: maroon">2. Make sure all provided information is correct.</small>
                            <form action="{{ url('/step/two/store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="store_title">Store Title<b class="required_mark">*</b></label>
                                    <input type="text" maxlength="100" class="form-control" name="store_title" value="{{ old('store_title') }}" placeholder="Enter title" required />
                                    <small><i>eg. XYZ Management System</i></small>
                                    @if (session('invalid') && session('invalid')->has('store_title'))
                                        <span class="text-danger">{{ session('invalid')->first('store_title') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="store_name">Store Name<b class="required_mark">*</b></label>
                                    <input type="text" maxlength="25" class="form-control" name="store_name" value="{{ old('store_name') }}" placeholder="Enter name" required />
                                    <small><i>eg. Your store name (ABC or XYZ)</i></small>
                                    @if (session('invalid') && session('invalid')->has('store_name'))
                                        <span class="text-danger">{{ session('invalid')->first('store_name') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="store_email">Store Email</label>
                                    <input type="email" maxlength="50" class="form-control" name="store_email" value="{{ old('store_email') }}" placeholder="Enter email" />
                                    @if (session('invalid') && session('invalid')->has('store_email'))
                                        <span class="text-danger">{{ session('invalid')->first('store_email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="store_phone">Store Phone</label>
                                    <input type="text" maxlength="11" class="form-control" name="store_phone" value="{{ old('store_phone') }}" placeholder="Enter phone" />
                                    @if (session('invalid') && session('invalid')->has('store_phone'))
                                        <span class="text-danger">{{ session('invalid')->first('store_phone') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="store_address">Store Address</label>
                                    <textarea class="form-control" name="store_address" style="resize: vertical;">{{ old('store_address') }}</textarea>
                                    @if (session('invalid') && session('invalid')->has('store_address'))
                                        <span class="text-danger">{{ session('invalid')->first('store_address') }}</span>
                                    @endif
                                </div>
                                <br>
                                <div class="form-group">
                                    <label></label>
                                    <button class="btn btn-primary pull-right" type="submit" name="btn_save_step_two">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    </body>
</html>
