<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
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
    <p>Expense Report from {{ $start_date }} to {{ $end_date }}</p>
</div>

<table class="table">
    <thead>
    <tr>
        <th style="border:1px solid black;padding:5px 5px;width: 5%">#</th>
        <th style="border:1px solid black;padding:5px 5px;width: 55%">Expense Criteria</th>
        <th style="border:1px solid black;padding:5px 5px;width: 20%">Total Amount</th>
        <th style="border:1px solid black;padding:5px 5px;width: 20%">Date</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($expense_list AS $expense)
        <tr>
            <td style="border:1px solid black;padding:5px 5px">{{ $loop->index + 1 }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ $expense->expense_head_info->name ?? '' }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ number_format($expense->expense_amount, 2) }}</td>
            <td style="border:1px solid black;padding:5px 5px">{{ $expense->expense_date }}</td>
        </tr>
    @empty
        <tr>
            <td style="border:1px solid black;padding:5px 5px" colspan="4">No records found</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
