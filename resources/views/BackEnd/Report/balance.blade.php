@extends('BackEnd.Layouts.layout')

@section('title', 'Balance & Stock Report')

@section('content')

    <div class="p-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Balance & Stock Report</h4>
                <p class="text-muted mb-0">Current account balance and inventory details</p>
            </div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa fa-print me-1"></i> Print Report
            </button>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Total Opening Balance</div>
                        <h4 class="mb-0 text-primary">
                            {{ number_format($totalOpeningBalance, 2) }}
                        </h4>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Total Current Balance</div>
                        <h4 class="mb-0 text-success">
                            {{ number_format($totalCurrentBalance, 2) }}
                        </h4>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Total Products</div>
                        <h4 class="mb-0 text-info">
                            {{ number_format($totalProducts) }}
                        </h4>
                        <small class="text-muted">Products</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Total Stock Value</div>
                        <h4 class="mb-0 text-warning">
                            {{ number_format($totalStockValue, 2) }}
                        </h4>
                        <small class="text-muted">BDT</small>
                    </div>
                </div>
            </div>

        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('report.balance') }}" method="GET">
                    <div class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label">Company</label>
                            <select name="company_id" class="form-select select2">
                                <option value="">All Companies</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ $companyId == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select select2">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" value="{{ $search }}"
                                placeholder="Account name, product name, code...">
                        </div>

                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-1"></i> Filter
                            </button>
                            <a href="{{ route('report.balance') }}" class="btn btn-secondary">
                                <i class="fa fa-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Report Tabs --}}
        <div class="card">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#accountBalance"
                            type="button">
                            <i class="fa fa-university me-1"></i>
                            Current Balance Details
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#stockDetails" type="button">
                            <i class="fa fa-cubes me-1"></i>
                            Stock Details
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#stockTransactions" type="button">
                            <i class="fa fa-exchange me-1"></i>
                            Stock Transactions
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accountTransactions" type="button">
                            <i class="fa fa-exchange me-1"></i>
                            Account Transactions
                        </button>
                    </li>

                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    {{-- Current Balance Details --}}
                    <div class="tab-pane fade show active" id="accountBalance">

                        <div class="d-flex justify-content-between mb-3">
                            <h5>Current Account Balance Details</h5>
                            <span class="badge bg-success">
                                {{ $accounts->total() }} Accounts
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="50">SL</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Account Holder</th>
                                        <th>Company</th>
                                        <th>Branch</th>
                                        <th>Payment Type</th>
                                        <th class="text-end">Opening Balance</th>
                                        <th class="text-end">Current Balance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($accounts as $key => $account)
                                        <tr>
                                            <td>{{ $accounts->firstItem() + $key }}</td>
                                            <td>
                                                <strong>{{ $account->account_name }}</strong>
                                            </td>
                                            <td>{{ $account->account_number }}</td>
                                            <td>{{ $account->account_holder_name }}</td>
                                            <td>{{ $account->company->name ?? 'N/A' }}</td>
                                            <td>{{ $account->branch->name ?? 'N/A' }}</td>
                                            <td>{{ $account->paymentType->name ?? 'N/A' }}</td>
                                            <td class="text-end">
                                                {{ number_format($account->opening_balance, 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($account->current_balance, 2) }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $account->status == 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $account->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-4">
                                                No account balance found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $accounts->links() }}

                    </div>

                    {{-- Stock Details --}}
                    <div class="tab-pane fade" id="stockDetails">

                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h5>Product Stock Details</h5>
                                <small class="text-muted">
                                    Total Stock: {{ number_format($totalStock, 2) }}
                                </small>
                            </div>
                            <span class="badge bg-info">
                                {{ $products->total() }} Products
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Model</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th class="text-end">Purchase Price</th>
                                        <th class="text-end">Sale Price</th>
                                        <th class="text-end">Current Stock</th>
                                        <th>Unit</th>
                                        <th class="text-end">Stock Value</th>
                                        <th class="text-end">Minimum Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($products as $key => $product)
                                        <tr>
                                            <td>{{ $products->firstItem() + $key }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                            </td>
                                            <td>{{ $product->model_no ?? 'N/A' }}</td>
                                            <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                            <td class="text-end">
                                                {{ number_format($product->purchase_price, 2) }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($product->sale_price, 2) }}
                                            </td>
                                            <td
                                                class="text-end fw-bold {{ $product->current_stock <= $product->minimum_stock ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($product->current_stock, 2) }}
                                            </td>
                                            <td>{{ $product->unit }}</td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($product->current_stock * $product->purchase_price, 2) }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($product->minimum_stock, 2) }}
                                            </td>
                                            <td>
                                                @if ($product->current_stock <= 0)
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($product->current_stock <= $product->minimum_stock)
                                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                                @else
                                                    <span class="badge bg-success">Available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center text-muted py-4">
                                                No product stock found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $products->links() }}

                    </div>

                    {{-- Stock Transactions --}}
                    <div class="tab-pane fade" id="stockTransactions">

                        <h5 class="mb-3">Stock Transaction History</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Product Code</th>
                                        <th>Branch</th>
                                        <th>Voucher No</th>
                                        <th>Transaction Type</th>
                                        <th class="text-end">Stock In</th>
                                        <th class="text-end">Stock Out</th>
                                        <th class="text-end">Balance</th>
                                        <th>Remarks</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($stockTransactions as $key => $transaction)
                                        <tr>
                                            <td>{{ $stockTransactions->firstItem() + $key }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                            </td>
                                            <td>{{ $transaction->product->name ?? 'N/A' }}</td>
                                            <td>{{ $transaction->product->product_code ?? 'N/A' }}</td>
                                            <td>{{ $transaction->branch->name ?? 'N/A' }}</td>
                                            <td>
                                                {{ $transaction->receipt->receipt_no ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $transaction->transaction_type }}
                                                </span>
                                            </td>
                                            <td class="text-end text-success">
                                                {{ number_format($transaction->stock_in, 2) }}
                                            </td>
                                            <td class="text-end text-danger">
                                                {{ number_format($transaction->stock_out, 2) }}
                                            </td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($transaction->balance, 2) }}
                                            </td>
                                            <td>{{ $transaction->remarks ?? 'N/A' }}</td>
                                            <td>{{ $transaction->creator->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted py-4">
                                                No stock transactions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $stockTransactions->links() }}

                    </div>

                    {{-- Account Transactions --}}
                    <div class="tab-pane fade" id="accountTransactions">

                        <h5 class="mb-3">Account Transaction History</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Company</th>
                                        <th>Branch</th>
                                        <th>Transaction Type</th>
                                        <th class="text-end">Debit (BDT)</th>
                                        <th class="text-end">Credit (BDT)</th>
                                        <th class="text-end">Balance (BDT)</th>
                                        <th>Remarks</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($transactions as $key => $transaction)
                                        <tr>
                                            <td>{{ $transactions->firstItem() + $key }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                            </td>
                                            <td>{{ $transaction->account->account_name ?? 'N/A' }}</td>
                                            <td>{{ $transaction->account->account_number ?? 'N/A' }}</td>
                                            <td>{{ $transaction->account->company->name ?? 'N/A' }}</td>
                                            <td>{{ $transaction->account->branch->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $transaction->transaction_type }}
                                                </span>
                                            </td>
                                            <td class="text-end text-danger">
                                                {{ number_format($transaction->debit, 2) }}
                                            </td>
                                            <td class="text-end text-success">
                                                {{ number_format($transaction->credit, 2) }}
                                            </td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($transaction->balance, 2) }}
                                            </td>
                                            <td>{{ $transaction->remarks ?? 'N/A' }}</td>
                                            <td>{{ $transaction->creator->name ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center text-muted py-4">
                                                No stock transactions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $stockTransactions->links() }}

                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
