@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Purchase History For <span style="color: maroon">({{ $company_info->item_company_name }})</span> <a href="javascript:void(0)" class="btn btn-primary btn-xs btn-rounded" data-toggle="modal" data-target="#add_company_user" class=""><i class="fa fa-plus-circle"></i> Add New</a></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4" style="height: 50px;border: 1px solid #F3F3F4;margin-bottom: 30px">
                                    <h4 style="font-weight: bold;color: blue;padding: 10px">
                                        Total Purchase Amount {{ $total_purchase != "" ? number_format($total_purchase, 2, '.', '') : '0.00' }} {{ $currency }}
                                    </h4>
                                </div>
                                <div class="col-md-4" style="height: 50px;border: 1px solid #F3F3F4;margin-bottom: 30px">
                                    <h4 style="font-weight: bold;color: green;padding: 10px">
                                        Total Bill Paid Amount {{ $total_paid_amount != "" ? number_format($total_paid_amount, 2, '.', '') : '0.00' }} {{ $currency }}
                                    </h4>
                                </div>
                                <div class="col-md-4" style="height: 50px;border: 1px solid #F3F3F4;margin-bottom: 30px">
                                    <h4 style="font-weight: bold;color: maroon;padding: 10px">
                                        Total Due Amount {{ $total_due_amount != "" ? number_format($total_due_amount, 2, '.', '') : '0.00' }} {{ $currency }}
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr style="background-color: #ddd">
                                                <th>Date</th>
                                                <th>Invoice No</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Due Amount</th>
                                                <th>Mode</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($purchase_list AS $obj)
                                            @php
                                                $total_bill_paid = \App\Models\PurchaseBillPaid::where('bp_purchase_id',$obj->purchase_id)->sum('bp_amount');
                                                $due_bill_amount = number_format(($obj->purchase_total_amount - $total_bill_paid ), 2);
                                                $total_due_bill = str_replace(',', '', $due_bill_amount);
                                            @endphp
                                            <tr>
                                                <td>{{ $obj->purchase_date }}</td>
                                                <td><b>{{ $obj->purchase_invoice_no }}</b></td>
                                                <td>{{ $obj->purchase_total_amount }}&nbsp;{{ $currency }}</td>
                                                @if ($obj->purchase_mode == 'Paid')
                                                    <td>{{ $obj->purchase_total_amount }}&nbsp;{{ $currency }}</td>
                                                    <td>0.00 {{ $currency }}</td>
                                                @else
                                                <td>{{ $total_bill_paid }}&nbsp;{{ $currency }}</td>
                                                <td>{{ $total_due_bill }} {{ $currency }}</td>
                                                @endif
                                                <td>{{ $obj->purchase_mode }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#bill_{{ $obj->purchase_id }}"><i class="fa fa-history"></i> Billing History</a>
                                                    <div id="bill_{{ $obj->purchase_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content" >
                                                                <div class="card">
                                                                    <div class="card-header font-weight-bold">Billing History For <span style="color: maroon">Invoice No - <?php echo $obj->purchase_invoice_no; ?></span> <button type="button" class="close" data-dismiss="modal" >&times;</button></div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12 table-responsive">
                                                                                @php
                                                                                $bill_paid = \App\Models\PurchaseBillPaid::where('bp_purchase_id',$obj->purchase_id)->orderByDesc('bp_date')->get();
                                                                                @endphp
                                                                                <table class="table table-bordered">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>Date</th>
                                                                                        <th>Amount</th>
                                                                                        <th>Paid By</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @foreach($bill_paid AS $bill)
                                                                                    <tr>
                                                                                        <td>{{ $bill->bp_date }}</td>
                                                                                        <td>{{ $bill->bp_amount }} {{ $currency }} </td>
                                                                                        <td>{{ $bill->admin_info->admin_name ?? '' }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($obj->purchase_mode == 'Due')
                                                    <br>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#pay_bill{{ $obj->purchase_id }}" style="color: maroon"><i class="fa fa-check"></i> Pay Due Amount</a>
                                                    <div id="pay_bill{{ $obj->purchase_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content" >
                                                                <form method="POST" action="{{ url('/company/purchase/due/update') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="bp_purchase_id" value="{{ $obj->purchase_id }}" />
                                                                    <input type="hidden" name="bp_company_id" value="{{ $company_info->item_company_id }}" />
                                                                    <input type="hidden" name="total_due_amount" value="{{ $total_due_amount }}" />
                                                                    <div class="card">
                                                                        <div class="card-header font-weight-bold"><i class="fa fa-money"></i> Pay Due Amount <i class="fa fa-angle-double-right"></i> <span style="color: maroon">Invoice No - <?php echo $obj->purchase_invoice_no; ?></span> <button type="button" class="close" data-dismiss="modal" >&times;</button></div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="col-md-12">
                                                                                        <h4 style="font-weight: bold">Total Due Amount - {{ $total_due_bill }}&nbsp;{{ $currency }}</h4>
                                                                                        <div class="form-group">
                                                                                            <label for="bp_amount">Amount<b class="required_mark">*</b></label>
                                                                                            <input type="text" class="form-control" name="bp_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="" placeholder="Enter Amount" required />
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="bp_amount">Date<b class="required_mark">*</b></label>
                                                                                            <input type="date" class="form-control" name="bp_date" id="bp_date" required />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-12">
                                                                                        <button type="submit" class="btn btn-primary" name="btn_pay_bill"><i class="fa fa-check"></i> Submit</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div id="add_company_user" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px">
                                                <form method="POST" action="{{ url('/company/purchase/store') }}">
                                                    @csrf
                                                    <input type="hidden" name="purchase_company_id" value="{{ $company_info->item_company_id }}" />
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add New Purchase From <span style="color: maroon"><?php echo $company_info->company_name; ?></span> <button type="button" class="close" data-dismiss="modal" >&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="purchase_date">Date<b class="required_mark">*</b></label>
                                                                        <input type="date" class="form-control" name="purchase_date" value="{{ old('purchase_date') }}" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="purchase_invoice_no">Invoice No<b class="required_mark">*</b></label>
                                                                        <input type="text" maxlength="100" class="form-control" name="purchase_invoice_no" value="{{ old('purchase_invoice_no') }}" placeholder="Enter Invoice No" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="purchase_total_amount">Total Amount<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="purchase_total_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('purchase_total_amount') }}" placeholder="Enter Total Amount" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="purchase_mode">Purchase Mode<b class="required_mark">*</b></label>
                                                                        <select class="form-control" name="purchase_mode" required>
                                                                            <option value="">-- select --</option>
                                                                            <option value="Paid" @selected(old('purchase_mode')=='Paid')>Paid</option>
                                                                            <option value="Due" @selected(old('purchase_mode')=='Due')>Due</option>
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
        $('#companies').addClass('active');
    </script>
@endsection
