<!-- Edit Receiver Modal -->
@foreach ($parties as $party)
    <div class="modal fade" id="editReceiverModal{{ $party->id }}" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('receiver.update', $party->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Edit Customer Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- company name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Company <span class="text-danger">*</span></label>
                                <select name="customer_company_id"
                                    class="form-select select2 @error('customer_company_id', 'edit') is-invalid @enderror"
                                    required>
                                    <option value="">Select Customer Company</option>
                                    @foreach ($customerCompanies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ (string) old('customer_company_id', $party->customer_company_id) === (string) $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_company_id', 'edit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Receiver Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    value="{{ session('edit_receiver_id') == $party->id && $errors->getBag('edit')->any() ? old('name', $party->name) : $party->name }}"
                                    class="form-control @error('name', 'edit') is-invalid @enderror" required
                                    placeholder="Enter Customer Name...">
                                @error('name', 'edit')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- designation -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation"
                                    value="{{ session('edit_receiver_id') == $party->id && $errors->getBag('edit')->any() ? old('designation', $party->designation) : $party->designation }}"
                                    class="form-control @error('designation', 'edit') is-invalid @enderror"
                                    placeholder="Enter Designation...">
                                @error('designation', 'edit')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone"
                                    value="{{ session('edit_receiver_id') == $party->id && $errors->getBag('edit')->any() ? old('phone', $party->phone) : $party->phone }}"
                                    class="form-control @error('phone', 'edit') is-invalid @enderror"
                                    placeholder="Enter Phone...">
                                @error('phone', 'edit')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    value="{{ session('edit_receiver_id') == $party->id && $errors->getBag('edit')->any() ? old('email', $party->email) : $party->email }}"
                                    class="form-control @error('email', 'edit') is-invalid @enderror"
                                    placeholder="Enter Email...">
                                @error('email', 'edit')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select select2 @error('status', 'edit') is-invalid @enderror">
                                    <option value="Active"
                                        {{ old('status', $party->status) == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $party->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status', 'edit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Address -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" rows="3" class="form-control @error('address', 'edit') is-invalid @enderror"
                                    placeholder="Enter Address...">{{ session('edit_receiver_id') == $party->id && $errors->getBag('edit')->any() ? old('address', $party->address) : $party->address }}</textarea>
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
                            <i class="fa fa-save me-1"></i>
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
