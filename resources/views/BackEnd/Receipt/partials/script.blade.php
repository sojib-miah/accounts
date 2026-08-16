@push('scripts')
    <script>
        $(document).ready(function() {
            let rowCounter = 0;

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
            initSelect2();
            $(document).on('change', '#company_id',
                function() {
                    const companyId = $(this).val();
                    const $companyOption =
                        $(this).find('option:selected');
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
                    resetBranch();
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
                    let url = "{{ url('/admin/ajax/company') }}/" + companyId + "/branches";
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            $branch
                                .empty()
                                .append(
                                    '<option value="">Select Branch</option>'
                                );
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
                                                    response.company.name : '',
                                                'data-name': branch.name || '',
                                                'data-phone': phone,
                                                'data-email': branch.email || '',
                                                'data-address': branch.address || ''
                                            })
                                        );
                                    }
                                );
                            }
                            $branch.prop(
                                'disabled',
                                false
                            );
                            refreshBranchSelect();
                            resetBranchInfo();
                        },
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
            $(document).on('change', '#branch_id',
                function() {
                    const branchId =
                        $(this).val();
                    const $option =
                        $(this).find('option:selected');
                    if (!branchId) {
                        resetBranchInfo();
                        return;
                    }
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
                $('#branch_company_name').text(
                    $('#company_id option:selected')
                    .attr('data-name') || '-'
                );
                $('#branch_name').text('-');
                $('#branch_phone').text('-');
                $('#branch_email').text('-');
                $('#branch_address').text('-');
            }

            $('#customer_company_id').on('change', function() {

                let customerCompanyId = $(this).val();

                $('#customer_company_name').text('');
                $('#customer_company_phone').text('');
                $('#customer_company_email').text('');
                $('#customer_company_address').text('');

                $('#party_id')
                    .html('<option value="">Select Party</option>')
                    .val('')
                    .trigger('change');

                $('#party_name').text('-');
                $('#party_designation').text('-');
                $('#party_phone').text('-');
                $('#party_email').text('-');
                $('#party_address').text('-');

                if (!customerCompanyId) {
                    return;
                }

                $.get(
                    "{{ url('/admin/ajax/customer-company') }}/" + customerCompanyId,
                    function(res) {

                        if (res.success) {

                            $('#customer_company_name')
                                .text(res.data.name ?? '');

                            $('#customer_company_phone')
                                .text(res.data.phone ?? '');

                            $('#customer_company_email')
                                .text(res.data.email ?? '');

                            $('#customer_company_address')
                                .text(res.data.address ?? '');
                        }
                    }
                );

                $.get(
                    "{{ url('/admin/ajax/customer-expense') }}/" +
                    customerCompanyId +
                    "/parties",
                    function(res) {

                        if (res.success) {

                            let html =
                                '<option value="">Select Party</option>';

                            $.each(res.parties, function(i, party) {

                                html += `
                        <option value="${party.id}">
                            ${party.name ?? '-'}
                            ${party.designation ? ' - ' + party.designation : ''}
                        </option>
                    `;
                            });

                            $('#party_id')
                                .html(html)
                                .trigger('change');
                        }
                    }
                );

            });

            $('#party_id').on('change', function() {

                let partyId = $(this).val();

                $('#party_name').text('-');
                $('#party_designation').text('-');
                $('#party_phone').text('-');
                $('#party_email').text('-');
                $('#party_address').text('-');

                if (!partyId) {
                    return;
                }

                $.get(
                    "{{ url('/admin/ajax/party') }}/" + partyId,
                    function(res) {

                        if (res.success) {

                            $('#party_name')
                                .text(res.data.name ?? '-');

                            $('#party_designation')
                                .text(res.data.designation ?? '-');

                            $('#party_phone')
                                .text(res.data.phone ?? '-');

                            $('#party_email')
                                .text(res.data.email ?? '-');

                            $('#party_address')
                                .text(res.data.address ?? '-');
                        }
                    }
                );

            });

            function resetPartyInfo() {
                $('#party_name').text('-');
                $('#party_designation').text('-');
                $('#party_phone').text('-');
                $('#party_email').text('-');
                $('#party_address').text('-');
            }
            addExpenseRow();
            $(document).on('click', '#addRow', function() {
                addExpenseRow();
                calculateGrandTotal();
            });

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
                initSelect2($row);
                updateRowNumbers();
                updateRemoveButtons();
                calculateRowTotal($row);
                calculateGrandTotal();
            }
            $(document).on('click', '.removeRow', function() {
                const rows =
                    $('#expenseBody .expense-row');
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
            });

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
            $(document).on('change', '.category', function() {
                const categoryId =
                    $(this).val();
                const $row =
                    $(this)
                    .closest('.expense-row');
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
                        $accountHead
                            .prop(
                                'disabled',
                                false
                            )
                            .trigger(
                                'change'
                            );

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

                            text: 'Unable to load expense/account head.'

                        });

                    }

                });

            });
            $(document).on('input', '.qty, .rate', function() {
                const $row =
                    $(this)
                    .closest('.expense-row');
                calculateRowTotal($row);
                calculateGrandTotal();
            });
            $(document).on('input', '#discount', function() {
                calculateGrandTotal();
            });
            $(document).on('input', '#vat', function() {
                calculateGrandTotal();
            });

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

            function calculateGrandTotal() {

                let totalQty = 0;

                let subTotal = 0;
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

                        $row.find('.total')
                            .val(
                                rowTotal.toFixed(2)
                            );

                        totalQty += qty;
                        subTotal += rowTotal;

                    });

                let discount =
                    parseFloat(
                        $('#discount').val()
                    ) || 0;
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


                let vatPercent =
                    parseFloat(
                        $('#vat').val()
                    ) || 0;


                if (vatPercent < 0) {

                    vatPercent = 0;

                    $('#vat').val(0);

                }

                const afterDiscount =
                    subTotal - discount;


                const vatAmount =
                    (
                        afterDiscount *
                        vatPercent
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

            function formatNumber(number) {

                if (
                    Number.isInteger(number)
                ) {

                    return number.toString();

                }

                return number.toFixed(2);

            }
            $('#receiptForm').on('submit', function(e) {

                let valid = true;

                let message = '';

                const items = [];

                if (!$('#company_id').val()) {

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
                        'Please select Party.';

                }

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

                            items.push({

                                category_id: category,

                                account_head_id: accountHead,

                                qty: qty,

                                rate: rate,

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
                        JSON.stringify(items)
                    );

            });
            calculateGrandTotal();
        });
    </script>
@endpush
