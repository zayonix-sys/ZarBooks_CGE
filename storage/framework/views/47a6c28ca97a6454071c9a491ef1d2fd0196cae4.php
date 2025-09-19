<?php $__env->startSection('title', 'Non-Taxable Invoice'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>
        <link href="<?php echo e(asset('lib/select2/select2.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/flatpickr/flatpickr.min.css')); ?>" rel="stylesheet">
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('footer'); ?>
        <script src="<?php echo e(asset('lib/select2/select2.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/jquery.dataTables.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables.net-responsive-bs4/dataTables.responsive.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/dataTables.buttons.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/jszip.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/pdfmake.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/vfs_fonts.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/buttons.html5.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/buttons.print.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/dataTableSum.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/flatpickr/flatpickr.min.js')); ?>"></script>
        <script src="<?php echo e(asset('js/number-formatter.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/date-js/date.js')); ?>"></script>

        <script type="text/javascript">
            $(document).ready(function () {

                //Select Focus on Search Filed
                $(document).on('select2:open', () => {
                    document.querySelector('input.select2-search__field').focus();
                });

                // Select Open on Down Key
                $(document).on('keydown', '.select2', function (e) {
                    if (e.originalEvent && e.which == 40) {
                        e.preventDefault();
                        $(this).siblings('select').select2('open');
                    }
                });

                //Initialize Select Picker
                $('select').select2({
                    theme: 'bootstrap4',
                    selectOnClose: true,
                });

                //Initialize Invoice Date Picker
                $('#dpInvoiceDate').flatpickr({
                    dateFormat: 'd-M-Y',
                    defaultDate: 'today'
                });

                //Initialize Purchase Order Date Picker
                $('#dpPurchaseOrderDate').flatpickr({
                    dateFormat: 'd-M-Y',
                });

                //Function to Add More Account Row
                function AddAccountRow() {
                    //Destroy Select Picker
                    $('.selectItem').select2("destroy");

                    $tableBody = $('#tblItemEntry').find("tbody");
                    $trLast = $tableBody.find("tr:last");
                    $trNew = $trLast.clone();
                    $trLast.after($trNew);

                    $tableBody.find("tr:last td:nth-child(2) input[type='text']").val('');
                    $tableBody.find("tr:last td:nth-child(3) input[type='number']").val('');
                    $tableBody.find("tr:last td:nth-child(4) input[type='number']").val('');
                    $tableBody.find("tr:last td:nth-child(5) input[type='number']").val('');
                    $tableBody.find("tr:last td:nth-child(6) input[type='number']").val('');

                    //Re-Initialized Select Picker
                    $('.selectItem').select2({
                        theme: 'bootstrap4',
                        selectOnClose: true,
                    });
                }

                //Add New Row in Entry Table
                $('#tblItemEntry').on('keyup', 'td:nth-child(6)', function () {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        AddAccountRow();
                    }
                    calculateRowSum();
                });

                $('#btnAddItem').on('click', function () {
                    AddAccountRow();
                });

                //Remove Row in Entry Table
                $('#tblItemEntry').on('click', '.deleteRow', function () {
                    $rowCount = $('#tblItemEntry tbody tr').length;
                    if ($rowCount > 1) {
                        $(this).closest('tr').remove();
                    }
                    calculateRowSum();
                });


                // const dtCustomers = $('#dtCustomers').DataTable();
                // new $.fn.dataTable.Buttons(dtCustomers, {
                //     buttons: [
                //         {
                //             extend: 'copy',
                //             className: 'btn btn-falcon-default btn-sm mr-1',
                //             text: '<span class="far fa-copy fa-lg"></span> Copy',
                //             footer: true
                //         },
                //         {
                //             extend: 'excel',
                //             className: 'btn btn-falcon-success btn-sm mr-1',
                //             text: '<span class="far fa-file-excel fa-lg"></span> Export Excel',
                //             footer: true
                //         },
                //         {
                //             extend: 'print',
                //             className: 'btn btn-falcon-warning btn-sm',
                //             text: '<span class="fas fa-print fa-lg"></span> Print',
                //             title: 'Transactions Summary',
                //             exportOptions: {
                //                 columns: [0, 1, 2, 3, 4, 5, 6, 7]
                //             },
                //             customize: function (win) {
                //                 $(win.document.body)
                //                     .css('font-size', '11pt')
                //                 $(win.document.body).find('table')
                //                     .addClass('compact')
                //                     .css('font-size', 'inherit');
                //             }
                //         },
                //     ]
                // });
                // dtCustomers.buttons().container().appendTo('#dtCustomersAction');

                //Getting Customer Info for Update
                // dtCustomers.on('click', '.editCustomer', function (e) {
                //     e.preventDefault();
                //
                //     var id = $(this).attr('data-id');
                //     let url = '/customer/'+id+'/edit';
                //
                //     $.get(url, function (customer) {
                //         console.log(customer);
                //         $('#txtName').val(customer.name);
                //         $('#txtContact').val(customer.contact);
                //         $('#txtCNIC').val(customer.cnic);
                //         $('#txtNTN').val(customer.ntn);
                //         $('#txtSTRN').val(customer.strn);
                //         $('#txtAddress').val(customer.address);
                //         $('#txtEmail').val(customer.email);
                //
                //         if (customer.status == 1) {
                //             $('#customerActive').attr('checked', 'checked');
                //         } else {
                //             $('#customerInActive').attr('checked', 'checked');
                //         }
                //
                //         $('#frmEditCustomer').attr('action', '/customer/'+customer.id);
                //
                //         $('#editCustomerModal').modal('show');
                //     })
                // });

                // // Display Add Item Modal
                // $('#addCustomer').on('click', function (){
                //     $('#addCustomerModal').modal('show');
                // });
            });
        </script>

    <?php $__env->stopPush(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="bg-holder d-none d-lg-block bg-card"
                     style="background-image:url('<?php echo e(asset('media/illustrations/corner-4.png')); ?>');">
                </div>
                <!--/.bg-holder-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Sales Invoice (Non-Taxable)</h3>

                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb fs-1">
                                <ol class="breadcrumb">
                                    <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->

                                    <?php echo e($link = ""); ?>

                                    <?php for($i = 1; $i <= count(Request::segments()); $i++): ?>

                                        <?php if($i < count(Request::segments()) & $i > 0): ?>
                                            <!-- <?php echo e($link .= "/" . Request::segment($i)); ?> -->
                                            <li class="breadcrumb-item">
                                                <a href="<?= $link ?>"><?php echo e(ucwords(str_replace('-',' ',Request::segment($i)))); ?></a>
                                            </li>
                                            <i class="fas fa-long-arrow-alt-right m-1"></i>

                                        <?php else: ?>
                                            <?php echo e(ucwords(str_replace('-',' ',Request::segment($i)))); ?>

                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <!-- <li class="breadcrumb-item"><a href="#">Library</a></li>
				          		<li class="breadcrumb-item active" aria-current="page">Data</li> -->
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                <div class="card-body">
                    <form action="" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-row ">
                            <div class="col-sm-3">
                                <label>Customer</label>
                                <select class="form-control selectpicker fs--1" id="ddCustomer" name="customer">
                                    <option value="#">Select Customer</option>
                                    <?php if(isset($customers)): ?>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="dpInvoiceDate">Invoice Date</label>
                                <input class="form-control datetimepicker flatpickr-input fs--1" id="dpInvoiceDate"
                                       type="text"
                                       name="invoice_date">
                            </div>
                            <div class="col-sm-1">
                                <label>Invoice Type</label>
                                <select class="form-control selectpicker fs--1" id="ddInvoiceType" name="invoice_type">
                                    <option value="Trading">Trading</option>
                                    <option value="Services">Services</option>
                                    <option value="Rental">Rental</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <label>Term</label>
                                <select class="form-control selectpicker fs--1" id="ddPaymentTerm" name="payment_term">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit">Credit</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="dpPurchaseOrderDate">Purchase Order Date</label>
                                <input class="form-control datetimepicker flatpickr-input fs--1"
                                       id="dpPurchaseOrderDate"
                                       placeholder="Select P.O Date"
                                       type="text"
                                       name="purchase_order_date">
                            </div>
                            <div class="col-sm-2">
                                <label>P.O. No.</label>
                                <input type="text" class="form-control fs--1" name="purchase_order_no"
                                       value="<?php echo e(old('payee')); ?>" required autofocus>
                            </div>
                            <div class="col-sm-1">
                                <label>D. C. No.</label>
                                <input type="text" class="form-control fs--1" name="dc_no" value="<?php echo e(old('payee')); ?>">
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="row mx-1 my-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive fs--1">
                                    <div class="d-flex">
                                        <p class="text-warning">- Press Enter On Amount Column to Add More Item or Use
                                            Button</p>
                                        <button
                                            class="btn btn-falcon-default btn-sm rounded-capsule ml-auto my-2 mx-1"
                                            type="button" id="btnAddItem">
                                            <span class="fas fa-plus mr-1" data-fa-transform="shrink-3"></span>Add
                                            Item
                                        </button>
                                    </div>
                                    <table id="tblItemEntry" class="table table-striped border">
                                        <thead class="bg-200 text-900">
                                            <tr class="text-center">
                                                <th class="border-0" width="20%">Item</th>
                                                <th class="border-0">Description</th>
                                                <th class="border-0" width="8%">Qty</th>
                                                <th class="border-0" width="10%">Rate</th>
                                                <th class="border-0" width="10%">Gross Amount</th>
                                                <th class="border-0" width="10%">Disc</th>
                                                <th class="border-0" width="10%">Net Amount</th>
                                                <th class="border-0 text-center" width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td class="align-middle">
                                                <select
                                                    class="form-control form-control-sm selectpicker selectItem"
                                                    name="item_id[]">
                                                    <?php if(isset($items)): ?>
                                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option
                                                                value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                            </td>
                                            <td class="align-middle">
                                                <textarea class="form-control form-control-sm fs--1" rows="1"
                                                          name="description[]"
                                                          type="text" placeholder="Enter Particular" required
                                                          autocomplete="true">
                                                </textarea>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="qty[]"
                                                       type="number" min="0" required>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="rate[]"
                                                       type="number" min="0" required>
                                            </td>
                                            <td class="align-left">
                                                <input class="form-control form-control-sm"
                                                       name="gross_amount[]"
                                                       type="number" min="0" required
                                                       readonly
                                                       onkeydown="return event.key != 'Enter';">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="disc[]"
                                                       type="number" min="0">
                                            </td>
                                            <td class="align-left">
                                                <input class="form-control form-control-sm transAmount"
                                                       name="net_amount[]"
                                                       type="number" min="0" required
                                                       readonly
                                                       onkeydown="return event.key != 'Enter';">
                                            </td>
                                            <td class="deleteRow align-middle text-center cursor-pointer">
                                                <span class="badge rounded-capsule badge-soft-danger cursor-pointer">
                                                    Delete <span class="ml-1 fas fa-window-close fa-lg"></span>
                                                </span>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot class="bg-200">
                                            <tr>
                                            <th></th>
                                            <th class="text-right">Total Amount</th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold" id="txtTotalAmount"></th>
                                            <th><input type="hidden" id="drTotal" name="trn_amount"></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal-->
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    <!--Edit Customer Modal-->
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/invoice/invoice-non-taxable.blade.php ENDPATH**/ ?>