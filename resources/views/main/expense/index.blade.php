@extends('layout.sidebar')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                @include('partials.alerts')
                <div class="card">
                    <div class="card-header font-weight-bold">Expense Information  <a href="javascript:void(0)" data-toggle="modal" data-target="#add_expense" class="btn btn-primary btn-xs btn-rounded"><i class="fa fa-plus-circle"></i> Add New</a>&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target="#search" style="background: black;border: 1px solid black" class="btn btn-success btn-xs btn-rounded"><i class="fa fa-search"></i> Advance Search</a></div>
                    <div id="search" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                                <form method="GET" action="{{ url('expense') }}">
                                    @csrf
                                    <div class="card">
                                        <div class="card-header font-weight-bold">Advance Search For Expense <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="head">Expense head<b class="required_mark">*</b></label><br>
                                                        <select class="js-example-basic-single" style="width: 100%" name="expense_head_id"  required>
                                                            <option value="">Select</option>
                                                            @foreach($expense_head_list AS $achead)
                                                                <option value="{{ $achead->id }}">{{ $achead->name }}</option>
                                                            @endforeach
                                                        </select>
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
                                            <th>Expense</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($expense_list as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_head_info->name ?? '' }}</td>
                                            <td>{{ $expense->expense_amount }}</td>
                                            <td>{{ $expense->expense_date }}</td>
                                            <td>
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#edit_{{ $expense->expense_id }}"><i class="fa fa-pencil"></i> Edit</a>
                                                <div id="edit_{{ $expense->expense_id }}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ url('expense/update') }}">
                                                                @csrf
                                                                <input type="hidden" name="expense_id" value="{{ $expense->expense_id }}" />
                                                                <div class="card">
                                                                    <div class="card-header font-weight-bold">Update Expense <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="amount">Expense head<b class="required_mark">*</b></label><br>
                                                                                    <select class="js-example-basic-single" style="width: 100%" name="expense_head_id"  required>
                                                                                        <option value="">Select</option>
                                                                                        @foreach($expense_head_list AS $achead)
                                                                                            <option value="{{ $achead->id }}">{{ $achead->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="amount">Amount<b class="required_mark">*</b></label>
                                                                                    <input type="text" class="form-control" name="expense_amount" value="{{ $expense->expense_amount}}" placeholder="Enter name" required />
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label for="date">Expense Date<b class="required_mark">*</b></label>
                                                                                    <input type="text" class="form-control" name="expense_date" value="{{ $expense->expense_date }}" placeholder="Enter name" required />
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
                                        @empty
                                        <tr>
                                            <td colspan="4">No records found</td>
                                        </tr>
                                        @endforelse
                                        @if($expense_list->isNotEmpty())
                                        <tr>
                                            <td colspan="4" class="text-right font-weight-bold font-italic">Total Amount - {{ $total }} {{ $currency }}</td>
                                        </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div>
                                    {{ $expense_list->links() }}
                                </div>

                                <div id="add_expense" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ url('expense/store') }}">
                                                @csrf
                                                <div class="card">
                                                    <div class="card-header font-weight-bold">Add New Expense Information <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="expense_head">Expense Head<b class="required_mark">*</b></label>
                                                                    <select class="js-example-basic-single" style="width: 100%" name="expense_head_id"   required>
                                                                        <option value="">Select</option>
                                                                        @foreach($expense_head_list AS $achead)
                                                                            <option value="{{ $achead->id }}">{{ $achead->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="item_category_name">Amount<b class="required_mark">*</b></label>
                                                                    <input type="text" class="form-control" name="expense_amount" value="{{ old('expense_amount') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="item_category_name">Expense Date<b class="required_mark">*</b></label>
                                                                    <input type="date" class="form-control" name="expense_date" value="{{ old('expense_date') }}" required />
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
    $('#expenses').addClass('active');
</script>
@endsection
