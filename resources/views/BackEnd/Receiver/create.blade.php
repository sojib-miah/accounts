<!-- Add Party Modal -->
<div class="modal fade" id="addReceiverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('receiver.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add Customer Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Receiver Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Customer Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                placeholder="Enter Receiver Name" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Designation -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Designation
                            </label>
                            <input type="text" name="designation" class="form-control"
                                placeholder="Enter designation" value="{{ old('designation') }}">
                            @error('designation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Phone
                            </label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Email
                            </label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Email Address"
                                value="{{ old('email') }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Status
                            </label>
                            <select name="status" class="form-select select2">
                                <option value="Active" selected>
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
                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Address
                            </label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Enter Address"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>
                        Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
