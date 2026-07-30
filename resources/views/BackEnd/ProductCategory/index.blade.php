@extends('BackEnd.Layouts.layout')

@section('title', 'Product Category')

@section('content')
    <div class="p-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Product Category</h4>
                <div>
                    <a href="{{ route('product-category.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>
                        Add Category
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->status }}</td>
                                <td>
                                    @can('product-category-edit')
                                        <a href="{{ route('product-category.edit', $category) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('product-category-delete')
                                        <form action="{{ route('product-category.destroy', $category) }}" method="POST"
                                            style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
