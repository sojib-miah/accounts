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

                        let $select = $(this);

                        // Already initialized হলে destroy
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

            $(document).on('change', '#company_id', function() {

                const companyId = $(this).val();

                const option = $(this).find('option:selected');


                /*
                |--------------------------------------------------------------------------
                | COMPANY INFORMATION
                |--------------------------------------------------------------------------
                */

                if (!companyId) {

                    $('#company_name').text('Select Company');

                } else {

                    $('#company_name').text(
                        option.data('name') || option.text().trim()
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | RESET BRANCH
                |--------------------------------------------------------------------------
                */

                const $branch = $('#branch_id');

                $branch.val(null).trigger('change');


                /*
                |--------------------------------------------------------------------------
                | SHOW ONLY COMPANY BRANCHES
                |--------------------------------------------------------------------------
                */

                $branch.find('option').each(function() {

                    const $option = $(this);

                    const optionValue = $option.val();

                    const branchCompanyId =
                        $option.attr('data-company-id');


                    // Placeholder
                    if (!optionValue) {

                        $option.prop('disabled', false);
                        $option.show();

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Super Admin / Company matching
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !companyId ||
                        String(branchCompanyId) === String(companyId)
                    ) {

                        $option.prop('disabled', false);
                        $option.show();

                    } else {

                        $option.prop('disabled', true);
                        $option.hide();

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | REINITIALIZE SELECT2
                |--------------------------------------------------------------------------
                */

                if ($branch.hasClass('select2-hidden-accessible')) {
                    $branch.select2('destroy');
                }

                $branch.select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select Branch'
                });


                /*
                |--------------------------------------------------------------------------
                | RESET BRANCH INFORMATION
                |--------------------------------------------------------------------------
                */

                resetBranchInfo();

            });


            /*
            |--------------------------------------------------------------------------
            | BRANCH CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '#branch_id', function() {

                const $option =
                    $(this).find('option:selected');

                const branchId = $(this).val();


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
                    $option.attr('data-company-name') || '-'
                );

                $('#branch_name').text(
                    $option.attr('data-name') ||
                    $option.text().trim()
                );

                $('#branch_phone').text(
                    $option.attr('data-phone') || '-'
                );

                $('#branch_email').text(
                    $option.attr('data-email') || '-'
                );

                $('#branch_address').text(
                    $option.attr('data-address') || '-'
                );


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                | Product / serial related code থাকলে branch change করার পরে
                | serial আবার reload করতে হবে।
                |--------------------------------------------------------------------------
                */

                reloadAllSerialCounts();

            });


            /*
            |--------------------------------------------------------------------------
            | RESET BRANCH INFORMATION
            |--------------------------------------------------------------------------
            */

            function resetBranchInfo() {

                $('#branch_company_name')
                    .text(
                        $('#company_id option:selected').attr('data-name') ||
                        'Select Company'
                    );

                $('#branch_name')
                    .text('-');

                $('#branch_phone')
                    .text('-');

                $('#branch_email')
                    .text('-');

                $('#branch_address')
                    .text('-');

            }


            /*
            |--------------------------------------------------------------------------
            | PARTY CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on('change', '#party_id', function() {

                const $option =
                    $(this).find('option:selected');

                const partyId = $(this).val();


                if (!partyId) {

                    resetPartyInfo();

                    return;

                }


                $('#party_name').text(
                    $option.attr('data-name') ||
                    $option.text().trim()
                );

                $('#party_designation').text(
                    $option.attr('data-designation') || '-'
                );

                $('#party_phone').text(
                    $option.attr('data-phone') || '-'
                );

                $('#party_email').text(
                    $option.attr('data-email') || '-'
                );

                $('#party_address').text(
                    $option.attr('data-address') || '-'
                );

            });


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
            | ADD FIRST ROW
            |--------------------------------------------------------------------------
            */

            addExpenseRow();


            /*
            |--------------------------------------------------------------------------
            | ADD ROW BUTTON
            |--------------------------------------------------------------------------
            */

            $('#addRow').on('click', function() {

                addExpenseRow();

            });


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


                /*
                |--------------------------------------------------------------------------
                | ROW ID
                |--------------------------------------------------------------------------
                */

                $row.attr(
                    'data-row',
                    rowCounter
                );


                /*
                |--------------------------------------------------------------------------
                | Append
                |--------------------------------------------------------------------------
                */

                $('#expenseBody').append($row);


                /*
                |--------------------------------------------------------------------------
                | Initialize Select2 ONLY in new row
                |--------------------------------------------------------------------------
                */

                initSelect2($row);


                /*
                |--------------------------------------------------------------------------
                | Update Serial Numbers
                |--------------------------------------------------------------------------
                */

                updateRowNumbers();


                /*
                |--------------------------------------------------------------------------
                | Remove button visibility
                |--------------------------------------------------------------------------
                */

                updateRemoveButtons();

            }


            /*
            |--------------------------------------------------------------------------
            | REMOVE ROW
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
                    | At least one row must remain
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


                    $(this)
                        .closest('.expense-row')
                        .remove();


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
                            .text(index + 1);

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | REMOVE BUTTON
            |--------------------------------------------------------------------------
            */

            function updateRemoveButtons() {

                const rows =
                    $('#expenseBody .expense-row');

                if (rows.length <= 1) {

                    rows.find('.removeRow')
                        .prop('disabled', true);

                } else {

                    rows.find('.removeRow')
                        .prop('disabled', false);

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
                        $(this).closest('.expense-row');

                    const $accountHead =
                        $row.find('.account-head');


                    /*
                    |--------------------------------------------------------------------------
                    | RESET
                    |--------------------------------------------------------------------------
                    */

                    $accountHead
                        .empty()
                        .append(
                            '<option value="">Select Expense</option>'
                        )
                        .prop('disabled', true);


                    /*
                    |--------------------------------------------------------------------------
                    | Select2 Refresh
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $accountHead.hasClass(
                            'select2-hidden-accessible'
                        )
                    ) {

                        $accountHead
                            .trigger('change');

                    }


                    if (!categoryId) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Loading
                    |--------------------------------------------------------------------------
                    */

                    $accountHead
                        .append(
                            '<option value="">Loading...</option>'
                        )
                        .prop('disabled', true)
                        .trigger('change');


                    /*
                    |--------------------------------------------------------------------------
                    | AJAX URL
                    |--------------------------------------------------------------------------
                    |
                    | আপনার route:
                    |
                    | receipt.account-heads
                    |
                    | Example:
                    | /admin/receipt/account-heads/{category}
                    |
                    |--------------------------------------------------------------------------
                    */

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


                            /*
                            |--------------------------------------------------------------------------
                            | Different possible response formats
                            |--------------------------------------------------------------------------
                            */

                            let accounts = [];


                            if (Array.isArray(response)) {

                                accounts = response;

                            } else if (
                                Array.isArray(response.accountHeads)
                            ) {

                                accounts =
                                    response.accountHeads;

                            } else if (
                                Array.isArray(response.data)
                            ) {

                                accounts =
                                    response.data;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Add Account Heads
                            |--------------------------------------------------------------------------
                            */

                            accounts.forEach(function(account) {

                                const id =
                                    account.id;

                                const name =
                                    account.name ||
                                    account.account_name ||
                                    account.title;


                                if (id && name) {

                                    $accountHead.append(

                                        $('<option>', {

                                            value: id,

                                            text: name

                                        })

                                    );

                                }

                            });


                            /*
                            |--------------------------------------------------------------------------
                            | Enable
                            |--------------------------------------------------------------------------
                            */

                            $accountHead
                                .prop('disabled', false)
                                .trigger('change');

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
                                .prop('disabled', true)
                                .trigger('change');


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
            | QTY / RATE CHANGE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'input',
                '.qty, .rate',
                function() {

                    const $row =
                        $(this).closest('.expense-row');

                    calculateRowTotal($row);

                    calculateGrandTotal();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CALCULATE ROW TOTAL
            |--------------------------------------------------------------------------
            */

            function calculateRowTotal($row) {

                let qty =
                    parseFloat(
                        $row.find('.qty').val()
                    ) || 0;


                let rate =
                    parseFloat(
                        $row.find('.rate').val()
                    ) || 0;


                let total =
                    qty * rate;


                $row.find('.total')
                    .val(
                        total.toFixed(2)
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | CALCULATE GRAND TOTAL
            |--------------------------------------------------------------------------
            */

            function calculateGrandTotal() {

                let grandTotal = 0;


                $('#expenseBody .expense-row')
                    .each(function() {

                        let total =
                            parseFloat(
                                $(this)
                                .find('.total')
                                .val()
                            ) || 0;


                        grandTotal += total;

                    });


                /*
                |--------------------------------------------------------------------------
                | Optional Grand Total field
                |--------------------------------------------------------------------------
                */

                $('#grandTotal')
                    .val(
                        grandTotal.toFixed(2)
                    );

                $('#grand_total')
                    .text(
                        grandTotal.toFixed(2)
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | SERIAL COUNT RELOAD
            |--------------------------------------------------------------------------
            |
            | আপনার Sales page-এ product serial আছে।
            | Expense page-এ এই function কাজ না করলেও সমস্যা নেই।
            |
            |--------------------------------------------------------------------------
            */

            function reloadAllSerialCounts() {

                if (
                    typeof window.reloadAllSerialCountsFunction ===
                    'function'
                ) {

                    window.reloadAllSerialCountsFunction();

                }

            }


            /*
            |--------------------------------------------------------------------------
            | FORM SUBMIT VALIDATION
            |--------------------------------------------------------------------------
            */

            $('form').on('submit', function(e) {

                let valid = true;

                let message = '';


                /*
                |--------------------------------------------------------------------------
                | Company
                |--------------------------------------------------------------------------
                */

                if (!$('#company_id').val()) {

                    valid = false;

                    message =
                        'Please select Company.';

                }


                /*
                |--------------------------------------------------------------------------
                | Branch
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
                | Party
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
                | Expense Rows
                |--------------------------------------------------------------------------
                */

                if (valid) {

                    $('#expenseBody .expense-row')
                        .each(function() {

                            const category =
                                $(this)
                                .find('.category')
                                .val();

                            const accountHead =
                                $(this)
                                .find('.account-head')
                                .val();

                            const qty =
                                parseFloat(
                                    $(this)
                                    .find('.qty')
                                    .val()
                                ) || 0;

                            const rate =
                                parseFloat(
                                    $(this)
                                    .find('.rate')
                                    .val()
                                ) || 0;


                            if (!category) {

                                valid = false;

                                message =
                                    'Please select Category.';

                                return false;

                            }


                            if (!accountHead) {

                                valid = false;

                                message =
                                    'Please select Expense.';

                                return false;

                            }


                            if (qty <= 0) {

                                valid = false;

                                message =
                                    'Quantity must be greater than 0.';

                                return false;

                            }


                            if (rate < 0) {

                                valid = false;

                                message =
                                    'Unit Price cannot be negative.';

                                return false;

                            }

                        });

                }


                /*
                |--------------------------------------------------------------------------
                | Stop Submit
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

            });


        });
    </script>
@endpush
