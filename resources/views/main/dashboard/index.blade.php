@extends('layout.sidebar')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @include('partials.alerts')
                    <div class="row">
                        @if(Auth::user()->admin_type != 2)
                            <div class="col-md-4 mb-4 transparent">
                                <div class="card card-tale">
                                    <div class="card-body">
                                        <p class="mb-4">Today Sales Amount</p>
                                        <p class="fs-30 mb-2">{{ $currency }}&nbsp;{{ $today_sale }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4 transparent">
                                <div class="card card-tale">
                                    <div class="card-body">
                                        <p class="mb-4">Today Due Amount</p>
                                        <p class="fs-30 mb-2">{{ $currency }}&nbsp;{{ $today_due }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4 transparent">
                                <div class="card card-tale">
                                    <div class="card-body">
                                        <p class="mb-4">Today Expenses Amount</p>
                                        <p class="fs-30 mb-2">{{ $currency }}&nbsp;{{ $today_expense }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4 transparent">
                                <div class="card card-tale">
                                    <div class="card-body">
                                        <p class="mb-4">Total Sales Amount</p>
                                        <p class="fs-30 mb-2">{{ $currency }}&nbsp;{{ $total_sale }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4 transparent">
                                <div class="card card-tale">
                                    <div class="card-body">
                                        <p class="mb-4">Total Due Amount</p>
                                        <p class="fs-30 mb-2">{{ $currency }}&nbsp;{{ $total_due }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4 transparent">
                                <div class="card card-tale">
                                    <div class="card-body">
                                        <p class="mb-4">Total Expenses Amount</p>
                                        <p class="fs-30 mb-2">{{ $currency }}&nbsp;{{ $total_expense_amount }}</p>
                                    </div>
                                </div>
                            </div>
                       @endif
                    </div>
                </div>
            </div>
            <div class="card rounded-0">
                <div class="card-header font-weight-bold">
                    Low Quantity Items
                </div>
                <div class="card-body">
                    @forelse($low_quantity_items AS $item)
                        <a href="javascript:void(0)"
                           style="font-weight:bold;padding: 5px;background: white;border: 1px solid maroon;color: black;border-radius: 50px;margin-bottom: 5px;display: inline-block">
                            {{ $item->item_name }} <span class="badge bg-danger" style="background: maroon;color: white;font-size: 16px;"> {{ $item->item_quantity }}</span></a>
                        @if($loop->last)
                            <a href="{{ url('item/reorder') }}" class="btn btn-primary btn-rounded"><i class="fa fa-check"></i> View All</a>
                        @endif
                    @empty
                        <p style="font-weight: bold; color: maroon">No data found</p>
                    @endforelse
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header font-weight-bold">Recent Sales</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover margin bottom">
                        <thead>
                        <tr style="background-color: #ddd">
                            <th>Order No</th>
                            <th class="text-center hidden-sm hidden-xs">Date</th>
                            <th class="text-center">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($order_list AS $order)
                            <tr>
                                <td>
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#order_{{ $order->order_info_id }}"><b style="color: #CC3333;">{{ $order->order_info_track_no }}</b></a>
                                    <div id="order_{{ $order->order_info_id }}" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content">
                                                <form method="POST" action="">
                                                    <div class="card mt-3">
                                                        <div class="card-header font-weight-bold">Invoice ID -
                                                            <a href="{{ url('print/pos/invoice?ot=' . $order->order_info_track_no) }}" style="color: maroon" target="_blank">{{ $order->order_info_track_no }}</a>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="table-responsive">
                                                                        @php
                                                                            $customer = \App\Models\CustomerWiseOrder::where('cwo_order_id',$order->order_info_track_no)->first();
                                                                        @endphp
                                                                        @if($customer)
                                                                            <table class="table table-bordered">
                                                                                <thead>
                                                                                <tr style="background-color: #ddd">
                                                                                    <th colspan="2">Customer Information</th>
                                                                                </tr>
                                                                                <tr style="background-color: #ddd">
                                                                                    <th>Name</th>
                                                                                    <th>Phone</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>{{ $customer->customer_info->customer_name ?? '' }}</td>
                                                                                    <td>{{ $customer->customer_info->customer_phone ?? '' }}</td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        @endif
                                                                    </div>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-hover">
                                                                            <thead>
                                                                                <tr style="background-color: #ddd">
                                                                                    <th colspan="4">Order Details</th>
                                                                                </tr>
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
                                                                            <tfoot>
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
                                                                                <td>{{ $currency }}&nbsp; {{ $order->order_info_due }}</td>
                                                                            </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center hidden-sm hidden-xs">{{ $order->order_info_date }}</td>
                                <td class="text-center">{{ $currency }}&nbsp;{{ $order->order_info_total }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No data found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#dashboard').addClass('active');
    </script>
@endsection
