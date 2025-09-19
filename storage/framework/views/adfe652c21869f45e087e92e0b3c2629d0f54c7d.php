<?php use Illuminate\Support\Arr;

$__env->startSection('title', 'Income Statment'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>
        <link href="<?php echo e(asset('lib/select2/select2.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/flatpickr/flatpickr.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('footer'); ?>
        <script src="<?php echo e(asset('lib/select2/select2.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/flatpickr/flatpickr.min.js')); ?>"></script>
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

                //Getting Fist Day of Month
                function getFirstDayOfMonth() {
                    return new Date(new Date().getFullYear(), new Date().getMonth(), 1);
                }

                //Getting Last Day of Month
                function getLastDayOfMonth() {
                    return new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);
                }

                //Initialize Transaction Date Picker
                $('#dpStartDate').flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    defaultDate: getFirstDayOfMonth(),
                });

                //Initialize Transaction Date Picker
                $('#dpEndDate').flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    defaultDate: getLastDayOfMonth(),
                });

                //Initialized Data Table and Set Values
                const dtIncomeStatement = $('#dtIncomeStatement').DataTable({
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 0, 1] },
                        { "bSearchable": false, "aTargets": [ 0, 1] }
                    ]
                });
                new $.fn.dataTable.Buttons(dtIncomeStatement, {
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
                        // {
                        //     extend: 'pdf',
                        //     className: 'btn btn-falcon-danger btn-sm mr-1',
                        //     text: '<span class="far fa-file-pdf fa-lg"></span> Export Pdf',
                        //     title: 'Income Statement',
                        //     footer: true
                        // },
                        // {
                        //     extend: 'print',
                        //     className: 'btn btn-falcon-info btn-sm',
                        //     text: '<span class="fas fa-print fa-lg"></span> Print',
                        //     title: 'Income Statement',
                        //     footer: true,
                        //     customize: function ( win ) {
                        //         $(win.document.body)
                        //             .css( 'font-size', '11pt' )
                        //         $(win.document.body).find( 'table' )
                        //             .addClass( 'compact' )
                        //             .css( 'font-size', 'inherit' );
                        //     }
                        // },
                    ]
                });
                dtIncomeStatement.buttons().container().appendTo('#dtIncomeStatementActions');

                $('#btnPrint').on('click', function (){
                    $('#dtIncomeStatement').printThis({
                        debug: false,               // show the iframe for debugging
                        importCSS: true,            // import parent page css
                        importStyle: true,         // import style tags
                        printContainer: true,       // print outer container/$.selector
                        loadCSS: "",                // path to additional css file - use an array [] for multiple
                        pageTitle: "",              // add title to print page
                        removeInline: false,        // remove inline styles from print elements
                        removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
                        printDelay: 150,            // variable print delay
                        header: '<h5 class="fs-2 py-2 py-xl-0 font-weight-bold text-center">' +
                            'Income Statement' +
                            '<span class="fs--1 d-block mt-1">' +
                            'For the Period <?php if(isset($totalAndPeriod)): ?><?php echo e($totalAndPeriod['from_date'].' ~ '.$totalAndPeriod['to_date']); ?><?php endif; ?>'+
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-success">Income Statement</h3>

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
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('accounts/report/incomeStatement')); ?>" method="POST" id="frmCrEntry">
                        <?php echo csrf_field(); ?>
                        <div class="form-row">
                            <div class="col-sm-12 mb-3">
                                <label for="dpStartDate">From Date</label>
                                <input class="form-control datetimepicker flatpickr-input fs-0" id="dpStartDate"
                                       type="text" name="from_date">
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="dpEndDate">To Date</label>
                                <input class="form-control datetimepicker flatpickr-input fs-0" id="dpEndDate"
                                       type="text"
                                       name="to_date">
                            </div>
                            <div class="col-md-12 d-flex justify-content-center">
                                <button type="submit" class="btn btn-falcon-primary" href="#">
                                    <span class="fas fa-search mr-1" data-fa-transform="shrink-3"></span>
                                    View
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header border-bottom bg-dark">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-sm-auto d-flex align-items-center pr-0">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Income Statement
                                <span class="text-light fs--1 d-block mt-1">For The Period
                                    <?php if(isset($totalAndPeriod)): ?>
                                        <?php echo e($totalAndPeriod['from_date'].' ~ '.$totalAndPeriod['to_date']); ?>

                                    <?php endif; ?>
                                </span>
                            </h5>
                        </div>
                        <div class="col-6 col-sm-auto ml-auto text-right pl-0">
                            <div id="dtIncomeStatementActions" class="d-inline-flex">

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
                        <table id="dtIncomeStatement" class="table table-sm border"
                               data-paging="false"
                               data-searching="false"
                               data-ordering="false">
                            <thead>
                                <tr>
                                    <th class="text-left font-weight-bold pl-5">Account Title</th>
                                    <th class="text-right font-weight-bold pr-5 ml-auto">Amount (Rs.)</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php if(isset($accounts)): ?>

                                <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($account->account_group == 40): ?>
                                        <tr>
                                            <td class="text-left font-weight-bold"><?php echo e($account->title); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php $__currentLoopData = $account->account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cntrlAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-left pl-5"><?php echo e($cntrlAccount->title); ?></td>
                                                <td class="text-right pr-5 ml-auto"><?php echo e(number_format(abs($cntrlAccount->balance))); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="2">No Record Found in Given Dates</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-right font-weight-bold bg-soft-success">
                                        Total Sales <i class="fas fa-long-arrow-alt-right fs-2 pt-2"></i>
                                    </td>
                                    <td class="text-right font-weight-bold pr-5 ml-auto bg-soft-success">Rs. <?php echo e(number_format(abs($totalAndPeriod['sales']))); ?></td>
                                </tr>

                                <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($account->account_group == 50): ?>
                                        <tr>
                                            <td class="text-left font-weight-bold"><?php echo e($account->title); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php $__currentLoopData = $account->account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cntrlAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-left pl-5"><?php echo e($cntrlAccount->title); ?></td>
                                                <td class="text-right pr-5"><?php echo e(number_format(abs($cntrlAccount->balance))); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="2">No Record Found in Given Dates</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-right font-weight-bold bg-soft-warning">
                                        Total Expenses <i class="fas fa-long-arrow-alt-right fs-2 pt-2"></i>
                                    </td>
                                    <td class="text-right font-weight-bold pr-5 bg-soft-warning">Rs. <?php echo e(number_format(abs($totalAndPeriod['expenses']))); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right font-weight-bold">
                                        Net Income/Loss <i class="fas fa-long-arrow-alt-right fs-2 pt-2"></i>
                                    </td>
                                    <td class="text-right font-weight-bold pr-5">Rs. <?php echo e(number_format(abs($totalAndPeriod['sales']) - abs($totalAndPeriod['expenses']))); ?>

                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/reports/income-statement.blade.php ENDPATH**/ ?>
