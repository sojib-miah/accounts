@extends('BackEnd.Layouts.layout')

@section('title', 'Account Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0 fw-bold">
                        Account Management
                    </h4>
                    <small class="text-muted">
                        Cash, Bank, Mobile Banking & Other Accounts
                    </small>
                </div>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <form action="{{ route('accounts.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">

                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Company Account">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                                Reset
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
                                    <th width="60">
                                        SN
                                    </th>
                                    <th>
                                        Account Name
                                    </th>
                                    <th>
                                        Address
                                    </th>
                                    <th>
                                        Holder Name
                                    </th>
                                    <th>
                                        Account Number
                                    </th>
                                    <th class="text-end">
                                        Opening Balance
                                    </th>
                                    <th class="text-end">
                                        Current Balance
                                    </th>
                                    <th>
                                        Default
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th width="170">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $account)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{ route('accounts.show', $account->id) }}"
                                                class="fw-bold text-decoration-none">
                                                {{ $account->account_name }}
                                            </a>
                                        </td>
                                        <td>{{ $account->address ?? '-' }}</td>
                                        <td>
                                            {{ $account->account_holder_name }}
                                        </td>
                                        <td>
                                            {{ $account->account_number }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($account->opening_balance, 2) }}
                                        </td>
                                        <td class="text-end">
                                            <strong>
                                                {{ number_format($account->current_balance, 2) }}
                                            </strong>
                                        </td>
                                        <td>
                                            @if ($account->default_status == 'Default')
                                                <span class="badge bg-primary">
                                                    Default
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    No
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($account->status == 'Active')
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
                                            @can('account-edit')
                                                <button class="btn btn-warning btn-sm editBtn" data-id="{{ $account->id }}"
                                                    data-account_name="{{ $account->account_name }}"
                                                    data-address="{{ $account->address }}"
                                                    data-holder="{{ $account->account_holder_name }}"
                                                    data-number="{{ $account->account_number }}"
                                                    data-opening="{{ $account->opening_balance }}"
                                                    data-default="{{ $account->default_status }}"
                                                    data-status="{{ $account->status }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('account-delete')
                                                <form action="{{ route('accounts.destroy', $account->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Delete this account?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            No Account Found
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

    {{-- add modal  --}}
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('accounts.store') }}" method="POST">
                    @csrf
                    <div class="modal-header text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-plus-circle me-2"></i>
                            Add New Account
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- Account Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="account_name" class="form-control"
                                    value="{{ old('account_name') }}" required>
                                @error('account_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Account address --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Address
                                </label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Holder Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Holder Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" value="{{ old('account_holder_name') }}" name="account_holder_name"
                                    class="form-control" required>
                                @error('account_holder_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Account Number --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Number
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="account_number" class="form-control"
                                    value="{{ old('account_number') }}" required>
                                @error('account_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Opening Balance --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Opening Balance
                                </label>
                                <input type="number" step="0.01" min="0" value="0"
                                    name="opening_balance" class="form-control">
                                @error('opening_balance')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Default Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Default
                                </label>
                                <select name="default_status" class="form-select select2">
                                    <option value="Not Default">
                                        Not Default
                                    </option>
                                    <option value="Default">
                                        Default
                                    </option>
                                </select>
                                @error('default_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Status
                                </label>
                                <select name="status" class="form-select select2">
                                    <option value="Active">
                                        Active
                                    </option>
                                    <option value="Inactive">
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
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
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editAccountForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-edit me-2"></i>
                            Edit Account
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- Account Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="account_name" id="edit_account_name" class="form-control"
                                    required>
                                @error('account_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Account address --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Address
                                </label>
                                <input type="text" name="address" id="edit_address" class="form-control">
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Account Holder --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Holder Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="account_holder_name" id="edit_account_holder_name"
                                    class="form-control" required>
                                @error('account_holder_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Account Number --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Account Number
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="account_number" id="edit_account_number"
                                    class="form-control" required>
                                @error('account_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Opening Balance --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Opening Balance
                                </label>
                                <input type="number" step="0.01" min="0" name="opening_balance"
                                    id="edit_opening_balance" class="form-control">
                                @error('opening_balance')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Default Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Default Status
                                </label>
                                <select name="default_status" id="edit_default_status" class="form-select select2">
                                    <option value="Default">
                                        Default
                                    </option>
                                    <option value="Not Default">
                                        Not Default
                                    </option>
                                </select>
                                @error('default_status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Status
                                </label>
                                <select name="status" id="edit_status" class="form-select select2">
                                    <option value="Active">
                                        Active
                                    </option>
                                    <option value="Inactive">
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.editBtn').forEach(function(button) {
                    button.addEventListener('click', function() {
                        let id = this.dataset.id;
                        document.getElementById('editAccountForm').action =
                            "{{ url('/admin/accounts') }}/" + id;
                        document.getElementById('edit_account_name').value = this.dataset.account_name;
                        document.getElementById('edit_address').value = this.dataset.address;
                        document.getElementById('edit_account_holder_name').value = this.dataset.holder;
                        document.getElementById('edit_account_number').value = this.dataset.number;
                        document.getElementById('edit_opening_balance').value = this.dataset.opening;
                        $('#edit_default_status').val(this.dataset.default).trigger('change');
                        $('#edit_status').val(this.dataset.status).trigger('change');
                        let modal = new bootstrap.Modal(
                            document.getElementById('editAccountModal')
                        );
                        modal.show();
                    });
                });
            });
        </script>

        @if ($errors->add->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new bootstrap.Modal(document.getElementById('addAccountModal')).show();
                });
            </script>
        @endif

        @if ($errors->edit->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new bootstrap.Modal(document.getElementById('editAccountModal')).show();
                });
            </script>
        @endif
    @endpush
@endsection
