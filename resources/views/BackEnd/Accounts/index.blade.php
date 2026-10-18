@extends('BackEnd.Layouts.layout')

@section('title', 'Account Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0 fw-bold"><i class="fa-solid fa-list-ul me-2"></i> Account Management List</h4>
                    <small class="text-muted">Cash, Bank, Mobile Banking & Other Accounts</small>
                </div>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('accounts.index') }}" method="GET"
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
                                placeholder="Search Company Account">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>
                        @if (request('search') || request('company_id'))
                            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset
                            </a>
                        @endif
                    </form>
                    @can('account-create')
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                            <i class="fa fa-plus me-2"></i>
                            Add Account
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
                                    <th>Company</th>
                                    <th>Branch</th>
                                    <th>Account Name</th>
                                    <th>Address</th>
                                    <th>Account Holder</th>
                                    <th>Account Number</th>
                                    <th>Payment Type</th>
                                    <th class="text-end">Opening Balance</th>
                                    <th class="text-end">Current Balance</th>
                                    <th>
                                        <div class="d-flex gap-1">
                                            Is Default <i class="fa-solid fa-circle-info text-danger fs-5"
                                                title="Here One Cash Account Must be Default."></i>
                                        </div>
                                    </th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $account)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $account->company->name ?? '-' }}</td>
                                        <td>{{ $account->branch->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('accounts.show', $account->id) }}"
                                                class="fw-bold text-decoration-none">
                                                {{ $account->account_name }}
                                            </a>
                                        </td>
                                        <td>{{ $account->address ?? '-' }}</td>
                                        <td>{{ $account->account_holder_name }}</td>
                                        <td>{{ $account->account_number }}</td>
                                        <td>{{ $account->paymentType->name ?? '-' }}</td>
                                        <td class="text-end">{{ number_format($account->opening_balance, 2) }}</td>
                                        <td class="text-end">
                                            <strong>{{ number_format($account->current_balance, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if ($account->is_default == true)
                                                <span class="badge bg-primary">Default</span>
                                            @else
                                                <span class="badge bg-secondary">Not Default</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($account->status == 'Active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                                    <li class="mb-2">
                                                        @can('account-edit')
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#editAccountModal{{ $account->id }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                            </button>
                                                        @endcan
                                                    </li>
                                                    <li>
                                                        @can('account-delete')
                                                            <form action="{{ route('accounts.destroy', $account->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger" type="submit"
                                                                    onclick="return confirm('Are you sure you want to delete this account?')">
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
                                        <td colspan="13" class="text-center">No Account Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $accounts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- add modal  --}}
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('accounts.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-plus-circle me-2"></i>
                            Add New Account
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- company  --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                <select name="company_id" id="add_company_id"
                                    class="form-select select2 @error('company_id', 'add') is-invalid @enderror" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option {{ old('company_id') == $company->id ? 'selected' : '' }}
                                            value="{{ $company->id }}">
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id', 'add')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- branch  --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch <span class="text-danger">*</span></label>
                                <select name="branch_id" id="add_branch_id"
                                    class="form-select select2 @error('branch_id', 'add') is-invalid @enderror" required>
                                    <option value="">Select Branch</option>
                                </select>
                                @error('branch_id', 'add')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- payment type  --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                <select name="payment_type_id"
                                    class="form-select select2 @error('payment_type_id', 'add') is-invalid @enderror"
                                    required>
                                    <option value="">Select Payment Type</option>
                                    @foreach ($paymentTypes as $paymentType)
                                        <option {{ old('payment_type_id') == $paymentType->id ? 'selected' : '' }}
                                            value="{{ $paymentType->id }}">
                                            {{ $paymentType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_type_id', 'add')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Account Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Name <span class="text-danger">*</span></label>
                                <input type="text" name="account_name"
                                    class="form-control @error('account_name', 'add') is-invalid @enderror"
                                    value="{{ old('account_name') }}" placeholder="Enter Account Name..." required>
                                @error('account_name', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Account address --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Address <span class="text-danger">*</span></label>
                                <input type="text" name="address"
                                    class="form-control @error('address', 'add') is-invalid @enderror"
                                    value="{{ old('address') }}" placeholder="Enter Account Address..." required>
                                @error('address', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Holder Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('account_holder_name') }}"
                                    name="account_holder_name"
                                    class="form-control @error('account_holder_name', 'add') is-invalid @enderror"
                                    placeholder="Enter Account Holder Name..." required>
                                @error('account_holder_name', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Account Number --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" name="account_number"
                                    class="form-control @error('account_number', 'add') is-invalid @enderror"
                                    value="{{ old('account_number') }}" placeholder="Enter Account Number..." required>
                                @error('account_number', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Opening Balance --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Opening Balance <span class="text-danger">*</span></label>
                                <input type="number" step="1" min="0" name="opening_balance"
                                    value="{{ old('opening_balance') }}"
                                    class="form-control @error('opening_balance', 'add') is-invalid @enderror"
                                    placeholder="Enter Opening Balance..." required>
                                @error('opening_balance', 'add')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- isdefault --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Is Default <span class="text-danger">*</span></label>
                                <select name="is_default"
                                    class="form-select select2 @error('is_default', 'add') is-invalid @enderror" required>
                                    <option value="0" {{ old('is_default', 0) == 0 ? 'selected' : '' }}>Not Default
                                    </option>
                                    <option value="1" {{ old('is_default', 0) == 1 ? 'selected' : '' }}>Default
                                    </option>
                                </select>
                                @error('is_default', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select select2 @error('status', 'add') is-invalid @enderror" required>
                                    <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="Inactive"
                                        {{ old('status', 'Active') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status', 'add')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fa fa-times me-1"></i>
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            Save Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Account Modal --}}
    @foreach ($accounts as $account)
        <div class="modal fade" id="editAccountModal{{ $account->id }}" tabindex="-1" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('accounts.update', $account->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Edit Account</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{-- company  --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <select name="company_id" id="edit_company_id_{{ $account->id }}"
                                        class="form-select select2 @error('company_id', 'edit') is-invalid @enderror"
                                        required>
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ (string) old('company_id', $account->company_id) === (string) $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id', 'edit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- branch  --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                                    <select name="branch_id" id="edit_branch_id_{{ $account->id }}"
                                        class="form-select select2" required>
                                        <option value="">Select Branch</option>
                                        @foreach ($account->company->branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', $account->branch_id) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- payment type  --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                    <select name="payment_type_id"
                                        class="form-select select2 @error('payment_type_id', 'edit') is-invalid @enderror"
                                        required>
                                        <option value="">Select Payment Type</option>
                                        @foreach ($paymentTypes as $paymentType)
                                            <option value="{{ $paymentType->id }}"
                                                {{ (string) old('payment_type_id', $account->payment_type_id) === (string) $paymentType->id ? 'selected' : '' }}>
                                                {{ $paymentType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_type_id', 'edit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Account Name --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Name <span class="text-danger">*</span></label>
                                    <input type="text" name="account_name"
                                        value="{{ session('edit_account_id') == $account->id && $errors->getBag('edit')->any() ? old('account_name', $account->account_name) : $account->account_name }}"
                                        class="form-control @error('account_name', 'edit') is-invalid @enderror"
                                        placeholder="Enter Account Name..." required>
                                    @error('account_name', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- Account address --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address"
                                        value="{{ session('edit_account_id') == $account->id && $errors->getBag('edit')->any() ? old('address', $account->address) : $account->address }}"
                                        class="form-control @error('address', 'edit') is-invalid @enderror"
                                        placeholder="Enter Account Address..." required>
                                    @error('address', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- Account Holder --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Holder Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="account_holder_name"
                                        value="{{ session('edit_account_id') == $account->id && $errors->getBag('edit')->any() ? old('account_holder_name', $account->account_holder_name) : $account->account_holder_name }}"
                                        class="form-control @error('account_holder_name', 'edit') is-invalid @enderror"
                                        placeholder="Enter Account Holder Name..." required>
                                    @error('account_holder_name', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- Account Number --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number"
                                        value="{{ session('edit_account_id') == $account->id && $errors->getBag('edit')->any() ? old('account_number', $account->account_number) : $account->account_number }}"
                                        class="form-control @error('account_number', 'edit') is-invalid @enderror"
                                        placeholder="Enter Account Number..." required>
                                    @error('account_number', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- Opening Balance --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Opening Balance <span class="text-danger">*</span></label>
                                    <input type="number" step="1" min="0" name="opening_balance"
                                        value="{{ session('edit_account_id') == $account->id && $errors->getBag('edit')->any() ? old('opening_balance', $account->opening_balance) : $account->opening_balance }}"
                                        class="form-control @error('opening_balance', 'edit') is-invalid @enderror"
                                        placeholder="Enter Opening Balance..." required>
                                    @error('opening_balance', 'edit')
                                        <small class="invalid-feedback">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- isdefault --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Is Default <span class="text-danger">*</span></label>
                                    <select name="is_default"
                                        class="form-select select2 @error('is_default', 'edit') is-invalid @enderror"
                                        required>
                                        <option value="0"
                                            {{ old('is_default', $account->is_default) == 0 ? 'selected' : '' }}>Not
                                            Default</option>
                                        <option value="1"
                                            {{ old('is_default', $account->is_default) == 1 ? 'selected' : '' }}>Default
                                        </option>
                                    </select>
                                    @error('is_default', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- Status --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status"
                                        class="form-select select2 @error('status', 'edit') is-invalid @enderror"
                                        required>
                                        <option value="Active"
                                            {{ old('status', $account->status) == 'Active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="Inactive"
                                            {{ old('status', $account->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status', 'edit')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-1"></i>
                                Close
                            </button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-save me-1"></i>
                                Update Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function loadBranches(companyId, selectedBranch = null) {
                let branchSelect = $('#add_branch_id');
                branchSelect.html('<option value="">Select Branch</option>');
                if (!companyId) {
                    return;
                }
                $.get('/admin/ajax/company/' + companyId + '/branches', function(res) {
                    $.each(res.branches, function(index, branch) {
                        let selected = selectedBranch == branch.id ? 'selected' : '';
                        branchSelect.append(
                            '<option value="' + branch.id + '" ' + selected + '>' + branch
                            .name + '</option>'
                        );
                    });
                    branchSelect.trigger('change');
                });
            }
            $('#add_company_id').on('change', function() {
                loadBranches($(this).val());
            });
            let oldCompanyId = "{{ old('company_id') }}";
            let oldBranchId = "{{ old('branch_id') }}";
            if (oldCompanyId) {
                loadBranches(oldCompanyId, oldBranchId);
            }
        });
    </script>

    <script>
        $(document).on('change', '[id^="edit_company_id_"]', function() {
            let companyId = $(this).val();
            let accountId = $(this).attr('id').replace('edit_company_id_', '');
            let branchSelect = $('#edit_branch_id_' + accountId);
            branchSelect.html('<option value="">Select Branch</option>');
            if (!companyId) {
                return;
            }
            $.get('/admin/ajax/company/' + companyId + '/branches', function(res) {
                $.each(res.branches, function(index, branch) {
                    branchSelect.append(
                        `<option value="${branch.id}">${branch.name}</option>`
                    );
                });
                branchSelect.trigger('change');
            });
        });
    </script>

    @if ($errors->getBag('add')->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalElement = document.getElementById('addAccountModal');

                if (modalElement) {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            });
        </script>
    @endif

    @if ($errors->getBag('edit')->any() && session('edit_account_id'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const accountId = @json(session('edit_account_id'));
                const modal = document.getElementById('editAccountModal' + accountId);

                if (modal) {
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                }
            });
        </script>
    @endif
@endpush
