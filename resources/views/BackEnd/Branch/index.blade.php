@extends('BackEnd.Layouts.layout')

@section('title', 'Branch Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0"><i class="fa-solid fa-list-ul me-2"></i> Branch List</h4>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('branch.index') }}" method="GET"
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
                                placeholder="Search Branch...">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('branch.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i>
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

            <div class="card">
                <div class="card-datatable table-responsive">
                    <table class="table table-bordered table-hover">
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
                            @forelse ($branches as $branch)
                                <tr>
                                    <td>{{ $loop->iteration ?? '-' }}</td>
                                    <td>{{ $branch->company->name ?? '-' }}</td>
                                    <td>{{ $branch->branch_id ?? '-' }}</td>
                                    <td>{{ $branch->name ?? '-' }}</td>
                                    <td>{{ $branch->phone_one ?? '-' }}</td>
                                    <td>{{ $branch->phone_two ?? '-' }}</td>
                                    <td>{{ $branch->email ?? '-' }}</td>
                                    <td>{{ $branch->address ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li class="mb-2">
                                                    @can('branch-edit')
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editBranchModal{{ $branch->id }}">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </button>
                                                    @endcan
                                                </li>
                                                <li>
                                                    @can('branch-delete')
                                                        <form method="POST" action="{{ route('branch.destroy', $branch) }}"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button onclick="return confirm('Delete Branch?')"
                                                                class="dropdown-item text-danger" type="submit">
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
                                    <td colspan="9" class="text-center">No data Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $branches->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- modal add  --}}
    <div class="modal fade" id="addBranchModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('branch.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5><i class="fa fa-plus me-2"></i> Add Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Company <span class="text-danger">*</span></label>
                                <select name="company_id"
                                    class="form-select select2 @error('company_id', 'add') is-invalid @enderror"
                                    data-allow-clear="true" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option {{ old('company_id') == $company->id ? 'selected' : '' }}
                                            value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Branch Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    placeholder="Branch Name..."
                                    class="form-control @error('name', 'add') is-invalid @enderror" required>
                                @error('name', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone One <span class="text-danger">*</span></label>
                                <input type="text" name="phone_one" value="{{ old('phone_one') }}"
                                    placeholder="Phone One..."
                                    class="form-control @error('phone_one', 'add') is-invalid @enderror" required>
                                @error('phone_one', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone Two</label>
                                <input type="text" name="phone_two" value="{{ old('phone_two') }}"
                                    placeholder="Phone Two..."
                                    class="form-control @error('phone_two', 'add') is-invalid @enderror">
                                @error('phone_two', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email', 'add') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="Email...">
                                @error('email', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <textarea name="address" placeholder="Address..." class="form-control @error('address', 'add') is-invalid @enderror"
                                    rows="3">{{ old('address') }}</textarea>
                                @error('address', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Branch Modal -->
    @foreach ($branches as $branch)
        <div class="modal fade" id="editBranchModal{{ $branch->id }}" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('branch.update', $branch->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Edit Branch</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <select name="company_id"
                                        class="form-select select2 @error('company_id', 'edit') is-invalid @enderror"
                                        required>
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ (string) old('company_id', $branch->company_id) === (string) $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" placeholder="Branch Name..."
                                        value="{{ session('edit_branch_id') == $branch->id && $errors->getBag('edit')->any() ? old('name', $branch->name) : $branch->name }}"
                                        class="form-control @error('name', 'edit') is-invalid @enderror" required>
                                    @error('name', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone One <span class="text-danger">*</span></label>
                                    <input type="text" name="phone_one" placeholder="Phone One..."
                                        value="{{ session('edit_branch_id') == $branch->id && $errors->getBag('edit')->any() ? old('phone_one', $branch->phone_one) : $branch->phone_one }}"
                                        class="form-control @error('phone_one', 'edit') is-invalid @enderror" required>
                                    @error('phone_one', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Two</label>
                                    <input type="text" name="phone_two" placeholder="Phone Two..."
                                        value="{{ session('edit_branch_id') == $branch->id && $errors->getBag('edit')->any() ? old('phone_two', $branch->phone_two) : $branch->phone_two }}"
                                        class="form-control @error('phone_two', 'edit') is-invalid @enderror">
                                    @error('phone_two', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" placeholder="Email..."
                                        class="form-control @error('email', 'edit') is-invalid @enderror"
                                        value="{{ session('edit_branch_id') == $branch->id && $errors->getBag('edit')->any() ? old('email', $branch->email) : $branch->email }}">
                                    @error('email', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" placeholder="Address..."
                                        class="form-control @error('address', 'edit') is-invalid @enderror" rows="3">{{ session('edit_branch_id') == $branch->id && $errors->getBag('edit')->any() ? old('address', $branch->address) : $branch->address }}</textarea>
                                    @error('address', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa-solid fa-xmark me-1"></i> Close
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save me-1"></i> Update Branch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('addBranchModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_branch_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const branchId = @json(session('edit_branch_id'));
                const modal = document.getElementById('editBranchModal' + branchId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
