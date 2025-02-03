@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.alerts')
                    <div class="card">
                        <div class="card-header font-weight-bold">Customer Order History For <span style="color: maroon">({{ $customer_info->customer_name }})</span></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr style="background-color: #ddd">
                                                <th>Invoice ID</th>
                                                <th>Total Amount</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($customer_order_list AS $order)
                                            <tr>
                                                <td>
                                                    <a href="javascript:void(0);" style="font-weight: bold" data-toggle="modal" data-target="#order_{{ $order->cwo_order_id }}">{{ $order->cwo_order_id }}</a>
                                                    <div id="order_{{ $order->cwo_order_id }}" class="modal fade" role="dialog">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content" style="border: 0px">
                                                                <div class="card">
                                                                    <div class="card-header font-weight-bold">Order History - Invoice ID - <span style="color: maroon">{{ $order->cwo_order_id }}</span> <button type="button" class="close" data-dismiss="modal" style="color: #C33712;opacity: 1;">&times;</button></div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-striped table-bordered table-hover">
                                                                                        <thead>
                                                                                        <tr style="background-color: #ddd">
                                                                                            <th>Item Name</th>
                                                                                            <th>Price</th>
                                                                                            <th>Qty</th>
                                                                                            <th>Total</th>
                                                                                        </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        @php
                                                                                            $order_details_info = \App\Models\OrderDetails::where('order_info_id',$order->order_info_id)->get();
                                                                                        @endphp
                                                                                        @foreach($order_details_info as $odi)
                                                                                            <tr>
                                                                                                <td>{{ $odi->item_info->item_name ?? '' }}</td>
                                                                                                <td>{{ $currency }}&nbsp;{{ $odi->order_details_item_sell_price }}</td>
                                                                                                <td>{{ $odi->order_details_item_qty }}</td>
                                                                                                <td>{{ $currency }}&nbsp;{{ number_format(($odi->order_details_item_sell_price * $odi->order_details_item_qty), 2, '.', '') }}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                        </tbody>
                                                                                        <tr>
                                                                                            <td colspan="3" class="text-right"><b>Sub Total</b></td>
                                                                                            <td>{{ $currency }}&nbsp;{{ $order->order_info_subtotal }}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3" class="text-right"><b>Discount Type</b></td>
                                                                                            <td>{{ $order->order_info_discount_type == 1 ? "(Flat Rate)" : "(Percent)" }}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3" class="text-right"><b>Discount Amount</b></td>
                                                                                            @if($order->order_info_discount_type == 1)
                                                                                                <td>{{ $currency }}&nbsp;{{ $order->order_info_discount }}</td>
                                                                                            @else
                                                                                                <td>{{ $order->order_info_discount }}&nbsp; %</td>
                                                                                            @endif
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3" class="text-right"><b>Gross Total</b></td>
                                                                                            <td>{{ $currency }}&nbsp;{{ $order->order_info_total }}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3" class="text-right"><b>Total Paid</b></td>
                                                                                            <td>{{ $currency }}&nbsp;{{ number_format(($order->order_info_total - $order->order_info_due), 2) }}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan="3" class="text-right"><b>Due Amount</b></td>
                                                                                            <td>{{ $currency }}&nbsp; {{ number_format($order->order_info_due,2) }}</td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $currency }}&nbsp;{{ $order->cwo_order_total }}</td>
                                                <td>{{ $order->cwo_date }}</td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No records found</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
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
