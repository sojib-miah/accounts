 <script>
     let rowNo = 1;

     function addRow() {
         let html = $('#salesRowTemplate').html();
         $('#salesBody').append(html);
         let row = $('#salesBody tr:last');
         row.find('.sl').text(rowNo);
         row.find('.product').select2({
             width: '100%'
         });
         rowNo++;
         serial();
         calculate();
     }
     $(function() {
         addRow();
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
         let duplicate = false;
         $('.product').not(this).each(function() {
             if ($(this).val() == productId && productId != '') {
                 duplicate = true;
             }
         });
         if (duplicate) {
             Swal.fire({
                 icon: 'warning',
                 title: 'Duplicate Product',
                 text: 'This product has already been added.'
             });
             $(this).val('').trigger('change');
             return;
         }
         row.find('.stock').val(
             option.data('stock') ?? 0
         );
         row.find('.rate').val(
             option.data('price') ?? 0
         );
         row.find('.qty').val(1);
         calculate();
         row.find('.qty').focus();
     });

     function calculate() {
         let totalQty = 0;
         let subTotal = 0;
         $('#salesBody tr').each(function() {
             let row = $(this);
             let stock = parseFloat(row.find('.stock').val()) || 0;
             let qty = parseFloat(row.find('.qty').val()) || 0;
             let rate = parseFloat(row.find('.rate').val()) || 0;
             let product = row.find('.product').val();
             if (product) {
                 if (qty > stock) {
                     Swal.fire({
                         icon: 'warning',
                         title: 'Stock Not Available',
                         text: 'Available Stock : ' + stock
                     });
                     qty = stock;
                     row.find('.qty').val(stock);
                 }
             }
             if (qty < 0) {
                 qty = 0;
                 row.find('.qty').val(0);
             }
             let amount = qty * rate;
             row.find('.total').val(
                 amount.toFixed(2)
             );
             totalQty += qty;
             subTotal += amount;
         });

         let discount = parseFloat(
             $('#discount').val()
         ) || 0;
         if (discount > subTotal) {
             discount = subTotal;
             $('#discount').val(
                 discount.toFixed(2)
             );
         }
         let vatPercent = parseFloat(
             $('#vat').val()
         ) || 0;
         let afterDiscount = subTotal - discount;
         let vatAmount =
             (afterDiscount * vatPercent) / 100;
         let grandTotal =
             afterDiscount + vatAmount;
         $('#total_qty').val(
             totalQty.toFixed(2)
         );
         $('#sub_total').val(
             subTotal.toFixed(2)
         );
         $('#grand_total').val(
             grandTotal.toFixed(2)
         );
     }
     $(document).on(
         'keyup change',
         '.qty',
         function() {
             calculate();
         }
     );
     $(document).on(
         'keyup change',
         '.rate',
         function() {
             calculate();
         }
     );
     $(document).on(
         'keyup change',
         '#discount',
         function() {
             calculate();
         }
     );
     $(document).on(
         'keyup change',
         '#vat',
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
             confirmButtonText: 'Yes',
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
                 .focus();
         }
     });
     $(document).on('focus', '.qty,.rate,#discount,#vat', function() {
         $(this).on('wheel.disableScroll', function(e) {
             e.preventDefault();
         });
     });
     $(document).on('blur', '.qty,.rate,#discount,#vat', function() {
         $(this).off('wheel.disableScroll');
     });
     $('form').submit(function(e) {
         let valid = true;
         $('#salesBody tr').each(function() {
             let row = $(this);
             row.removeClass('table-danger');
             if (
                 row.find('.product').val() == '' ||
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
                 text: 'Please complete all product information.'
             });
             return;
         }
     });
     $(document).keydown(function(e) {
         if (e.ctrlKey && e.key.toLowerCase() === 's') {
             e.preventDefault();
             $('form').submit();
         }
     });

     calculate();

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
