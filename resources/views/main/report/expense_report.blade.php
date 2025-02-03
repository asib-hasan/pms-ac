@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Generate Expense Reports</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <form method="GET" action="{{ url('report/expenses') }}">
                                        <div class="row">
                                            <label class="font-label col-md-12">Choose Start & End Date</label>
                                            <div class="col-md-3">
                                                <input type="date" class="input-sm form-control" name="start_date" value="{{ $start_date }}" placeholder="Choose start date" required/>
                                            </div>
                                            <div class="col-auto mt-2">
                                                <span>to</span>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" class="input-sm form-control" name="end_date" value="{{ $end_date }}" placeholder="Choose end date" required/>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-primary mt-md-0 mt-3" type="submit" name="btn_report"><i class="fa fa-check" aria-hidden="true"></i> Get Report</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @if ($flag == 1)
                                <div class="col-md-4">
                                    <div class="bg-success card card-body mt-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <i class="fa fa-bar-chart fa-4x"></i>
                                            </div>
                                            <div class="col-md-9 text-right">
                                                <span> Total Expenses </span>
                                                <h3 class="font-bold">{{ $currency }}&nbsp;{{ $gross_amount }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @if($flag == 1)
                                    <div style="margin-top: 20px;border-bottom: 2px solid #eee;padding: 10px;margin-bottom: 20px">
                                        <h3 style="text-align: center;">Showing Expenses Report from
                                            <b style="color: #18A689;">{{ $start_date }}</b>
                                            to
                                            <b style="color: #18A689;">{{ $end_date }}</b>
                                        </h3>
                                        <center>
                                            <a href="{{ url('report/expense/print?start=' . $start_date . '&end=' . $end_date) }}" target="_blank" class="btn btn-primary" style=""><i class="fa fa-print"></i> Print Report</a>
                                        </center>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr style="background-color: #ddd">
                                                <th>Serial No</th>
                                                <th>Expense Criteria</th>
                                                <th>Total Amount</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($expense_list AS $expense)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $expense->expense_head_info->name ?? '' }}</td>
                                                <td>{{ $currency }} {{ $expense->expense_amount }}</td>
                                                <td>{{ $expense->expense_date }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4">No records found</td>
                                            </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#reports').addClass('active');
    </script>
@endsection
