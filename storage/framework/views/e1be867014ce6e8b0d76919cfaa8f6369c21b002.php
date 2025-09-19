<?php use Illuminate\Support\Arr;

$__env->startSection('title', 'General Ledger'); ?>

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
                $('#ddAccounts').select2({
                    theme: 'bootstrap4',
                    selectOnClose: true,
                    placeholder: 'Select Account'
                });

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

                //Initalized Data Table and Set Values
                const dtTrns = $('#dtTrns').DataTable();
                new $.fn.dataTable.Buttons(dtTrns, {
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
                            extend: 'pdf',
                            className: 'btn btn-falcon-danger btn-sm mr-1',
                            text: '<span class="far fa-file-pdf fa-lg"></span> Export Pdf',
                            title: 'General Ledger - <?php echo e(isset($trns[0]->title) ? $trns[0]->title : ""); ?>',
                            footer: true
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-falcon-info btn-sm',
                            text: '<span class="fas fa-print fa-lg"></span> Print',
                            title: 'General Ledger - <?php echo e(isset($trns[0]->title) ? $trns[0]->title : ""); ?>',
                            footer: true,
                            customize: function ( win ) {
                                $(win.document.body)
                                    .css( 'font-size', '11pt' )
                                $(win.document.body).find( 'table' )
                                    .addClass( 'compact' )
                                    .css( 'font-size', 'inherit' );
                            }
                        },
                    ]
                });
                dtTrns.buttons().container().appendTo('#dtTrnsActions');

                //Table Footer Total
                let drTotal = dtTrns.column(4).data().sum();
                let crTotal = dtTrns.column(5).data().sum();

                $('#drTotal').text(drTotal).formatNumber();
                $('#crTotal').text(crTotal).formatNumber();

                //Display in Widgets Cards
                let ob = <?php echo e(isset($openingBal['ob']) ? $openingBal['ob'] : 0); ?>;
                let balance = (ob + drTotal) - crTotal;

                $('#wdTotalDebit').text('Rs. ' + thousandSeperator(drTotal));
                $('#wdTotalCredit').text('Rs. ' + thousandSeperator(crTotal));
                $('#wdBalance').text('Rs. ' + thousandSeperator(balance) + (balance > 0 ? ' Dr' : ' Cr'));

                //Display Self Balancing Column in DataTable
                let data = dtTrns.rows().data();
                let dr = 0, cr = 0, opBal = 0;
                let isOpBal = 0, isDr = 0, isCr = 0;

                data.each(function (value, index) {
                    opBal = dtTrns.cell( index, 6).data().replace(/,/g, '');

                    if (index != 0) {
                        isOpBal = dtTrns.cell(index - 1, 6).data().replace(/\s+/g, "").replace(/,/g, '');
                        isDr = dtTrns.cell(index, 4).data().replace(/\s+/g, "").replace(/,/g, '');
                        isCr = dtTrns.cell(index, 5).data().replace(/\s+/g, "").replace(/,/g, '');

                        opBal = isOpBal == '' ? 0 : parseFloat(isOpBal);
                        dr = isDr == '' ? 0 : parseFloat(isDr);
                        cr = isCr == '' ? 0 : parseFloat(isCr);

                        dtTrns.cell(index, 6).data(thousandSeperator((opBal + dr) - cr));
                    }
                });

                //Display Individual Transaction
                dtTrns.on('click', '.showTrn', function (e) {
                    e.preventDefault();

                    var $trnId = $(this).attr('data-id');
                    let url = "<?php echo e(route('getTrnDetails', ':trnId')); ?>";
                    url = url.replace(':trnId', $trnId);

                    $.get(url, function (trnDetails)
                    {
                        $trnType = trnDetails[0].trn_type;
                        var trnHtml;
                        switch ($trnType)
                        {
                            case "DR":
                                trnHtml = '' +
                                    '<span class="badge badge-soft-danger fs-1 pl-5 pr-4">' +
                                        'Debit Voucher' +
                                    '</span>';
                                break;
                            case "CR":
                                trnHtml = '' +
                                    '<span class="badge badge-soft-success fs-1 pl-5 pr-4">' +
                                        'Credit Voucher' +
                                    '</span>';
                                break;
                            case "JV":
                                trnHtml = '' +
                                    '<span class="badge badge-soft-warning fs-1 pl-5 pr-4">' +
                                        'Journal Voucher' +
                                    '</span>';
                                break;
                        }

                        $('#showTrnsType').html(trnHtml);
                        $('#trnPayee').text(trnDetails[0].payee);
                        $('#trnMesser').text(trnDetails[0].messer);
                        $('#trnDate').text(Date.parse(trnDetails[0].trn_date).toString("ddd, dd-MMM-yyyy"));
                        $('#trnNo').text(trnDetails[0].id);

                        var dr = 0, cr = 0;

                        $('#tblShowVoucher tbody').empty();
                        for (let i = 0; i < trnDetails.length; i++)
                        {
                            dr = trnDetails[i].dr_amount == '0' ? dr = '-' : thousandSeperator(trnDetails[i].dr_amount);
                            cr = trnDetails[i].cr_amount == '0' ? cr = '-' : thousandSeperator(trnDetails[i].cr_amount);

                            $('#tblShowVoucher tbody').append(
                                '<tr><td class="text-center">'+trnDetails[i].code+'</td>' +
                                '<td>'+trnDetails[i].account+'</td>' +
                                '<td class="text-word-break">'+trnDetails[i].particular+'</td>' +
                                '<td class="text-center drTrnsAmount">'+dr+'</td>' +
                                '<td class="text-center crTrnsAmount">'+cr+'</td></tr>'
                            );
                        }
                        $('#showVoucherModal').modal('show');
                        setTimeout(function(){
                            calculateRowSum();
                        },100)

                    })
                });

                //Display Total Sum of Accounts
                function calculateRowSum() {
                    var drSum = 0, crSum = 0;
                    var dr = 0, cr = 0;
                    //Debit Total
                    $('.drTrnsAmount').each(function () {
                        dr = $(this).text().replace(/\s+/g, "").replace(/,/g, '');
                        drSum += parseFloat(dr == '-' ? dr = 0 : dr);
                    });
                    $('#trnsDr').text(drSum).formatNumber();

                    //Credit Total
                    $('.crTrnsAmount').each(function () {
                        cr = $(this).text().replace(/\s+/g, "").replace(/,/g, '');
                        crSum += parseFloat(cr == '-' ? cr = 0 : cr);
                    });
                    $('#trnsCr').text(crSum).formatNumber();
                }
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-success">Ledger Report</h3>

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
            <div class="card-deck">
                <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                         style="background-image:url('<?php echo e(asset('media/illustrations/corner-3.png')); ?>');"></div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>
                            <?php if(isset($trns)): ?>
                                <?php if($trns->isNotEmpty()): ?>
                                    <?php echo e($trns[0]->code); ?>

                                <?php endif; ?>
                            <?php endif; ?>
                        </h6>
                        <div class="display-4 fs-2 mb-2 font-weight-bold text-warning">
                            <?php if(isset($trns)): ?>
                                <?php if($trns->isNotEmpty()): ?>
                                    <?php echo e($trns[0]->title); ?>

                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                         style="background-image:url('<?php echo e(asset('media/illustrations/corner-1.png')); ?>');"></div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Total Debit</h6>
                        <div class="display-4 fs-2 mb-2 font-weight-semi-bold text-warning" id="wdTotalDebit">
                            58.39k
                        </div>
                    </div>
                </div>
                <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                         style="background-image:url('<?php echo e(asset('media/illustrations/corner-2.png')); ?>');"></div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Total Credit</h6>
                        <div class="display-4 fs-2 mb-2 font-weight-semi-bold text-warning" id="wdTotalCredit">
                            58.39k
                        </div>
                    </div>
                </div>
                <div class="card mb-3 overflow-hidden" style="min-width: 12rem">
                    <div class="bg-holder bg-card"
                         style="background-image:url('<?php echo e(asset('media/illustrations/corner-3.png')); ?>');"></div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <h6>Closing Balance</h6>
                        <div class="display-4 fs-2 mb-2 font-weight-semi-bold text-warning" id="wdBalance">
                            58.39k
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('accounts/showLedgerReport')); ?>" method="POST" id="frmCrEntry">
                        <?php echo csrf_field(); ?>
                        <div class="form-row">
                            <div class="col-sm-12 mb-3">
                                <label>Select Account</label>
                                <select class="form-control selectpicker" id="ddAccounts" name="account" autofocus>
                                    <option value="">--Select Account--</option>
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($account->id); ?>"><?php echo e($account->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="datepicker">Start Date</label>
                                <input class="form-control datetimepicker flatpickr-input fs-0" id="dpStartDate"
                                       type="text" name="from_date">
                            </div>
                            <div class="col-sm-12 mb-3">
                                <label for="datepicker">End Date</label>
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
                                General Ledger
                                <span class="text-light fs--1 d-block mt-1">For The Period
                                    <?php if(isset($openingBal)): ?>
                                        <?php echo e($openingBal['from_date'].' ~ '.$openingBal['to_date']); ?>

                                    <?php endif; ?>
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
                        <table id="dtTrns" class="table table-striped border">
                            <thead class="bg-200 text-900 align-middle">
                            <tr>
                                <th class="border-0 text-center align-middle" width="5%">S.No</th>
                                <th class="border-0 text-center align-middle" width="12%">Trn Date</th>
                                <th class="border-0 text-center align-middle" width="5%">P/R</th>
                                <th class="border-0 align-middle">Particular</th>
                                <th class="border-0 text-center align-middle" width="10%">Debit (DR)</th>
                                <th class="border-0 text-center align-middle" width="10%">Credit (CR)</th>
                                <th class="border-0 text-right align-middle" width="10%">Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($openingBal)): ?>
                                <tr>
                                    <td></td>
                                    <td class="text-center"><?php echo e($openingBal['from_date']); ?></td>
                                    <td>-</td>
                                    <td>Opening Balance</td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right pr-4"><?php echo e(number_format($openingBal['ob'])); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if(isset($trns)): ?>
                                <?php $__empty_1 = true; $__currentLoopData = $trns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($loop->index+1); ?></td>
                                        <td class="text-center"><?php echo e(date('d-M-Y', strtotime($trn->trn_date))); ?></td>
                                        <td class="text-center">
                                            <?php switch( $trn->status ):
                                                case ('Pending'): ?>
                                                <a href="#" class="showTrn" data-id="<?php echo e($trn->id); ?>">
                                                    <span class="badge badge rounded-capsule badge-soft-warning fs--2 pl-3 pr-3">
                                                    <?php echo e($trn->id); ?>

                                                        <span class="ml-1 fas fa-stream"></span>
                                                    </span>
                                                </a>
                                                <?php break; ?>

                                                <?php case ('Approved'): ?>
                                                <a href="#" class="showTrn" data-id="<?php echo e($trn->id); ?>">
                                                    <span class="badge badge rounded-capsule badge-soft-success fs--2 pl-3 pr-3">
                                                        <?php echo e($trn->id); ?>

                                                        <span class="ml-1 fas fa-check"></span>
                                                    </span>
                                                </a>
                                                <?php break; ?>

                                                <?php case ('Rejected'): ?>
                                                <a href="#" class="showTrn" data-id="<?php echo e($trn->id); ?>">
                                                    <span class="badge badge rounded-capsule badge-danger fs--2 pl-3 pr-3">
                                                        <?php echo e($trn->id); ?>

                                                        <span class="ml-1 fas fa-ban"></span>
                                                    </span>
                                                </a>
                                                <?php break; ?>
                                            <?php endswitch; ?>
                                        </td>
                                        <td><?php echo e($trn->particular); ?></td>
                                        <td class="text-center"><?php echo e(!empty($trn->dr_amount) ? number_format($trn->dr_amount) : ''); ?></td>
                                        <td class="text-center"><?php echo e(!empty($trn->cr_amount) ? number_format($trn->cr_amount) : ''); ?></td>
                                        <td class="text-right pr-4"></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot class="bg-200">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-right">Total Amount</th>
                                <th id="drTotal" class="text-center"></th>
                                <th id="crTotal" class="text-center"></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Transaction Modal-->
    <div class="modal fade" id="showVoucherModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gradient">
                    <h5 class="modal-title font-weight-bold text-2xl text-white" id="exampleModalLabel">View Transaction</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light text-white" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('addParentAccount')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="text-center mb-2" id="showTrnsType">

                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-2">
                                                <p class="font-weight-semi-bold mb-1">Payee: </p>
                                            </div>
                                            <div class="col" id="trnPayee"><!-- Display Payee --></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p class="font-weight-semi-bold mb-1">Messer: </p>
                                            </div>
                                            <div class="col" id="trnMesser"> <!-- Display Messer --> </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-5">
                                                <p class="font-weight-semi-bold mb-1">Trn Date:</p>
                                            </div>
                                            <div id="trnDate"><!-- Display Date --></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-5">
                                                <p class="font-weight-semi-bold mb-1">Vch. No.</p>
                                            </div>
                                            <div id="trnNo"><!-- Display Vch No. --></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive fs--1">
                                    <table class="table table-striped border" id="tblShowVoucher">
                                        <thead class="bg-200 text-900">
                                        <tr>
                                            <th class="border-0 text-center">A/C Code</th>
                                            <th class="border-0">Account Title</th>
                                            <th class="border-0">Particular</th>
                                            <th class="border-0">Debit</th>
                                            <th class="border-0">Credit</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot class="bg-200">
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">Total Amount</th>
                                            <th id="trnsDr" class="text-center"></th>
                                            <th id="trnsCr" class="text-center"></th>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-6" id="showStatus"></div>
                        <div class="col-sm-6" id="showPrintBtn"></div>
                    </div>
                </div>


            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/reports/ledger-report.blade.php ENDPATH**/ ?>
