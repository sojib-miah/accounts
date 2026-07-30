@extends('BackEnd.Layouts.layout')

@section('title', 'Products')

@section('content')

    <div class="p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                Product List
            </h2>
            <div>
                @can('product-create')
                    <a href="{{ route('product.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>
                        Add Product
                    </a>
                @endcan
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Total Products</h6>
                        <h2>
                            {{ $statistics['total'] }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Active</h6>
                        <h2 class="text-success">
                            {{ $statistics['active'] }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Inactive</h6>
                        <h2 class="text-danger">
                            {{ $statistics['inactive'] }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6>Low Stock</h6>
                        <h2 class="text-warning">
                            {{ $statistics['low_stock'] }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">
                                    All Category
                                </option>
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
                                <option value="">
                                    All Status
                                </option>
                                <option value="Active">
                                    Active
                                </option>
                                <option value="Inactive">
                                    Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary">
                                Search
                            </button>
                            <a href="{{ route('product.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
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
                            <th>SL</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Buy</th>
                            <th>Sale</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th width="80">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $product->product_code }}
                                </td>
                                <td>
                                    {{ $product->name }}
                                </td>
                                <td>
                                    {{ $product->category->name ?? '-' }}
                                </td>
                                <td>
                                    {{ number_format($product->purchase_price, 2) }}
                                </td>
                                <td>
                                    {{ number_format($product->sale_price, 2) }}
                                </td>
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
                                <td>
                                    @if ($product->status == 'Active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
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
                                                    View
                                                </a>
                                            </li>
                                            @can('product-edit')
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('product.edit', $product->id) }}">
                                                        Edit
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('product-delete')
                                                <li>
                                                    <form action="{{ route('product.destroy', $product->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="dropdown-item text-danger delete-btn">
                                                            Delete
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
                                <td colspan="9" class="text-center">
                                    No Product Found
                                </td>
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
@endsection

@push('scripts')
    <script>
        $('.delete-btn').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Delete Product?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
