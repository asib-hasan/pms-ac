<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<style>
    .header{
        text-align: center;
    }
    .header,h2,p{
        margin: 3px;
    }
</style>
<body>
<div class="header">
    <h2>{{ $store_info->store_name }}</h2>
    <p>Email: {{ $store_info->store_email }}</p>
    <p>Phone: {{ $store_info->store_phone }}</p>
    <p>{{ $store_info->store_address }}</p>
</div>

<table style="width: 100%; border-collapse: collapse; border:1px solid black;margin-top: 30px">
    <tr>
        @if($order_info->customer_wise_order_info)
        <td style="width: 50%; text-align: left; border-right: 1px solid black">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li>{{ $order_info->customer_wise_order_info->customer_info->customer_name }}</li>
                <li>Phone: {{ $order_info->customer_wise_order_info->customer_info->customer_phone }}</li>
                <li>Email: {{ $order_info->customer_wise_order_info->customer_info->customer_email }}</li>
                <li>Address: {{ $order_info->customer_wise_order_info->customer_info->customer_address }}</li>
            </ul>
        </td>
        @else
        <td style="width: 50%; text-align: left; border-right: 1px solid black">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li>Guest Customer</li>
                <li>Phone: N/A</li>
                <li>Email: N/A</li>
                <li>Address: N/A</li>
            </ul>
        </td>
        @endif
        <td style="width: 50%; text-align: right;">
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li>Order No #{{ $order_info->order_info_track_no }}</li>
                <li>Date #{{ $order_info->order_info_date }}</li>
                <li>Status #{{ $order_info->order_info_status }}</li>
                <li>Served By #{{ Auth::user()->admin_name }}</li>
            </ul>
        </td>
    </tr>
</table>

<table style="width: 100%; border-collapse: collapse;margin-top: 30px; border: 1px solid black;text-align: center">
    <thead>
    <tr>
        <td colspan="4" style="border:1px solid black;padding:5px 5px;width: 100%;background-color: #ddd;text-align: left;font-weight: bold">Order Summery</td>
    </tr>
    <tr>
        <th style="border:1px solid black;padding:5px 5px;width: 35%;background-color: #ddd;">Item Name</th>
        <th style="border:1px solid black;padding:5px 5px;width: 20%;background-color: #ddd;">Price</th>
        <th style="border:1px solid black;padding:5px 5px;width: 25%;background-color: #ddd;">Quantity</th>
        <th style="border:1px solid black;padding:5px 5px;width: 20%;background-color: #ddd;">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($order_list AS $order)
        <tr>
            <td style="border:1px solid black;padding:5px 5px">{{ $order->item_info->item_name ?? '' }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ $price = number_format($order->order_details_item_sell_price, 2) }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ $qty = $order->order_details_item_qty }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ number_format($qty*$order->order_details_item_sell_price,2) }}</td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td>Subtotal</td>
        <td>{{ $order_info->order_info_subtotal }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Discount Type</td>
        <td>
            @if($order_info->order_info_discount_type == 1)
                Flat
            @elseif($order_info->order_info_discount_type == 2)
                Percentage
            @else

            @endif
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Discount Amount</td>
        <td>{{ $order_info->order_info_discount }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Gross Total</td>
        <td>{{ number_format($order_info->order_info_total,2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Total Paid</td>
        <td>{{ number_format($order_info->order_info_total - $order_info->order_info_due,2) }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Due Amount</td>
        <td>{{ $order_info->order_info_due }}</td>
    </tr>
    </tbody>
</table>


</body>
</html>
