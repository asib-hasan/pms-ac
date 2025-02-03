@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Generate Reports</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3" style="height: 50px;">
                                    <a href="{{ url('report/sales') }}" class="btn btn-success btn-block"><i class="fa fa-bar-chart"></i> Sales Report</a>
                                </div>
                                <div class="col-md-3" style="height: 50px;">
                                    <a href="{{ url('report/expenses') }}" class="btn btn-primary btn-block"><i class="fa fa-money"></i> Expense Report</a>
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
