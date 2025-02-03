@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Add New Item Information <a href="{{ url('item') }}" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-backward"></i> go back</a></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form"  method="POST" action="{{ url('/item/store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="item_name">Name<b class="required_mark">*</b></label>
                                            <input name="item_name" maxlength="255" class="form-control" type="text" value="{{ old('item_name') }}" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="item_code">Code</label>
                                            <input name="item_code" class="form-control" type="text" value="{{ old('item_code') }}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="item_category">Category<b class="required_mark">*</b></label>
                                            <select class="form-control select2_demo_1" name="item_category_id" required>
                                                <option value="">-- select category --</option>
                                                @foreach($category_list AS $category)
                                                    <option value="{{ $category->item_category_id }}" @selected(old('item_category_id' == $category->item_category_id))>{{ $category->item_category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="item_buy_price">Purchase Price<b class="required_mark">*</b></label>
                                            <input type="text" maxlength="10" name="item_buy_price" value="{{ old('item_buy_price') }}" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="item_sell_price">Selling Price<b class="required_mark">*</b></label>
                                            <input type="text" maxlength="10" name="item_sell_price" value="{{ old('item_sell_price') }}" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="item_rack_no">Store Rack Number</label>
                                            <input type="text" name="item_rack_no" value="{{ old('item_rack_no') }}" maxlength="50" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="item_quantity">Quantity<b class="required_mark">*</b></label>
                                            <input type="number" min="1" name="item_quantity" value="{{ old('item_quantity')  }}" class="form-control" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="item_company">Company Name<b class="required_mark">*</b></label>
                                            <select class="form-control select2_demo_1" name="item_company_id" required>
                                                <option value="">-- select company --</option>
                                                @foreach($company_list AS $company)
                                                    <option value="{{ $company->item_company_id }}" @selected($company->item_company_id == old('item_company_id'))>{{ $company->item_company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="item_reorder_level">Reorder Level<b class="required_mark">*</b></label>
                                            <input type="number" min="1" name="item_reorder_level" value="{{ old('item_reorder_level') }}" class="form-control" required />
                                        </div>
                                        <div class="form-group" id="expire_date">
                                            <label for="item_expire_date">Expire Date</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input class="form-control" name="item_expire_date" value="{{ old('item_expire_date') }}" type="date">
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#items').addClass('active');
    </script>
@endsection
