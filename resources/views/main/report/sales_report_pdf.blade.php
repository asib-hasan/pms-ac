<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>{{ $store_info->store_name }}</h1>
    <p>{{ $store_info->store_address }}</p>
    <p>Phone: {{ $store_info->store_phone }}</p>
    <p>Sales Report from {{ $start_date }} to {{ $end_date }}</p>
</div>

<table class="table">
    <thead>
    <tr>
        <th style="border:1px solid black;padding:5px 5px;width: 5%">#</th>
        <th style="border:1px solid black;padding:5px 5px;width: 30%">Item Name</th>
        <th style="border:1px solid black;padding:5px 5px;width: 15%">Sell Quantity</th>
        <th style="border:1px solid black;padding:5px 5px;width: 15%">Sell Price</th>
        <th style="border:1px solid black;padding:5px 5px;width: 15%">Total Amount</th>
        <th style="border:1px solid black;padding:5px 5px;width: 15%">Sell Date</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($order_list AS $order)
        <tr>
            <td style="border:1px solid black;padding:5px 5px">{{ $loop->index + 1 }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ $order->item_info->item_name }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ $order->order_details_item_qty }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ number_format($order->order_details_item_sell_price, 2) }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ number_format($order->order_details_item_sell_price*$order->order_details_item_qty, 2) }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ $order->order_details_date }}</td>
        </tr>
    @empty
        <tr>
            <td style="border:1px solid black;padding:5px 5px" colspan="6">No records found</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
