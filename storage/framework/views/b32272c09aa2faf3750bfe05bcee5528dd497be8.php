<?php $__env->startSection('title', 'Balance Sheet'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>

        <link href="<?php echo e(asset('lib/flatpickr/flatpickr.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('footer'); ?>

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
        <script src="<?php echo e(asset('js/moment.js')); ?>"></script>
        <script src="<?php echo e(asset('js/printThis.js')); ?>"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                //
                //Getting Fist Day of Month
                function getFirstDayOfMonth() {
                    return new Date(new Date().getFullYear(), new Date().getMonth(), 1);
                }

                //Getting Last Day of Month
                function getLastDayOfMonth() {
                    return new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);
                }

                //Initialize Transaction Date Picker
                // $('#dpStartDate').flatpickr({
                //     altInput: true,
                //     altFormat: "F j, Y",
                //     dateFormat: "Y-m-d",
                //     defaultDate: getFirstDayOfMonth(),
                // });

                //Initialize Transaction Date Picker
                $('#dpEndDate').flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    defaultDate: 'today',
                });

                //Initialize Ledger Transaction Date Picker
                $('#dpLedgerStartDate').flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    defaultDate: getFirstDayOfMonth(),
                });

                //Initialize Ledger Transaction Date Picker
                $('#dpLedgerEndDate').flatpickr({
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    defaultDate: getLastDayOfMonth(),
                });

                //Initialized Data Table and Set Values
                const dtBalanceSheet = $('#dtBalanceSheet').DataTable({
                    "aoColumnDefs": [
                        { "bSortable": false, "aTargets": [ 0, 1] },
                        { "bSearchable": false, "aTargets": [ 0, 1] }
                    ]
                });
                new $.fn.dataTable.Buttons(dtBalanceSheet, {
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
                dtBalanceSheet.buttons().container().appendTo('#dtBalanceSheetActions');

                //A Thousand Separator Function
                function thousandSeperator(nStr) {
                    nStr += '';
                    var x = nStr.split('.');
                    var x1 = x[0];
                    var x2 = x.length > 1 ? '.' + x[1] : '';
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1 + x2;
                }

                //Display Self Balancing Column in DataTable
                function SelfBalancingDataTableColumn() {

                    var dtTrns = $('#dtTrns').DataTable();

                    let data = dtTrns.rows().data();
                    let dr = 0, cr = 0, opBal = 0;
                    let isOpBal = 0, isDr = 0, isCr = 0;
                    let drSum = 0, crSum = 0;

                    data.each(function (value, index) {
                        opBal = dtTrns.cell(index, 6).data().replace(/,/g, '');

                        if (index != 0) {
                            isOpBal = dtTrns.cell(index - 1, 6).data().replace(/\s+/g, "").replace(/,/g, '');
                            isDr = dtTrns.cell(index, 4).data().replace(/\s+/g, "").replace(/,/g, '');
                            isCr = dtTrns.cell(index, 5).data().replace(/\s+/g, "").replace(/,/g, '');

                            opBal = isOpBal == '' ? 0 : parseFloat(isOpBal);
                            dr = isNaN(isDr) != true ? parseFloat(isDr) : 0;
                            cr = isNaN(isCr) != true ? parseFloat(isCr) : 0;

                            dtTrns.cell(index, 6).data(thousandSeperator((opBal + dr) - cr));

                            drSum += dr;
                            crSum += cr;
                        }
                    });

                    //Display Total
                    $('#drTotal').text(thousandSeperator(drSum));
                    $('#crTotal').text(thousandSeperator(crSum));
                    $('#balTotal').text(thousandSeperator(drSum-crSum));

                    dtTrns.destroy();
                }

                //Display Input Dates Modal
                var $accountId;
                var $accountTitle;
                dtBalanceSheet.on('click', '.showLedger', function (e) {
                    e.preventDefault();

                    $accountId = $(this).attr('data-id');
                    $accountTitle = $(this).attr('data-name');

                    $('#showAccountTitle').text($accountTitle);
                    $('#showLedgerDatesModal').modal('show');
                });

                //Display Ledger Transaction
                $('#showLedgerTrns').on('click', function (e) {

                    let url = '/accounts/showLedgerReport';

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        type: 'POST',
                        data: {
                            'account': $accountId,
                            'from_date': $('#dpLedgerStartDate').val(),
                            'to_date': $('#dpLedgerEndDate').val(),
                            'request_from': 'trialBalance'
                        },
                        success: function (response) {
                            var row;
                            //Opening Bal
                            row += '<tr class="d-flex">' +
                                '<td class="col-1 text-center">-</td>' +
                                '<td class="col-1 text-center">' + response.openingBal.from_date + '</td>' +
                                '<td class="col-1 text-center">-</td>' +
                                '<td class="col-6">Opening Balance</td>' +
                                '<td class="col-1 text-center">-</td>' +
                                '<td class="col-1 text-center">-</td>' +
                                '<td class="col-1 text-right pr-2">' +thousandSeperator(response.openingBal.ob)+ '</td>' +
                                '</tr>';

                            //Transactions
                            $.each(response.trns, function (index, value) {
                                var drTrns = value.dr_amount === 0 ? "-" : thousandSeperator(value.dr_amount);
                                var crTrns = value.cr_amount === 0 ? "-" : thousandSeperator(value.cr_amount);
                                row += '<tr class="d-flex">' +
                                    '<td class="col-1 text-center">' + (++index) + '</td>' +
                                    '<td class="col-1 text-center">' + moment(value.trn_date).format('DD-MMM-YYYY')  + '</td>' +
                                    '<td class="col-1 text-center">' + value.id + '</td>' +
                                    '<td class="col-6">' + value.particular + '</td>' +
                                    '<td class="col-1 text-center">' + drTrns + '</td>' +
                                    '<td class="col-1 text-center">' + crTrns + '</td>' +
                                    '<td class="col-1 text-right pr-2"></td>' +
                                    '</tr>';
                            })

                            var fromDate = moment($('#dpStartDate').val()).format('DD-MMM-YYYY');
                            var toDate = moment($('#dpEndDate').val()).format('DD-MMM-YYYY');

                            $('#dtTrns tbody').html(row);
                            $('#ledgerAccountTitle').text($accountTitle);
                            $('#trnsPeriod').text('For the Period: '+ fromDate+ ' ~ '+ toDate);

                            SelfBalancingDataTableColumn();
                            $('#showLedgerModal').modal('show');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });

                });

                $('#btnPrint').on('click', function (){
                    $('#dtBalanceSheet').printThis({
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
                            'Balance Sheet' +
                            '<span class="fs--1 d-block mt-1">' +
                            'As On <?php if(isset($totalAndPeriod)): ?><?php echo e($totalAndPeriod['to_date']); ?><?php endif; ?>'+
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-success">Balance Sheet</h3>

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
                    <form action="<?php echo e(route('accounts/report/balanceSheet')); ?>" method="POST" id="frmCrEntry">
                        <?php echo csrf_field(); ?>
                        <div class="form-row">





                            <div class="col-sm-12 mb-3">
                                <label for="dpEndDate">As On</label>
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
                        <div class="col-6 col-sm-auto d-flex align-items-center pr-3">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Balance Sheet
                                <span class="text-light fs--1 d-block mt-1">As On
                                    <?php if(isset($totalAndPeriod)): ?>
                                        <?php echo e($totalAndPeriod['to_date']); ?>

                                    <?php endif; ?>
                                </span>
                            </h5>
                        </div>
                        <div class="col-6 col-sm-auto ml-auto text-right pl-0">
                            <div id="dtBalanceSheetActions" class="d-inline-flex">
                                
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
                        <table id="dtBalanceSheet" class="table table-sm border"
                               data-paging="false"
                               data-searching="false"
                               data-ordering="false">
                            <thead>
                                <tr>
                                    <th class="text-left font-weight-bold pl-5 ">Account Title</th>
                                    <th class="text-right font-weight-bold pr-3 ml-auto">Amount (Rs.)</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php if(isset($accounts)): ?>
                                
                                <tr class="bg-gray-800">
                                    <td class="font-weight-bold fs-2 text-light">Assets</td>
                                    <td></td>
                                </tr>
                                <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($account->account_group == 10): ?>
                                        <tr>
                                            <td class="text-left font-weight-bold"><?php echo e($account->title); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php $__currentLoopData = $account->account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cntrlAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($cntrlAccount->balance != 0): ?>
                                                <tr>
                                                    <td class="text-left pl-5">
                                                        <a href="#" class="showLedger" data-id="<?php echo e($cntrlAccount->id); ?>" data-name="<?php echo e($cntrlAccount->title); ?>">
                                                            <?php echo e($cntrlAccount->title); ?>

                                                        </a>
                                                    </td>
                                                    <td class="text-right pr-3 ml-auto"><?php echo e(number_format($cntrlAccount->balance)); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-right font-weight-bold bg-soft-success">
                                        Total Assets <i class="fas fa-long-arrow-alt-right fs-2 pt-2"></i>
                                    </td>
                                    <td class="text-right font-weight-bold pr-3 ml-auto bg-soft-success">Rs. <?php echo e(number_format($totalAndPeriod['totalAssets'])); ?></td>
                                </tr>
                                
                                <tr class="bg-gray-800">
                                    <td class="font-weight-bold fs-2 text-light">Equities</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-left font-weight-bold text-underline">Liabilities</td>
                                    <td></td>
                                </tr>
                                <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($account->account_group == 20): ?>
                                        <tr>
                                            <td class="text-left font-weight-bold "><?php echo e($account->title); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php $__currentLoopData = $account->account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cntrlAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($cntrlAccount->balance != 0): ?>
                                                <tr>
                                                    <td class="text-left pl-5">
                                                        <a href="#" class="showLedger" data-id="<?php echo e($cntrlAccount->id); ?>" data-name="<?php echo e($cntrlAccount->title); ?>">
                                                            <?php echo e($cntrlAccount->title); ?>

                                                        </a>
                                                    </td>
                                                    <td class="text-right pr-3"><?php echo e(number_format($cntrlAccount->balance)); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td>No Record Found in Given Dates</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-right font-weight-bold bg-soft-warning">
                                        Total Liabilities <i class="fas fa-long-arrow-alt-right fs-2 pt-2"></i>
                                    </td>
                                    <td class="text-right font-weight-bold pr-3 bg-soft-warning">Rs. <?php echo e(number_format($totalAndPeriod['liabilities'])); ?></td>
                                </tr>
                                
                                <tr>
                                    <td class="text-left font-weight-bold text-underline">Owner Equities</td>
                                    <td></td>
                                </tr>
                                <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($account->account_group == 30): ?>
                                        <tr>
                                            <td class="text-left font-weight-bold "><?php echo e($account->title); ?></td>
                                            <td></td>
                                        </tr>
                                        <?php $__currentLoopData = $account->account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cntrlAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-left pl-5">
                                                    <a href="#" class="showLedger" data-id="<?php echo e($cntrlAccount->id); ?>" data-name="<?php echo e($cntrlAccount->title); ?>">
                                                        <?php echo e($cntrlAccount->title); ?>

                                                    </a>
                                                </td>
                                                <td class="text-right pr-3"><?php echo e(number_format(abs($cntrlAccount->balance))); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td>No Record Found in Given Dates</td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-left font-weight-bold">Net Profit/Loss</td>
                                    <td class="text-right pr-3"><?php echo e(number_format($totalAndPeriod['profitLoss'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right font-weight-bold bg-soft-warning">
                                        Total Owner Equities <i class="fas fa-long-arrow-alt-right fs-2 pt-2"></i>
                                    </td>
                                    <td class="text-right font-weight-bold pr-3 bg-soft-warning">Rs. <?php echo e(number_format(abs($totalAndPeriod['totalOwnerEquity']))); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right font-weight-bold bg-soft-success">
                                        Total Equities <i class="fas fa-long-arrow-alt-right fs-2 pt-2"></i>
                                    </td>
                                    <td class="text-right font-weight-bold pr-3 bg-soft-success">Rs. <?php echo e(number_format(abs($totalAndPeriod['totalEquities']))); ?></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show Ledger Dates Modal-->
    <div class="modal fade" id="showLedgerDatesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                
                
                
                
                
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header border-bottom bg-dark">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-12 col-sm-auto d-flex align-items-center pr-0">
                                        <span class="text-light fs--1 d-block mt-1">Account Title</span>
                                    </div>
                                </div>
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-12 col-sm-auto d-flex align-items-center pr-0">
                                        <h5 class="fs-1 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold d-block" id="showAccountTitle">
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <label for="datepicker">Start Date</label>
                                        <input class="form-control datetimepicker flatpickr-input fs-0" id="dpLedgerStartDate"
                                               type="text" name="from_date">
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label for="datepicker">End Date</label>
                                        <input class="form-control datetimepicker flatpickr-input fs-0" id="dpLedgerEndDate"
                                               type="text" name="to_date">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Close</button>
                                <button class="btn btn-primary btn-sm" id="showLedgerTrns" type="button">Show</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Show Ledger Modal-->
    <div class="modal fade" id="showLedgerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                
                
                
                
                
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header border-bottom bg-dark">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-6 col-sm-auto d-flex align-items-center pr-0">
                                        <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                            <span id="ledgerAccountTitle"></span>
                                            <span class="text-light fs--1 d-block mt-1" id="trnsPeriod">For The Period

                                            </span>
                                        </h5>
                                    </div>
                                    <div class="col-6 col-sm-auto ml-auto text-right pl-0">
                                        <div id="dtTrnsActions">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive fs--1">
                                    <table id="dtTrns" class="table table-sm table-striped border">
                                        <thead class="bg-200 text-900 align-middle">
                                        <tr class="d-flex">
                                            <th class="col-1 border-0 text-center align-middle">S.No</th>
                                            <th class="col-1 border-0 text-center align-middle">Trn Date</th>
                                            <th class="col-1 border-0 text-center align-middle">P/R</th>
                                            <th class="col-6 border-0 align-middle">Particular</th>
                                            <th class="col-1 border-0 text-center align-middle">Debit (DR)</th>
                                            <th class="col-1 border-0 text-center align-middle">Credit (CR)</th>
                                            <th class="col-1 border-0 text-right align-middle">Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot class="bg-200">
                                        <tr class="d-flex">
                                            <th class="col-1 border-0 text-center align-middle"></th>
                                            <th class="col-1 border-0 text-center align-middle"></th>
                                            <th class="col-1 border-0 text-center align-middle"></th>
                                            <th class="col-6 border-0 align-middle text-right">Total Amount</th>
                                            <th class="col-1 border-0 text-center align-middle" id="drTotal"></th>
                                            <th class="col-1 border-0 text-center align-middle" id="crTotal"></th>
                                            <th class="col-1 border-0 text-right align-middle" id="balTotal"></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6" id="showStatus"></div>
                    <div class="col-sm-6" id="showPrintBtn"></div>
                </div>
            </div>
            
            
            
            

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\OneDrive\Zeeshan Programming\ZarBooks\resources\views/reports/balance-sheet.blade.php ENDPATH**/ ?>