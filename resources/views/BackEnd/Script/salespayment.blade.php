<script>
    $(document).ready(function() {

        // Payment Type Change
        $(document).on('change', '#payment_type_id', function() {
            let paymentTypeId = $(this).val();
            let accountSelect = $('#account_id');
            let accountBalance = $('#account_balance');
            accountSelect.empty().append('<option value="">Loading accounts...</option>').prop(
                'disabled', true).prop('required', true);
            accountBalance.html('');
            accountSelect.trigger('change.select2');
            if (!paymentTypeId) {
                accountSelect.empty().append('<option value="">Select Payment Type First</option>')
                    .prop('disabled', true).prop('required', true);
                accountBalance.html('');
                accountSelect.trigger('change.select2');
                return;
            }
            $.ajax({
                url: "{{ url('/admin/ajax/payment-type') }}/" + paymentTypeId + "/accounts",
                type: "GET",
                success: function(response) {
                    accountSelect.empty();
                    if (response.success && response.accounts && response.accounts.length >
                        0) {
                        accountSelect.append(
                            '<option value="">Select Account</option>'
                        );
                        $.each(
                            response.accounts,
                            function(index, account) {
                                let option = $('<option>', {
                                    value: account.id,
                                    text: account.account_name + ' - ' + account
                                        .account_number
                                });
                                // Keep payment type information
                                option.attr('data-payment-type', account
                                    .payment_type_id);
                                accountSelect.append(option);
                            }
                        );
                        accountSelect.prop('disabled', false).prop('required', true);
                        accountBalance.html('');
                    } else {
                        accountSelect.append('<option value="">No Account Found</option>');
                        accountSelect.prop('disabled', true).prop('required', true);
                        accountBalance.html(`
                            <div class="alert alert-warning py-2 mb-0">
                                <i class="fa fa-exclamation-triangle me-1"></i>
                                No active account found for this payment type.
                            </div>
                        `);
                    }
                    accountSelect.trigger('change.select2');
                },
                error: function() {
                    accountSelect.empty().append(
                        '<option value="">Unable to load accounts</option>').prop(
                        'disabled', true).prop('required', true);
                    accountBalance.html(`
                        <div class="alert alert-danger py-2 mb-0">
                            <i class="fa fa-times-circle me-1"></i>
                            Unable to load payment accounts.
                        </div>
                    `);
                    accountSelect.trigger('change.select2');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to load accounts.'
                    });
                }
            });
        });
        // Account Change
        $(document).on('change', '#account_id', function() {
            let accountId = $(this).val();
            // No balance check/display
            $('#account_balance').html('');
            if (!accountId) {
                return;
            }
        });
        // Payment Amount Input
        $(document).on('input', '#payment_amount', function() {
            // No balance checking here
        });
        // Payment Form Submit
        $('#paymentForm').on('submit', function(e) {
            let paymentTypeId = $('#payment_type_id').val();
            let accountId = $('#account_id').val();
            let amount = parseFloat($('#payment_amount').val()) || 0;
            let due = parseFloat("{{ $receipt->due_amount }}") || 0;
            let paymentTypeName = $('#payment_type_id option:selected').text().trim();
            let account = $('#account_id option:selected');
            // Payment Type Validation
            if (!paymentTypeId) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Payment Type Required',
                    text: 'Please select a payment type.'
                });
                return false;
            }
            // Account Validation
            if (!accountId) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Account Required',
                    text: 'Please select an account for ' + paymentTypeName + ' payment.'
                });
                return false;
            }
            // Amount Validation
            if (amount <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Amount',
                    text: 'Payment amount must be greater than zero.'
                });
                return false;
            }
            // Due Amount Validation
            if (amount > due) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Amount Exceeds Due',
                    text: 'Maximum payment allowed is ৳ ' + due.toFixed(2)
                });
                return false;
            }
            // Confirmation
            e.preventDefault();
            Swal.fire({
                title: 'Confirm Payment',
                html: 'Payment Type: <strong>' + paymentTypeName + '</strong><br>' +
                    'Account: <strong>' + account.text().trim() + '</strong><br>' +
                    'Payment Amount: <strong>৳ ' + amount.toFixed(2) + '</strong><br><br>' +
                    'Are you sure you want to receive this payment?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Receive Payment',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $('#payButton')
                        .prop('disabled', true)
                        .html(
                            '<i class="fa fa-spinner fa-spin me-1"></i>' +
                            ' Processing...'
                        );
                    $('#paymentForm')[0].submit();
                }
            });
            return false;
        });
    });
</script>
