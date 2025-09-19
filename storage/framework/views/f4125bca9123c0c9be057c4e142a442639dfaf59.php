<?php $__env->startSection('title', 'Trial Balance'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>


        <link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('footer'); ?>


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
        <script src="<?php echo e(asset('js/printThis.js')); ?>"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                //Initalized Data Table and Set Values
                const dtTrialBalance = $('#dtTrialBalance').DataTable({
                    "pageLength": 50
                });
                new $.fn.dataTable.Buttons(dtTrialBalance, {
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
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                    ]
                });
                dtTrialBalance.buttons().container().appendTo('#dtTrialBalanceActions');

                //Table Footer Total
                let drTotal = dtTrialBalance.column(3).data().sum();
                let crTotal = dtTrialBalance.column(4).data().sum();

                $('#drTotal').text(drTotal).formatNumber();
                $('#crTotal').text(crTotal).formatNumber();
                $('#differ').text(drTotal-crTotal).formatNumber();
            });

            $('#btnPrint').on('click', function (){
                $('#dtTrialBalance').printThis({
                    debug: false,               // show the iframe for debugging
                    importCSS: true,            // import parent page css
                    importStyle: false,         // import style tags
                    printContainer: true,       // print outer container/$.selector
                    loadCSS: "",                // path to additional css file - use an array [] for multiple
                    pageTitle: "",              // add title to print page
                    removeInline: false,        // remove inline styles from print elements
                    removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
                    printDelay: 150,            // variable print delay
                    header: '<h5 class="fs-2 py-2 py-xl-0 font-weight-bold text-center">' +
                                'Trail Balance' +
                                '<span class="fs--1 d-block mt-1">' +
                                    'As On <?php echo e(date_format(today(), 'd-M-Y')); ?>'+
                                '</span>' +
                            '</h5>',               // prefix to html
                    footer: null,               // postfix to html
                    base: false,                // preserve the BASE tag or accept a string for the URL
                    formValues: true,           // preserve input/form values
                    canvas: false,              // copy canvas content
                    doctypeString: '...',       // enter a different doctype for older markup
                    removeScripts: false,       // remove script tags from print content
                    copyTagClasses: false,      // copy classes from the html & body tag
                    beforePrintEvent: null,     // function for printEvent in iframe
                    beforePrint: null,          // function called before iframe is filled
                    afterPrint: null            // function called before iframe is removed
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-success">Trial Balance</h3>

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
        <div class="col-md-12">
            <div class="card" id="printTbl">
                <div class="card-header border-bottom bg-dark">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-sm-auto d-flex align-items-center pr-0">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Trail Balance
                                <span class="text-light fs--1 d-block mt-1">As On <?php echo e(date_format(today(), 'd-M-Y')); ?></span>
                            </h5>
                        </div>
                        <div class="col-6 col-sm-auto ml-auto text-right pl-0">
                            <div id="dtTrialBalanceActions" class="d-inline-flex">
                                
                                <a id="btnPrint">
                                    <button class="btn btn-falcon-info btn-sm mr-2">
                                    <span class="fas fa-print fa-lg"></span> Print
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive fs--1">
                        <table id="dtTrialBalance" class="table table-striped border table-sm">
                            <thead class="bg-200 text-900">
                            <tr>
                                <th class="border-0 text-center align-middle" width="5%">S.No.</th>
                                <th class="border-0 text-center align-middle" width="15%">Account Code</th>
                                <th class="border-0">Controlling Account</th>
                                <th class="border-0 text-center align-middle" width="15%">Debit (DR)</th>
                                <th class="border-0 text-center align-middle" width="15%">Credit (CR)</th>
                                <th class="border-0 text-center align-right" width="15%">Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trialAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center align-middle"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-center align-middle"><?php echo e($trialAccount->code); ?></td>
                                    <td><?php echo e($trialAccount->title); ?></td>
                                    <td class="text-center align-middle"><?php echo e(number_format($trialAccount->dr_amount)); ?></td>
                                    <td class="text-center align-middle"><?php echo e(number_format($trialAccount->cr_amount)); ?></td>
                                    <td class="text-center align-middle pr-4"><?php echo e(number_format($trialAccount->balance)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="bg-200">
                            <tr>
                                <th></th>
                                <th></th>
                                <th class="text-right">Total Amount</th>
                                <th id="drTotal" class="text-center align-middle"></th>
                                <th id="crTotal" class="text-center align-middle"></th>
                                <th id="differ" class="text-center align-middle text-danger font-weight-bold fs-1"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/reports/trial-balance-report.blade.php ENDPATH**/ ?>