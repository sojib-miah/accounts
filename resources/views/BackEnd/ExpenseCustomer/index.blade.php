@extends('BackEnd.Layouts.layout')

@section('content')
    <div class="p-5">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h4 class="mb-0">
                    Customer Company
                </h4>

                <small class="text-muted">
                    Manage customer companies
                </small>
            </div>

            <button type="button" class="btn btn-primary" id="addCustomerCompany">

                <i class="fa fa-plus me-1"></i>
                Add Customer Company

            </button>

        </div>


        {{-- TABLE --}}
        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle" id="customerCompanyTable">

                        <thead>

                            <tr>

                                <th width="60">
                                    SL
                                </th>

                                <th>
                                    Company Name
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Phone
                                </th>

                                <th>
                                    Address
                                </th>

                                <th width="100">
                                    Status
                                </th>

                                <th width="130">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($customerCompanies as $key => $company)
                                <tr id="row-{{ $company->id }}">

                                    <td>
                                        {{ $key + 1 }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $company->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $company->email ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $company->phone ?: '-' }}
                                    </td>

                                    <td>
                                        {{ $company->address ?: '-' }}
                                    </td>

                                    <td>

                                        @if ($company->status === 'Sales')
                                            <span class="badge bg-success">
                                                Sales
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Expense
                                            </span>
                                        @endif

                                    </td>

                                    <td class="text-center">

                                        <button type="button" class="btn btn-sm btn-info editCompany"
                                            data-id="{{ $company->id }}">

                                            <i class="fa fa-edit"></i>

                                        </button>


                                        <button type="button" class="btn btn-sm btn-danger deleteCompany"
                                            data-id="{{ $company->id }}" data-name="{{ $company->name }}">

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-4">
                                        No Customer Company Found
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- ADD / EDIT MODAL --}}

    <div class="modal fade" id="customerCompanyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerCompanyModalTitle">
                        <i class="fa fa-building me-2"></i>
                        Add Customer Company
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="customerCompanyForm">
                    @csrf
                    <input type="hidden" id="company_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Company Name
                                    <span class="text-danger">
                                        *
                                    </span>
                                </label>
                                <input type="text" name="name" id="company_name" class="form-control"
                                    placeholder="Enter company name" required>
                                <div class="invalid-feedback" id="error-name"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Email
                                </label>
                                <input type="email" name="email" id="company_email" class="form-control"
                                    placeholder="company@example.com">
                                <div class="invalid-feedback" id="error-email"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Phone
                                </label>
                                <input type="text" name="phone" id="company_phone" class="form-control"
                                    placeholder="Enter phone number">
                                <div class="invalid-feedback" id="error-phone"></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Address
                                </label>
                                <textarea name="address" id="company_address" class="form-control" rows="3" placeholder="Enter company address"></textarea>
                                <div class="invalid-feedback" id="error-address"></div>
                            </div>
                        </div>
                    </div>
                    {{-- FOOTER --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveCompanyBtn">
                            <i class="fa fa-save me-1"></i>
                            <span id="saveCompanyText">
                                Save
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            let editMode = false;
            $('#addCustomerCompany').on('click', function() {
                editMode = false;
                $('#customerCompanyForm')[0].reset();
                $('#company_id').val('');
                clearErrors();
                $('#customerCompanyModalTitle').html(
                    '<i class="fa fa-building me-2"></i> Add Customer Company'
                );
                $('#saveCompanyText').text('Save');
                $('#customerCompanyModal')
                    .modal('show');
            });

            $(document).on('click', '.editCompany', function() {
                let id = $(this).data('id');
                editMode = true;
                clearErrors();
                $('#customerCompanyForm')[0].reset();
                $('#customerCompanyModalTitle').html(
                    '<i class="fa fa-edit me-2"></i> Edit Customer Company'
                );
                $('#saveCompanyText').text('Update');
                $('#customerCompanyModal')
                    .modal('show');
                $.ajax({
                    url: "{{ url('admin/customer-expense') }}/" +
                        id,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let data = response.data;
                            $('#company_id')
                                .val(data.id);

                            $('#company_name')
                                .val(data.name);

                            $('#company_email')
                                .val(data.email);

                            $('#company_phone')
                                .val(data.phone);

                            $('#company_address')
                                .val(data.address);

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });

                        }

                    },

                    error: function() {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to load customer company.'
                        });

                    }

                });

            });

            $('#customerCompanyForm').on(
                'submit',
                function(e) {

                    e.preventDefault();

                    clearErrors();

                    let id = $('#company_id').val();

                    let url = editMode ?
                        "{{ url('admin/customer-expense') }}/" + id :
                        "{{ route('customer-expense.store') }}";

                    let method = editMode ?
                        'PUT' :
                        'POST';


                    let formData = {
                        _token: "{{ csrf_token() }}",
                        name: $('#company_name').val(),
                        email: $('#company_email').val(),
                        phone: $('#company_phone').val(),
                        address: $('#company_address').val(),
                    };
                    if (editMode) {
                        formData._method = 'PUT';
                    }
                    $('#saveCompanyBtn')
                        .prop('disabled', true);
                    $('#saveCompanyText')
                        .text(
                            editMode ?
                            'Updating...' :
                            'Saving...'
                        );
                    $.ajax({

                        url: url,

                        type: 'POST',

                        data: formData,

                        success: function(response) {

                            if (response.success) {

                                $('#customerCompanyModal')
                                    .modal('hide');
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    text: response.message,
                                    timer: 3000,
                                    showConfirmButton: false,
                                    timerProgressBar: true
                                }).then(function() {

                                    location.reload();

                                });

                            }

                        },

                        error: function(xhr) {

                            if (xhr.status === 422) {

                                let errors =
                                    xhr.responseJSON.errors;

                                $.each(
                                    errors,
                                    function(field, messages) {

                                        let input =
                                            $('[name="' + field + '"]');

                                        input.addClass(
                                            'is-invalid'
                                        );

                                        $('#error-' + field)
                                            .text(messages[0]);

                                    }
                                );

                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message ||
                                        'Something went wrong.'
                                });

                            }

                        },

                        complete: function() {

                            $('#saveCompanyBtn')
                                .prop('disabled', false);

                            $('#saveCompanyText')
                                .text(
                                    editMode ?
                                    'Update' :
                                    'Save'
                                );

                        }

                    });

                }
            );

            $(document).on(
                'click',
                '.deleteCompany',
                function() {

                    let id = $(this).data('id');

                    let name = $(this).data('name');


                    Swal.fire({
                        icon: 'warning',
                        title: 'Delete Company?',
                        text: 'Are you sure you want to delete "' +
                            name +
                            '"?',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',

                        confirmButtonText: 'Yes, Delete',

                        cancelButtonText: 'Cancel'

                    }).then(function(result) {

                        if (!result.isConfirmed) {
                            return;
                        }


                        $.ajax({

                            url: "{{ url('admin/customer-expense') }}/" +
                                id,

                            type: 'POST',

                            data: {

                                _token: "{{ csrf_token() }}",

                                _method: 'DELETE'

                            },

                            success: function(response) {

                                if (response.success) {

                                    $('#row-' + id)
                                        .fadeOut(
                                            300,
                                            function() {

                                                $(this).remove();

                                            }
                                        );


                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        text: response.message,
                                        timer: 3000,
                                        showConfirmButton: false,
                                        timerProgressBar: true
                                    });

                                }

                            },

                            error: function(xhr) {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message ||
                                        'Unable to delete company.'
                                });
                            }
                        });

                    });

                }
            );

            function clearErrors() {
                $('.is-invalid')
                    .removeClass('is-invalid');
                $('.invalid-feedback')
                    .text('');
            }

            $(document).on(
                'input change',
                '#customerCompanyForm input, #customerCompanyForm textarea, #customerCompanyForm select',
                function() {
                    $(this)
                        .removeClass('is-invalid');

                }
            );

        });
    </script>
@endpush
