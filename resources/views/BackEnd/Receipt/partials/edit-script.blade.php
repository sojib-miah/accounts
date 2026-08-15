<script>
    $(document).ready(function() {
        const editData = window.expenseEditData || {};
        const receiptItems = Array.isArray(editData.receiptItems) ? editData.receiptItems : [];
        const categories = Array.isArray(editData.categories) ? editData.categories : [];
        const receiptBranchId = editData.receiptBranchId;
        const receiptCompanyId = editData.receiptCompanyId;
        let rowCounter = 0;

        function initSelect2(container = document) {
            $(container).find('.select2').each(function() {
                const $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select value'
                });
            });
        }
        initSelect2();
        $(document).on('change', '#company_id', function() {
            const companyId = $(this).val();
            const $option = $(this).find('option:selected');
            $('#name').text($option.attr('data-name') || $option.text().trim());
            $('#hidden_company_id').val(companyId);
            if (!companyId) {
                resetBranch();
                return;
            }
            loadBranches(companyId, null);
        });

        function loadBranches(companyId, selectedBranchId = null) {
            const $branch = $('#branch_id');
            $branch.empty().append('<option value="">Loading branches...</option>').prop('disabled', true);
            refreshBranchSelect();
            const url = "{{ url('/admin/ajax/company') }}/" + companyId + "/branches";
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $branch.empty().append('<option value="">Select Branch</option>');
                    if (response.branches && Array.isArray(response.branches)) {
                        response.branches.forEach(
                            function(branch) {
                                const phone = branch.phone_one || branch.phone_two || branch
                                    .phone || '';
                                const companyName = response.company ? response.company.name :
                                    '';
                                const $option = $('<option>', {
                                    value: branch.id,
                                    text: branch.name,
                                    'data-company-id': branch.company_id,
                                    'data-company-name': companyName,
                                    'data-name': branch.name || '',
                                    'data-phone': phone,
                                    'data-email': branch.email || '',
                                    'data-address': branch.address || ''
                                });
                                if (selectedBranchId && Number(branch.id) === Number(
                                        selectedBranchId)) {
                                    $option.prop('selected', true);
                                }
                                $branch.append($option);
                            }
                        );
                    }
                    $branch.prop('disabled', false);
                    refreshBranchSelect();
                    if (selectedBranchId) {
                        $branch.val(selectedBranchId).trigger('change');
                    }
                },
                error: function(xhr) {
                    console.error('Branch loading error:', xhr.responseText);
                    $branch.empty().append('<option value="">Unable to load branches</option>')
                        .prop('disabled', true);
                    refreshBranchSelect();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to load branches for this company.'
                    });
                }
            });
        }

        function refreshBranchSelect() {
            const $branch = $('#branch_id');
            if ($branch.hasClass('select2-hidden-accessible')) {
                $branch.select2('destroy');
            }
            $branch.select2({
                width: '100%',
                allowClear: true,
                placeholder: 'Select Branch'
            });
        }
        $(document).on('change', '#branch_id', function() {
            const branchId = $(this).val();
            const $option = $(this).find('option:selected');
            if (!branchId) {
                resetBranchInfo();
                return;
            }
            $('#company_name').text($option.attr('data-company-name') || '-');
            $('#branch_name').text($option.attr('data-name') || $option.text().trim());
            $('#branch_phone').text($option.attr('data-phone') || '-');
            $('#branch_email').text(

                $option.attr(
                    'data-email'
                ) || '-'

            );


            $('#branch_address').text(

                $option.attr(
                    'data-address'
                ) || '-'

            );

        });

        function resetBranch() {

            const $branch =
                $('#branch_id');


            $branch
                .empty()
                .append(
                    '<option value="">Select Branch</option>'
                )
                .val('')
                .prop(
                    'disabled',
                    true
                );


            refreshBranchSelect();


            resetBranchInfo();

        }


        function resetBranchInfo() {

            $('#company_name').text('-');

            $('#branch_name').text('-');

            $('#branch_phone').text('-');

            $('#branch_email').text('-');

            $('#branch_address').text('-');

        }



        $(document).on(
            'change',
            '#party_id',
            function() {

                const partyId =
                    $(this).val();


                const $option =
                    $(this).find(
                        'option:selected'
                    );


                if (!partyId) {

                    resetPartyInfo();

                    return;

                }


                $('#party_name').text(

                    $option.attr(
                        'data-name'
                    ) ||
                    $option.text().trim()

                );


                $('#party_designation').text(

                    $option.attr(
                        'data-designation'
                    ) || '-'

                );


                $('#party_phone').text(

                    $option.attr(
                        'data-phone'
                    ) || '-'

                );


                $('#party_email').text(

                    $option.attr(
                        'data-email'
                    ) || '-'

                );


                $('#party_address').text(

                    $option.attr(
                        'data-address'
                    ) || '-'

                );

            }
        );


        function resetPartyInfo() {

            $('#party_name').text('-');

            $('#party_designation').text('-');

            $('#party_phone').text('-');

            $('#party_email').text('-');

            $('#party_address').text('-');

        }


        if (receiptItems.length > 0) {

            receiptItems.forEach(
                function(item) {

                    addExpenseRow(item);

                }
            );

        } else {

            addExpenseRow();

        }


        $(document).on(
            'click',
            '#addRow',
            function() {

                addExpenseRow();

                calculateGrandTotal();

            }
        );



        function addExpenseRow(item = null) {

            rowCounter++;

            const $row =
                $('<tr>', {

                    class: 'expense-row',

                    'data-row': rowCounter

                });

            const $serial =
                $('<td>', {

                    class: 'serial-no text-center',

                    text: rowCounter

                });

            const $category =
                $('<select>', {

                    class: 'form-select category select2',

                    name: 'category[]',

                    required: true

                });


            $category.append(

                $('<option>', {

                    value: '',

                    text: 'Select Category'

                })

            );

            categories.forEach(
                function(category) {

                    const $option =
                        $('<option>', {

                            value: category.id,

                            text: category.name

                        });


                    if (
                        item &&
                        Number(
                            item.category_id
                        ) === Number(
                            category.id
                        )
                    ) {

                        $option.prop(
                            'selected',
                            true
                        );

                    }


                    $category.append(
                        $option
                    );

                }
            );

            const $accountHead =
                $('<select>', {

                    class: 'form-select account-head select2',

                    name: 'account_head[]',

                    required: true

                });


            $accountHead.append(

                $('<option>', {

                    value: '',

                    text: 'Select Expense'

                })

            );

            const $qty =
                $('<input>', {

                    type: 'number',

                    class: 'form-control text-end qty',

                    min: '1',

                    value: item ?
                        item.qty : '1'

                });

            const $rate =
                $('<input>', {

                    type: 'number',

                    class: 'form-control text-end rate',

                    min: '0',

                    value: item ?
                        item.rate : '0'

                });

            const $total =
                $('<input>', {

                    type: 'text',

                    class: 'form-control text-end total',

                    readonly: true,

                    value: item ?
                        Number(
                            item.amount || 0
                        ).toFixed(2) : '0.00'

                });

            const $details =
                $('<input>', {

                    type: 'text',

                    class: 'form-control details',

                    value: item ?
                        item.details || '' : '',

                    placeholder: 'Remarks'

                });

            const $remove =
                $('<button>', {

                    type: 'button',

                    class: 'btn btn-danger btn-sm removeRow',

                    title: 'Remove'

                });


            $remove.html(
                '<i class="fa fa-trash"></i>'
            );

            $row.append(
                $serial
            );


            $row.append(
                $('<td>').append(
                    $category
                )
            );


            $row.append(
                $('<td>').append(
                    $accountHead
                )
            );


            $row.append(
                $('<td>').append(
                    $qty
                )
            );


            $row.append(
                $('<td>').append(
                    $rate
                )
            );


            $row.append(
                $('<td>').append(
                    $total
                )
            );


            $row.append(
                $('<td>').append(
                    $details
                )
            );


            $row.append(
                $('<td class="text-center">')
                .append(
                    $remove
                )
            );

            $('#expenseBody')
                .append(
                    $row
                );

            initSelect2(
                $row
            );

            if (
                item &&
                item.category_id
            ) {

                loadAccountHeads(
                    $row,
                    item.category_id,
                    item.account_head_id
                );

            }

            calculateRowTotal(
                $row
            );

            updateRowNumbers();

            updateRemoveButtons();

        }

        $(document).on(
            'change',
            '.category',
            function() {

                const categoryId =
                    $(this).val();


                const $row =
                    $(this)
                    .closest(
                        '.expense-row'
                    );

                loadAccountHeads(
                    $row,
                    categoryId,
                    null
                );

            }
        );

        function loadAccountHeads(
            $row,
            categoryId,
            selectedAccountHeadId = null
        ) {

            const $accountHead =
                $row.find(
                    '.account-head'
                );


            $accountHead
                .empty()
                .append(
                    '<option value="">Select Expense</option>'
                )
                .prop(
                    'disabled',
                    true
                );


            if (
                $accountHead.hasClass(
                    'select2-hidden-accessible'
                )
            ) {

                $accountHead.select2(
                    'destroy'
                );

            }


            $accountHead.select2({

                width: '100%',

                allowClear: true,

                placeholder: 'Select Expense'

            });


            if (!categoryId) {

                return;

            }


            $accountHead
                .empty()
                .append(
                    '<option value="">Loading...</option>'
                )
                .prop(
                    'disabled',
                    true
                );


            $accountHead.trigger(
                'change'
            );



            let url =
                "{{ route('ajax.account-head', ['category' => '__CATEGORY__']) }}";


            url =
                url.replace(
                    '__CATEGORY__',
                    categoryId
                );



            $.ajax({

                url: url,

                type: 'GET',

                dataType: 'json',


                success: function(response) {


                    $accountHead
                        .empty()
                        .append(
                            '<option value="">Select Expense</option>'
                        );


                    let accounts = [];


                    if (
                        response &&
                        Array.isArray(
                            response.data
                        )
                    ) {

                        accounts =
                            response.data;

                    } else if (
                        response &&
                        Array.isArray(
                            response.accountHeads
                        )
                    ) {

                        accounts =
                            response.accountHeads;

                    } else if (
                        Array.isArray(
                            response
                        )
                    ) {

                        accounts =
                            response;

                    }

                    accounts.forEach(
                        function(account) {

                            const $option =
                                $('<option>', {

                                    value: account.id,

                                    text: account.name

                                });

                            if (
                                selectedAccountHeadId &&
                                Number(
                                    selectedAccountHeadId
                                ) === Number(
                                    account.id
                                )
                            ) {

                                $option.prop(
                                    'selected',
                                    true
                                );

                            }


                            $accountHead.append(
                                $option
                            );

                        }
                    );

                    $accountHead
                        .prop(
                            'disabled',
                            false
                        )
                        .trigger(
                            'change'
                        );

                    if (
                        selectedAccountHeadId
                    ) {

                        $accountHead
                            .val(
                                selectedAccountHeadId
                            )
                            .trigger(
                                'change'
                            );

                    }

                },

                error: function(xhr) {

                    console.error(
                        'Account Head Error:',
                        xhr.responseText
                    );


                    $accountHead
                        .empty()
                        .append(
                            '<option value="">Unable to load Expense</option>'
                        )
                        .prop(
                            'disabled',
                            true
                        )
                        .trigger(
                            'change'
                        );


                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text: 'Unable to load Expense.'

                    });

                }

            });

        }
        $(document).on(
            'input',
            '.qty, .rate',
            function() {

                const $row =
                    $(this)
                    .closest(
                        '.expense-row'
                    );


                calculateRowTotal(
                    $row
                );


                calculateGrandTotal();

            }
        );


        $(document).on(
            'input',
            '#discount',
            function() {

                calculateGrandTotal();

            }
        );

        $(document).on(
            'input',
            '#vat',
            function() {

                calculateGrandTotal();

            }
        );


        function calculateRowTotal(
            $row
        ) {

            const qty =
                parseFloat(
                    $row.find(
                        '.qty'
                    ).val()
                ) || 0;


            const rate =
                parseFloat(
                    $row.find(
                        '.rate'
                    ).val()
                ) || 0;


            const total =
                qty * rate;


            $row.find(
                '.total'
            ).val(
                total.toFixed(2)
            );

        }


        function calculateGrandTotal() {

            let totalQty = 0;

            let subTotal = 0;


            $('#expenseBody .expense-row')
                .each(function() {

                    const $row =
                        $(this);


                    const qty =
                        parseFloat(
                            $row.find(
                                '.qty'
                            ).val()
                        ) || 0;


                    const rate =
                        parseFloat(
                            $row.find(
                                '.rate'
                            ).val()
                        ) || 0;


                    const amount =
                        qty * rate;

                    $row.find(
                        '.total'
                    ).val(
                        amount.toFixed(2)
                    );


                    totalQty += qty;


                    subTotal += amount;

                });


            let discount =
                parseFloat(
                    $('#discount').val()
                ) || 0;


            if (discount < 0) {

                discount = 0;

                $('#discount').val(
                    '0'
                );

            }


            if (discount > subTotal) {

                discount = subTotal;

                $('#discount').val(
                    subTotal.toFixed(2)
                );

            }

            let vat =
                parseFloat(
                    $('#vat').val()
                ) || 0;


            if (vat < 0) {

                vat = 0;

                $('#vat').val(
                    '0'
                );

            }


            const afterDiscount =
                subTotal - discount;


            const vatAmount =
                (
                    afterDiscount * vat
                ) / 100;


            const grandTotal =
                afterDiscount +
                vatAmount;


            $('#total_qty')
                .val(
                    formatNumber(
                        totalQty
                    )
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

        function formatNumber(
            number
        ) {

            if (
                Number.isInteger(
                    number
                )
            ) {

                return number.toString();

            }


            return number.toFixed(2);

        }

        function updateRowNumbers() {

            $('#expenseBody .expense-row')
                .each(function(index) {

                    $(this)
                        .find(
                            '.serial-no'
                        )
                        .text(
                            index + 1
                        );

                });

        }

        function updateRemoveButtons() {

            const rows =
                $('#expenseBody .expense-row');


            if (
                rows.length <= 1
            ) {

                rows
                    .find(
                        '.removeRow'
                    )
                    .prop(
                        'disabled',
                        true
                    );

            } else {

                rows
                    .find(
                        '.removeRow'
                    )
                    .prop(
                        'disabled',
                        false
                    );

            }

        }


        $(document).on(
            'click',
            '.removeRow',
            function() {

                const rows =
                    $('#expenseBody .expense-row');

                if (
                    rows.length <= 1
                ) {

                    Swal.fire({

                        icon: 'warning',

                        title: 'Cannot Remove',

                        text: 'At least one expense item is required.'

                    });

                    return;

                }
                $(this)
                    .closest(
                        '.expense-row'
                    )
                    .remove();

                updateRowNumbers();

                updateRemoveButtons();

                calculateGrandTotal();

            }
        );

        $('#receiptForm').on(
            'submit',
            function(e) {

                let valid = true;

                let message = '';

                const items = [];

                if (
                    !$('#company_id').val()
                ) {

                    valid = false;

                    message =
                        'Please select Company.';

                }

                if (
                    valid &&
                    !$('#branch_id').val()
                ) {

                    valid = false;

                    message =
                        'Please select Branch.';

                }
                if (
                    valid &&
                    !$('#party_id').val()
                ) {

                    valid = false;

                    message =
                        'Please select Customer.';

                }

                if (valid) {

                    $('#expenseBody .expense-row')
                        .each(function() {

                            const $row =
                                $(this);


                            const categoryId =
                                $row
                                .find(
                                    '.category'
                                )
                                .val();


                            const accountHeadId =
                                $row
                                .find(
                                    '.account-head'
                                )
                                .val();


                            const qty =
                                parseFloat(
                                    $row
                                    .find(
                                        '.qty'
                                    )
                                    .val()
                                ) || 0;


                            const rate =
                                parseFloat(
                                    $row
                                    .find(
                                        '.rate'
                                    )
                                    .val()
                                ) || 0;


                            const details =
                                $row
                                .find(
                                    '.details'
                                )
                                .val() || '';

                            if (!categoryId) {

                                valid = false;

                                message =
                                    'Please select Category.';

                                return false;

                            }
                            if (!accountHeadId) {

                                valid = false;

                                message =
                                    'Please select Expense.';

                                return false;

                            }

                            if (
                                qty <= 0
                            ) {

                                valid = false;

                                message =
                                    'Quantity must be greater than 0.';

                                return false;

                            }

                            if (
                                rate < 0
                            ) {

                                valid = false;

                                message =
                                    'Unit Price cannot be negative.';

                                return false;

                            }

                            const amount =
                                qty * rate;


                            items.push({

                                category_id: categoryId,

                                account_head_id: accountHeadId,

                                qty: qty,

                                rate: rate,

                                amount: amount,

                                details: details

                            });

                        });

                }

                if (!valid) {

                    e.preventDefault();


                    Swal.fire({

                        icon: 'warning',

                        title: 'Required',

                        text: message

                    });


                    return false;

                }

                calculateGrandTotal();

                $('#items_json')
                    .val(
                        JSON.stringify(
                            items
                        )
                    );

                return true;

            }
        );

        if (
            receiptCompanyId
        ) {

            $('#company_id')
                .val(
                    receiptCompanyId
                )
                .trigger(
                    'change'
                );

            loadBranches(
                receiptCompanyId,
                receiptBranchId
            );

        }

        if (
            $('#party_id').val()
        ) {

            $('#party_id')
                .trigger(
                    'change'
                );

        }

        calculateGrandTotal();


    });
</script>
