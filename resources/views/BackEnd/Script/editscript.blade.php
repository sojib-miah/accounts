<script>
    let rowNo = 1;

    function addRow(data = null) {
        let html = $('#salesRowTemplate').html();
        $('#salesBody').append(html);
        let row = $('#salesBody tr:last');
        row.find('.sl').text(rowNo);
        row.find('.product').select2({
            width: '100%'
        });
        if (data) {
            row.find('.product')
                .val(data.product_id)
                .trigger('change.select2');
            setTimeout(function() {
                row.find('.stock').val(data.stock);
                row.find('.qty').val(data.qty);
                row.find('.rate').val(data.rate);
                row.find('.details').val(data.details);
                calculate();
            }, 100);
        }
        rowNo++;
        serial();
    }
    $(document).ready(function() {
        if (typeof oldItems !== 'undefined' && oldItems.length) {
            oldItems.forEach(function(item) {
                addRow(item);
            });
        } else {
            addRow();
        }
    });
    $('#addRow').click(function() {
        addRow();
    });

    function serial() {
        let i = 1;
        $('#salesBody tr').each(function() {
            $(this).find('.sl').text(i);
            i++;
        });
    }
    $(document).on('change', '.product', function() {
        let row = $(this).closest('tr');
        let option = $(this).find(':selected');
        let productId = $(this).val();
        if (productId == '') {
            row.find('.stock').val('');
            row.find('.rate').val('');
            row.find('.qty').val(1);
            row.find('.total').val('');
            calculate();
            return;
        }
        let count = 0;
        $('.product').each(function() {
            if ($(this).val() == productId) {
                count++;
            }
        });
        if (count > 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Duplicate Product',
                text: 'This product already exists.'
            });
            $(this).val('').trigger('change');
            row.find('.stock').val('');
            row.find('.rate').val('');
            row.find('.qty').val(1);
            row.find('.total').val('');
            calculate();
            return;
        }
        if (row.find('.stock').val() == '') {
            row.find('.stock').val(
                option.data('stock') ?? 0
            );
        }
        if (row.find('.rate').val() == '') {
            row.find('.rate').val(
                option.data('price') ?? 0
            );
        }
        row.find('.qty').focus();
        calculate();
    });

    function calculate() {
        let totalQty = 0;
        let subTotal = 0;
        $('#salesBody tr').each(function() {
            let row = $(this);
            let product = row.find('.product').val();
            if (!product) {
                row.find('.total').val('0.00');
                return;
            }
            let stock = parseFloat(row.find('.stock').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;
            let rate = parseFloat(row.find('.rate').val()) || 0;
            if (qty < 1) {
                qty = 1;
                row.find('.qty').val(1);
            }
            if (qty > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Not Available',
                    text: 'Available Stock : ' + stock
                });
                qty = stock;
                row.find('.qty').val(stock);
            }
            if (rate < 0) {
                rate = 0;
                row.find('.rate').val(0);
            }
            let amount = qty * rate;
            row.find('.total').val(amount.toFixed(2));
            totalQty += qty;
            subTotal += amount;
        });
        let discount = parseFloat($('#discount').val()) || 0;
        if (discount < 0) {
            discount = 0;
            $('#discount').val(0);
        }
        if (discount > subTotal) {
            discount = subTotal;
            $('#discount').val(discount.toFixed(2));
        }
        let vatPercent = parseFloat($('#vat').val()) || 0;
        if (vatPercent < 0) {
            vatPercent = 0;
            $('#vat').val(0);
        }
        let afterDiscount = subTotal - discount;
        let vatAmount = (afterDiscount * vatPercent) / 100;
        let grandTotal = afterDiscount + vatAmount;
        $('#total_qty').val(totalQty.toFixed(2));
        $('#sub_total').val(subTotal.toFixed(2));
        $('#grand_total').val(grandTotal.toFixed(2));
    }
    $(document).on(
        'keyup change',
        '.qty,.rate',
        function() {
            calculate();
        }
    );
    $(document).on(
        'keyup change',
        '#discount,#vat',
        function() {
            calculate();
        }
    );
    $(document).on(
        'keyup',
        '.details',
        function() {
            calculate();
        }
    );
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
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                row.remove();
                serial();
                calculate();
            }
        });
    });
    $(document).on('keydown', '.qty', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this)
                .closest('tr')
                .find('.rate')
                .focus();
        }
    });
    $(document).on('keydown', '.rate', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this)
                .closest('tr')
                .find('.details')
                .focus();
        }
    });
    $(document).on('keydown', '.details', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addRow();
            $('#salesBody tr:last')
                .find('.product')
                .select2('open');
        }
    });
    $(document).on(
        'focus',
        '.qty,.rate,#discount,#vat',
        function() {
            $(this).on(
                'wheel.disableScroll',
                function(e) {
                    e.preventDefault();
                }
            );
        }
    );
    $(document).on(
        'blur',
        '.qty,.rate,#discount,#vat',
        function() {
            $(this).off('wheel.disableScroll');
        }
    );
    $('form').submit(function(e) {
        let valid = true;
        let message = '';
        $('#salesBody tr').each(function() {
            let row = $(this);
            row.removeClass('table-danger');
            let product = row.find('.product').val();
            let qty = row.find('.qty').val();
            let rate = row.find('.rate').val();
            if (product == '') {
                valid = false;
                message = 'Please select a product.';
                row.addClass('table-danger');
                return false;
            }
            if (qty == '' || parseFloat(qty) <= 0) {
                valid = false;
                message = 'Quantity must be greater than zero.';
                row.addClass('table-danger');
                return false;
            }
            if (rate == '' || parseFloat(rate) < 0) {
                valid = false;
                message = 'Please enter a valid sale price.';
                row.addClass('table-danger');
                return false;
            }
        });
        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Information',
                text: message
            });
            return false;
        }
    });
    $(document).keydown(function(e) {
        if (e.ctrlKey && e.key.toLowerCase() === 's') {
            e.preventDefault();
            $('form').submit();
        }
    });
    setTimeout(function() {
        calculate();
    }, 300);

    $('#company_id').change(function() {
        let company = $(this).val();
        if (company == '') {
            $('#branch_id').html('<option value="">Select Branch</option>');
            $('#name').text('');
            return;
        }
        $.get('/admin/ajax/company/' + company + '/branches', function(res) {
            // Company Information
            $('#name').text(res.company.name ?? '');
            // Branch List
            let html = '<option value="">Select Branch</option>';
            $.each(res.branches, function(i, item) {
                html += '<option value="' + item.id + '">' + item.name + '</option>';
            });
            $('#branch_id').html(html).trigger('change');
        });
    });

    $('#branch_id').change(function() {
        let id = $(this).val();
        if (id == '') {
            $('#company_name').text('');
            $('#branch_name').text('');
            $('#branch_phone').text('');
            $('#branch_email').text('');
            $('#branch_address').text('');
            return;
        }
        $.get('/admin/ajax/branch/' + id, function(res) {
            $('#company_name').text(res.data.company_name ?? '');
            $('#branch_name').text(res.data.name);
            $('#branch_phone').text(res.data.phone);
            $('#branch_email').text(res.data.email);
            $('#branch_address').text(res.data.address);
        });
    });

    $('#party_id').change(function() {
        let id = $(this).val();
        if (id == '') {
            $('#party_id_text').text('');
            $('#party_name').text('');
            $('#party_phone').text('');
            $('#party_address').text('');
            $('#party_email').text('');
            $('#party_designation').text('');
            return;
        }
        $.get('/admin/ajax/party/' + id, function(res) {
            $('#party_id_text').text(res.data.id);
            $('#party_name').text(res.data.name);
            $('#party_email').text(res.data.email);
            $('#party_designation').text(res.data.designation);
            $('#party_phone').text(res.data.phone);
            $('#party_address').text(res.data.address);
        });
    });
</script>
