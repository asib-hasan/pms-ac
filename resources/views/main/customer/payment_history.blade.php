@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Customer Due Payment Paid History For <span style="color: maroon">({{ $customer_info->customer_name }})</span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Paid Amount</th>
                                                <th>Received By</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($customer_paid_list AS $cpl)
                                            <tr>
                                                <td>{{ $cpl->dp_date }}</td>
                                                <td>{{ $currency }}&nbsp;{{ $cpl->dp_amount }}</td>
                                                <td>{{ $cpl->admin_info->admin_name ?? '' }}</td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No records found</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div>
                                        {{ $customer_paid_list->links() }}
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
