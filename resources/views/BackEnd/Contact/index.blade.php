@extends('BackEnd.Layouts.layout')

@section('title', 'Contact')

@section('content')
    <div class="mt-5">
        <div class="p-5">
            {{-- Page Header --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Contact Requests</h4>
                    <p class="text-muted mb-0">
                        Manage all contact and demo requests.
                    </p>
                </div>
                <div>
                    <span class="badge bg-primary fs-6">
                        {{ $contacts->count() }} Contacts
                    </span>
                </div>
            </div>

            {{-- Contact Table Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            Contact Requests
                        </h5>
                        <span class="text-muted small">
                            Latest requests first
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Submitted</th>
                                        <th class="text-end px-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            {{-- ID --}}
                                            <td class="px-4">
                                                <span class="text-muted">
                                                    #{{ $contact->id }}
                                                </span>
                                            </td>
                                            {{-- Name --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{-- Avatar --}}
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 42px; height: 42px;">
                                                        {{ strtoupper(substr($contact->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $contact->name }}
                                                        </div>
                                                        <small class="text-muted">
                                                            Contact Request
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- Email --}}
                                            <td>
                                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                                    {{ $contact->email }}
                                                </a>
                                            </td>
                                            {{-- Phone --}}
                                            <td>
                                                @if ($contact->phone)
                                                    <a href="tel:{{ $contact->phone }}" class="text-decoration-none">
                                                        {{ $contact->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">
                                                        N/A
                                                    </span>
                                                @endif
                                            </td>
                                            {{-- Date --}}
                                            <td>
                                                <div>
                                                    {{ $contact->created_at->format('d M Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $contact->created_at->format('h:i A') }}
                                                </small>
                                            </td>
                                            {{-- Actions --}}
                                            <td class="text-end px-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    {{-- View Button --}}
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#contactModal{{ $contact->id }}">
                                                        <i class="fa fa-eye me-1"></i>
                                                    </button>
                                                    {{-- Delete Button --}}
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $contact->id }}">
                                                        <i class="fa fa-trash me-1"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        {{-- CONTACT DETAILS MODAL --}}
                                        <div class="modal fade" id="contactModal{{ $contact->id }}" tabindex="-1"
                                            aria-labelledby="contactModalLabel{{ $contact->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    {{-- Modal Header --}}
                                                    <div class="modal-header">
                                                        <div>
                                                            <h5 class="modal-title"
                                                                id="contactModalLabel{{ $contact->id }}">
                                                                Contact Details
                                                            </h5>
                                                            <small class="text-muted">
                                                                Request #{{ $contact->id }}
                                                            </small>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    {{-- Modal Body --}}
                                                    <div class="modal-body">
                                                        {{-- Contact Header --}}
                                                        <div class="text-center mb-4">
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                                                style="width: 70px; height: 70px; font-size: 28px;">
                                                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                                                            </div>
                                                            <h4 class="mb-1">
                                                                {{ $contact->name }}
                                                            </h4>
                                                            <p class="text-muted mb-0">
                                                                Contact Request
                                                            </p>
                                                        </div>
                                                        {{-- Details --}}
                                                        <div class="row g-3">
                                                            {{-- Name --}}
                                                            <div class="col-md-6">
                                                                <div class="border rounded p-3 h-100">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Name
                                                                    </small>
                                                                    <div class="fw-semibold">
                                                                        {{ $contact->name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Email --}}
                                                            <div class="col-md-6">
                                                                <div class="border rounded p-3 h-100">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Email
                                                                    </small>
                                                                    <a href="mailto:{{ $contact->email }}"
                                                                        class="fw-semibold text-decoration-none">
                                                                        {{ $contact->email }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            {{-- Phone --}}
                                                            <div class="col-md-6">
                                                                <div class="border rounded p-3 h-100">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Phone
                                                                    </small>
                                                                    @if ($contact->phone)
                                                                        <a href="tel:{{ $contact->phone }}"
                                                                            class="fw-semibold text-decoration-none">
                                                                            {{ $contact->phone }}
                                                                        </a>
                                                                    @else
                                                                        <span class="text-muted">
                                                                            Not provided
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            {{-- Submitted Date --}}
                                                            <div class="col-md-6">
                                                                <div class="border rounded p-3 h-100">
                                                                    <small class="text-muted d-block mb-1">
                                                                        Submitted At
                                                                    </small>
                                                                    <div class="fw-semibold">
                                                                        {{ $contact->created_at->format('d M Y, h:i A') }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Message --}}
                                                            <div class="col-12">
                                                                <div class="border rounded p-3">
                                                                    <small class="text-muted d-block mb-2">
                                                                        Message
                                                                    </small>
                                                                    @if ($contact->message)
                                                                        <div class="bg-light rounded p-3"
                                                                            style="white-space: pre-line;">
                                                                            {{ $contact->message }}
                                                                        </div>
                                                                    @else
                                                                        <span class="text-muted">
                                                                            No message provided.
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Modal Footer --}}
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $contact->id }}"
                                                            data-bs-dismiss="modal">
                                                            <i class="bi bi-trash me-1"></i>
                                                            Delete Contact
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- DELETE CONFIRMATION MODAL --}}
                                        <div class="modal fade" id="deleteModal{{ $contact->id }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel{{ $contact->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $contact->id }}">
                                                            Delete Contact
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center py-4">
                                                        <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                                            style="width: 60px; height: 60px;">
                                                            <i class="bi bi-trash fs-4"></i>
                                                        </div>
                                                        <h5>
                                                            Are you sure?
                                                        </h5>
                                                        <p class="text-muted mb-0">
                                                            You are about to delete
                                                            <strong>
                                                                {{ $contact->name }}
                                                            </strong>
                                                            from your contacts.
                                                        </p>
                                                        <p class="text-danger small mt-2 mb-0">
                                                            This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Cancel
                                                        </button>
                                                        {{-- Delete Form --}}
                                                        <form action="{{ route('contact.destroy', $contact->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash me-1"></i>
                                                                Yes, Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 70px; height: 70px;">
                                <i class="bi bi-inbox fs-2 text-muted"></i>
                            </div>
                            <h5 class="mb-2">
                                No Contact Requests
                            </h5>
                            <p class="text-muted mb-0">
                                Contact requests will appear here when someone submits the form.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
