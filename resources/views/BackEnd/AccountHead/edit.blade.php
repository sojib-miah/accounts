<!-- Edit Expense Modal -->
@foreach ($accountHeads as $head)
    <div class="modal fade" id="editAccountHeadModal{{ $head->id }}" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('account-head.update', $head->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Edit Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Category -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Expense Category <span class="text-danger">*</span></label>
                                <select name="category_id"
                                    class="form-select select2 @error('category_id', 'edit') is-invalid @enderror"
                                    required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option
                                            {{ (string) old('category_id', $head->category_id) === (string) $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">
                                            {{ $category->name }} ({{ $category->type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id', 'edit')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Expense Name -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Expense Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    value="{{ session('edit_accountHead_id') == $head->id && $errors->getBag('edit')->any() ? old('name', $head->name) : $head->name }}"
                                    class="form-control @error('name', 'edit') is-invalid @enderror"
                                    placeholder="Enter Expense Name..." required>
                                @error('name', 'edit')
                                    <small class="invalid-feedback">{{ $message }}</small>
                                @enderror
                            </div>
                            <!-- Status -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status"
                                    class="form-select select2 @error('status', 'edit') is-invalid @enderror">
                                    <option value="Active"
                                        {{ old('status', $head->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive"
                                        {{ old('status', $head->status) == 'Inactive' ? 'selected' : '' }}>Inactive
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
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save me-1"></i>
                            Update Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
