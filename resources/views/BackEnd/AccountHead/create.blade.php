<!-- Add Expense Modal -->
<div class="modal fade" id="addAccountHeadModal" tabindex="-1" aria-labelledby="addAccountHeadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('account-head.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountHeadModalLabel">
                        Add Expense
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Category -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" class="form-select select2" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                        ({{ $category->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Expense Name -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Expense Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Expense Name"
                                required>
                        </div>
                        <!-- Status -->
                        <div class="col-md-12 mb-3">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
