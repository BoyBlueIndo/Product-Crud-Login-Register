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
            <a href="{{ route('user.transactions.summary-pdf') }}" 
               class="btn btn-primary btn-sm">
                Download Summary
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse ($transactions as $trx)
        <div class="card mb-4 shadow-sm">

            <div class="card-header fw-semibold">
                Transaction #{{ $trx->id }}
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

            <div class="card-footer text-muted text-end">
                {{ $trx->created_at->format('d M Y, H:i') }}
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            You have no transactions.
        </div>
    @endforelse

</div>
@endsection