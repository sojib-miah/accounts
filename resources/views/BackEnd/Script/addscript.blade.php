@push('scripts')
    <script>
        let rowNo = 1;
        let currentSerialRow = null;
        let serialModalElement = document.getElementById('serialModal');
        let serialModal = new bootstrap.Modal(serialModalElement);

        function addRow() {
            let html = $('#salesRowTemplate').html();
            $('#salesBody').append(html);
            let row = $('#salesBody tr:last');
            row.find('.product').select2({
                width: '100%'
            });
            row.find('.stock').val('');
            row.find('.qty').val(1);
            row.find('.rate').val(0);
            row.find('.total').val('0.00');
            row.find('.serial_json').val('[]');
            row.find('.serialBtn').prop('disabled', true);
            row.find('.serialCountText').html('<small class="text-muted">No Serial</small>');
            serial();
            calculate();
            rowNo++;
        }
        $(function() {
            if ($('#salesBody tr').length === 0) {
                addRow();
            }
        });
        $(document).on('click', '#addRow', function() {
            addRow();
            setTimeout(function() {
                $('#salesBody tr:last').find('.product').select2('open');
            }, 200);
        });

        function serial() {
            let i = 1;
            $('#salesBody tr').each(
                function() {
                    $(this).find('.sl').text(i);
                    i++;
                }
            );
        }
        $(document).on('change', '.product', function() {
            let row = $(this).closest('tr');
            let option = $(this).find(':selected');
            let productId = $(this).val();
            if (!productId) {
                row.find('.stock').val('');
                row.find('.rate').val(0);
                row.find('.serialBtn').prop('disabled', true);
                row.find('.serial_json').val('[]');
                row.find('.serialCountText').html('<small class="text-muted">No Serial</small>');
                calculate();
                return;
            }
            let duplicate = false;
            $('.product').not(this).each(function() {
                if ($(this).val() == productId && productId !== '') {
                    duplicate = true;
                }
            });
            if (duplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Product',
                    text: 'This product has already been added.'
                });
                $(this).val('').trigger('change');
                return;
            }
            let stock = parseFloat(option.data('stock')) || 0;
            row.find('.stock').val(stock);
            let price = parseFloat(option.data('price')) || 0;
            row.find('.rate').val(price);
            row.find('.serial_json').val('[]');
            row.find('.serialCountText').html('<small class="text-muted">Loading...</small>');
            row.find('.serialBtn').prop('disabled', false);
            loadSerialCount(row, productId);
            row.find('.qty').val(1);
            calculate();
            row.find('.qty').focus();
        });

        function loadSerialCount(row, productId) {
            let branchId = $('#branch_id').val();
            let url = "{{ route('ajax.product.availableSerials', ':product') }}";
            url = url.replace(':product', productId);
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    branch_id: branchId
                },
                success: function(res) {
                    if (!res.success) {
                        row.find(
                            '.serialCountText'
                        ).html(
                            '<small class="text-danger">Error</small>'
                        );
                        return;
                    }
                    let count = parseInt(res.count) || 0;

                    if (count > 0) {
                        row.find(
                            '.serialCountText'
                        ).html(
                            '<small class="text-success">' + count + ' Available' + '</small>'
                        );
                    } else {
                        row.find(
                            '.serialCountText'
                        ).html(
                            '<small class="text-muted">' +
                            'No Serial' +
                            '</small>'
                        );
                    }
                },
                error: function() {
                    row.find(
                        '.serialCountText'
                    ).html(
                        '<small class="text-danger">' +
                        'Unable to load' +
                        '</small>'
                    );
                }
            });
        }
        $(document).on('click', '.serialBtn', function() {
            currentSerialRow = $(this).closest('tr');
            let productId = currentSerialRow.find('.product').val();
            if (!productId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Product',
                    text: 'Please select a product first.'
                });
                return;
            }
            let productName = currentSerialRow.find('.product option:selected').text().trim();
            let qty = parseInt(currentSerialRow.find('.qty').val()) || 0;
            $('#serialProductName').val(productName);
            $('#serialRequiredQty').val(qty);
            $('#serialSelectedQty').val(0);
            $('#serialList')
                .html(
                    '<div class="text-center py-4">' +
                    '<div class="spinner-border text-primary">' +
                    '</div>' +
                    '<div class="mt-2">' +
                    'Loading serial numbers...' +
                    '</div>' +
                    '</div>'
                );
            $('#serialModalError').addClass('d-none').text('');
            $('#selectAllSerial')
                .prop(
                    'checked',
                    false
                );
            $('#serialSearch').val('');
            serialModal.show();
            loadSerials(
                productId
            );
        });

        function loadSerials(productId) {
            let branchId = $('#branch_id').val();
            let url = "{{ route('ajax.product.availableSerials', ':product') }}";
            url = url.replace(':product', productId);
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    branch_id: branchId
                },
                success: function(res) {
                    if (!res.success) {
                        showSerialError(res.message || 'Unable to load serial numbers.');
                        return;
                    }
                    let serials = res.serials || [];
                    let selected = getCurrentSelectedSerials();
                    $('#serialAvailableText')
                        .text(serials.length + ' Available');
                    if (serials.length === 0) {
                        $('#serialList')
                            .html(
                                '<div class="alert alert-warning mb-0">' +
                                '<i class="fa fa-info-circle me-1"></i>' +
                                'No available serial number found for this product.' +
                                '</div>'
                            );
                        $('#selectAllSerial')
                            .prop('disabled', true);
                        updateSelectedCount();
                        return;
                    }
                    $('#selectAllSerial')
                        .prop('disabled', false);
                    let html = '';
                    $.each(serials, function(index, item) {
                        let checked =
                            selected.includes(
                                String(
                                    item.serial_no
                                )
                            );
                        html += `
                        <div
                            class="form-check border-bottom py-2 serial-item"
                            data-serial="${escapeHtml(item.serial_no)}">
                            <input
                                type="checkbox"
                                class="form-check-input serialCheckbox"
                                id="serial_${item.id}"
                                value="${escapeHtml(item.serial_no)}"
                                ${checked ? 'checked' : ''}>
                            <label
                                class="form-check-label w-100"
                                for="serial_${item.id}">
                                <strong>
                                    ${escapeHtml(item.serial_no)}
                                </strong>
                            </label>
                        </div>
                    `;
                    });
                    $('#serialList').html(html);
                    updateSelectedCount();
                    updateSelectAll();
                },
                error: function(xhr) {
                    let message = 'Unable to load serial numbers.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showSerialError(
                        message
                    );
                }
            });
        }

        function getCurrentSelectedSerials() {
            if (!currentSerialRow) {
                return [];
            }
            let value = currentSerialRow.find('.serial_json').val();
            if (!value) {
                return [];
            }
            try {
                let data = JSON.parse(value);
                if (!Array.isArray(data)) {
                    return [];
                }
                return data.map(
                    function(serial) {
                        return String(
                            serial
                        );
                    }
                );
            } catch (error) {
                return [];
            }
        }
        $(document).on('change', '.serialCheckbox', function() {
            let qty = parseInt($('#serialRequiredQty').val()) || 0;
            let selectedCount = $('.serialCheckbox:checked').length;
            if ($(this).is(':checked') && selectedCount > qty) {
                $(this)
                    .prop('checked', false);
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantity Limit',
                    text: 'You can select only ' + qty + ' serial number(s).'
                });
                updateSelectedCount();
                updateSelectAll();
                return;
            }
            updateSelectedCount();
            updateSelectAll();
        });
        $('#selectAllSerial').on('change', function() {
            let checked = $(this).is(':checked');
            let qty = parseInt($('#serialRequiredQty').val()) || 0;
            let checkboxes = $('.serialCheckbox');
            if (!checked) {
                checkboxes.prop('checked', false);
                updateSelectedCount();
                return;
            }
            checkboxes.prop('checked', false);
            checkboxes.slice(0, qty).prop('checked', true);
            updateSelectedCount();
            updateSelectAll();
        });

        function updateSelectedCount() {
            let count = $('.serialCheckbox:checked').length;
            $('#serialSelectedQty').val(count);
            let qty = parseInt($('#serialRequiredQty').val()) || 0;
            if (count === qty) {
                $('#serialSelectedQty')
                    .removeClass('is-invalid')
                    .addClass('is-valid');
            } else {
                $('#serialSelectedQty')
                    .removeClass('is-valid')
                    .addClass('is-invalid');
            }
        }

        function updateSelectAll() {
            let total = $('.serialCheckbox').length;
            let checked = $('.serialCheckbox:checked').length;
            $('#selectAllSerial').prop('checked', total > 0 && total === checked);
        }
        $('#serialSearch').on('keyup', function() {
            let search = $(this).val().toLowerCase().trim();
            $('.serial-item').each(function() {
                let serial =
                    String($(this).data('serial')).toLowerCase();
                if (serial.includes(search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        $('#saveSerialSelection').on('click', function() {
            if (!currentSerialRow) {
                return;
            }
            let qty = parseInt(currentSerialRow.find('.qty').val()) || 0;
            let selected = [];
            $('.serialCheckbox:checked')
                .each(
                    function() {
                        selected.push(
                            $(this).val()
                        );
                    }
                );
            if (selected.length !== qty) {
                showSerialError('Please select exactly ' + qty + ' serial number(s). ' + 'Currently selected: ' +
                    selected.length);
                return;
            }
            currentSerialRow
                .find('.serial_json')
                .val(
                    JSON.stringify(
                        selected
                    )
                );
            updateRowSerialDisplay(
                currentSerialRow,
                selected
            );
            serialModal.hide();

        });

        function updateRowSerialDisplay(row, serials) {
            let html = '';
            if (!serials || serials.length === 0) {
                html = '<small class="text-muted">' + 'No Serial' + '</small>';
            } else {
                html = '<span class="badge bg-success">' + serials.length + ' Selected' + '</span>';
            }
            row.find('.serialCountText').html(html);
        }

        function showSerialError(message) {
            $('#serialModalError').removeClass('d-none').text(message);
        }

        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }
        $(document).on('keyup change', '.qty', function() {
            let row = $(this).closest('tr');
            let qty = parseInt($(this).val()) || 0;
            let stock = parseFloat(row.find('.stock').val()) || 0;
            if (qty > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Not Available',
                    text: 'Available Stock : ' + stock
                });
                qty = stock;
                $(this).val(stock);
            }
            let selected = getRowSerials(row);
            if (selected.length > qty) {
                selected = selected.slice(0, qty);
                row.find('.serial_json').val(JSON.stringify(selected));
                updateRowSerialDisplay(row, selected);
            }
            calculate();
        });

        function getRowSerials(row) {
            let value = row.find('.serial_json').val();
            if (!value) {
                return [];
            }
            try {
                let serials = JSON.parse(value);
                return Array.isArray(serials) ? serials : [];
            } catch (e) {
                return [];
            }
        }

        function calculate() {
            let totalQty = 0;
            let subTotal = 0;
            $('#salesBody tr')
                .each(
                    function() {
                        let row = $(this);
                        let stock = parseFloat(row.find('.stock').val()) || 0;
                        let qty = parseFloat(row.find('.qty').val()) || 0;
                        let rate = parseFloat(row.find('.rate').val()) || 0;
                        let product = row.find('.product').val();
                        if (product && qty > stock) {
                            qty = stock;
                            row.find('.qty').val(stock);
                        }
                        if (qty < 0) {
                            qty = 0;
                            row.find('.qty').val(0);
                        }
                        let amount = qty * rate;
                        row.find('.total').val(amount.toFixed(2));
                        totalQty += qty;
                        subTotal += amount;
                    }
                );
            let discount = parseFloat($('#discount').val()) || 0;
            if (discount > subTotal) {
                discount = subTotal;
                $('#discount').val(discount.toFixed(2));
            }
            let vatPercent = parseFloat($('#vat').val()) || 0;
            let afterDiscount = subTotal - discount;
            let vatAmount = (afterDiscount * vatPercent) / 100;
            let grandTotal = afterDiscount + vatAmount;
            $('#total_qty').val(totalQty);
            $('#sub_total').val(subTotal.toFixed(2));
            $('#grand_total').val(grandTotal.toFixed(2));
        }
        $(document).on('keyup change', '#discount, #vat, .rate', function() {
            calculate();
        });
        $(document).on('click', '.remove', function(e) {
            e.preventDefault();
            if ($('#salesBody tr').length <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'At least one product is required.'
                });
                return;
            }
            let row = $(this).closest('tr');
            Swal.fire({
                title: 'Remove Product?',
                text: 'This product will be removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then(
                function(result) {
                    if (result.isConfirmed) {
                        row.remove();
                        serial();
                        calculate();
                    }
                }
            );
        });
        $(document).on('keydown', '.qty', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $(this).closest('tr').find('.rate').focus();
            }
        });
        $(document).on('keydown', '.rate', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $(this).closest('tr').find('.details').focus();
            }
        });
        $(document).on('keydown', '.details', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addRow();
                $('#salesBody tr:last').find('.product').focus();
            }
        });
        $(document).on('focus', '.qty,.rate,#discount,#vat', function() {
            $(this).on('wheel.disableScroll', function(e) {
                e.preventDefault();
            });
        });
        $(document).on('blur', '.qty,.rate,#discount,#vat', function() {
            $(this).off(
                'wheel.disableScroll'
            );
        });
        $('#receiptForm').on('submit', function(e) {
            let valid = true;
            $('#salesBody tr').each(function() {
                let row = $(this);
                row.removeClass(
                    'table-danger'
                );
                let product = row.find('.product').val();
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let rate = row.find('.rate').val();
                if (product === '' || qty <= 0 || rate === '') {
                    valid = false;
                    row.addClass(
                        'table-danger'
                    );
                    return;
                }
                let serials = getRowSerials(row);
                let stock = parseFloat(row.find('.stock').val()) || 0;
                if (serials.length > 0 && serials.length !== qty) {
                    valid = false;
                    row.addClass('table-danger');
                }
            });
            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Data',
                    text: 'Please complete all product and serial information.'
                });
                return false;
            }
        });
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.key.toLowerCase() === 's') {
                e.preventDefault();
                $('#receiptForm').submit();
            }
        });
        $('#company_id').change(function() {
            let company = $(this).val();
            if (company === '') {
                $('#branch_id')
                    .html('<option value="">Select Branch</option>');
                $('#name').text('');
                return;
            }
            $.get(
                '/admin/ajax/company/' + company + '/branches',
                function(res) {
                    $('#name')
                        .text(res.company.name ?? '');
                    let html = '<option value="">Select Branch</option>';
                    $.each(
                        res.branches,
                        function(i, item) {
                            html += '<option value="' + item.id + '">' + item.name + '</option>';
                        }
                    );
                    $('#branch_id').html(html).trigger('change');
                }
            );
        });
        $('#branch_id').change(function() {
            let id = $(this).val();
            if (id === '') {
                $('#company_name').text('');
                $('#branch_name').text('');
                $('#branch_phone').text('');
                $('#branch_email').text('');
                $('#branch_address').text('');
                return;
            }
            $.get('/admin/ajax/branch/' + id,
                function(res) {
                    $('#company_name').text(res.data.company_name ?? '');
                    $('#branch_name').text(res.data.name);
                    $('#branch_phone').text(res.data.phone);
                    $('#branch_email').text(res.data.email);
                    $('#branch_address').text(res.data.address);
                    $('#salesBody tr').each(function() {
                        let row = $(this);
                        let productId = row.find('.product').val();
                        if (productId) {
                            loadSerialCount(row, productId);
                        }
                    });
                }
            );
        });
        $('#party_id').change(function() {
            let id = $(this).val();
            if (id === '') {
                $('#party_id_text').text('');
                $('#party_name').text('');
                $('#party_phone').text('');
                $('#party_address').text('');
                $('#party_email').text('');
                $('#party_designation').text('');
                return;
            }
            $.get('/admin/ajax/party/' + id,
                function(res) {
                    $('#party_id_text').text(res.data.id);
                    $('#party_name').text(res.data.name);
                    $('#party_email').text(res.data.email);
                    $('#party_designation').text(res.data.designation);
                    $('#party_phone').text(res.data.phone);
                    $('#party_address').text(res.data.address);
                }
            );
        });
        calculate();
    </script>
@endpush
