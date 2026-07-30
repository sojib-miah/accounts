@extends('BackEnd.Layouts.layout')

@section('title', 'Supplier List')

@section('content')
    <div class="p-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Supplier List</h4>
                <div>
                    <a href="{{ route('supplier.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>
                        Add Supplier
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Supplier ID</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->party_id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->company_name }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->status }}</td>
                                <td>
                                    <a href="{{ route('supplier.edit', $supplier) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('supplier.destroy', $supplier) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
@endsection
