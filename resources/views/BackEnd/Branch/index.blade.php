@extends('BackEnd.Layouts.layout')

@section('title', 'Branch Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Branch List</h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('branch.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">

                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Branch">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('branch.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif

                    </form>
                    @can('branch-create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                            <i class="fa fa-plus me-2"></i> Add Branch
                        </button>
                    @endcan
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Company Name</th>
                                <th>Branch ID</th>
                                <th>Branch Name</th>
                                <th>Phone One</th>
                                <th>Phone Two</th>
                                <th>E-mail</th>
                                <th>Address</th>
                                <th width="170">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($branches as $branch)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $branch->company->name }}</td>
                                    <td>{{ $branch->branch_id }}</td>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ $branch->phone_one }}</td>
                                    <td>{{ $branch->phone_two }}</td>
                                    <td>{{ $branch->email }}</td>
                                    <td>{{ $branch->address }}</td>
                                    <td>
                                        @can('branch-edit')
                                            <button class="btn btn-warning btn-sm editBtn" data-id="{{ $branch->id }}"
                                                data-company="{{ $branch->company_id }}"
                                                data-branch="{{ $branch->branch_id }}" data-name="{{ $branch->name }}"
                                                data-phone1="{{ $branch->phone_one }}" data-phone2="{{ $branch->phone_two }}"
                                                data-email="{{ $branch->email }}" data-address="{{ $branch->address }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endcan
                                        @can('branch-delete')
                                            <form method="POST" action="{{ route('branch.destroy', $branch) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Delete Branch?')"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add  --}}
    <div class="modal fade" id="addBranchModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('branch.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Company</label>
                                <select name="company_id" class="form-select select2" data-allow-clear="true" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Branch Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone One</label>
                                <input type="text" name="phone_one" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone Two</label>
                                <input type="text" name="phone_two" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Branch Modal -->
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                <select name="company_id" id="edit_company_id" class="form-select select2" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone One <span class="text-danger">*</span></label>
                                <input type="text" name="phone_one" id="edit_phone_one" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Two</label>
                                <input type="text" name="phone_two" id="edit_phone_two" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" id="edit_address" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            Update Branch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.editBtn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                $('#edit_company_id').val(this.dataset.company).trigger('change');
                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_phone_one').value = this.dataset.phone1;
                document.getElementById('edit_phone_two').value = this.dataset.phone2;
                document.getElementById('edit_email').value = this.dataset.email;
                document.getElementById('edit_address').value = this.dataset.address;
                document.getElementById('editForm').action = "{{ url('/admin/branch') }}/" + this.dataset
                    .id;
                var editModal = new bootstrap.Modal(
                    document.getElementById('editBranchModal')
                );
                editModal.show();
            });
        });
    </script>
@endpush
