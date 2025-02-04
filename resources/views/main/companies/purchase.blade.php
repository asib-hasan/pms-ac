@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Accounts Details For <span style="color: maroon">({{ $company_info->item_company_name }})</span>
                            <a href="javascript:void(0)" class="btn btn-primary btn-xs btn-rounded" data-toggle="modal" data-target="#add_credit"><i class="fa fa-plus-circle"></i> Credit</a>
                            <a href="javascript:void(0)" class="btn btn-primary btn-xs btn-rounded" data-toggle="modal" data-target="#add_debit"><i class="fa fa-minus-circle"></i> Debit</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4" style="height: 50px;border: 1px solid #F3F3F4;margin-bottom: 30px">
                                    <h4 style="font-weight: bold;color: blue;padding: 10px">
                                        Total Debit Amount {{ $total_debit_amount != "" ? number_format($total_debit_amount, 2, '.', '') : '0.00' }} {{ $currency }}
                                    </h4>
                                </div>
                                <div class="col-md-4" style="height: 50px;border: 1px solid #F3F3F4;margin-bottom: 30px">
                                    <h4 style="font-weight: bold;color: green;padding: 10px">
                                        Total Credit Amount {{ $total_credit_amount != "" ? number_format($total_credit_amount, 2, '.', '') : '0.00' }} {{ $currency }}
                                    </h4>
                                </div>
                                <div class="col-md-4" style="height: 50px;border: 1px solid #F3F3F4;margin-bottom: 30px">
                                    <h4 style="font-weight: bold;color: maroon;padding: 10px">
                                        @php $current_balance = $total_credit_amount - $total_debit_amount; @endphp
                                        Current Balance {{ number_format($total_credit_amount - $total_debit_amount, 2, '.', '') }} {{ $currency }}
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tbody>
                                            @foreach($purchase_list AS $i)
                                            @if($i->mode == 'Debit')
                                                <tr>
                                                    <td colspan="2" style="background-color: #51a2ef;font-weight: bold">Debit</td>
                                                    <td style="background-color: #64b202;font-weight: bold">Balance - {{ $current_balance + $i->amount }}</td> @php $current_balance = $current_balance + $i->amount @endphp
                                                </tr>
                                                <tr>
                                                    <td>Date</td>
                                                    <td>Payment Type</td>
                                                    <td>Amount</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $i->purchase_date }}</td>
                                                    <td>
                                                        {{
                                                            $i->payment_type == '1' ? 'Cash' :
                                                            ($i->payment_type == '2' ? 'Mobile Banking' : 'Bank Transfer')
                                                        }}
                                                    </td>
                                                    <td>{{ $i->amount }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="2" style="background-color: #51a2ef;font-weight: bold">Credit</td>
                                                    <td style="background-color: #64b202;font-weight: bold">Balance - {{ $current_balance - $i->amount }}</td> @php $current_balance = $current_balance - $i->amount @endphp
                                                </tr>
                                                <tr>
                                                    <td>Date</td>
                                                    <td>Invoice No.</td>
                                                    <td>Amount</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $i->purchase_date }}</td>
                                                    <td>{{ $i->purchase_invoice_no }}</td>
                                                    <td>{{ $i->amount }}</td>
                                                </tr>
                                            @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="add_credit" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px">
                                                <form method="POST" action="{{ url('/company/purchase/credit/store') }}">
                                                    @csrf
                                                    <input type="hidden" name="purchase_company_id" value="{{ $company_info->item_company_id }}" />
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add Credit <button type="button" class="close" data-dismiss="modal" >&times;</button></div>
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
                                                                        <label for="purchase_mode">Payment Type<b class="required_mark">*</b></label>
                                                                        <select class="form-control" name="payment_type" required>
                                                                            <option value="">-- select --</option>
                                                                            <option value="1" @selected(old('payment_type')=='1')>Cash</option>
                                                                            <option value="2" @selected(old('payment_type')=='2')>Mobile Banking</option>
                                                                            <option value="3" @selected(old('payment_type')=='3')>Bank Transfer</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="purchase_total_amount">Amount<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('purchase_total_amount') }}" placeholder="Enter Total Amount" required />
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

                                    <div id="add_debit" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px">
                                                <form method="POST" action="{{ url('/company/purchase/debit/store') }}">
                                                    @csrf
                                                    <input type="hidden" name="purchase_company_id" value="{{ $company_info->item_company_id }}" />
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add Debit <button type="button" class="close" data-dismiss="modal" >&times;</button></div>
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
                                                                        <label for="purchase_total_amount">Amount<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('purchase_total_amount') }}" placeholder="Enter Total Amount" required />
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

                                    <div id="add_credit" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px">
                                                <form method="POST" action="{{ url('/company/purchase/credit/store') }}">
                                                    @csrf
                                                    <input type="hidden" name="purchase_company_id" value="{{ $company_info->item_company_id }}" />
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add Credit <button type="button" class="close" data-dismiss="modal" >&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Date<b class="required_mark">*</b></label>
                                                                        <input type="date" class="form-control" name="purchase_date" value="{{ old('purchase_date') }}" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Payment Type<b class="required_mark">*</b></label>
                                                                        <select class="form-control" name="payment_type" required>
                                                                            <option value="">-- select --</option>
                                                                            <option value="1" @selected(old('payment_type')=='1')>Cash</option>
                                                                            <option value="2" @selected(old('payment_type')=='2')>Mobile Banking</option>
                                                                            <option value="3" @selected(old('payment_type')=='3')>Bank Transfer</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="purchase_total_amount">Amount<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('purchase_total_amount') }}" placeholder="Enter Total Amount" required />
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

                                    <div id="add_debit" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px">
                                                <form method="POST" action="{{ url('/company/purchase/debit/store') }}">
                                                    @csrf
                                                    <input type="hidden" name="purchase_company_id" value="{{ $company_info->item_company_id }}" />
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add Debit <button type="button" class="close" data-dismiss="modal" >&times;</button></div>
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
                                                                        <label for="purchase_total_amount">Amount<b class="required_mark">*</b></label>
                                                                        <input type="text" class="form-control" name="purchase_total_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('purchase_total_amount') }}" placeholder="Enter Total Amount" required />
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
