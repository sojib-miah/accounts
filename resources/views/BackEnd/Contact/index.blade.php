@extends('BackEnd.Layouts.layout')

@section('title', 'Contact Management')

@section('content')
    <div class="p-5">
        <div class="container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Contact List</h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('contact.index') }}" method="GET"
                        class="d-flex justify-content-center align-items-center gap-2">

                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Contact">

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search me-1"></i>
                            Search
                        </button>

                        @if (request('search'))
                            <a href="{{ route('contact.index') }}" class="btn btn-secondary">
                                Reset
                            </a>
                        @endif

                    </form>
                    @can('contact-create')
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">
                            <i class="fa fa-plus me-2"></i> Add Contact
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
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($contacts as $contact)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->phone ?? '-' }}</td>
                                    <td>{{ $contact->subject ?? '-' }}</td>
                                    <td>{{ $contact->message }}</td>
                                    <td>
                                        @if ($contact->status)
                                            <span class="badge bg-label-success">Read</span>
                                        @else
                                            <span class="badge bg-label-warning">Unread</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('contact-list')
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#viewContactModal{{ $contact->id }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @endcan
                                        @can('contact-edit')
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#editContactModal{{ $contact->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endcan
                                        @can('contact-delete')
                                            <form action="{{ route('contact.destroy', $contact->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No contacts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addContactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('contact.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="4" required>{{ old('message') }}</textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="status" value="1" class="form-check-input"
                                    id="addStatus">
                                <label class="form-check-label" for="addStatus">Mark as read</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Contact</button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($contacts as $contact)
        <div class="modal fade" id="viewContactModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Contact Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Name:</strong>
                                <div>{{ $contact->name }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong>
                                <div>{{ $contact->email }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Phone:</strong>
                                <div>{{ $contact->phone ?? '-' }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Subject:</strong>
                                <div>{{ $contact->subject ?? '-' }}</div>
                            </div>

                            <div class="col-12">
                                <strong>Message:</strong>
                                <div class="border rounded p-3 mt-2">{{ $contact->message }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editContactModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('contact.update', $contact->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Contact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $contact->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $contact->email) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $contact->phone) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control"
                                    value="{{ old('subject', $contact->subject) }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="4" required>{{ old('message', $contact->message) }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="status" value="1" class="form-check-input"
                                        id="editStatus{{ $contact->id }}" @checked(old('status', $contact->status))>
                                    <label class="form-check-label" for="editStatus{{ $contact->id }}">Mark as
                                        read</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Contact</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
