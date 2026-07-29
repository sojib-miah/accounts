@extends('BackEnd.Layouts.layout')

@section('title', 'Edit Purchase')

@section('content')
    <div class="p-4">
        <form action="{{ route('purchase.update', $purchase->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('BackEnd.Purchase.partials.edit-form')
            <div class="card shadow-sm mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        Purchase Items
                    </h5>
                    <button type="button" class="btn btn-primary btn-sm" id="addRow">
                        <i class="fa fa-plus me-2"></i>
                        Add Product
                    </button>
                </div>
                <div class="card-body">
                    @include('BackEnd.Purchase.partials.edit-items')
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 ms-auto">
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Qty</th>
                            <td>
                                <input type="text" id="totalQty" name="total_qty" class="form-control"
                                    value="{{ $purchase->total_qty }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Sub Total</th>
                            <td>
                                <input type="text" id="subTotal" name="sub_total" class="form-control"
                                    value="{{ number_format($purchase->sub_total, 2, '.', '') }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                <input type="number" step="0.01" id="discount" name="discount" class="form-control"
                                    value="{{ $purchase->discount }}">
                            </td>
                        </tr>
                        <tr>
                            <th>VAT</th>
                            <td>
                                <input type="number" step="0.01" id="vat" name="vat" class="form-control"
                                    value="{{ $purchase->vat }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Grand Total</th>
                            <td>
                                <input type="text" id="grandTotal" class="form-control"
                                    value="{{ number_format($purchase->total_amount, 2, '.', '') }}" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td>
                                <input type="number" step="0.01" id="paidAmount" name="paid_amount" class="form-control"
                                    value="{{ $purchase->paid_amount }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Due Amount</th>
                            <td>
                                <input type="text" id="dueAmount" class="form-control"
                                    value="{{ number_format($purchase->due_amount, 2, '.', '') }}" readonly>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('purchase.index') }}" class="btn btn-secondary">
                    Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-2"></i>
                    Update Purchase
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('BackEnd.Purchase.partials.edit-script')
@endpush
