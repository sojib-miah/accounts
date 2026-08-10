<script>
    let currentRow = null;
    let serialArray = [];

    function initSelect2() {
        $('.select2').select2({
            width: '100%'
        });
    }

    function normalizeSerial(serial) {
        return String(serial || '')
            .trim()
            .toUpperCase();
    }

    function getOtherRowsSerials() {
        let allSerials = [];
        $('.serial_json').each(function() {
            if (
                currentRow &&
                $(this).closest('tr')[0] === currentRow[0]
            ) {
                return;
            }
            let json = $(this).val();
            if (!json || json === '[]') {
                return;
            }
            try {
                let serials = JSON.parse(json);
                if (Array.isArray(serials)) {
                    serials.forEach(function(serial) {
                        if (String(serial).trim() !== '') {
                            allSerials.push(
                                normalizeSerial(serial)
                            );
                        }
                    });
                }
            } catch (e) {
                console.error(
                    'Invalid serial JSON:',
                    e
                );
            }
        });
        return allSerials;
    }

    function calculateSummary() {
        let totalQty = 0;
        let subTotal = 0;
        $('#purchaseBody tr').each(function() {
            let qty = parseFloat(
                $(this).find('.qty').val()
            ) || 0;
            let amount = parseFloat(
                $(this).find('.amount').val()
            ) || 0;
            totalQty += qty;
            subTotal += amount;
        });
        $('#totalQty').val(totalQty);
        $('#subTotal').val(subTotal.toFixed(2));
        let discount = parseFloat($('#discount').val()) || 0;
        let vatPercent = parseFloat($('#vat').val()) || 0;
        let afterDiscount = subTotal - discount;
        if (afterDiscount < 0) {
            afterDiscount = 0;
        }
        let vatAmount = (afterDiscount * vatPercent) / 100;
        let grandTotal = afterDiscount + vatAmount;
        $('#grandTotal').val(grandTotal.toFixed(2));
        let paid = parseFloat($('#paidAmount').val()) || 0;
        let due = grandTotal - paid;
        if (due < 0) {
            due = 0;
        }
        $('#dueAmount').val(
            due.toFixed(2)
        );
    }

    function calculateRow(row) {
        if (!row || row.length === 0) {
            return;
        }
        let qty = parseFloat(row.find('.qty').val()) || 0;
        let rate = parseFloat(row.find('.rate').val()) || 0;
        let amount = qty * rate;
        row.find('.amount').val(amount.toFixed(2));
        calculateSummary();
    }

    function renderSerialList() {
        let html = '';
        if (serialArray.length === 0) {
            $('#serialStatus')
                .removeClass(
                    'text-success text-danger'
                )
                .addClass('text-warning')
                .text('No Serial');

            return;
        } else {
            $.each(serialArray,
                function(index, serial) {
                    let safeSerial = $('<div>').text(serial).html();
                    html += `
                        <tr>
                            <td>
                                ${index + 1}
                            </td>
                            <td>
                                ${safeSerial}
                            </td>
                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm removeSerial"
                                    data-index="${index}"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                }
            );
        }
        $('#serialList').html(html);
        $('#serialCount').text(serialArray.length);
        $('#requiredQty').text(serialArray.length);
        if (!currentRow) {
            $('#serialStatus')
                .removeClass(
                    'text-success text-danger text-warning'
                )
                .text('Waiting...');
            return;
        }
        let qty = parseFloat(currentRow.find('.qty').val()) || 0;
        if (serialArray.length === 0) {
            $('#serialStatus')
                .removeClass(
                    'text-success text-danger'
                )
                .addClass('text-warning')
                .text('No Serial');
            return;
        }
        if (serialArray.length == qty) {
            $('#serialStatus')
                .removeClass(
                    'text-warning text-danger'
                )
                .addClass('text-success')
                .text('Serial Complete');
            return;
        }
        $('#serialStatus')
            .removeClass(
                'text-warning text-success'
            )
            .addClass('text-danger')
            .text(
                'Serial ' +
                serialArray.length +
                ' / Qty ' +
                qty
            );
    }
    $(document).on('click', '.serialBtn', function() {
        currentRow = $(this).closest('tr');
        let json = currentRow.find('.serial_json').val();
        serialArray = [];
        if (json && json !== '[]') {
            try {
                let parsed = JSON.parse(json);
                if (Array.isArray(parsed)) {
                    serialArray = parsed
                        .map(function(serial) {
                            return String(serial).trim();
                        })
                        .filter(function(serial) {
                            return serial !== '';
                        });
                }
            } catch (e) {
                serialArray = [];
            }
        }
        $('#serialInput').val('');
        let productName = currentRow
            .find('.product option:selected')
            .text()
            .trim();
        $('#serialProductName').val(productName);
        renderSerialList();
        $('#serialModal').modal('show');
        setTimeout(function() {
            $('#serialInput').focus();
        }, 300);
    });
    $('#addSerial').on(
        'click',
        function() {
            let serial =
                $('#serialInput').val().trim();
            if (serial === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Serial Required',
                    text: 'Please enter a serial number.'
                });
                $('#serialInput').val('').focus();
                return;
            }
            let normalizedSerial = normalizeSerial(serial);
            let currentDuplicate =
                serialArray.some(
                    function(item) {
                        return normalizeSerial(item) === normalizedSerial;
                    }
                );
            if (currentDuplicate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Serial',
                    text: 'This serial number is already added.'
                });
                $('#serialInput').val('').focus();
                return;
            }
            let otherRowsSerials = getOtherRowsSerials();
            if (
                otherRowsSerials.includes(
                    normalizedSerial
                )
            ) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Serial Number',
                    text: 'This serial number is already used for another product.'
                });
                $('#serialInput').val('').focus();
                return;
            }
            serialArray.push(serial);
            renderSerialList();
            $('#serialInput').val('').focus();
        }
    );
    $('#serialInput').on(
        'keypress',
        function(e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#addSerial').click();
            }
        }
    );
    $(document).on('click', '.removeSerial', function() {
        let index =
            parseInt(
                $(this).data('index')
            );
        if (
            isNaN(index) ||
            index < 0 ||
            index >= serialArray.length
        ) {
            return;
        }
        Swal.fire({
            title: 'Remove Serial?',
            text: 'Are you sure you want to remove this serial number?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Remove',
            cancelButtonText: 'Cancel'
        }).then(
            function(result) {
                if (
                    result.isConfirmed
                ) {
                    serialArray.splice(index, 1);
                    renderSerialList();
                }
            }
        );
    });
    $('#saveSerial').on(
        'click',
        function() {
            if (!currentRow) {
                return;
            }
            let currentQty = parseFloat(currentRow.find('.qty').val()) || 0;
            if (serialArray.length > 0) {
                currentRow.find('.qty').val(serialArray.length);
                currentRow.find('.serial_json').val(JSON.stringify(serialArray));
            } else {
                currentRow.find('.serial_json').val('[]');
            }
            calculateRow(currentRow);
            calculateSummary();
            $('#serialModal').modal('hide');
        }
    );
    $('#serialModal').on(
        'hidden.bs.modal',
        function() {
            $('#serialInput').val('');
        }
    );
    $(document).on('change', '.product', function() {
        let current = $(this);
        let value = current.val();
        let row = current.closest('tr');
        if (value === '') {
            row.find('.unit').val('');
            row.find('.stock').val('');
            row.find('.rate').val('');
            row.find('.amount').val('0.00');
            row.find('.serial_json').val('[]');
            row.find('.qty').val(0);
            calculateSummary();
            return;
        }
        let duplicate = false;
        $('.product')
            .not(current)
            .each(
                function() {
                    if (
                        $(this).val() ===
                        value
                    ) {
                        duplicate = true;
                        return false;
                    }
                }
            );
        if (duplicate) {
            Swal.fire({
                icon: 'warning',
                title: 'Duplicate Product',
                text: 'This product has already been added.'
            });
            current.val('');
            if (
                current.hasClass('select2-hidden-accessible')
            ) {
                current.trigger('change.select2');
            }
            row.find('.unit').val('');
            row.find('.stock').val('');
            row.find('.rate').val('');
            row.find('.amount').val('0.00');
            row.find('.qty').val(0);
            row.find('.serial_json').val('[]');
            calculateSummary();
            return;
        }
        let option = current.find(':selected');
        let unit = option.data('unit');
        let stock = option.data('stock');
        let rate = option.data('rate');
        row.find('.unit').val(unit || '');
        row.find('.stock').val(stock ?? '');
        if (
            rate !== undefined &&
            rate !== null
        ) {
            row.find('.rate').val(rate);
        }
        if (
            !row.find('.qty').val() ||
            parseFloat(
                row.find('.qty').val()
            ) < 1
        ) {
            row.find('.qty').val(1);
        }
        calculateRow(row);
        row.find('.qty').focus();
    });
    $(document).on('input change', '.qty', function() {
        let row = $(this).closest('tr');
        let qty = parseFloat($(this).val()) || 0;
        let json = row.find('.serial_json').val();
        let serials = [];
        if (json) {
            try {
                serials = JSON.parse(json);
            } catch (e) {
                serials = [];
            }
        }
        if (
            Array.isArray(serials) &&
            serials.length > 0 &&
            serials.length !== qty
        ) {
            row.addClass(
                'table-warning'
            );
        } else {
            row.removeClass(
                'table-warning'
            );
        }
        calculateRow(row);
    });
    $(document).on('input change', '.rate', function() {
        calculateRow(
            $(this).closest('tr')
        );
    });
    $(document).on('input change', '#discount, #vat, #paidAmount', function() {
        calculateSummary();
    });
    $('#addRow').on('click', function() {
        let html = $('#purchaseRowTemplate').html();
        $('#purchaseBody').append(html);
        let newRow = $('#purchaseBody tr:last');
        newRow.find('.product').val('').trigger('change');
        newRow.find('.unit').val('');
        newRow.find('.stock').val('');
        newRow.find('.qty').val(0);
        newRow.find('.rate').val('');
        newRow.find('.amount').val('0.00');
        newRow.find('.serial_json').val('[]');
        newRow.find('.select2').select2({
            width: '100%'
        });
        currentRow = null;
        serialArray = [];
        $('#serialInput').val('');
        $('#serialProductName').val('');
        $('#serialList').html('<tr>' + '<td colspan="3" class="text-center text-muted">' + 'No Serial Added' +
            '</td>' + '</tr>');
        $('#serialCount').text('0');
        $('#requiredQty').text('0');
        $('#serialStatus').removeClass('text-success text-danger text-warning').text('Waiting...');
        $('#saveSerial').prop('disabled', false);
        calculateSummary();
        newRow.find('.product').focus();
    });
    $(document).on('click', '.removeRow', function() {
        let rows = $('#purchaseBody tr').length;
        if (rows <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Cannot Remove',
                text: 'At least one product is required.'
            });
            return;
        }
        Swal.fire({
            title: 'Remove Product?',
            text: 'Are you sure you want to remove this product?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Remove',
            cancelButtonText: 'Cancel'
        }).then(
            function(result) {
                if (
                    result.isConfirmed
                ) {
                    $(this).closest('tr').remove();
                    calculateSummary();
                }
            }.bind(this)
        );
    });
    $(document).on('keypress', '.qty', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $(this).closest('tr').find('.rate').focus();
        }
    });
    $(document).on('keypress', '.rate', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#addRow').click();
        }
    });
    $('form').on('submit', function(e) {
        let valid = true;
        let supplier = $('select[name="party_id"]').val();
        if (!supplier) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Supplier Required',
                text: 'Please select supplier.'
            });
            return false;
        }
        $('#purchaseBody tr')
            .each(
                function() {
                    let row = $(this);
                    let product = row.find('.product').val();
                    let qty = parseFloat(row.find('.qty').val()) || 0;
                    let rate = parseFloat(row.find('.rate').val());
                    if (!product) {
                        valid = false;
                        row.addClass(
                            'table-danger'
                        );
                        return;
                    }
                    if (qty < 1) {
                        valid = false;
                        row.addClass('table-danger');
                        return;
                    }
                    if (
                        isNaN(rate) ||
                        rate < 0
                    ) {
                        valid = false;
                        row.addClass('table-danger');
                        return;
                    }
                    let json = row.find('.serial_json').val();
                    let serials = [];
                    if (json) {
                        try {
                            serials = JSON.parse(json);
                        } catch (error) {
                            serials = [];
                        }
                    }
                    if (
                        Array.isArray(serials) &&
                        serials.length > 0
                    ) {
                        if (
                            serials.length !== qty
                        ) {
                            valid = false;
                            row.addClass('table-danger');
                            Swal.fire({
                                icon: 'warning',
                                title: 'Serial Quantity Mismatch',
                                text: 'Product Qty is ' + qty + ' but Serial count is ' + serials
                                    .length + '.'
                            });
                            return false;
                        }
                        let normalized =
                            serials.map(
                                function(serial) {
                                    return normalizeSerial(
                                        serial
                                    );
                                }
                            );
                        let unique = [...new Set(normalized)];
                        if (unique.length !== normalized.length) {
                            valid = false;
                            row.addClass('table-danger');
                            Swal.fire({
                                icon: 'error',
                                title: 'Duplicate Serial',
                                text: 'Duplicate serial found for this product.'
                            });
                            return false;
                        }
                    }
                    row.removeClass('table-danger');
                }
            );
        if (!valid) {
            e.preventDefault();
            return false;
        }
        let allSerials = {};
        let duplicateSerial = null;
        $('.serial_json').each(
            function() {
                let json = $(this).val();
                if (!json) {
                    return;
                }
                let serials = [];
                try {
                    serials = JSON.parse(json);
                } catch (e) {
                    serials = [];
                }
                if (!Array.isArray(serials)) {
                    return;
                }
                serials.forEach(
                    function(serial) {
                        let normalized =
                            normalizeSerial(
                                serial
                            );
                        if (!normalized) {
                            return;
                        }
                        if (
                            allSerials[normalized]
                        ) {
                            duplicateSerial = serial;
                        } else {
                            allSerials[normalized] = true;
                        }
                    }
                );
            }
        );
        if (duplicateSerial) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Duplicate Serial Number',
                text: 'Serial number "' + duplicateSerial + '" is used more than once.'
            });
            return false;
        }
        calculateSummary();
        return true;
    });
    $(function() {
        initSelect2();
        $('#purchaseBody tr')
            .each(
                function() {
                    calculateRow(
                        $(this)
                    );
                }
            );
        calculateSummary();
    });
</script>
