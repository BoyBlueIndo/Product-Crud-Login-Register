@extends('layout.app')

@section('title', 'My Transactions')

@section('content')
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">My Transactions</h2>

        <div class="btn-group">
            <a href="{{ route('user.index') }}" class="btn btn-outline-secondary btn-sm">
                Back to Store
            </a>
            {{-- <a href="{{ route('user.transactions.summary-pdf') }}" 
               class="btn btn-primary btn-sm">
                Download Summary
            </a> --}}
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse ($transactions as $trx)
        <div class="card mb-4 shadow-sm">

            <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                <span>
                    Transaction #{{ $trx->id }}
                </span>

                <span class="badge bg-info text-dark text-uppercase">
                    {{ str_replace('_', ' ', $trx->payment_method) }}
                </span>
            </div>

            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th width="120">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($trx->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>x{{ $item->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-muted">
                <div class="d-flex justify-content-between">
                    <small>
                        Payment: {{ ucwords($trx->payment_method) }}
                    </small>
                    <small>
                        {{ $trx->created_at->format('d M Y, H:i') }}
                    </small>
                </div>

                @if ($trx->payment_method === 'cash')
                    <div class="mt-1">
                        <small>
                            Cash Paid:
                            <strong>Rp {{ number_format($trx->cash_paid,0,',','.') }}</strong>
                            |
                            Change:
                            <strong class="text-success">
                                Rp {{ number_format($trx->change_amount,0,',','.') }}
                            </strong>
                        </small>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            You have no transactions.
        </div>
    @endforelse

</div>
@endsection