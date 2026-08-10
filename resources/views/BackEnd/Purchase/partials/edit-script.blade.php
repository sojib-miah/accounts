<script>
    $(function() {
        let currentRow = null;
        let serialArray = [];
        let originalSerialArray = [];
        let serialSaved = false;

        function initSelect2() {
            $('.select2').select2({
                width: '100%'
            });
        }
        initSelect2();

        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }

        function getRowSerials(row) {
            let input = row.find('.serial_json');
            if (!input.length) {
                return [];
            }
            let json = input.val();
            if (!json || json === '') {
                return [];
            }
            try {
                let data = JSON.parse(json);
                if (!Array.isArray(data)) {
                    return [];
                }
                return data
                    .map(function(serial) {
                        return String(serial).trim().toUpperCase();
                    })
                    .filter(function(serial) {
                        return serial !== '';
                    });
            } catch (error) {
                console.error('Serial JSON Error:', error);
                return [];
            }
        }

        function saveRowSerials(row, serials) {
            row.find('.serial_json').val(
                JSON.stringify(serials)
            );
        }

        function renderSerialList() {
            let html = '';
            if (serialArray.length === 0) {
                html = `<tr><td colspan="3" class="text-center text-muted">No Serial Added</td></tr>`;
            } else {
                $.each(
                    serialArray,
                    function(index, serial) {
                        html += `
                        <tr>
                            <td>
                                ${index + 1}
                            </td>
                            <td>
                                ${escapeHtml(serial)}
                            </td>
                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm removeSerial"
                                    data-index="${index}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    }
                );
            }
            $('#serialList').html(html);
            $('#serialCount').text(serialArray.length);
            $('#requiredQty').text(serialArray.length);

            if (serialArray.length === 0) {
                $('#serialStatus')
                    .removeClass('text-success text-danger').addClass('text-warning').text('No Serial Added');
            } else {
                $('#serialStatus').removeClass('text-warning text-danger').addClass('text-success').text(
                    serialArray.length + ' Serial Added');
            }
        }

        function calculateSummary() {
            let totalQty = 0;
            let subTotal = 0;
            $('#purchaseBody tr')
                .each(
                    function() {
                        let qty = parseFloat($(this).find('.qty').val()) || 0;
                        let amount = parseFloat($(this).find('.amount').val()) || 0;
                        totalQty += qty;
                        subTotal += amount;
                    }
                );
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
            $('#dueAmount').val(due.toFixed(2));
        }

        function calculateRow(row) {
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let rate = parseFloat(row.find('.rate').val()) || 0;
            let amount = qty * rate;
            row.find('.amount').val(amount.toFixed(2));
            calculateSummary();
        }
        $('#purchaseBody tr')
            .each(
                function() {
                    let row = $(this);
                    calculateRow(row);
                }
            );
        calculateSummary();
        $(document).on('change', '.product', function() {
            let current = $(this);
            let value = current.val();
            if (!value) {
                return;
            }
            let duplicate = false;
            $('.product')
                .not(current)
                .each(
                    function() {
                        if ($(this).val() == value) {
                            duplicate = true;
                        }
                    }
                );
            if (duplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Product',
                    text: 'This product is already selected.'
                });
                current.val('').trigger('change');
                let duplicateRow = current.closest('tr');
                duplicateRow.find('.unit').val('');
                duplicateRow.find('.stock').val('');
                duplicateRow.find('.rate').val('');
                duplicateRow.find('.amount').val('0.00');
                duplicateRow.find('.serial_json').val('[]');
                duplicateRow.find('.qty').val(1);
                calculateSummary();
                return;
            }
            let row = current.closest('tr');
            let option = current.find(':selected');
            row.find('.unit').val(option.data('unit') || '');
            row.find('.stock').val(option.data('stock') ?? '');

            if (option.data('rate') !== undefined) {
                row.find('.rate').val(option.data('rate'));
            }
            row.find('.serial_json').val('[]');
            row.find('.qty').val(1);
            calculateRow(row);
            row.find('.qty').focus();
        });
        $(document).on('keyup change', '.qty', function() {
            let row = $(this).closest('tr');
            let qty = parseFloat($(this).val()) || 0;
            let serials = getRowSerials(row);
            if (serials.length > 0 && qty !== serials.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Serial Quantity',
                    text: 'This product has ' + serials.length +
                        ' serial number(s). Please use Serial button to change quantity.'
                });
                $(this).val(serials.length);
            }
            calculateRow(row);
        });
        $(document).on('keyup change', '.rate', function() {
            calculateRow(
                $(this).closest('tr')
            );
        });
        $('#discount, #vat, #paidAmount').on('keyup change', function() {
            calculateSummary();
        });
        $('#addRow').on('click', function() {
            let html = $('#purchaseRowTemplate').html();
            $('#purchaseBody').append(html);
            let newRow = $('#purchaseBody tr:last');
            newRow.find('.serial_json').val('[]');
            newRow.find('.qty').val(1);
            newRow.find('.rate').val('');
            newRow.find('.amount').val('0.00');
            newRow.find('.select2').select2({
                width: '100%'
            });
            calculateSummary();
        });
        $(document).on('click', '.removeRow', function() {
            if ($('#purchaseBody tr').length <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'Minimum one product is required.'
                });
                return;
            }
            let row = $(this).closest('tr');
            Swal.fire({
                icon: 'warning',
                title: 'Remove Product?',
                text: 'Are you sure you want to remove this product?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel'
            }).then(function(result) {
                if (result.isConfirmed) {
                    row.remove();
                    calculateSummary();
                }
            });
        });
        $(document).on('click', '.serialBtn', function() {
            currentRow = $(this).closest('tr');
            let productId = currentRow.find('.product').val();
            if (!productId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Product',
                    text: 'Please select a product first.'
                });
                return;
            }
            let productName = currentRow.find('.product option:selected').text().trim();
            $('#serialProductName').val(productName);
            serialArray = getRowSerials(currentRow);
            originalSerialArray = [...serialArray];
            serialSaved = false;
            renderSerialList();
            let modalElement =
                document.getElementById(
                    'serialModal'
                );
            let modal =
                bootstrap.Modal
                .getOrCreateInstance(
                    modalElement
                );
            modal.show();
            setTimeout(
                function() {
                    $('#serialInput').val('').focus();
                },
                300
            );
        });
        $('#addSerial').on('click', function() {
            let serial = $('#serialInput').val().trim().toUpperCase();
            if (serial === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Serial Required',
                    text: 'Please enter serial number.'
                });
                $('#serialInput').focus();
                return;
            }
            let duplicate =
                serialArray.some(
                    function(item) {
                        return String(item).trim().toUpperCase() === serial;
                    }
                );
            if (duplicate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Serial',
                    text: 'This serial number is already added.'
                });
                $('#serialInput').val('').focus();
                return;
            }

            serialArray.push(serial);
            renderSerialList();
            $('#serialInput').val('').focus();
        });
        $('#serialInput').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#addSerial').trigger('click');
            }

        });
        $(document).on('click', '.removeSerial', function() {
            let index = parseInt($(this).data('index'));
            if (isNaN(index)) {
                return;
            }
            Swal.fire({
                icon: 'warning',
                title: 'Remove Serial?',
                text: 'Are you sure you want to remove this serial?',
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
        $('#saveSerial').on('click', function() {
            if (!currentRow) {
                return;
            }
            serialSaved = true;
            saveRowSerials(
                currentRow,
                serialArray
            );
            if (serialArray.length > 0) {
                currentRow
                    .find('.qty')
                    .val(
                        serialArray.length
                    );
            }
            calculateRow(
                currentRow
            );
            calculateSummary();
            let modalElement =
                document.getElementById(
                    'serialModal'
                );
            let modal =
                bootstrap.Modal
                .getInstance(
                    modalElement
                );
            if (modal) {
                modal.hide();
            }
        });
        $('#serialModal').on('hidden.bs.modal', function() {
            if (currentRow && serialSaved === false) {
                saveRowSerials(
                    currentRow,
                    originalSerialArray
                );
                if (originalSerialArray.length > 0) {
                    currentRow.find('.qty').val(originalSerialArray.length);
                }
                calculateRow(
                    currentRow
                );
                calculateSummary();
            }

            $('#serialInput')
                .val('');
            serialArray = [];
            originalSerialArray = [];
            currentRow = null;
            serialSaved = false;
        });
        $('form').on('submit', function(e) {
            let valid = true;
            if ($('select[name="party_id"]').val() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Supplier Required',
                    text: 'Please select supplier.'
                });
                e.preventDefault();
                return false;
            }
            $('.product').each(
                function() {
                    if (
                        $(this).val() === ''
                    ) {
                        valid = false;
                    }
                }
            );
            if (!valid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Product Required',
                    text: 'Please select all products.'
                });
                e.preventDefault();
                return false;
            }
            let serialValid = true;
            let serialError = '';

            $('#purchaseBody tr')
                .each(
                    function() {
                        let row = $(this);
                        let productName = row.find('.product option:selected').text().trim();
                        let qty = parseFloat(row.find('.qty').val()) || 0;

                        let serials = getRowSerials(row);
                        if (serials.length === 0) {
                            return;
                        }
                        if (serials.length !== qty) {
                            serialValid = false;
                            serialError = productName + ' → Qty: ' + qty + ', Serial: ' + serials
                                .length;
                            return false;
                        }
                        let unique = [...new Set(serials)];
                        if (unique.length !== serials.length) {
                            serialValid = false;
                            serialError = productName + ' → Duplicate serial found.';
                            return false;
                        }
                    }
                );
            if (!serialValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Serial Quantity Error',
                    text: serialError
                });
                e.preventDefault();
                return false;
            }
            calculateSummary();
            return true;
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
                $('#addRow').click();
            }
        });
        calculateSummary();
    });
</script>
