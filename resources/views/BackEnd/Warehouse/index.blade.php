@extends('BackEnd.Layouts.layout')

@section('title', 'Warehouse')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-warehouse me-2"></i>
                    Warehouse Receive List
                </h4>
                <span class="badge bg-warning fs-6">
                    Pending : {{ $purchases->where('status', 'Draft')->count() }}
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>Date</th>
                                <th>PO No</th>
                                <th>Supplier</th>
                                <th>Part No</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total Price</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchases->firstItem() + $loop->index }}</td>
                                    <td>{{ date('d-m-Y', strtotime($purchase->receipt_date)) }}</td>
                                    <td>{{ $purchase->po_no }}</td>
                                    <td>{{ $purchase->supplier->name ?? '' }}</td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->sku }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->name }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->product->description }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end">{{ number_format($purchase->total_qty) }}</td>
                                    <td>
                                        @foreach ($purchase->items as $item)
                                            <div>
                                                {{ $item->rate }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-end">{{ number_format($purchase->total_amount, 2) }}</td>
                                    <td>
                                        @if ($purchase->status == 'Completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($purchase->status == 'Cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-warning">Waiting Receive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('warehouse.show', $purchase) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-danger">No Pending Purchase Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
