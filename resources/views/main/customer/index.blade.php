@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Customers Information <a href="javascript:void(0)" data-toggle="modal" data-target="#add_customer" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-plus-circle"></i> Add New</a>&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#search" class="btn btn-success btn-xs btn-rounded" style="background: black;border: 1px solid black"><i class="fa fa-search"></i> Advance Search</a></div>
                        <div id="search" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form method="GET" action="{{ url('/customer') }}">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header font-weight-bold">Advance Search For Customers <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="q">Search Keyword<b class="required_mark">*</b></label>
                                                            <input type="text" class="form-control" name="q" placeholder="Enter customer name or phone number or type any word" required />
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
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($customer_list AS $customer)
                                            <tr>
                                                <td>
                                                    {{ $customer->customer_name }} <br>

                                                    @php
                                                        $total_paid_amount = \App\Models\DuePaid::where('dp_customer_id',$customer->customer_id)->sum('dp_amount');
                                                        $total_due_amount = (\App\Models\CustomerWiseOrder::where('cwo_customer_id',$customer->customer_id)->sum('cwo_due') - $total_paid_amount);
                                                    @endphp
                                                    @if ($total_due_amount > 0)
                                                        <span style="color: maroon;font-weight: bold;font-size: 14px;">{{ $currency }}&nbsp;{{ $total_due_amount }} due</span>
                                                    @endif
                                                </td>
                                                <td>{{ $customer->customer_phone }}</td>
                                                <td>{{ $customer->customer_email }}</td>
                                                <td>{{ $customer->customer_address }}</td>
                                                <td>{{ $customer->customer_status }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_{{ $customer->customer_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                                    <a href="{{ url('/customer/orders?id=' . $customer->customer_id) }}" style="color: blue;"><i class="fa fa-cart-plus"></i> Orders</a><br>
                                                    <a href="{{ url('/customer/due/payments?id=' . $customer->customer_id) }}" style="color: magenta;"><i class="fa fa-history"></i> Due Payment History</a><br>
                                                    @if ($total_due_amount > 0)
                                                    <a href="javascript:void(0);" style="color: red;font-weight: bold;" data-toggle="modal" data-target="#due_{{ $customer->customer_id }}"><i class="fa fa-check"></i> Pay Due Amount</a>
                                                    @endif
                                                    <div id="edit_{{ $customer->customer_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content" style="border: 0px;">
                                                                <form method="POST" action="{{ url('/customer/update') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}" />
                                                                    <div class="card">
                                                                        <div class="card-header font-weight-bold">Update Customer Information <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="customer_name">Name<b class="required_mark">*</b></label>
                                                                                        <input type="text" maxlength="100" class="form-control" name="customer_name" value="{{ $customer->customer_name }}" placeholder="Enter name" required />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="customer_phone">Phone<b class="required_mark">*</b></label>
                                                                                        <input type="text" maxlength="11" class="form-control" name="customer_phone" value="{{ $customer->customer_phone }}" placeholder="Enter phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="customer_email">Email</label>
                                                                                        <input type="email" maxlength="50" class="form-control" name="customer_email" value="{{ $customer->customer_email }}" placeholder="Enter email" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="customer_address">Address</label>
                                                                                        <input type="text" maxlength="255" class="form-control" name="customer_address" value="{{ $customer->customer_address }}" placeholder="Enter address" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="customer_address">Status</label>
                                                                                        <select name="customer_status" class="form-control" required>
                                                                                            <option value="">--Select--</option>
                                                                                            <option value="Active" @selected($customer->customer_status == 'Active')>Active</option>
                                                                                            <option value="Inactive" @selected($customer->customer_status == 'Inactive')>Inactive</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <button type="submit" class="btn btn-primary" name="btn_edit_customer"><i class="fa fa-check"></i> Submit</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="due_{{ $customer->customer_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content" style="border: 0px;">
                                                                <form method="POST" action="{{ url('customer/due/payment/update') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}" />
                                                                    <input type="hidden" name="due_amount" value="{{ $total_due_amount }}" />
                                                                    <div class="card">
                                                                        <div class="card-header font-weight-bold">Pay Due Amount <i class="fa fa-angle-double-right"></i><span style="color: maroon">Total Due {{ $currency }}&nbsp;{{ $total_due_amount }}</span> <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="pay_amount">Enter Amount<b class="required_mark">*</b></label>
                                                                                        <input type="text" maxlength="20" class="form-control" name="pay_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Enter amount" required />
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label for="pay_amount">Date<b class="required_mark">*</b></label>
                                                                                        <input type="date" class="form-control" name="date" required />
                                                                                    </div>
                                                                                    <button type="submit" class="btn btn-primary" name="btn_pay_due"><i class="fa fa-check"></i> Submit</button>
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
                                                    <td colspan="6">No records found</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        {{ $customer_list->links() }}
                                    </div>
                                    <div id="add_customer" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content" style="border: 0px;">
                                                <form method="POST" action="{{ url('/customer/store') }}">
                                                    @csrf
                                                    <div class="card">
                                                        <div class="card-header font-weight-bold">Add New Customer Information <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="customer_name">Name<b class="required_mark">*</b></label>
                                                                        <input type="text" maxlength="100" class="form-control" name="customer_name" value="{{ old('customer_name') }}" placeholder="Enter name" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="customer_phone">Phone<b class="required_mark">*</b></label>
                                                                        <input type="text" maxlength="11" class="form-control" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Enter phone" required />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="customer_email">Email</label>
                                                                        <input type="email" maxlength="50" class="form-control" name="customer_email" value="{{ old('customer_email') }}" placeholder="Enter email" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="customer_address">Address</label>
                                                                        <input type="text" maxlength="255" class="form-control" name="customer_address" value="{{ old('customer_address') }}" placeholder="Enter address" />
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
        $('#customers').addClass('active');
    </script>
@endsection
