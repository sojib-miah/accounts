@extends('BackEnd.Layouts.layout')

@section('title', 'Product Details')

@section('content')
    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">
                Product Details
            </h3>
            <div>
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning">
                    <i class="fa fa-edit me-2"></i>
                    Edit
                </a>
                <a href="{{ route('product.index') }}" class="btn btn-secondary">
                    Back
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <i class="fa fa-box fa-5x text-primary mb-3"></i>
                        <h3>
                            {{ $product->name }}
                        </h3>
                        <p>
                            {{ $product->product_code }}
                        </p>
                        @if ($product->status == 'Active')
                            <span class="badge bg-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-danger">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>
                            Product Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">
                                    Product Code
                                </th>
                                <td>
                                    {{ $product->product_code }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Product Name
                                </th>
                                <td>
                                    {{ $product->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Brand Name
                                </th>
                                <td>
                                    {{ $product->brand->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Category
                                </th>
                                <td>
                                    {{ $product->category->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Model Number
                                </th>
                                <td>
                                    {{ $product->model_no ?: '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Barcode
                                </th>
                                <td>
                                    {{ $product->barcode ?: '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    SKU
                                </th>
                                <td>
                                    {{ $product->sku ?: '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Unit
                                </th>
                                <td>
                                    {{ $product->unit }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Purchase Price
                                </th>
                                <td>
                                    {{ number_format($product->purchase_price, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Sale Price
                                </th>
                                <td>
                                    {{ number_format($product->sale_price, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Minimum Stock
                                </th>
                                <td>
                                    {{ $product->minimum_stock }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Current Stock
                                </th>
                                <td>
                                    @if ($product->current_stock <= $product->minimum_stock)
                                        <span class="badge bg-danger">
                                            {{ $product->current_stock }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ $product->current_stock }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Created By
                                </th>
                                <td>
                                    {{ $product->creator->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Created At
                                </th>
                                <td>
                                    {{ $product->created_at->format('d M Y h:i A') }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Description
                                </th>
                                <td>
                                    {{ $product->description ?: '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row mt-4">
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h6>
                            Purchase History
                        </h6>
                        <a href="#" class="btn btn-primary btn-sm">
                            View
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h6>
                            Sales History
                        </h6>
                        <a href="#" class="btn btn-success btn-sm">
                            View
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h6>
                            Stock Ledger
                        </h6>
                        <a href="#" class="btn btn-warning btn-sm">
                            View
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h6>
                            Stock Adjustment
                        </h6>
                        <a href="#" class="btn btn-danger btn-sm">
                            View
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
