@extends('BackEnd.Layouts.layout')

@section('title', 'Payment Result')

@section('content')
    <div class="container py-5">
        <div class="card mt-5 shadow-sm mx-auto" style="max-width: 650px;">
            <div class="card-body p-4 text-center">

                @if (session('info'))
                    <div class="alert alert-info">
                        {{ session('info') }}
                    </div>
                @endif

                @if ($payment->status === 'Paid')
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                    <h3>Payment Successful!</h3>
                    <p>Your subscription payment has been completed.</p>
                @elseif($payment->status === 'Pending')
                    <h3>Payment Pending</h3>
                    <p>Your payment is awaiting confirmation.</p>
                @elseif($payment->status === 'Cancelled')
                    <h3>Payment Cancelled</h3>
                    <p>You cancelled the payment.</p>
                @else
                    <h3>Payment Not Completed</h3>
                    <p>Your payment status is {{ $payment->status }}.</p>
                @endif

                <hr>

                <div class="text-start">
                    <p>
                        <strong>Transaction ID:</strong>
                        {{ $payment->tran_id }}
                    </p>
                    <p>
                        <strong>Package:</strong>
                        {{ $payment->package->name ?? 'N/A' }}
                    </p>
                    <p>
                        <strong>Amount:</strong>
                        BDT {{ number_format((float) $payment->amount, 2) }}
                    </p>
                    <p>
                        <strong>Payment Method:</strong>
                        {{ $payment->payment_method ?? 'Not available' }}
                    </p>
                    <p>
                        <strong>Payment Date:</strong>
                        {{ $payment->paid_at?->format('d M Y, h:i A') ?? 'Not paid' }}
                    </p>
                    <p>
                        <strong>Status:</strong>
                        <span
                            class="badge
                        {{ $payment->status === 'Paid'
                            ? 'bg-success'
                            : ($payment->status === 'Pending'
                                ? 'bg-warning text-dark'
                                : 'bg-danger') }}">
                            {{ $payment->status }}
                        </span>
                    </p>
                </div>

                <a href="{{ route('dashboard.index') }}" class="btn btn-primary mt-3">
                    Go to Dashboard
                </a>

            </div>
        </div>
    </div>
@endsection
