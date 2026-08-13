@push('scripts')
    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | GLOBAL
            |--------------------------------------------------------------------------
            */

            let rowCounter = 0;


            /*
            |--------------------------------------------------------------------------
            | SELECT2 INITIALIZE
            |--------------------------------------------------------------------------
            */

            function initSelect2(container = document) {

                $(container)
                    .find('.select2, .select2-expense')
                    .each(function() {

                        const $select = $(this);

                        if (
                            $select.hasClass(
                                'select2-hidden-accessible'
                            )
                        ) {
                            $select.select2('destroy');
                        }

                        $select.select2({
                            width: '100%',
                            allowClear: true,
                            placeholder: 'Select value'
                        });

                    });
            }


            /*
            |--------------------------------------------------------------------------
            | INITIAL SELECT2
            |--------------------------------------------------------------------------
            */

            initSelect2();


            /*
            |--------------------------------------------------------------------------
            | COMPANY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'change',
                '#company_id',
                function() {

                    const companyId = $(this).val();

                    const $companyOption =
                        $(this).find('option:selected');


                    /*
                    |--------------------------------------------------------------------------
                    | COMPANY INFORMATION
                    |--------------------------------------------------------------------------
                    */

                    if (!companyId) {

                        $('#company_name').text('-');

                        resetBranch();

                        return;
                    }


                    $('#company_name').text(

                        $companyOption.attr(
                            'data-name'
                        ) ||
                        $companyOption.text().trim()

                    );


                    /*
                    |--------------------------------------------------------------------------
                    | RESET BRANCH
                    |--------------------------------------------------------------------------
                    */

                    resetBranch();


                    /*
                    |--------------------------------------------------------------------------
                    | BRANCH SELECT
                    |--------------------------------------------------------------------------
                    */

                    const $branch =
                        $('#branch_id');


                    $branch
                        .empty()
                        .append(
                            '<option value="">Loading branches...</option>'
                        )
                        .prop(
                            'disabled',
                            true
                        );


                    refreshBranchSelect();


                    /*
                    |--------------------------------------------------------------------------
                    | LOAD BRANCHES
                    |--------------------------------------------------------------------------
                    | Existing route:
                    |
                    | /ajax/company/{company}/branches
                    |--------------------------------------------------------------------------
                    */

                    let url =
                        "{{ url('/admin/ajax/company') }}/" +
                        companyId +
                        "/branches";


                    $.ajax({

                        url: url,

                        type: 'GET',

                        dataType: 'json',

                        success: function(response) {

                            /*
                            |--------------------------------------------------------------------------
                            | RESET OPTIONS
                            |--------------------------------------------------------------------------
                            */

                            $branch
                                .empty()
                                .append(
                                    '<option value="">Select Branch</option>'
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | BRANCHES
                            |--------------------------------------------------------------------------
                            */

                            if (
                                response.branches &&
                                Array.isArray(
                                    response.branches
                                )
                            ) {

                                response.branches.forEach(
                                    function(branch) {

                                        const phone =
                                            branch.phone_one ||
                                            branch.phone_two ||
                                            branch.phone ||
                                            '';


                                        $branch.append(

                                            $('<option>', {

                                                value: branch.id,

                                                text: branch.name,

                                                'data-company-id': branch.company_id,

                                                'data-company-name': response.company ?
                                                    response.company.name :
                                                    '',

                                                'data-name': branch.name || '',

                                                'data-phone': phone,

                                                'data-email': branch.email || '',

                                                'data-address': branch.address || ''

                                            })

                                        );

                                    }
                                );

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ENABLE BRANCH
                            |--------------------------------------------------------------------------
                            */

                            $branch.prop(
                                'disabled',
                                false
                            );


                            refreshBranchSelect();


                            resetBranchInfo();

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | AJAX ERROR
                        |--------------------------------------------------------------------------
                        */

                        error: function(xhr) {

                            console.error(
                                'Branch Loading Error:',
                                xhr.responseText
                            );


                            $branch
                                .empty()
                                .append(
                                    '<option value="">Unable to load branches</option>'
                                )
                                .prop(
                                    'disabled',
                                    true
                                );


                            refreshBranchSelect();


                            Swal.fire({

                                icon: 'error',

                                title: 'Error',

                                text: 'Unable to load branches for this company.'

                            });

                        }

                    });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | REFRESH BRANCH SELECT2
            |--------------------------------------------------------------------------
            */

            function refreshBranchSelect() {

                const $branch =
                    $('#branch_id');


                if (
                    $branch.hasClass(
                        'select2-hidden-accessible'
                    )
                ) {

                    $branch.select2('destroy');

                }


                $branch.select2({

                    width: '100%',

                    allowClear: true,

                    placeholder: 'Select Branch'

                });

            }


            /*
            |--------------------------------------------------------------------------
            | BRANCH CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'change',
                '#branch_id',
                function() {

                    const branchId =
                        $(this).val();


                    const $option =
                        $(this).find('option:selected');


                    /*
                    |--------------------------------------------------------------------------
                    | NO BRANCH
                    |--------------------------------------------------------------------------
                    */

                    if (!branchId) {

                        resetBranchInfo();

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | BRANCH INFORMATION
                    |--------------------------------------------------------------------------
                    */

                    $('#branch_company_name').text(

                        $option.attr(
                            'data-company-name'
                        ) || '-'

                    );


                    $('#branch_name').text(

                        $option.attr(
                            'data-name'
                        ) ||
                        $option.text().trim()

                    );


                    $('#branch_phone').text(

                        $option.attr(
                            'data-phone'
                        ) || '-'

                    );


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

                }
            );


            /*
            |--------------------------------------------------------------------------
            | RESET BRANCH
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | RESET BRANCH INFORMATION
            |--------------------------------------------------------------------------
            */

            function resetBranchInfo() {

                $('#branch_company_name').text(
                    $('#company_id option:selected')
                    .attr('data-name') || '-'
                );

                $('#branch_name').text('-');

                $('#branch_phone').text('-');

                $('#branch_email').text('-');

                $('#branch_address').text('-');

            }


            /*
            |--------------------------------------------------------------------------
            | PARTY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'change',
                '#party_id',
                function() {

                    const partyId =
                        $(this).val();


                    const $option =
                        $(this).find('option:selected');


                    /*
                    |--------------------------------------------------------------------------
                    | RESET
                    |--------------------------------------------------------------------------
                    */

                    if (!partyId) {

                        resetPartyInfo();

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PARTY INFORMATION
                    |--------------------------------------------------------------------------
                    */

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


            /*
            |--------------------------------------------------------------------------
            | RESET PARTY
            |--------------------------------------------------------------------------
            */

            function resetPartyInfo() {

                $('#party_name').text('-');

                $('#party_designation').text('-');

                $('#party_phone').text('-');

                $('#party_email').text('-');

                $('#party_address').text('-');

            }


            /*
            |--------------------------------------------------------------------------
            | ADD FIRST EXPENSE ROW
            |--------------------------------------------------------------------------
            */

            addExpenseRow();


            /*
            |--------------------------------------------------------------------------
            | ADD ROW BUTTON
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '#addRow',
                function() {

                    addExpenseRow();

                    calculateGrandTotal();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ADD EXPENSE ROW
            |--------------------------------------------------------------------------
            */

            function addExpenseRow() {

                rowCounter++;


                const template =
                    $('#expenseRowTemplate').html();


                const $row =
                    $(template);


                $row.attr(
                    'data-row',
                    rowCounter
                );


                $('#expenseBody')
                    .append($row);


                /*
                |--------------------------------------------------------------------------
                | SELECT2 FOR NEW ROW
                |--------------------------------------------------------------------------
                */

                initSelect2($row);


                /*
                |--------------------------------------------------------------------------
                | ROW NUMBER
                |--------------------------------------------------------------------------
                */

                updateRowNumbers();


                /*
                |--------------------------------------------------------------------------
                | REMOVE BUTTON
                |--------------------------------------------------------------------------
                */

                updateRemoveButtons();


                /*
                |--------------------------------------------------------------------------
                | INITIAL ROW TOTAL
                |--------------------------------------------------------------------------
                */

                calculateRowTotal($row);


                /*
                |--------------------------------------------------------------------------
                | GRAND TOTAL
                |--------------------------------------------------------------------------
                */

                calculateGrandTotal();

            }


            /*
            |--------------------------------------------------------------------------
            | REMOVE EXPENSE ROW
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.removeRow',
                function() {

                    const rows =
                        $('#expenseBody .expense-row');


                    /*
                    |--------------------------------------------------------------------------
                    | KEEP ONE ROW
                    |--------------------------------------------------------------------------
                    */

                    if (rows.length <= 1) {

                        Swal.fire({

                            icon: 'warning',

                            title: 'Cannot Remove',

                            text: 'At least one expense item is required.'

                        });

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE
                    |--------------------------------------------------------------------------
                    */

                    $(this)
                        .closest('.expense-row')
                        .remove();


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */

                    updateRowNumbers();

                    updateRemoveButtons();

                    calculateGrandTotal();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | UPDATE ROW NUMBERS
            |--------------------------------------------------------------------------
            */

            function updateRowNumbers() {

                $('#expenseBody .expense-row')
                    .each(function(index) {

                        $(this)
                            .find('.serial-no')
                            .text(
                                index + 1
                            );

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE REMOVE BUTTONS
            |--------------------------------------------------------------------------
            */

            function updateRemoveButtons() {

                const rows =
                    $('#expenseBody .expense-row');


                if (rows.length <= 1) {

                    rows
                        .find('.removeRow')
                        .prop(
                            'disabled',
                            true
                        );

                } else {

                    rows
                        .find('.removeRow')
                        .prop(
                            'disabled',
                            false
                        );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | CATEGORY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'change',
                '.category',
                function() {

                    const categoryId =
                        $(this).val();


                    const $row =
                        $(this)
                        .closest('.expense-row');


                    const $accountHead =
                        $row.find(
                            '.account-head'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | RESET ACCOUNT HEAD
                    |--------------------------------------------------------------------------
                    */

                    $accountHead
                        .empty()
                        .append(
                            '<option value="">Select Expense</option>'
                        )
                        .prop(
                            'disabled',
                            true
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | SELECT2 RESET
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | NO CATEGORY
                    |--------------------------------------------------------------------------
                    */

                    if (!categoryId) {

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | LOADING
                    |--------------------------------------------------------------------------
                    */

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


                    /*
                    |--------------------------------------------------------------------------
                    | ACCOUNT HEAD URL
                    |--------------------------------------------------------------------------
                    */

                    let url =
                        "{{ route('ajax.account-head', ['category' => '__CATEGORY__']) }}";


                    url =
                        url.replace(
                            '__CATEGORY__',
                            categoryId
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | AJAX ACCOUNT HEAD
                    |--------------------------------------------------------------------------
                    */

                    $.ajax({

                        url: url,

                        type: 'GET',

                        dataType: 'json',

                        success: function(response) {

                            /*
                            |--------------------------------------------------------------------------
                            | RESET
                            |--------------------------------------------------------------------------
                            */

                            $accountHead
                                .empty()
                                .append(
                                    '<option value="">Select Expense</option>'
                                );


                            let accounts = [];


                            /*
                            |--------------------------------------------------------------------------
                            | RESPONSE DATA
                            |--------------------------------------------------------------------------
                            */

                            if (
                                Array.isArray(
                                    response
                                )
                            ) {

                                accounts =
                                    response;

                            } else if (
                                Array.isArray(
                                    response.data
                                )
                            ) {

                                accounts =
                                    response.data;

                            } else if (
                                Array.isArray(
                                    response.accountHeads
                                )
                            ) {

                                accounts =
                                    response.accountHeads;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ADD ACCOUNT HEADS
                            |--------------------------------------------------------------------------
                            */

                            accounts.forEach(
                                function(account) {

                                    const id =
                                        account.id;


                                    const name =
                                        account.name ||
                                        account.account_name ||
                                        account.title;


                                    if (
                                        id &&
                                        name
                                    ) {

                                        $accountHead.append(

                                            $('<option>', {

                                                value: id,

                                                text: name

                                            })

                                        );

                                    }

                                }
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | ENABLE
                            |--------------------------------------------------------------------------
                            */

                            $accountHead
                                .prop(
                                    'disabled',
                                    false
                                )
                                .trigger(
                                    'change'
                                );

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | ERROR
                        |--------------------------------------------------------------------------
                        */

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

                                text: 'Unable to load expense/account head.'

                            });

                        }

                    });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | QTY / RATE INPUT
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'input',
                '.qty, .rate',
                function() {

                    const $row =
                        $(this)
                        .closest('.expense-row');


                    calculateRowTotal($row);

                    calculateGrandTotal();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | DISCOUNT INPUT
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'input',
                '#discount',
                function() {

                    calculateGrandTotal();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | VAT INPUT
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'input',
                '#vat',
                function() {

                    calculateGrandTotal();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CALCULATE ROW TOTAL
            |--------------------------------------------------------------------------
            */

            function calculateRowTotal($row) {

                const qty =
                    parseFloat(
                        $row.find('.qty').val()
                    ) || 0;


                const rate =
                    parseFloat(
                        $row.find('.rate').val()
                    ) || 0;


                const total =
                    qty * rate;


                $row.find('.total')
                    .val(
                        total.toFixed(2)
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | CALCULATE ALL TOTALS
            |--------------------------------------------------------------------------
            */

            function calculateGrandTotal() {

                let totalQty = 0;

                let subTotal = 0;


                /*
                |--------------------------------------------------------------------------
                | LOOP ROWS
                |--------------------------------------------------------------------------
                */

                $('#expenseBody .expense-row')
                    .each(function() {

                        const $row =
                            $(this);


                        const qty =
                            parseFloat(
                                $row.find('.qty').val()
                            ) || 0;


                        const rate =
                            parseFloat(
                                $row.find('.rate').val()
                            ) || 0;


                        const rowTotal =
                            qty * rate;


                        /*
                        |--------------------------------------------------------------------------
                        | ROW TOTAL
                        |--------------------------------------------------------------------------
                        */

                        $row.find('.total')
                            .val(
                                rowTotal.toFixed(2)
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | TOTAL QTY
                        |--------------------------------------------------------------------------
                        */

                        totalQty += qty;


                        /*
                        |--------------------------------------------------------------------------
                        | SUB TOTAL
                        |--------------------------------------------------------------------------
                        */

                        subTotal += rowTotal;

                    });


                /*
                |--------------------------------------------------------------------------
                | DISCOUNT
                |--------------------------------------------------------------------------
                */

                let discount =
                    parseFloat(
                        $('#discount').val()
                    ) || 0;


                /*
                |--------------------------------------------------------------------------
                | DISCOUNT CANNOT EXCEED SUBTOTAL
                |--------------------------------------------------------------------------
                */

                if (discount < 0) {

                    discount = 0;

                    $('#discount').val(0);

                }


                if (discount > subTotal) {

                    discount = subTotal;

                    $('#discount').val(
                        subTotal.toFixed(2)
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | VAT
                |--------------------------------------------------------------------------
                */

                let vatPercent =
                    parseFloat(
                        $('#vat').val()
                    ) || 0;


                if (vatPercent < 0) {

                    vatPercent = 0;

                    $('#vat').val(0);

                }


                /*
                |--------------------------------------------------------------------------
                | AFTER DISCOUNT
                |--------------------------------------------------------------------------
                */

                const afterDiscount =
                    subTotal - discount;


                /*
                |--------------------------------------------------------------------------
                | VAT AMOUNT
                |--------------------------------------------------------------------------
                */

                const vatAmount =
                    (
                        afterDiscount *
                        vatPercent
                    ) / 100;


                /*
                |--------------------------------------------------------------------------
                | GRAND TOTAL
                |--------------------------------------------------------------------------
                */

                const grandTotal =
                    afterDiscount +
                    vatAmount;


                /*
                |--------------------------------------------------------------------------
                | UPDATE TOTAL QTY
                |--------------------------------------------------------------------------
                */

                $('#total_qty')
                    .val(
                        formatNumber(
                            totalQty
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | UPDATE SUB TOTAL
                |--------------------------------------------------------------------------
                */

                $('#sub_total')
                    .val(
                        subTotal.toFixed(2)
                    );


                /*
                |--------------------------------------------------------------------------
                | UPDATE GRAND TOTAL
                |--------------------------------------------------------------------------
                */

                $('#grand_total')
                    .val(
                        grandTotal.toFixed(2)
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | FORMAT NUMBER
            |--------------------------------------------------------------------------
            */

            function formatNumber(number) {

                if (
                    Number.isInteger(number)
                ) {

                    return number.toString();

                }

                return number.toFixed(2);

            }


            /*
            |--------------------------------------------------------------------------
            | FORM SUBMIT
            |--------------------------------------------------------------------------
            */

            $('#receiptForm').on(
                'submit',
                function(e) {

                    let valid = true;

                    let message = '';

                    const items = [];


                    /*
                    |--------------------------------------------------------------------------
                    | COMPANY
                    |--------------------------------------------------------------------------
                    */

                    if (!$('#company_id').val()) {

                        valid = false;

                        message =
                            'Please select Company.';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | BRANCH
                    |--------------------------------------------------------------------------
                    */

                    if (
                        valid &&
                        !$('#branch_id').val()
                    ) {

                        valid = false;

                        message =
                            'Please select Branch.';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PARTY
                    |--------------------------------------------------------------------------
                    */

                    if (
                        valid &&
                        !$('#party_id').val()
                    ) {

                        valid = false;

                        message =
                            'Please select Party.';

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | EXPENSE ITEMS
                    |--------------------------------------------------------------------------
                    */

                    if (valid) {

                        $('#expenseBody .expense-row')
                            .each(function() {

                                const $row =
                                    $(this);


                                const category =
                                    $row
                                    .find('.category')
                                    .val();


                                const accountHead =
                                    $row
                                    .find('.account-head')
                                    .val();


                                const qty =
                                    parseFloat(
                                        $row
                                        .find('.qty')
                                        .val()
                                    ) || 0;


                                const rate =
                                    parseFloat(
                                        $row
                                        .find('.rate')
                                        .val()
                                    ) || 0;


                                const details =
                                    $row
                                    .find('.details')
                                    .val() || '';


                                /*
                                |--------------------------------------------------------------------------
                                | CATEGORY
                                |--------------------------------------------------------------------------
                                */

                                if (!category) {

                                    valid = false;

                                    message =
                                        'Please select Category.';

                                    return false;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | ACCOUNT HEAD
                                |--------------------------------------------------------------------------
                                */

                                if (!accountHead) {

                                    valid = false;

                                    message =
                                        'Please select Expense.';

                                    return false;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | QTY
                                |--------------------------------------------------------------------------
                                */

                                if (qty <= 0) {

                                    valid = false;

                                    message =
                                        'Quantity must be greater than 0.';

                                    return false;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | RATE
                                |--------------------------------------------------------------------------
                                */

                                if (rate < 0) {

                                    valid = false;

                                    message =
                                        'Unit Price cannot be negative.';

                                    return false;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | PUSH ITEM
                                |--------------------------------------------------------------------------
                                */

                                items.push({

                                    category_id: category,

                                    account_head_id: accountHead,

                                    qty: qty,

                                    rate: rate,

                                    details: details

                                });

                            });

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | VALIDATION FAILED
                    |--------------------------------------------------------------------------
                    */

                    if (!valid) {

                        e.preventDefault();


                        Swal.fire({

                            icon: 'warning',

                            title: 'Required',

                            text: message

                        });


                        return false;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | FINAL CALCULATION
                    |--------------------------------------------------------------------------
                    */

                    calculateGrandTotal();


                    /*
                    |--------------------------------------------------------------------------
                    | STORE ITEMS JSON
                    |--------------------------------------------------------------------------
                    */

                    $('#items_json')
                        .val(
                            JSON.stringify(items)
                        );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | INITIAL CALCULATION
            |--------------------------------------------------------------------------
            */

            calculateGrandTotal();

        });
    </script>
@endpush
