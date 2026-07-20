@extends('BackEnd.Layouts.layout')

@section('title', 'Package Details')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bx bx-package text-primary"></i>
                    {{ $package->name }}

                </h3>
                <small class="text-muted">
                    Package Information
                </small>
            </div>
            <div>
                <a href="{{ route('package.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i>
                    Back
                </a>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-start border-primary border-4 shadow-sm">

                    <div class="card-body">
                        <small class="text-muted">
                            Price
                        </small>
                        <h3 class="text-primary mt-2">
                            {{ number_format($package->price, 2) }}

                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-start border-success border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">
                            Status

                        </small>
                        <h4 class="mt-2">
                            @if ($package->is_active)
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-danger">

                                    Inactive
                                </span>
                            @endif
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-start border-info border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">
                            Created
                        </small>
                        <h5 class="mt-2">
                            {{ $package->created_at->format('d M Y') }}
                        </h5>
                    </div>

                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card border-start border-warning border-4 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">
                            Last Updated

                        </small>
                        <h5 class="mt-2">
                            {{ $package->updated_at->format('d M Y') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    Package Details

                </h5>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="45%">Package Name</th>
                                <td>{{ $package->name }}</td>
                            </tr>

                            <tr>
                                <th>Price</th>
                                <td>{{ number_format($package->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>User Limit</th>

                                <td>{{ $package->user_limit }}</td>
                            </tr>
                            <tr>
                                <th>Company Limit</th>
                                <td>{{ $package->company_limit }}</td>
                            </tr>
                            <tr>
                                <th>Branch Limit</th>

                                <td>{{ $package->branch_limit }}</td>
                            </tr>
                        </table>

                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="45%">Income Limit</th>

                                <td>{{ $package->income_limit }}</td>
                            </tr>
                            <tr>
                                <th>Expense Limit</th>
                                <td>{{ $package->expense_limit }}</td>
                            </tr>

                            <tr>
                                <th>Challan Limit</th>
                                <td>{{ $package->challan_limit }}</td>
                            </tr>
                            <tr>
                                <th>Party Limit</th>
                                <td>{{ $package->party_limit }}</td>

                            </tr>
                            <tr>
                                <th>Account Limit</th>
                                <td>{{ $package->account_limit }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Additional Information
                </h5>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="45%">Storage Limit</th>
                                <td>

                                    {{ $package->storage_limit }} MB
                                </td>
                            </tr>
                            <tr>

                                <th>Status</th>
                                <td>

                                    @if ($package->is_active)
                                        <span class="badge bg-success">
                                            Active

                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
                                    @endif

                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-bold">
                            Remarks
                        </label>
                        <div class="border rounded p-3">
                            {{ $package->remarks ?: 'No remarks available.' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
