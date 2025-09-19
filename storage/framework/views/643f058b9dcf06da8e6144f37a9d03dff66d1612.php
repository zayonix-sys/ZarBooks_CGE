<?php use Illuminate\Support\Arr;

$__env->startSection('content'); ?>

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
        <script src="<?php echo e(asset('lib/datatables/js/dataTableSum.js')); ?>"></script>
        <script src="<?php echo e(asset('js/number-formatter.js')); ?>"></script>

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
                    placeholder: 'Select Account',
                });



                //Generate Parent Account DropDown on Basis of Account Group
                $('#ddAccountGroup').on('change', function (e) {
                    e.preventDefault();

                    var $accGroup = $(this).val();
                    let url = "<?php echo e(route('getParentAccounts', ':accGroup')); ?>";
                    url = url.replace(':accGroup', $accGroup);

                    $.get(url, function (parentAccounts) {
                        //console.log(parentAccounts);
                        $("#ddParentAccount").empty().trigger('change');
                        for (let i = 0; i < parentAccounts.length; i++) {
                            $('#ddParentAccount').append(
                                '<option value=' + parentAccounts[i].id + '>' + parentAccounts[i].title + '</option>'
                            );
                        }
                    })
                });

                //Function For Edit Account
                function EditAccount($dataTable)
                {
                    $dataTable.on('click', '.editAccount', function () {
                        $tr = $(this).closest('tr');
                        var data = $dataTable.row($tr).data();

                        var accCode = $tr.find("td:eq(0)").text().replace(/\s+/g, "");
                        var accTitle = $tr.find("td:eq(2)").text().trim();
                        var accStatus = $tr.find("td:eq(3)").text().replace(/\s+/g, "");

                        // console.log(accCode +' '+accTitle +' '+accStatus);
                        $('#editAccountTitle').val(accTitle);
                        if (accStatus == 'Active') {
                            $('#accActive').attr('checked', 'checked');
                        } else {
                            $('#accInActive').attr('checked', 'checked');
                        }

                        let url = "<?php echo e(route('getControllingAccount', ':accCode')); ?>";
                        url = url.replace(':accCode', accCode);

                        $.get(url, function (accountDetails) {
                            //Set Selected Value from Controller
                            $('#ddEditAccount').val(accountDetails[0].parent_account_id);
                            $('#ddEditAccount').select2({
                                theme: 'bootstrap4',
                                placeholder: 'Select Account',
                                dropdownParent: $('#editAccModal')
                            }).trigger('change');

                            $('#frmEditAccount').attr('action', '/updateAccount/' + accountDetails[0].id);
                            $('#editAccModal').modal('show');
                        })
                    });
                }
                const dtAssets = $('#dtAssets').DataTable();
                EditAccount(dtAssets);

                const dtLiabilities = $('#dtLiabilities').DataTable();
                EditAccount(dtLiabilities);

                const dtEquities = $('#dtEquities').DataTable();
                EditAccount(dtEquities);

                const dtRevenues = $('#dtRevenues').DataTable();
                EditAccount(dtRevenues);

                const dtExpenses = $('#dtExpenses').DataTable();
                EditAccount(dtExpenses);





                // $('.editAccount').on('click', function (){
                //     $('#editAccModal').modal('show');
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Charts of Accounts</h3>

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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo e(route('accounts/store')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="form-row ">
                            <div class="col-sm-3">
                                <label>Account Group</label>
                                <select class="form-control selectpicker" id="ddAccountGroup">
                                    <option value="10">Assets</option>
                                    <option value="20">Liabilities</option>
                                    <option value="30">Equities</option>
                                    <option value="40">Revenues</option>
                                    <option value="50">Expenses</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>Parent Account</label>
                                <select class="form-control selectpicker" id="ddParentAccount" name="parent_account_id">
                                    <?php $__currentLoopData = $assetsAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assetsAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($assetsAccount->id); ?>"><?php echo e($assetsAccount->title); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <span class="float-right">
								<a href="" class="fs--1" data-toggle="modal" data-target="#parentAccModal">Add New</a>
							</span>
                            </div>

                            <div class="col-sm-4">
                                <label>Controlling Account</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                            <div class="col-sm-2 my-auto text-center">
                                <button type="submit" class="btn btn-outline-success" id="btnAdd">Add Account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts Details Tables-->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tabs nav -->
                    <div class="nav nav-pills nav-fill" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link mx-3 p-3 shadow active" id="v-pills-home-tab" data-toggle="pill"
                           href="#tbAssets" role="tab" aria-controls="v-pills-home" aria-selected="true">
                            <i class="fas fa-dollar-sign fa-lg mr-2"></i>
                            <span class="font-weight-bold text-uppercase">Assets</span></a>

                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-profile-tab" data-toggle="pill"
                           href="#tbLiabilities" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                            <i class="fab fa-amazon-pay fa-lg mr-2"></i>
                            <span class="font-weight-bold text-uppercase">Liabilities</span></a>

                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-messages-tab" data-toggle="pill"
                           href="#tbEquities" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                            <i class="fas fa-crown fa-lg mr-2"></i>
                            <span class="font-weight-bold text-uppercase">Equities</span></a>

                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill"
                           href="#tbRevenues" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="fas fa-file-invoice-dollar fa-lg mr-2"></i>
                            <span class="font-weight-bold text-uppercase">Revenues</span></a>

                        <a class="nav-link mx-3 p-3 shadow" id="v-pills-settings-tab" data-toggle="pill"
                           href="#tbExpenses" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="fab fa-buromobelexperte fa-lg mr-2"></i>
                            <span class="font-weight-bold text-uppercase">Expenses</span></a>
                    </div>

                    <!-- Tabs content -->
                    <div class="tab-content mx-3 my-3" id="v-pills-tabContent">


                        <div class="tab-pane fade shadow rounded bg-white show active p-3" id="tbAssets"
                             role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="table-responsive fs--1">
                                <table id="dtAssets" class="table table-striped border-bottom">
                                    <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="border-0">Code</th>
                                        <th class="border-0 ">Parent Account</th>
                                        <th class="border-0 ">Controlling Account</th>
                                        <th class="border-0 ">Status</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $controllingAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controllingAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($controllingAccount->parent_accounts->account_group == 10): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <?php echo e($controllingAccount->code); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <?php echo e($controllingAccount->parent_accounts->title); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <h6><?php echo e($controllingAccount->title); ?></h6>
                                                </td>
                                                <td class="align-middle ">
                                                    <?php if($controllingAccount->status == 1 ): ?>
                                                        <span class="badge rounded-capsule badge-soft-success">Active
                                                            <span class="ml-1 fas fa-check"></span>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                            <span class="ml-1 fas fa-times"></span>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle text-center cursor-pointer">
					                            <span class="badge rounded-capsule badge-soft-success editAccount">Edit
					                    	        <span class="ml-1 fas fa-edit fa-lg"></span>
					                  	        </span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6">No Record Found..!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot class="bg-200">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th id="assTotal"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane fade shadow rounded bg-white show p-3" id="tbLiabilities"
                             role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="table-responsive fs--1">
                                <table id="dtLiabilities" class="table table-striped border-bottom">
                                    <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="border-0">Code</th>
                                        <th class="border-0 ">Parent Account</th>
                                        <th class="border-0 ">Controlling Account</th>
                                        <th class="border-0 ">Status</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $controllingAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controllingAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($controllingAccount->parent_accounts->account_group == 20): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <?php echo e($controllingAccount->code); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <?php echo e($controllingAccount->parent_accounts->title); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <h6><?php echo e($controllingAccount->title); ?></h6>
                                                </td>
                                                <td class="align-middle ">
                                                    <?php if($controllingAccount->status == 1 ): ?>
                                                        <span class="badge rounded-capsule badge-soft-success">Active
                                                <span class="ml-1 fas fa-check"></span>
                                            </span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                <span class="ml-1 fas fa-times"></span>
                                            </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle text-center cursor-pointer">
					                            <span class="badge rounded-capsule badge-soft-success editAccount">Edit
					                    	        <span class="ml-1 fas fa-edit fa-lg"></span>
					                  	        </span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6">No Record Found..!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot class="bg-200">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th id="assTotal"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane fade shadow rounded bg-white show p-3" id="tbEquities"
                             role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="table-responsive fs--1">
                                <table id="dtEquities" class="table table-striped border-bottom">
                                    <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="border-0">Code</th>
                                        <th class="border-0 ">Parent Account</th>
                                        <th class="border-0 ">Controlling Account</th>
                                        <th class="border-0 ">Status</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $controllingAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controllingAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($controllingAccount->parent_accounts->account_group == 30): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <?php echo e($controllingAccount->code); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <?php echo e($controllingAccount->parent_accounts->title); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <h6><?php echo e($controllingAccount->title); ?></h6>
                                                </td>
                                                <td class="align-middle ">
                                                    <?php if($controllingAccount->status == 1 ): ?>
                                                        <span class="badge rounded-capsule badge-soft-success">Active
                                                <span class="ml-1 fas fa-check"></span>
                                            </span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                <span class="ml-1 fas fa-times"></span>
                                            </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle text-center cursor-pointer">
					                            <span class="badge rounded-capsule badge-soft-success editAccount">Edit
					                    	        <span class="ml-1 fas fa-edit fa-lg"></span>
					                  	        </span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6">No Record Found..!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot class="bg-200">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th id="assTotal"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane fade shadow rounded bg-white show p-3" id="tbRevenues"
                             role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="table-responsive fs--1">
                                <table id="dtRevenues" class="table table-striped border-bottom">
                                    <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="border-0">Code</th>
                                        <th class="border-0 ">Parent Account</th>
                                        <th class="border-0 ">Controlling Account</th>
                                        <th class="border-0 ">Status</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $controllingAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controllingAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($controllingAccount->parent_accounts->account_group == 40): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <?php echo e($controllingAccount->code); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <?php echo e($controllingAccount->parent_accounts->title); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <h6><?php echo e($controllingAccount->title); ?></h6>
                                                </td>
                                                <td class="align-middle ">
                                                    <?php if($controllingAccount->status == 1 ): ?>
                                                        <span class="badge rounded-capsule badge-soft-success">Active
                                                <span class="ml-1 fas fa-check"></span>
                                            </span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                <span class="ml-1 fas fa-times"></span>
                                            </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle text-center cursor-pointer">
					                            <span class="badge rounded-capsule badge-soft-success editAccount">Edit
					                    	        <span class="ml-1 fas fa-edit fa-lg"></span>
					                  	        </span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6">No Record Found..!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot class="bg-200">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th id="assTotal"></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane fade shadow rounded bg-white show p-3" id="tbExpenses"
                             role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="table-responsive fs--1">
                                <table id="dtExpenses" class="table table-striped border-bottom">
                                    <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="border-0">Code</th>
                                        <th class="border-0 ">Parent Account</th>
                                        <th class="border-0 ">Controlling Account</th>
                                        <th class="border-0 ">Status</th>
                                        <th class="border-0 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $controllingAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $controllingAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php if($controllingAccount->parent_accounts->account_group == 50): ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <?php echo e($controllingAccount->code); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <?php echo e($controllingAccount->parent_accounts->title); ?>

                                                </td>
                                                <td class="align-middle ">
                                                    <h6><?php echo e($controllingAccount->title); ?></h6>
                                                </td>
                                                <td class="align-middle ">
                                                    <?php if($controllingAccount->status == 1 ): ?>
                                                        <span class="badge rounded-capsule badge-soft-success">Active
                                                <span class="ml-1 fas fa-check"></span>
                                            </span>
                                                    <?php else: ?>
                                                        <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                <span class="ml-1 fas fa-times"></span>
                                            </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle text-center cursor-pointer">
					                            <span class="badge rounded-capsule badge-soft-success editAccount">Edit
					                    	        <span class="ml-1 fas fa-edit fa-lg"></span>
					                  	        </span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6">No Record Found..!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                    <tfoot class="bg-200">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th id="assTotal"></th>
                                        <th></th>
                                        <th></th>
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

    <!-- Parent Account Modal-->
    <div class="modal fade" id="parentAccModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Parent Account</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('addParentAccount')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="form-row ">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Account Group</label>
                                    <select class="form-control selectpicker" id="accGroup" name="account_group">
                                        <option value="10">Assets</option>
                                        <option value="20">Liabilities</option>
                                        <option value="30">Equities</option>
                                        <option value="40">Revenues</option>
                                        <option value="50">Expenses</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Parent Account</label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 my-auto text-center">
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-success ">Add Account</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>

    <!-- Edit Account Modal-->
    <div class="modal fade" id="editAccModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Account</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="frmEditAccount">
                        <?php echo csrf_field(); ?>
                        <div class="form-row ">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Parent Account</label>
                                    <select class="form-control selectpicker" id="ddEditAccount" name="parent_account">
                                        <?php if(isset($parentAccounts)): ?>
                                            <?php $__currentLoopData = $parentAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($parentAccount->id); ?>"><?php echo e($parentAccount->title); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Account Title</label>
                                    <input type="text" class="form-control" name="title" id="editAccountTitle">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="customRadioInline1">Status</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" id="accActive" type="radio"
                                               name="is_active" value="1">
                                        <label class="custom-control-label" for="accActive">Active</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" id="accInActive" type="radio"
                                               name="is_active" value="0">
                                        <label class="custom-control-label" for="accInActive">In-Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 my-auto text-center">
                            <div class="form-group">
                                <button type="submit" class="btn btn-outline-success ">Update Account</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/accounts/chartOfAccounts.blade.php ENDPATH**/ ?>
