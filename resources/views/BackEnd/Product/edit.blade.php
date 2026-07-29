@extends('BackEnd.Layouts.layout')

@section('title', 'Edit Product')

@section('content')
    <div class="p-4">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Edit Product</h3>
                <a href="{{ route('product.index') }}" class="btn btn-secondary">
                    Back
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('product.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('BackEnd.Product.partials.form')
                    <div class="text-end mt-3">
                        <button class="btn btn-success">
                            <i class="fa fa-check me-2"></i>
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
