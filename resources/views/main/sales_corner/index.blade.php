@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card mt-1">
                        <div class="card-header font-weight-bold">Sales Corner</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select class="js-example-basic-single w-100" style="width: 100%" id="item_name_type" name="item_name_type" onchange="javascript:check_click_item(event, this.value);">
                                            <option value="">-- select item from list and click on the item --</option>
                                            @foreach($item_list AS $item)
                                                <option value="{{ $item->item_id }}">{{ $item->item_name }}(Price: {{ $item->item_sell_price }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="margin-top: 20px;" class="table-responsive">
                                        <input type="hidden" id="sub_total_price" value=""/>
                                        <div class="table-responsive-md" id="sales_table_data">
                                                <table class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th style="background-color: #ddd">#</th>
                                                            <th style="background-color: #ddd">Item Name</th>
                                                            <th style="background-color: #ddd">Expire Date</th>
                                                            <th style="background-color: #ddd">In Stock</th>
                                                            <th style="background-color: #ddd">Sell Qty</th>
                                                            <th style="background-color: #ddd">Sell Price&nbsp; {{ $currency }}</th>
                                                            <th style="background-color: #ddd">Total Price&nbsp; {{ $currency }}</th>
                                                            <th style="background-color: #ddd">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="temp_table_body">
                                                        <tr id="no-data">
                                                            <td colspan="8">No data found</td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="6" style="text-align: right">Subtotal&nbsp; ({{ $currency }})</th>
                                                            <td colspan="2"><span id="total_sales_subtotal"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="6" style="text-align: right">Discount Type</th>
                                                            <td colspan="2">
                                                                <select style="border:1px solid #ddd;border-radius: 5px;padding:5px" id="discount_type" name="discount_type">
                                                                    <option value="0">-- select --</option>
                                                                    <option value="1">Flat Rate</option>
                                                                    <option value="2">Percent</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="6" style="text-align: right">Discount&nbsp;({{ $currency }})</th>
                                                            <td colspan="2">
                                                                <input style="border:1px solid #ddd;border-radius: 5px;padding:5px" type="number" min="1"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="6" style="text-align: right">Gross Total&nbsp;({{ $currency }})</th>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="6" style="text-align: right">Total Paid&nbsp;({{ $currency }})</th>
                                                            <td colspan="2">
                                                                <input class="input-sm" style="border:1px solid #ddd;border-radius: 5px;padding:5px" type="number" min="1"/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="6" style="text-align: right">Due Amount&nbsp;({{ $currency }})</th>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="6" style="text-align: right">Return Amount&nbsp;({{ $currency }})</th>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                    </div>
                                        <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <h4 style="color: maroon;text-align: center"><i class="fa fa-search"></i>
                                                Search From Existing Customer <small> (optional)</small></h4>
                                            <select class="js-example-basic-single" style="width: 100%" id="old_customer" name="old_customer">
                                                <option value="">-- select customer --</option>
                                                @foreach($customer_list AS $customer)
                                                    <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}({{ $customer->customer_phone }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <h4 style="color: green;text-align: center"><i class="fa fa-plus-circle"></i> Add New Customer<small> (optional)</small></h4>
                                            <form role="form" class="form-inline">
                                                <div class="form-group mr-1">
                                                    <input type="text" maxlength="100" placeholder="Enter name" id="customer_name" name="customer_name" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" maxlength="11" placeholder="Enter phone" id="customer_phone" name="customer_phone" class="form-control">
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <input type="checkbox" id="is_invoice" class="mt-1" name="is_invoice" value="1"> Do you want to print invoice?
                                        <button class="btn btn-primary float-right mt-1" type="button" onclick="javascript:complete_sale();" id="save_data" name="save_data"><i class="fa fa-check"></i> Complete Order</button>
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
        $('#sales_corner').addClass('active');
    </script>
    <script src="{{ asset('js/custom_script.js') }}"></script>
@endsection
