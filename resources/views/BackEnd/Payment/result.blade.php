<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Result</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-body text-center p-5">
                            @if ($payment->status === 'Paid')
                                <div class="text-success mb-3">
                                    <i class="fas fa-check-circle fa-4x"></i>
                                </div>
                                <h3>Payment Successful</h3>
                                <p class="text-muted">Your package has been activated successfully.</p>
                            @elseif($payment->status === 'Pending')
                                <h3>Payment Pending</h3>
                            @else
                                <h3>Payment {{ $payment->status }}</h3>
                            @endif
                            <hr>
                            <div class="text-start">
                                <p>
                                    <strong>Transaction:</strong>
                                    {{ $payment->tran_id }}
                                </p>
                                <p>
                                    <strong>Package:</strong>
                                    {{ $payment->package?->name }}
                                </p>
                                <p>
                                    <strong>Amount:</strong>
                                    {{ number_format($payment->amount, 2) }}
                                    {{ $payment->currency }}
                                </p>
                                <p>
                                    <strong>Status:</strong>
                                    {{ $payment->status }}
                                </p>
                            </div>
                            <a href="{{ route('dashboard.index') }}" class="btn btn-primary mt-3">
                                Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
