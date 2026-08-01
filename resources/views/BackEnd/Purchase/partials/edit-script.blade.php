<script>
    function initSelect2() {
        $('.select2').select2({
            width: '100%'
        });
    }
    $(function() {
        initSelect2();
        calculateSummary();

        function calculateRow(row) {
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let rate = parseFloat(row.find('.rate').val()) || 0;
            let amount = qty * rate;
            row.find('.amount').val(amount.toFixed(2));
            calculateSummary();
        }

        function calculateSummary() {
            let totalQty = 0;
            let subTotal = 0;
            $('#purchaseBody tr').each(function() {
                totalQty += parseFloat($(this).find('.qty').val()) || 0;
                subTotal += parseFloat($(this).find('.amount').val()) || 0;
            });
            $('#totalQty').val(totalQty);
            $('#subTotal').val(subTotal.toFixed(2));
            let discount = parseFloat($('#discount').val()) || 0;
            // VAT Percentage
            let vatPercent = parseFloat($('#vat').val()) || 0;
            // VAT Amount
            let vatAmount = ((subTotal - discount) * vatPercent) / 100;
            let grandTotal = (subTotal - discount) + vatAmount;
            $('#grandTotal').val(grandTotal.toFixed(2));
            let paid = parseFloat($('#paidAmount').val()) || 0;
            let due = grandTotal - paid;
            if (due < 0) {
                due = 0;
            }
            $('#dueAmount').val(due.toFixed(2));
        }
        $(document).on('change', '.product', function() {
            let current = $(this);
            let value = current.val();
            if (value !== '') {
                let duplicate = false;
                $('.product').not(current).each(function() {
                    if ($(this).val() == value) {
                        duplicate = true;
                    }
                });
                if (duplicate) {
                    alert('This product is already selected.');
                    current.val('');
                    current.closest('tr').find('.unit').val('');
                    current.closest('tr').find('.stock').val('');
                    current.closest('tr').find('.rate').val('');
                    current.closest('tr').find('.amount').val('0.00');
                    calculateSummary();
                    return;
                }
            }
            let row = current.closest('tr');
            let option = current.find(':selected');
            row.find('.unit').val(option.data('unit'));
            row.find('.stock').val(option.data('stock'));
            if (option.data('rate') != undefined) {
                row.find('.rate').val(option.data('rate'));
            }
            calculateRow(row);
            row.find('.qty').focus();
        });
        $(document).on('keyup change', '.qty', function() {
            calculateRow($(this).closest('tr'));
        });
        $(document).on('keyup change', '.rate', function() {
            calculateRow($(this).closest('tr'));
        });
        $('#discount,#vat,#paidAmount').on('keyup change', function() {
            calculateSummary();
        });
        $('#addRow').click(function() {
            let html = $('#purchaseRowTemplate').html();
            $('#purchaseBody').append(html);
            let newRow = $('#purchaseBody tr:last');
            newRow.find('.select2').select2({
                width: '100%'
            });
            newRow.find('.product').focus();
        });
        $(document).on('click', '.removeRow', function() {
            if ($('#purchaseBody tr').length <= 1) {
                alert('Minimum one product is required.');
                return;
            }
            if (confirm('Remove this product?')) {
                $(this).closest('tr').remove();
                calculateSummary();
            }
        });
        $(document).on('keypress', '.qty', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $(this).closest('tr').find('.rate').focus();
            }
        });
        $(document).on('keypress', '.rate', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $('#addRow').click();
            }
        });
        $('form').submit(function() {
            if ($('select[name="party_id"]').val() == '') {
                alert('Please select supplier.');
                return false;
            }
            let valid = true;
            $('.product').each(function() {
                if ($(this).val() == '') {
                    valid = false;
                }
            });
            if (!valid) {
                alert('Please select all products.');
                return false;
            }
            calculateSummary();
            return true;
        });
    });
</script>
