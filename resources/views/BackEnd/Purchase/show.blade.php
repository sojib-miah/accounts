@extends('BackEnd.Layouts.layout')

@section('title', 'Purchase Details')

@section('content')
    <div class="p-5">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between">
                <h4>Purchase Details</h4>
                <div>
                    <a href="{{ route('purchase.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Back
                    </a>
                    <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn btn-warning">
                        <i class="fa fa-edit me-2"></i>
                        Edit
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fa-solid fa-print me-2"></i>
                        Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>{{ $purchase->company->name ?? '' }}</h5>
                        <p>
                            {{ $purchase->branch->name ?? '' }}
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Receipt No</th>
                                <td>{{ $purchase->receipt_no }}</td>
                            </tr>
                            <tr>
                                <th>Purchase Date</th>
                                <td>{{ date('d-m-Y', strtotime($purchase->receipt_date)) }}</td>
                            </tr>
                            <tr>
                                <th>Supplier</th>
                                <td>{{ $purchase->supplier->name }}</td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td>{{ $purchase->payment_status }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>Product</th>
                                <th>Part No</th>
                                <th width='250'>Serial No</th>
                                <th>Unit</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $item->product->product_code }}
                                        -
                                        {{ $item->product->name }}
                                    </td>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>
                                        @if ($item->serialNumbers->count())
                                            {{ $item->serialNumbers->pluck('serial_no')->implode(', ') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->product->unit }}</td>
                                    <td class="text-end">{{ number_format($item->qty) }}</td>
                                    <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 ms-auto">
                        <div class="border">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Qty</th>
                                    <td class="text-end">{{ $purchase->total_qty }}</td>
                                </tr>
                                <tr>
                                    <th>Sub Total</th>
                                    <td class="text-end">{{ number_format($purchase->sub_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td class="text-end">{{ number_format($purchase->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>VAT</th>
                                    <td class="text-end">{{ number_format($purchase->vat, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td class="text-end fw-bold">{{ number_format($purchase->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Paid</th>
                                    <td class="text-end">{{ number_format($purchase->paid_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Due</th>
                                    <td class="text-end text-danger">{{ number_format($purchase->due_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                @if ($purchase->remarks)
                    <div class="mt-3">
                        <strong>Remarks</strong>
                        <hr>
                        {{ $purchase->remarks }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
