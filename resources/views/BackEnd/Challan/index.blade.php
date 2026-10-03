@extends('BackEnd.Layouts.layout')

@section('title', 'Challan')

@section('content')
    <div class="p-5">
        <div class="mt-3">
            <div class="mb-3">
                <h2 class="fw-bold mb-0">
                    Challan List
                </h2>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('challan.index') }}">
                        <div class="mb-3">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <div style="width: 200px;">
                                    <select name="company_id" class="form-select select2 w-100">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search Receipt / Party" value="{{ request('search') }}">
                                </div>
                                <div style="width: 100px;">
                                    <select name="per_page" class="form-select select2 w-100">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="24" {{ request('per_page', 24) == 24 ? 'selected' : '' }}>24
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                        </option>
                                    </select>
                                </div>
                                <div style="width: 200px;">
                                    <select name="status" class="form-select select2 w-100">
                                        <option value="">All Status</option>
                                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>
                                            Partial
                                        </option>
                                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>
                                            Paid
                                        </option>
                                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                    </select>
                                </div>
                                <button class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                    Search
                                </button>
                                @if (request('search') || request('company_id') || request('status') || request('per_page'))
                                    <a href="{{ route('challan.index') }}" class="btn btn-secondary">
                                        Reset
                                    </a>
                                @endif
                                {{-- @can('income-challan-create')
                                        <a href="{{ route('challan.create') }}" class="btn btn-success">
                                            <i class="fa fa-plus me-2"></i>
                                            Create Challan
                                        </a>
                                    @endcan --}}
                            </div>
                        </div>
                    </form>
                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th class="text-center">Company Name</th>
                                    <th class="text-center">Sales Order No</th>
                                    <th class="text-center">CHALLAN No</th>
                                    <th class="text-center">CUstomer Name</th>
                                    <th class="text-center">Product Name</th>
                                    <th class="text-center">Desription</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Created BY</th>
                                    <th class="text-center">DATE & TIME</th>
                                    <th class="text-center" width="90">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receipts as $receipt)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $receipt->company->name ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('challan.show', $receipt->id) }}"
                                                class="text-decoration-none fw-semibold">
                                                {{ $receipt->so_no ?? '-' }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('challan.show', $receipt->id) }}"
                                                class="text-decoration-none fw-semibold">
                                                {{ $receipt->dm_no ?? '-' }}
                                            </a>
                                        </td>
                                        <td class="text-center">{{ $receipt->customerCompany->name ?? '-' }}</td>
                                        <td class="text-center">
                                            @foreach ($receipt->items as $item)
                                                {{ $item->product->name ?? '-' }}
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach ($receipt->items as $item)
                                                {{ $item->product->description ?? '-' }}
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ $receipt->total_qty ?? '-' }}</td>
                                        <td class="text-center">{{ $receipt->creator->name ?? '-' }}</td>
                                        <td class="text-center">{{ $receipt->created_at->format('d-m-Y h:i A') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('challan.show', $receipt->id) }}">
                                                            <i class="fa fa-eye text-primary me-2"></i>
                                                            View
                                                        </a>
                                                    </li>
                                                    {{-- @if ($receipt->status != 'Cancelled')
                                                        <li>
                                                            <form action="{{ route('challan.cancel', $receipt->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button onclick="return confirm('Cancel this receipt?')"
                                                                    class="dropdown-item">
                                                                    <i class="fa fa-ban text-secondary me-2"></i>
                                                                    Cancel
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif --}}
                                                    {{-- @if ($receipt->payment_status == 'Pending')
                                                        <li>
                                                            <form action="{{ route('receipt.destroy', $receipt->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button onclick="return confirm('Delete this receipt?')"
                                                                    class="dropdown-item text-danger">
                                                                    <i class="fa fa-trash me-2"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <i class="fa fa-folder-open fa-4x text-secondary mb-3"></i>
                                            <br>
                                            No Challan Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $receipts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
