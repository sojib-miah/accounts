@extends('BackEnd.Layouts.layout')

@section('title', 'Brand List')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fa fa-tags me-2"></i>
                    Brand List
                </h4>
            </div>
            <div class="card-body">
                <form method="GET">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search Brand..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">
                                    All Status
                                </option>
                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-search me-2"></i>
                                Search
                            </button>
                            <a href="{{ route('brand.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                            <button type="button" class="btn ms-3 btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addBrandModal">
                                <i class="fa fa-plus me-2"></i>
                                Add Brand
                            </button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">
                                    SL
                                </th>
                                <th>
                                    Brand Name
                                </th>
                                <th>
                                    Description
                                </th>
                                <th width="120">
                                    Status
                                </th>
                                <th width="160">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>
                                        {{ $brands->firstItem() + $loop->index }}
                                    </td>
                                    <td>
                                        {{ $brand->name }}
                                    </td>
                                    <td>
                                        {{ $brand->description }}
                                    </td>
                                    <td>
                                        @if ($brand->status == 'Active')
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
                                        <button class="btn btn-warning btn-sm editBrand" data-id="{{ $brand->id }}"
                                            data-name="{{ $brand->name }}" data-description="{{ $brand->description }}"
                                            data-status="{{ $brand->status }}" data-bs-toggle="modal"
                                            data-bs-target="#editBrandModal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route('brand.destroy', $brand->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm ">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-danger">
                                        No Brand Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $brands->links() }}
                </div>
            </div>
        </div>
    </div>

    @include('BackEnd.Brand.partials.create-modal')

    @include('BackEnd.Brand.partials.edit-modal')
@endsection

@push('scripts')
    <script>
        const brandBaseUrl = "{{ url('admin/brand') }}";

        $(document).on('click', '.editBrand', function() {

            let id = $(this).data('id');

            $('#editBrandForm').attr('action', brandBaseUrl + '/' + id);

            $('#edit_name').val($(this).data('name'));
            $('#edit_description').val($(this).data('description'));
            $('#edit_status').val($(this).data('status'));

        });
    </script>
@endpush
