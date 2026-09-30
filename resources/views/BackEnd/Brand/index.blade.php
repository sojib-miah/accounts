@extends('BackEnd.Layouts.layout')

@section('title', 'Brand List')

@section('content')
    <div class="p-5">
        <div class="card shadow-sm mt-3">
            <div class="card-header d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="fa fa-tags me-2"></i>
                    Brand List
                </h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('brand.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">
                        <select name="company_id" class="form-select select2 form-control">
                            <option value="">Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="search" class="form-control" placeholder="Search Brand..."
                            value="{{ request('search') }}">
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
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-search me-2"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('brand.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif
                    </form>

                    <button type="button" class="btn ms-3 btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addBrandModal">
                        <i class="fa fa-plus me-2"></i>
                        Add Brand
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">SN</th>
                                <th>Company Name</th>
                                <th>Brand Name</th>
                                <th>Description</th>
                                <th width="120">Status</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>{{ $brands->firstItem() + $loop->index }}</td>
                                    <td>{{ $brand->company->name ?? '-' }}</td>
                                    <td>{{ $brand->name ?? '-' }}</td>
                                    <td>{{ $brand->description ?? '-' }}</td>
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
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li class="mb-2">
                                                    <button class="btn w-100 btn-warning btn-sm editBrand"
                                                        data-id="{{ $brand->id }}" data-name="{{ $brand->name }}"
                                                        data-description="{{ $brand->description }}"
                                                        data-status="{{ $brand->status }}" data-bs-toggle="modal"
                                                        data-bs-target="#editBrandModal">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('brand.destroy', $brand->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger">
                                        No Brand Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 p-3">
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
