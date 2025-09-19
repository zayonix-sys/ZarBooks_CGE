<?php $__env->startSection('title', 'Customer List'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>
        <link href="<?php echo e(asset('lib/select2/select2.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
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

                /**
                 * Initialize Select Picker
                 */

                $('select').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Select Parent Category',
                    dropdownParent: $('#addCustomerModal')
                });

                const dtCustomers = $('#dtCustomers').DataTable();
                new $.fn.dataTable.Buttons(dtCustomers, {
                    buttons: [
                        {
                            extend: 'copy',
                            className: 'btn btn-falcon-default btn-sm mr-1',
                            text: '<span class="far fa-copy fa-lg"></span> Copy',
                            footer: true
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-falcon-success btn-sm mr-1',
                            text: '<span class="far fa-file-excel fa-lg"></span> Export Excel',
                            footer: true
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-falcon-warning btn-sm',
                            text: '<span class="fas fa-print fa-lg"></span> Print',
                            title: 'Transactions Summary',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            },
                            customize: function (win) {
                                $(win.document.body)
                                    .css('font-size', '11pt')
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                            }
                        },
                    ]
                });
                dtCustomers.buttons().container().appendTo('#dtCustomersAction');

                //Getting Customer Info for Update
                dtCustomers.on('click', '.editCustomer', function (e) {
                    e.preventDefault();

                    var id = $(this).attr('data-id');
                    let url = '/customer/'+id+'/edit';

                    $.get(url, function (customer) {
                        console.log(customer);
                        $('#txtName').val(customer.name);
                        $('#txtContact').val(customer.contact);
                        $('#txtCNIC').val(customer.cnic);
                        $('#txtNTN').val(customer.ntn);
                        $('#txtSTRN').val(customer.strn);
                        $('#txtAddress').val(customer.address);
                        $('#txtEmail').val(customer.email);

                        if (customer.status == 1) {
                            $('#customerActive').attr('checked', 'checked');
                        } else {
                            $('#customerInActive').attr('checked', 'checked');
                        }

                        $('#frmEditCustomer').attr('action', '/customer/'+customer.id);

                        $('#editCustomerModal').modal('show');
                    })
                });

                // Display Add Item Modal
                $('#addCustomer').on('click', function (){
                    $('#addCustomerModal').modal('show');
                });
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Customers</h3>

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
        <!-- Items Detail Tables-->
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom bg-dark">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-3 col-sm-auto d-flex align-items-center pr-0">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-success font-weight-semi-bold">
                                Customers
                            </h5>
                        </div>
                        <div class="col-6 col-sm-auto text-center pl-0">
                            <div id="dtCustomersAction" class="d-inline-flex">
                                
                            </div>
                        </div>
                        <div class="col-3 col-sm-auto d-flex align-items-right pr-3">
                            <button class="btn btn-falcon-success btn-sm mr-2" id="addCustomer">
                                <i class="fa-solid fa-user-plus fa-lg"></i> Add New Customer
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive fs--1">
                        <table id="dtCustomers" class="table table-sm border-bottom table-hover">
                            <thead class="bg-200 text-900">
                            <tr>
                                <th class="border-0">S.No</th>
                                <th class="border-0 ">Customer Name</th>

                                <th class="border-0 ">Contact No.</th>
                                <th class="border-0 ">Email</th>
                                <th class="border-0 text-center">CNIC</th>
                                <th class="border-0 text-center">NTN No.</th>
                                <th class="border-0 text-center">STRN No.</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($customers)): ?>
                                <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($customer->name); ?></td>

                                        <td><?php echo e($customer->contact); ?></td>
                                        <td><?php echo e($customer->email); ?></td>
                                        <td><?php echo e($customer->cnic); ?></td>
                                        <td><?php echo e($customer->ntn); ?></td>
                                        <td><?php echo e($customer->strn); ?></td>
                                        <td class="text-center">
                                            <?php if($customer->status === 1 ): ?>
                                                <span class="badge rounded-capsule badge-soft-success">Active
                                                    <span class="ml-1 fas fa-check"></span>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                    <span class="ml-1 fas fa-times"></span>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center cursor-pointer ">





                                                <span class="badge rounded-capsule badge-soft-info ml-2">
                                                    <a href="#" data-id="<?php echo e($customer->id); ?>" class="editCustomer">Edit
                                                        <span class="ml-1 fas fa-edit fa-lg px-1"></span>
                                                    </a>
                                                </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal-->
    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom bg-dark">
                    <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                        Add Customer
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form action="/customer" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="form-row">
                                    <div class="col-sm-8">
                                        <label class="mb-0">Customer Name</label>
                                        <input type="text" name="name" class="form-control"
                                               value="<?php echo e(old('name')); ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">Contact No.</label>
                                        <input type="text" name="contact" class="form-control"
                                               value="<?php echo e(old('contact')); ?>">
                                    </div>
                                </div>

                                <div class="form-row mt-3">
                                    <div class="col-sm-4">
                                        <label class="mb-0">CNIC No.</label>
                                        <input type="text" name="cnic" class="form-control"
                                               value="<?php echo e(old('cnic')); ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">NTN No.</label>
                                        <input type="text" name="ntn" class="form-control"
                                               value="<?php echo e(old('ntn')); ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">STRN No.</label>
                                        <input type="text" name="strn" class="form-control"
                                               value="<?php echo e(old('strn')); ?>">
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-sm-12">
                                        <label class="mb-0">Address</label>
                                        <textarea class="form-control" rows="3" name="address" ><?php echo e(old('address')); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-sm-4">
                                        <label class="mb-0">Email</label>
                                        <input type="text" name="email" class="form-control"
                                               value="<?php echo e(old('email')); ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">Select Associate Account</label>
                                        <select class="form-control selectpicker"
                                                name="parent_account_id">
                                            <?php $__currentLoopData = $parentAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($parentAccount->id); ?>"><?php echo e($parentAccount->title); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mt-3">
                                        <button type="submit" class="btn btn-outline-success btn-block" id="btnAdd">Add
                                            Customer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Edit Customer Modal-->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom bg-soft-warning">
                    <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                        Edit Customer
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="POST" id="frmEditCustomer">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="form-row">
                                    <div class="col-sm-8">
                                        <label class="mb-0">Customer Name</label>
                                        <input type="text" name="name" class="form-control" id="txtName">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">Contact No.</label>
                                        <input type="text" name="contact" class="form-control" id="txtContact">
                                    </div>
                                </div>

                                <div class="form-row mt-3">
                                    <div class="col-sm-4">
                                        <label class="mb-0">CNIC No.</label>
                                        <input type="text" name="cnic" class="form-control" id="txtCNIC">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">NTN No.</label>
                                        <input type="text" name="ntn" class="form-control" id="txtNTN">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">STRN No.</label>
                                        <input type="text" name="strn" class="form-control" id="txtSTRN">
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-sm-12">
                                        <label class="mb-0">Address</label>
                                        <textarea class="form-control" rows="3" name="address" id="txtAddress"></textarea>
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-sm-4">
                                        <label class="mb-0">Email</label>
                                        <input type="text" name="email" class="form-control" id="txtEmail">
                                    </div>
                                    <div class="col-sm-5 mt-4">
                                        <div class="form-group">
                                            <label for="customRadioInline1">Status</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input class="custom-control-input" id="customerActive" type="radio"
                                                       name="status" value="1">
                                                <label class="custom-control-label" for="customerActive">Active</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input class="custom-control-input" id="customerInActive" type="radio"
                                                       name="status" value="0">
                                                <label class="custom-control-label" for="customerInActive">In-Active</label>
                                            </div>
                                        </div>
                                    </div>










                                    <div class="col-sm-3 mt-3">
                                        <button type="submit" class="btn btn-sm btn-outline-success btn-block" id="btnAdd">Update
                                            Customer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH I:\OneDrive\Zeeshan Programming\ZarBooks\resources\views/customer/customers-list.blade.php ENDPATH**/ ?>