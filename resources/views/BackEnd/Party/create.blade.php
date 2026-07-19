<!-- Add Party Modal -->
<div class="modal fade" id="addPartyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('party.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        Add Payee
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Party Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Payee Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Payee Name"
                                required>
                        </div>
                        <!-- designation -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Designation
                            </label>
                            <input type="text" name="designation" class="form-control"
                                placeholder="Enter designation">
                        </div>
                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Phone
                            </label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number">
                        </div>
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Email
                            </label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Email Address">
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
                        Save Payee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
