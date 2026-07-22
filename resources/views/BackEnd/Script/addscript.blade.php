 <script>
     let rowNo = 1;
     let categories = @json($categories);

     function addRow() {
         let options = '<option value="">Select Category</option>';
         categories.forEach(function(category) {
             options += `<option value="${category.id}">${category.name}</option>`;
         });
         let html = `
                    <tr>
                    <td class="sl">${rowNo}</td>
                    <td class="custome"><select class="form-select category select2">${options}</select></td>
                    <td class="custome">
                    <select class="form-select account select2"><option value="">Select Expense</option></select>
                    </td>
                    <td><input type="number" class="form-control qty" value="1"></td>
                    <td><input type="number" class="form-control rate" value="0"></td>
                    <td><input type="text" class="form-control total" readonly></td>
                    <td><input type="text" class="form-control details"></td>
                    <td><button type="button" class="btn btn-danger remove"><i class='fa fa-trash'></i></button></td>
                    </tr>
                    `;
         $('#expenseBody').append(html);
         calculate();
         let row = $('#expenseBody tr:last');
         row.find('.category').select2({
             width: '100%'
         });
         row.find('.account').select2({
             width: '100%'
         });
         rowNo++;
         //  let lastRow = $('#expenseBody tr:last');
         //  lastRow.find('.category').select2('open');
     }
     $(function() {
         addRow();
     });
     $('#addRow').click(function() {
         addRow();
     });

     function serial() {
         let i = 1;
         $('#expenseBody tr').each(function() {
             $(this).find('.sl').text(i);
             i++;
         });
     }
     $(document).on('change', '.category', function() {
         let row = $(this).closest('tr');
         let categoryId = $(this).val();
         let account = row.find('.account');
         account.empty();
         account.append('<option value="">Loading...</option>');
         if (categoryId == '') {
             account.html('<option value="">Select Expense</option>');
             return;
         }
         $.ajax({
             url: "{{ route('ajax.account-head', ':id') }}".replace(':id', categoryId),
             type: 'GET',
             success: function(response) {
                 let option = '<option value="">Select Expense</option>';
                 $.each(response.data, function(index, item) {
                     option += `<option value="${item.id}">${item.name}</option>`;
                 });
                 account.html(option);
                 account.trigger('change.select2');
             },
             error: function() {
                 alert('Failed to load Expense Head.');
             }
         });
     });

     function calculate() {
         let qtyTotal = 0;
         let subTotal = 0;
         let items = [];

         $('#expenseBody tr').each(function() {

             let row = $(this);

             let qty = parseFloat(row.find('.qty').val()) || 0;
             let rate = parseFloat(row.find('.rate').val()) || 0;

             let amount = qty * rate;

             row.find('.total').val(amount.toFixed(2));

             qtyTotal += qty;
             subTotal += amount;

             items.push({
                 category_id: row.find('.category').val(),
                 category_name: row.find('.category option:selected').text(),
                 account_head_id: row.find('.account').val(),
                 account_head_name: row.find('.account option:selected').text(),
                 qty: qty,
                 rate: rate,
                 amount: amount,
                 details: row.find('.details').val()
             });
         });

         let discount = parseFloat($('#discount').val()) || 0;
         let vatPercent = parseFloat($('#vat').val()) || 0;

         if (discount > subTotal) {
             discount = subTotal;
             $('#discount').val(discount.toFixed(2));
         }

         let afterDiscount = subTotal - discount;
         let vatAmount = (afterDiscount * vatPercent) / 100;
         let grandTotal = afterDiscount + vatAmount;

         $('#total_qty').val(qtyTotal);
         $('#sub_total').val(subTotal.toFixed(2));
         $('#grand_total').val(grandTotal.toFixed(2));
         $('#items_json').val(JSON.stringify(items));
     }
     $(document).on('keyup change', '.qty', function() {
         calculate();
     });
     $(document).on('keyup change', '.rate', function() {
         calculate();
     });
     $(document).on('keyup change', '#discount', function() {
         calculate();
     });
     $(document).on('keyup change', '#vat', function() {
         calculate();
     });
     $(document).on('keyup', '.details', function() {
         calculate();
     });
     $(document).on('change', '.account', function() {
         calculate();
     });
     $(document).on('change', '.account', function() {
         $(this)
             .closest('tr')
             .find('.qty')
             .focus();

         calculate();
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
         }
     });
     $('form').submit(function(e) {
         let valid = true;
         $('#expenseBody tr').each(function() {
             let row = $(this);
             row.removeClass('table-danger');
             if (
                 row.find('.category').val() == '' ||
                 row.find('.account').val() == '' ||
                 row.find('.qty').val() == '' ||
                 row.find('.rate').val() == ''
             ) {
                 valid = false;
                 row.addClass('table-danger');
             }
         });
         if (!valid) {
             e.preventDefault();
             Swal.fire({
                 icon: 'warning',
                 title: 'Incomplete Data',
                 text: 'Please complete all rows before saving.'
             });
         }
     });
     $(document).keydown(function(e) {
         if (e.ctrlKey && e.key === 's') {
             e.preventDefault();
             $('form').submit();
         }
     });
     $(document).on('focus', '.qty,.rate', function() {
         $(this).on('wheel.disableScroll', function(e) {
             e.preventDefault();
         });
     });
     $(document).on('blur', '.qty,.rate', function() {
         $(this).off('wheel.disableScroll');
     });
     $(document).on('click', '.remove', function(e) {
         e.preventDefault();
         // Check BEFORE showing delete confirmation
         if ($('#expenseBody tr').length <= 1) {
             Swal.fire({
                 icon: 'warning',
                 title: 'At least one item is required.'
             });
             return;
         }

         let row = $(this).closest('tr');
         Swal.fire({
             title: 'Delete Item?',
             text: 'This row will be removed.',
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
