@extends('BackEnd.Layouts.layout')

@section('title', 'Products List')

@section('content')
    <div class="p-5">
        <div class="my-4">
            <h2 class="fw-bold"><i class="fa-solid fa-list-ul me-2"></i> Products List</h2>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Total Products</h6>
                        <h2>{{ $statistics['total'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Active Products</h6>
                        <h2 class="text-success">{{ $statistics['active'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Inactive Products</h6>
                        <h2 class="text-danger">{{ $statistics['inactive'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Low Stock Products</h6>
                        <h2 class="text-warning">{{ $statistics['low_stock'] }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-2">
                            <select name="company_id" class="form-select select2 form-control">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search Name, Code, Sku..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select select2">
                                <option value="">All Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search me-1"></i>
                                        Search</button>
                                    @if (request('search') || request('company_id') || request('category') || request('status'))
                                        <a href="{{ route('product.index') }}" class="btn btn-secondary">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Reset
                                        </a>
                                    @endif
                                </div>
                                @can('product-create')
                                    <button type="button" class="ms-3 btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createProductModal">
                                        <i class="fa fa-plus me-2"></i>
                                        Create Product
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Company Name</th>
                            <th>Product Code</th>
                            <th>Part No</th>
                            <th>Category</th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->company->name ?? '-' }}</td>
                                <td>{{ $product->product_code ?? '-' }}</td>
                                <td>{{ $product->sku ?? '-' }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>{{ $product->name ?? '-' }}</td>
                                <td>{{ $product->description ?? '-' }}</td>
                                <td>
                                    @if ($product->current_stock <= $product->minimum_stock)
                                        <span class="badge bg-danger">
                                            {{ number_format($product->current_stock) }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ number_format($product->current_stock) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('product.show', $product->id) }}">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </li>
                                            @can('product-edit')
                                                <li>
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editProductModal{{ $product->id }}">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </button>
                                                </li>
                                            @endcan
                                            @can('product-delete')
                                                <li>
                                                    <form action="{{ route('product.destroy', $product->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger delete-btn">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No Product Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @include('BackEnd.Product.partials.create')
    @include('BackEnd.Product.partials.edit')
@endsection

@push('scripts')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('createProductModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_product_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productId = @json(session('edit_product_id'));
                const modal = document.getElementById('editProductModal' + productId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
