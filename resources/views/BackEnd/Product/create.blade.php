@extends('BackEnd.Layouts.layout')

@section('title', 'Create Product')

@section('content')
    <div class="p-4">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Create Product</h3>
                <a href="{{ route('product.index') }}" class="btn btn-secondary">
                    Back
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST">
                    @csrf
                    @include('BackEnd.Product.partials.form')
                    <div class="text-end mt-3">
                        <button class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>
                            Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
