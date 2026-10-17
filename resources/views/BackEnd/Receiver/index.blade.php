@extends('BackEnd.Layouts.layout')

@section('title', 'Customer Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fa-solid fa-list-ul me-2"></i> Customer List</h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('receiver.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">
                        <div style="width: 200px;">
                            <select name="company_id" class="form-select select2 w-100">
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search Customer">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('receiver.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset
                            </a>
                        @endif
                    </form>
                    @can('receiver-list-create')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReceiverModal">
                            <i class="fa fa-plus me-1"></i>
                            Add Customer
                        </button>
                    @endcan
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Customer ID</th>
                                    <th>Company</th>
                                    <th>Supplier Company</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parties as $party)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $party->party_id ?? '-' }}</td>
                                        <td>{{ $party->company->name ?? '-' }}</td>
                                        <td>{{ $party->customerCompany->name ?? '-' }}</td>
                                        <td>{{ $party->name ?? '-' }}</td>
                                        <td>{{ $party->designation ?? '-' }}</td>
                                        <td>{{ $party->phone ?? '-' }}</td>
                                        <td>{{ $party->email ?? '-' }}</td>
                                        <td>{{ $party->address ?? '-' }}</td>
                                        <td>{{ $party->creator->name ?? '-' }}</td>
                                        <td>{{ $party->created_at ?? '-' }}</td>
                                        <td>
                                            @if ($party->status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        @can('receiver-list-edit')
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editReceiverModal{{ $party->id }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </button>
                                                        @endcan
                                                    </li>
                                                    <li>
                                                        @can('receiver-list-delete')
                                                            <form action="{{ route('receiver.destroy', $party->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this Customer?')">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">No Customer Found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-3">
                            {{ $parties->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('BackEnd.Receiver.create')
    @include('BackEnd.Receiver.edit')
@endsection

@push('scripts')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('addReceiverModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_receiver_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const receiverId = @json(session('edit_receiver_id'));
                const modal = document.getElementById('editReceiverModal' + receiverId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
