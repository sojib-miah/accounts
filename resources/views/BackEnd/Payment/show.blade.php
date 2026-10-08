@extends('BackEnd.Layouts.layout')

@section('title', 'Payment Details')

@section('content')
    <div class="mt-5">
        <div class="p-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">Payment Details</h4>
                    <p class="text-muted mb-0">Transaction #{{ $payment->id }}</p>
                </div>
                <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back
                </a>
            </div>
            <div class="row">
                {{-- Payment --}}
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header">
                            <strong>Payment Information</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Payment ID</th>
                                    <td>{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction ID</th>
                                    <td>{{ $payment->tran_id }}</td>
                                </tr>
                                <tr>
                                    <th>Validation ID</th>
                                    <td>{{ $payment->val_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>
                                        {{ number_format($payment->amount, 2) }}
                                        {{ $payment->currency }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($payment->status === 'Paid')
                                            <span class="badge bg-success">
                                                Paid
                                            </span>
                                        @elseif($payment->status === 'Pending')
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                        @elseif($payment->status === 'Failed')
                                            <span class="badge bg-danger">
                                                Failed
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td>
                                        {{ $payment->payment_method ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Paid At</th>
                                    <td>
                                        {{ $payment->paid_at?->format('d M Y h:i A') ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>
                                        {{ $payment->created_at->format('d M Y h:i A') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Customer --}}
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header">
                            <strong>Customer & Package</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">User</th>
                                    <td>{{ $payment->user?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $payment->user?->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td>{{ $payment->company?->name ?? 'No Company' }}</td>
                                </tr>
                                <tr>
                                    <th>Package</th>
                                    <td>{{ $payment->package?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Package Price</th>
                                    <td>
                                        {{ number_format($payment->package?->price ?? 0, 2) }}
                                        BDT
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{-- Gateway Response --}}
                    @if ($payment->gateway_response)
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <strong>Gateway Response</strong>
                            </div>
                            <div class="card-body">
                                <pre class="bg-light p-3" style="max-height:400px; overflow:auto;">{{ json_encode(json_decode($payment->gateway_response), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
