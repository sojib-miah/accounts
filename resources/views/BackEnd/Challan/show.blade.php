@extends('BackEnd.Layouts.layout')

@section('title', 'Receipt Details')

@section('content')
    <div class="mx-5 py-3">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- LEFT SIDE -->
            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <!-- Company Logo -->
                            <div class="col-md-6">
                                <img src="{{ optional(setting())->logo ? asset('uploads/settings/' . setting()->logo) : asset('default-favicon.ico') }}"
                                    height="55">
                            </div>
                            <!-- Receipt Info -->
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <div class="col-4 text-end">
                                            <b>Challan No:</b>
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control" readonly
                                                value="{{ $receipt->receipt_no }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <div class="col-4 text-end mt-2">
                                            <b>Date:</b>
                                        </div>
                                        <div class="col-8 mt-2">
                                            <input type="text" class="form-control" readonly
                                                value="{{ \Carbon\Carbon::parse($receipt->receipt_date)->format('d-m-Y') }}">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <div class="col-4 text-end mt-2">
                                            <b>By:</b>
                                        </div>
                                        <div class="col-8 mt-2">
                                            <input type="text" class="form-control" readonly
                                                value="{{ $receipt->creator->name ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- Company -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Company Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->company->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1"><b>Company Name :</b> {{ $receipt->company->name ?? '' }}</p>
                                </div>
                            </div>
                            <!-- Branch -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Branch Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->branch->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1"><b>Company Name :</b> {{ $receipt->branch->company->name ?? '' }}</p>
                                    <p class="mb-1">
                                        <b>Branch Name:</b>
                                        {{ $receipt->branch->name ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Mobile:</b>
                                        {{ $receipt->branch->phone_one ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>E-mail:</b>
                                        {{ $receipt->branch->email ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Address:</b>
                                        {{ $receipt->branch->address ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <!-- Party -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    Customer Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $receipt->party->name ?? '' }}">
                                <div class="mt-3">
                                    <p class="mb-1">
                                        <b>Name:</b>
                                        {{ $receipt->party->name ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Designation:</b>
                                        {{ $receipt->party->designation ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Mobile:</b>
                                        {{ $receipt->party->phone ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>E-mail:</b>
                                        {{ $receipt->party->email ?? '' }}
                                    </p>
                                    <p class="mb-1">
                                        <b>Address:</b>
                                        {{ $receipt->party->address ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================= --}}
                {{-- Income Receipt List --}}
                {{-- ========================= --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header" style="padding: 8px !important;">
                        <h3 class="mb-0 fw-bold">
                            Delivery Items
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="60" class="text-center">SN</th>
                                        <th>Category</th>
                                        <th>Item Description</th>
                                        <th width="90" class="text-center">Qty</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQty = 0;
                                    @endphp
                                    @forelse($receipt->items as $item)
                                        @php
                                            $totalQty += $item->qty;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $item->category->name ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $item->accountHead->name ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($item->qty) }}
                                            </td>
                                            <td>
                                                {{ $item->details }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                No Item Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- RIGHT SIDE -->
            <div class="col-lg-3">
                {{-- Receipt Notes --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header">
                        <strong>
                            Delivery Notes
                        </strong>
                        <span class="text-danger">*</span>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" rows="6" readonly>{{ $receipt->remarks }}</textarea>
                    </div>
                </div>
                {{-- Action Buttons --}}
                @if ($receipt->status != 'Cancelled')
                    @can('income-challan-edit')
                        <a href="{{ route('challan.edit', $receipt->id) }}"
                            class="btn btn-warning btn-lg d-block mb-2 text-white">
                            <i class="fa fa-edit me-2"></i>
                            Modify
                        </a>
                    @endcan
                @endif
                @if ($receipt->payment_status == 'Pending')
                    @can('income-challan-delete')
                        <form action="{{ route('receipt.destroy', $receipt->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this receipt?')" class="btn btn-danger mb-2 btn-lg w-100">
                                <i class="fa fa-trash me-2"></i>
                                Delete
                            </button>
                        </form>
                    @endcan
                @endif
                <a href="{{ route('challan.print', $receipt->id) }}" target="_blank"
                    class="btn d-block mb-2 btn-primary btn-lg">
                    <i class="fa fa-print me-2"></i>
                    Print
                </a>
                <a href="{{ route('challan.pdf', $receipt->id) }}" target="_blank"
                    class="btn btn-danger d-block mb-2 btn-lg">
                    <i class="fa fa-file-pdf me-2"></i>
                    PDF
                </a>
            </div>
        </div>
    </div>
    </div>
@endsection
