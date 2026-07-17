@extends('BackEnd.Layouts.layout')

@section('title', 'Party Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Payee List</h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('party.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">

                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Payee List">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('party.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif

                    </form>
                    @can('payee-list-create')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartyModal">
                            <i class="fa fa-plus me-2"></i>
                            Add Payee
                        </button>
                    @endcan
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th width="60">SN</th>
                                    <th>Payee ID</th>
                                    <th>Payee Name</th>
                                    <th>Phone</th>
                                    <th>E-mail</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parties as $party)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $party->party_id }}</td>
                                        <td>
                                            <a href="{{ route('party.profile', $party->id) }}">
                                                {{ $party->name }}
                                            </a>
                                        </td>
                                        <td>{{ $party->phone ?? '-' }}</td>
                                        <td>{{ $party->email ?? '-' }}</td>
                                        <td>{{ $party->address ?? '-' }}</td>
                                        <td>
                                            @if ($party->type == 'Income')
                                                <span class="badge bg-success">Income</span>
                                            @elseif($party->type == 'Expense')
                                                <span class="badge bg-danger">Expense</span>
                                            @else
                                                <span class="badge bg-primary">Both</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($party->status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $party->creator->name ?? '-' }}</td>
                                        <td>
                                            @can('payee-list-edit')
                                                <button class="btn btn-warning btn-sm editBtn" data-id="{{ $party->id }}"
                                                    data-name="{{ $party->name }}" data-phone="{{ $party->phone }}"
                                                    data-email="{{ $party->email }}" data-address="{{ $party->address }}"
                                                    data-status="{{ $party->status }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('payee-list-delete')
                                                <form action="{{ route('party.destroy', $party->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this party?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            No Payee Found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('BackEnd.Party.create')
    @include('BackEnd.Party.edit')
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_phone').value = this.dataset.phone;
                document.getElementById('edit_email').value = this.dataset.email;
                document.getElementById('edit_address').value = this.dataset.address;
                $('#edit_status').val(this.dataset.status).trigger('change');
                document.getElementById('editPartyForm').action =
                    "{{ url('/admin/party') }}/" + this.dataset.id;
                new bootstrap.Modal(
                    document.getElementById('editPartyModal')
                ).show();
            });
        });
    </script>
@endpush
