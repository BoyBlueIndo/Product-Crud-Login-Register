<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        h2, h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
        th {
            background: #eee;
        }
        .right {
            text-align: right;
        }
        .total-box {
            margin-top: 15px;
            padding: 10px;
            border: 2px solid #000;
        }
        .trx-info {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<h2>TRANSACTION SUMMARY</h2>

<p>
    <strong>Name:</strong> {{ $user->name }}<br>
    <strong>Email:</strong> {{ $user->email }}<br>
    <strong>Printed At:</strong> {{ now()->format('d M Y H:i') }}
</p>

@foreach ($transactions as $trx)

    <h3>Transaction #{{ $trx->id }}</h3>

    {{-- TRANSACTION INFO --}}
    <div class="trx-info">
        <strong>Date:</strong> {{ $trx->created_at->format('d M Y H:i') }}<br>
        <strong>Payment Method:</strong> {{ strtoupper($trx->payment_method) }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th width="70">Qty</th>
                <th width="110">Price</th>
                <th width="120">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $trxTotal = 0;
            @endphp

            @foreach ($trx->items as $item)
                @php
                    $subtotal = $item->product->price * $item->quantity;
                    $trxTotal += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">
                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                    </td>
                    <td class="right">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- CASH DETAIL --}}
    @if ($trx->payment_method === 'cash')
        <table style="margin-top:8px">
            <tr>
                <td><strong>Cash Paid</strong></td>
                <td class="right">
                    Rp {{ number_format($trx->cash_paid, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td><strong>Change</strong></td>
                <td class="right">
                    Rp {{ number_format($trx->change_amount, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    @endif

@endforeach

<div class="total-box">
    <strong>TOTAL ITEMS:</strong> {{ $totalItems }} <br>
    <strong>GRAND TOTAL:</strong>
    Rp {{ number_format($grandTotal, 0, ',', '.') }}
</div>

</body>
</html>