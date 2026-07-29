@extends('BackEnd.Layouts.layout')

@section('title', 'Add Purchase')

@section('content')
    <div class="p-4">
        <form action="{{ route('purchase.store') }}" method="POST">
            @csrf
            @include('BackEnd.Purchase.partials.form')
            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Purchase Items</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="addRow">
                        <i class="fa fa-plus"></i>
                        Add Product
                    </button>
                </div>
                <div class="card-body">
                    @include('BackEnd.Purchase.partials.items')
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 ms-auto">
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Qty</th>
                            <td>
                                <input type="text" id="totalQty" name="total_qty" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Sub Total</th>
                            <td>
                                <input type="text" id="subTotal" name="sub_total" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>
                                <input type="number" step="0.01" id="discount" name="discount" value="0"
                                    class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>VAT</th>
                            <td>
                                <input type="number" step="0.01" id="vat" name="vat" value="0"
                                    class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Grand Total</th>
                            <td>
                                <input type="text" id="grandTotal" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <th>Paid Amount</th>
                            <td>
                                <input type="number" step="0.01" id="paidAmount" name="paid_amount" value="0"
                                    class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Due Amount</th>
                            <td>
                                <input type="text" id="dueAmount" class="form-control" readonly>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-end mt-3">
                <button class="btn btn-success btn-lg">
                    <i class="fa fa-save me-2"></i>
                    Save Purchase
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('BackEnd.Purchase.partials.script')
@endpush
