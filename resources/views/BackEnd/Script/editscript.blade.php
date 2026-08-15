<script>
    let rowNo = 1;
    let currentSerialRow = null;
    let currentSerialData = [];
    const serialModalElement =
        document.getElementById('serialModal');
    const serialModal =
        new bootstrap.Modal(
            serialModalElement
        );

    function addRow(data = null) {
        let html =
            $('#salesRowTemplate').html();
        $('#salesBody').append(html);

        let row =
            $('#salesBody tr:last');

        row.find('.sl')
            .text(rowNo);

        row.find('.product').select2({

            width: '100%',
        });

        if (data) {

            row.find('.product')
                .val(data.product_id)
                .trigger('change');


            setTimeout(function() {

                row.find('.stock')
                    .val(data.stock);


                row.find('.qty')
                    .val(data.qty);


                row.find('.rate')
                    .val(data.rate);


                row.find('.details')
                    .val(data.details ?? '');


                let serials =
                    data.serials ?? [];


                row.find('.serial_json')
                    .val(
                        JSON.stringify(serials)
                    );


                updateSerialDisplay(
                    row
                );


                calculate();

            }, 300);
        }


        rowNo++;

        serial();

        calculate();
    }

    $(document).ready(function() {

        if (
            typeof oldItems !== 'undefined' &&
            oldItems.length
        ) {

            oldItems.forEach(function(item) {

                addRow(item);

            });

        } else {

            addRow();

        }

    });

    $(document).on(
        'click',
        '#addRow',
        function() {

            addRow();

            setTimeout(function() {

                $('#salesBody tr:last')
                    .find('.product')
                    .select2('open');

            }, 200);

        }
    );

    function serial() {

        let i = 1;


        $('#salesBody tr').each(function() {

            $(this)
                .find('.sl')
                .text(i);

            i++;

        });

    }

    $(document).on('change', '.product', function() {

        let row = $(this).closest('tr');
        let option = $(this).find(':selected');
        let productId = $(this).val();

        if (!productId) {

            row.find('.stock').val('');
            row.find('.rate').val('');
            row.find('.qty').val(1);
            row.find('.description').val('');

            row.find('.total').val('0.00');

            clearSerials(row);

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

            $(this)
                .val('')
                .trigger('change');

            row.find('.stock').val('');
            row.find('.rate').val('');
            row.find('.qty').val(1);
            row.find('.description').val('');

            row.find('.total').val('0.00');

            clearSerials(row);

            calculate();

            return;
        }
        row.find('.stock').val(
            option.data('stock') ?? 0
        );
        row.find('.rate').val(
            option.data('price') ?? 0
        );
        row.find('.description').val(
            option.data('description') ?? ''
        );
        row.find('.qty').val(1);
        clearSerials(row);

        calculate();

        row.find('.qty').focus();

    });

    function clearSerials(row) {
        row.find('.serial_json')
            .val('[]');
        updateSerialDisplay(row);
    }

    function updateSerialDisplay(row) {
        let serials = [];
        try {
            serials =
                JSON.parse(
                    row.find('.serial_json').val() ||
                    '[]'
                );
        } catch (e) {
            serials = [];
        }
        row.find('.serialCount')
            .text(serials.length);
        if (serials.length > 0) {
            row.find('.serialStatus')
                .text(
                    serials.length +
                    ' serial selected'
                )
                .removeClass(
                    'text-muted text-danger'
                )
                .addClass(
                    'text-success'
                );

        } else {

            row.find('.serialStatus')
                .text(
                    'No serial selected'
                )
                .removeClass(
                    'text-success text-danger'
                )
                .addClass(
                    'text-muted'
                );

        }

    }

    $(document).on(
        'click',
        '.serialBtn',
        function() {

            let row =
                $(this).closest('tr');


            let productId =
                row.find('.product').val();


            if (!productId) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Select Product',

                    text: 'Please select a product first.'

                });

                return;
            }


            let productName =
                row.find(
                    '.product option:selected'
                ).text();


            let qty =
                parseFloat(
                    row.find('.qty').val()
                ) || 0;


            if (qty < 1) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Invalid Quantity',

                    text: 'Please enter quantity first.'

                });

                return;
            }

            currentSerialRow = row;


            $('#serialProductName')
                .val(
                    $.trim(productName)
                );


            $('#serialQty')
                .val(qty);


            $('#serialSelected')
                .val(0);


            $('#serialAvailable')
                .val(0);


            $('#serialSearch')
                .val('');


            $('#serialList').html(`

                <div class="text-center py-4">

                    <i class="fa fa-spinner fa-spin fa-2x"></i>

                    <div class="mt-2">
                        Loading serial numbers...
                    </div>

                </div>

            `);


            $('#serialEmpty')
                .addClass('d-none');


            serialModal.show();


            let selectedSerials = [];


            try {

                selectedSerials =
                    JSON.parse(
                        row.find('.serial_json').val() ||
                        '[]'
                    );

            } catch (e) {

                selectedSerials = [];

            }

            $.ajax({

                url: "{{ route('sales.order.serials', [
                    'receipt' => $receipt->id,
                    'product' => '__PRODUCT__',
                ]) }}"
                    .replace(
                        '__PRODUCT__',
                        productId
                    ),

                type: 'GET',

                success: function(response) {

                    if (
                        !response.success
                    ) {

                        showSerialError(
                            'Unable to load serial numbers.'
                        );

                        return;
                    }


                    currentSerialData =
                        response.serials || [];


                    renderSerialList(
                        currentSerialData,
                        selectedSerials
                    );

                },

                error: function(xhr) {

                    let message =
                        'Unable to load serial numbers.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;
                    }


                    showSerialError(
                        message
                    );

                }

            });

        }
    );

    function renderSerialList(
        serials,
        selectedSerials = []
    ) {

        let html = '';


        if (!serials.length) {

            $('#serialList')
                .html('');

            $('#serialEmpty')
                .removeClass('d-none');

            $('#serialAvailable')
                .val(0);

            $('#serialSelected')
                .val(0);

            return;
        }


        $('#serialEmpty')
            .addClass('d-none');


        serials.forEach(function(item, index) {

            let serial =
                item.serial_no;


            let checked =
                selectedSerials.includes(
                    serial
                );


            html += `

                <div
                    class="form-check serial-item border rounded p-2 mb-2">

                    <input
                        class="form-check-input serialCheckbox"
                        type="checkbox"

                        value="${escapeHtml(serial)}"

                        id="serial_${index}"

                        ${checked ? 'checked' : ''}>

                    <label
                        class="form-check-label w-100"
                        for="serial_${index}">

                        <strong>
                            ${escapeHtml(serial)}
                        </strong>

                        <span
                            class="badge ${
                                item.status === 'Available'
                                ? 'bg-success'
                                : 'bg-warning text-dark'
                            } float-end">

                            ${
                                item.status === 'Available'
                                ? 'Available'
                                : 'Current'
                            }

                        </span>

                    </label>

                </div>

            `;

        });


        $('#serialList')
            .html(html);


        $('#serialAvailable')
            .val(
                serials.length
            );


        updateSelectedSerialCount();

    }

    $(document).on(
        'change',
        '.serialCheckbox',
        function() {

            let qty =
                parseInt(
                    $('#serialQty').val()
                ) || 0;


            let selected =
                $('.serialCheckbox:checked')
                .length;


            if (
                selected > qty
            ) {

                $(this)
                    .prop('checked', false);


                Swal.fire({

                    icon: 'warning',

                    title: 'Maximum Serial Reached',

                    text: 'You can select only ' +
                        qty +
                        ' serial number(s).'

                });


                updateSelectedSerialCount();

                return;
            }


            updateSelectedSerialCount();

        }
    );

    function updateSelectedSerialCount() {

        let selected =
            $('.serialCheckbox:checked')
            .length;


        $('#serialSelected')
            .val(selected);

    }

    $(document).on(
        'keyup',
        '#serialSearch',
        function() {

            let search =
                $.trim(
                    $(this).val()
                ).toUpperCase();


            $('.serial-item').each(
                function() {

                    let serial =
                        $(this)
                        .find(
                            '.serialCheckbox'
                        )
                        .val()
                        .toUpperCase();


                    if (
                        serial.indexOf(search) !==
                        -1
                    ) {

                        $(this).show();

                    } else {

                        $(this).hide();

                    }

                }
            );

        }
    );

    $('#saveSerialBtn').click(
        function() {

            if (!currentSerialRow) {

                return;
            }


            let qty =
                parseInt(
                    currentSerialRow
                    .find('.qty')
                    .val()
                ) || 0;


            let selectedSerials = [];


            $('.serialCheckbox:checked')
                .each(function() {

                    selectedSerials.push(
                        $(this).val()
                    );

                });


            if (
                currentSerialData.length > 0 &&
                selectedSerials.length !== qty
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Serial Selection Required',

                    text: 'Please select exactly ' +
                        qty +
                        ' serial number(s).'

                });

                return;
            }

            currentSerialRow
                .find('.serial_json')
                .val(
                    JSON.stringify(
                        selectedSerials
                    )
                );


            updateSerialDisplay(
                currentSerialRow
            );

            serialModal.hide();


            currentSerialRow = null;

        }
    );

    $('#serialModal').on(
        'hidden.bs.modal',
        function() {

            currentSerialRow = null;

            currentSerialData = [];

            $('#serialList')
                .html('');

            $('#serialSearch')
                .val('');

        }
    );

    function showSerialError(message) {

        $('#serialList').html(`

            <div class="alert alert-danger mb-0">

                <i class="fa fa-exclamation-triangle me-2"></i>

                ${escapeHtml(message)}

            </div>

        `);

    }


    function escapeHtml(text) {

        return $('<div>')
            .text(text)
            .html();

    }

    function calculate() {

        let totalQty = 0;

        let subTotal = 0;


        $('#salesBody tr').each(
            function() {

                let row =
                    $(this);


                let product =
                    row.find(
                        '.product'
                    ).val();


                if (!product) {

                    row.find(
                        '.total'
                    ).val('0.00');

                    return;
                }


                let stock =
                    parseFloat(
                        row.find('.stock').val()
                    ) || 0;


                let qty =
                    parseFloat(
                        row.find('.qty').val()
                    ) || 0;


                let rate =
                    parseFloat(
                        row.find('.rate').val()
                    ) || 0;


                if (qty < 1) {

                    qty = 1;

                    row.find('.qty')
                        .val(1);

                }

                if (qty > stock) {

                    Swal.fire({

                        icon: 'warning',

                        title: 'Stock Not Available',

                        text: 'Available Stock : ' +
                            stock

                    });


                    qty = stock;


                    row.find('.qty')
                        .val(stock);

                }


                if (rate < 0) {

                    rate = 0;

                    row.find('.rate')
                        .val(0);

                }


                let amount =
                    qty * rate;


                row.find('.total')
                    .val(
                        amount.toFixed(2)
                    );


                totalQty += qty;

                subTotal += amount;

            }
        );

        let discount =
            parseFloat(
                $('#discount').val()
            ) || 0;


        if (discount < 0) {

            discount = 0;

            $('#discount')
                .val(0);

        }


        if (discount > subTotal) {

            discount = subTotal;

            $('#discount')
                .val(
                    discount.toFixed(2)
                );

        }

        let vatPercent =
            parseFloat(
                $('#vat').val()
            ) || 0;


        if (vatPercent < 0) {

            vatPercent = 0;

            $('#vat')
                .val(0);

        }


        let afterDiscount =
            subTotal - discount;


        let vatAmount =
            (
                afterDiscount *
                vatPercent
            ) / 100;


        let grandTotal =
            afterDiscount +
            vatAmount;


        $('#total_qty')
            .val(
                totalQty
            );


        $('#sub_total')
            .val(
                subTotal.toFixed(2)
            );


        $('#grand_total')
            .val(
                grandTotal.toFixed(2)
            );

    }

    $(document).on(
        'keyup change',
        '.qty,.rate',
        function() {

            calculate();

        }
    );

    $(document).on(
        'change',
        '.qty',
        function() {

            let row =
                $(this).closest('tr');


            let serials = [];


            try {

                serials =
                    JSON.parse(
                        row.find(
                            '.serial_json'
                        ).val() ||
                        '[]'
                    );

            } catch (e) {

                serials = [];

            }


            let qty =
                parseInt(
                    $(this).val()
                ) || 0;

            if (
                serials.length > qty
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Quantity Changed',

                    text: 'Selected serial numbers are more than the new quantity. Please select serial numbers again.'

                });


                clearSerials(row);

            }


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

    $(document).on(
        'click',
        '.remove',
        function(e) {

            e.preventDefault();


            if (
                $('#salesBody tr')
                .length <= 1
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'At least one product is required.'

                });

                return;
            }


            let row =
                $(this).closest('tr');


            Swal.fire({

                title: 'Remove Product?',

                text: 'This product will be removed.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Delete',

                cancelButtonText: 'Cancel'

            }).then(
                function(result) {

                    if (
                        result.isConfirmed
                    ) {

                        row.remove();

                        serial();

                        calculate();

                    }

                }
            );

        }
    );

    $(document).on(
        'keydown',
        '.qty',
        function(e) {

            if (
                e.key === 'Enter'
            ) {

                e.preventDefault();


                $(this)
                    .closest('tr')
                    .find('.rate')
                    .focus();

            }

        }
    );


    $(document).on(
        'keydown',
        '.rate',
        function(e) {

            if (
                e.key === 'Enter'
            ) {

                e.preventDefault();


                $(this)
                    .closest('tr')
                    .find('.details')
                    .focus();

            }

        }
    );

    $(document).on(
        'keydown',
        '.details',
        function(e) {

            if (
                e.key === 'Enter'
            ) {

                e.preventDefault();


                addRow();


                setTimeout(function() {

                    $('#salesBody tr:last')
                        .find('.product')
                        .select2('open');

                }, 200);

            }

        }
    );


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

            $(this).off(
                'wheel.disableScroll'
            );

        }
    );

    $('form').submit(
        function(e) {

            let valid = true;

            let message = '';


            $('#salesBody tr').each(
                function() {

                    let row =
                        $(this);


                    row.removeClass(
                        'table-danger'
                    );


                    let product =
                        row.find(
                            '.product'
                        ).val();


                    let qty =
                        parseFloat(
                            row.find(
                                '.qty'
                            ).val()
                        ) || 0;


                    let rate =
                        parseFloat(
                            row.find(
                                '.rate'
                            ).val()
                        );


                    if (!product) {

                        valid = false;

                        message =
                            'Please select a product.';

                        row.addClass(
                            'table-danger'
                        );

                        return false;
                    }


                    if (qty <= 0) {

                        valid = false;

                        message =
                            'Quantity must be greater than zero.';

                        row.addClass(
                            'table-danger'
                        );

                        return false;
                    }


                    if (
                        isNaN(rate) ||
                        rate < 0
                    ) {

                        valid = false;

                        message =
                            'Please enter a valid sale price.';

                        row.addClass(
                            'table-danger'
                        );

                        return false;
                    }

                    let serials = [];


                    try {

                        serials =
                            JSON.parse(
                                row.find(
                                    '.serial_json'
                                ).val() ||
                                '[]'
                            );

                    } catch (error) {

                        serials = [];

                    }

                    if (
                        serials.length > 0 &&
                        serials.length !==
                        parseInt(qty)
                    ) {

                        valid = false;

                        message =
                            'Please select exactly ' +
                            qty +
                            ' serial number(s) for this product.';

                        row.addClass(
                            'table-danger'
                        );

                        return false;
                    }

                }
            );


            if (!valid) {

                e.preventDefault();


                Swal.fire({

                    icon: 'warning',

                    title: 'Incomplete Information',

                    text: message

                });


                return false;
            }

        }
    );


    $(document).keydown(
        function(e) {

            if (
                e.ctrlKey &&
                e.key.toLowerCase() ===
                's'
            ) {

                e.preventDefault();

                $('form').submit();

            }

        }
    );

    setTimeout(
        function() {

            calculate();

        },
        500
    );

    $('#company_id').change(
        function() {

            let company =
                $(this).val();


            if (
                company == ''
            ) {

                $('#branch_id')
                    .html(
                        '<option value="">Select Branch</option>'
                    );

                $('#name')
                    .text('');

                return;
            }


            $.get(
                '/admin/ajax/company/' +
                company +
                '/branches',

                function(res) {

                    $('#name')
                        .text(
                            res.company.name ??
                            ''
                        );


                    let html =
                        '<option value="">Select Branch</option>';


                    $.each(
                        res.branches,
                        function(i, item) {

                            html +=
                                '<option value="' +
                                item.id +
                                '">' +
                                item.name +
                                '</option>';

                        }
                    );


                    $('#branch_id')
                        .html(html)
                        .trigger('change');

                }
            );

        }
    );

    $('#branch_id').change(
        function() {

            let id =
                $(this).val();


            if (
                id == ''
            ) {

                $('#company_name')
                    .text('');

                $('#branch_name')
                    .text('');

                $('#branch_phone')
                    .text('');

                $('#branch_email')
                    .text('');

                $('#branch_address')
                    .text('');

                return;
            }


            $.get(
                '/admin/ajax/branch/' +
                id,

                function(res) {

                    $('#company_name')
                        .text(
                            res.data.company_name ??
                            ''
                        );

                    $('#branch_name')
                        .text(
                            res.data.name
                        );

                    $('#branch_phone')
                        .text(
                            res.data.phone
                        );

                    $('#branch_email')
                        .text(
                            res.data.email
                        );

                    $('#branch_address')
                        .text(
                            res.data.address
                        );

                }
            );

        }
    );

    $('#party_id').change(
        function() {

            let id =
                $(this).val();


            if (
                id == ''
            ) {

                $('#party_id_text')
                    .text('');

                $('#party_name')
                    .text('');

                $('#party_phone')
                    .text('');

                $('#party_address')
                    .text('');

                $('#party_email')
                    .text('');

                $('#party_designation')
                    .text('');

                return;
            }


            $.get(
                '/admin/ajax/party/' +
                id,

                function(res) {

                    $('#party_id_text')
                        .text(
                            res.data.id
                        );

                    $('#party_name')
                        .text(
                            res.data.name
                        );

                    $('#party_email')
                        .text(
                            res.data.email
                        );

                    $('#party_designation')
                        .text(
                            res.data.designation
                        );

                    $('#party_phone')
                        .text(
                            res.data.phone
                        );

                    $('#party_address')
                        .text(
                            res.data.address
                        );

                }
            );

        }
    );
</script>
