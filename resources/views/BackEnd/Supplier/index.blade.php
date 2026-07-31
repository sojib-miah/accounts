@extends('BackEnd.Layouts.layout')

@section('title', 'Supplier List')

@section('content')
    <div class="p-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Supplier List</h4>
                <div>
                    @can('supplier-create')
                        <a href="{{ route('supplier.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i>
                            Add Supplier
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Supplier ID</th>
                            <th>Company</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Phone</th>
                            <th>
                                E-mail
                            </th>
                            <th>Address</th>
                            <th>Created By</th>
                            <th>Create Date</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->party_id }}</td>
                                <td>{{ $supplier->company_name }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->designation }}</td>
                                <td>{{ $supplier->phone ?? '-' }}</td>
                                <td>{{ $supplier->email ?? '-' }}</td>
                                <td>{{ $supplier->address ?? '-' }}</td>
                                <td>{{ $supplier->creator->name ?? '-' }}</td>
                                <td>{{ $supplier->created_at }}</td>
                                <td>{{ $supplier->status }}</td>
                                <td>
                                    @can('supplier-edit')
                                        <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('supplier-delete')
                                        <form action="{{ route('supplier.destroy', $supplier) }}" method="POST"
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
                                <td colspan="7" class="text-center">No Supplier Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
@endsection
