<?php use Illuminate\Support\Arr;

$__env->startSection('content'); ?>
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

                //Transactions DataTable
                const dtTrns = $('#dtTrns').DataTable();
                new $.fn.dataTable.Buttons(dtTrns, {
                    buttons: [
                        {
                            extend: 'copy',
                            className: 'btn btn-falcon-default btn-sm mr-1',
                            text: '<span class="far fa-copy fa-lg"></span> Copy'

                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-falcon-success btn-sm mr-1',
                            text: '<span class="far fa-file-excel fa-lg"></span> Export Excel'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-falcon-danger btn-sm mr-1',
                            text: '<span class="far fa-file-pdf fa-lg"></span> Export Pdf',
                            title: 'Transactions Summary',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
                            },
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-falcon-info btn-sm',
                            text: '<span class="fas fa-print fa-lg"></span> Print',
                            title: 'Transactions Summary',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6]
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
                dtTrns.buttons().container().appendTo('#dtTransActions');

                //Display Individual Transaction
                dtTrns.on('click', '.showTrn', function (e) {
                    e.preventDefault();

                    var $trnId = $(this).attr('data-id');
                    let url = "<?php echo e(route('getTrnDetails', ':trnId')); ?>";
                    url = url.replace(':trnId', $trnId);

                    $.get(url, function (trnDetails) {
                        $trnType = trnDetails[0].trn_type;
                        var trnHtml;
                        switch ($trnType) {
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
                        for (let i = 0; i < trnDetails.length; i++) {
                            dr = trnDetails[i].dr_amount == '0' ? dr = '-' : thousandSeperator(trnDetails[i].dr_amount);
                            cr = trnDetails[i].cr_amount == '0' ? cr = '-' : thousandSeperator(trnDetails[i].cr_amount);

                            $('#tblShowVoucher tbody').append(
                                '<tr><td class="text-center">' + trnDetails[i].code + '</td>' +
                                '<td>' + trnDetails[i].account + '</td>' +
                                '<td class="text-word-break">' + trnDetails[i].particular + '</td>' +
                                '<td class="text-center drTrnsAmount">' + dr + '</td>' +
                                '<td class="text-center crTrnsAmount">' + cr + '</td></tr>'
                            );
                        }
                        $('#showVoucherModal').modal('show');
                        setTimeout(function () {
                            calculateRowSum();
                        }, 100)
                    })
                });

                // //Display Total Sum of Accounts
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

                //Bulk Selection
                $("#checkAllIds").click(function () {
                    $('input:checkbox').not(this).prop('checked', this.checked);
                });

                $('#btnPrint').on('click', function () {
                    $('#tblPrint').printThis({
                        debug: false,               // show the iframe for debugging
                        importCSS: true,            // import parent page css
                        importStyle: true,         // import style tags
                        printContainer: true,       // print outer container/$.selector
                        loadCSS: "",                // path to additional css file - use an array [] for multiple
                        pageTitle: "",              // add title to print page
                        removeInline: false,        // remove inline styles from print elements
                        removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
                        printDelay: 300,            // variable print delay
                        header: null,               // prefix to html
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


    <div class="card mb-3">
        <div class="bg-holder d-none d-lg-block bg-card"
             style="background-image:url('<?php echo e(asset('media/illustrations/corner-4.png')); ?>');"></div>
        <!--/.bg-holder-->
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <h3 class="mb-0 font-weight-bold text-2xl text-warning">Dashboard</h3>
                    <nav aria-label="breadcrumb">
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
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <form action="<?php echo e(route('updateBulkTrnStatus')); ?>" method="POST" id="bulkUpdate"><?php echo csrf_field(); ?></form>

                <div class="card-header border-bottom bg-dark">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-3 col-sm-auto d-flex align-items-center pr-0">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">Recent
                                Transactions</h5>
                        </div>
                        <div class="col-6 col-sm-auto text-center pl-0">
                            <div id="dtTransActions">

                            </div>
                        </div>
                        <div class="col-3 col-sm-auto d-flex align-items-right pr-3">
                            <select class="custom-select" aria-label="Bulk actions"
                                    form="bulkUpdate"
                                    name="bulk_status">
                                <option selected="">Bulk actions</option>
                                <option value='1'>Approved</option>
                                <option value='0'>Rejected</option>
                            </select>
                            <input class="btn btn-falcon-default btn-sm ml-2" type="submit" value="Apply"
                                   form="bulkUpdate">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive fs--1">
                        <table id="dtTrns" class="table table-striped table-hover border-bottom">
                            <thead class="bg-200 text-900">
                            <tr>
                                <th class="align-middle white-space-nowrap no-sort">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input checkbox-bulk-select"
                                               id="checkAllIds" type="checkbox">
                                        <label class="custom-control-label" for="checkAllIds"></label>
                                    </div>
                                </th>
                                <th class="border-0 text-center">Trn No.</th>
                                <th class="border-0 text-center">Trn Date</th>
                                <th class="border-0 text-center">Trn Type</th>
                                <th class="border-0 ">Payee</th>
                                <th class="border-0 ">Messer</th>
                                <th class="border-0 ">Trn Amount</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center no-sort">Action
                            </tr>
                            </thead>
                            <tbody id="bulk-select-body">
                            <?php $__empty_1 = true; $__currentLoopData = $trns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="align-middle white-space-nowrap">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input checkbox-bulk-select-target"
                                                   type="checkbox"
                                                   form="bulkUpdate"
                                                   name="chk[<?php echo e($trn->id); ?>]"
                                                   value="<?php echo e($trn->id); ?>"
                                                   id="checkbox<?php echo e($loop->index); ?>">
                                            <label class="custom-control-label" for="checkbox<?php echo e($loop->index); ?>"></label>
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo e($trn->id); ?></td>
                                    <td class="text-center"><?php echo e(date('d-M-Y', strtotime($trn->trn_date))); ?></td>
                                    <td class="text-center"><?php echo e($trn->trn_type); ?></td>
                                    <td><?php echo e($trn->payee); ?></td>
                                    <td><?php echo e($trn->messer); ?></td>
                                    <td><?php echo e(number_format($trn->trn_amount)); ?></td>
                                    <td class="text-center">
                                        <?php switch( $trn->status ):
                                            case ('Pending'): ?>
                                            <span class="badge badge rounded-capsule badge-soft-warning fs--2">
                                                            <?php echo e($trn->status); ?>

                                                            <span class="ml-1 fas fa-stream"></span>
                                                        </span>
                                            <?php break; ?>

                                            <?php case ('Approved'): ?>
                                            <span class="badge badge rounded-capsule badge-soft-success fs--2">
                                                            <?php echo e($trn->status); ?>

                                                            <span class="ml-1 fas fa-check"></span>
                                                        </span>
                                            <?php break; ?>

                                            <?php case ('Rejected'): ?>
                                            <span class="badge badge rounded-capsule badge-danger fs--2">
                                                            <?php echo e($trn->status); ?>

                                                            <span class="ml-1 fas fa-ban"></span>
                                                        </span>
                                            <?php break; ?>
                                        <?php endswitch; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown text-sans-serif position-static">
                                            <button class="btn btn-link dropdown-toggle btn-reveal"
                                                    type="button" id="dropdown5" data-toggle="dropdown"
                                                    data-boundary="html" aria-haspopup="true" aria-expanded="false">
                                                <span class="fas fa-ellipsis-h fs-2 py-1 px-1 border rounded border-400 bg-soft-info"></span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right border py-0"
                                                 aria-labelledby="dropdown5" style="">
                                                <div class="bg-white py-2">
                                                    <a class="dropdown-item showTrn" href="" data-id="<?php echo e($trn->id); ?>">
                                                        View Transaction
                                                    </a>







                                                    <div class="dropdown-divider"></div>
                                                    <form action="<?php echo e(route('updateTrnStatus', $trn->id)); ?>"
                                                          method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <input type="hidden" value="1" name="status">
                                                        <button type="submit"
                                                                class="dropdown-item text-success updateTrn" href="">
                                                            Approved
                                                        </button>
                                                    </form>
                                                    <form action="<?php echo e(route('updateTrnStatus', $trn->id)); ?>"
                                                          method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PUT'); ?>
                                                        <input type="hidden" value="0" name="status">
                                                        <button class="dropdown-item text-danger updateTrn" href="">
                                                            Rejected
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7">No Record Found..!</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
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
                    <h5 class="modal-title font-weight-bold text-2xl text-white" id="exampleModalLabel">View
                        Transaction</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light text-white" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="tblPrint">
                    <form method="post">
                        <?php echo csrf_field(); ?>
                        <div class="card mb-1">
                            <div class="card-body py-0">
                                <div class="text-center mb-2" id="showTrnsType">

                                </div>
                                    <div class="d-flex bg-200">
                                        <div class="py-2 pl-2 pr-5 font-weight-semi-bold font-italic">Payee:</div>
                                        <div class="p-2 mr-auto" id="trnPayee"><!-- Display Payee --></div>
                                        <div class="py-2 pl-2 pr-3 font-weight-semi-bold font-italic">Trans. Date:</div>
                                        <div class="p-2" id="trnDate"><!-- Display Date --></div>
                                    </div>
                                    <div class="dropdown-divider m-1"></div>
                                    <div class="d-flex bg-200 mb-1">
                                        <div class="py-2 pl-2 pr-5 font-weight-semi-bold font-italic">Messer:</div>
                                        <div class="p-2 mr-auto" id="trnMesser"> <!-- Display Messer --> </div>
                                        <div class="py-2 pl-2 pr-5 font-weight-semi-bold font-italic">Ref. No.</div>
                                        <div class="p-2" id="trnNo"><!-- Display Vch No. --></div>
                                    </div>
                                    <div class="dropdown-divider m-0"></div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body py-1">
                                <div class="table-responsive fs--1">
                                    <table class="table table-sm table-striped table-bordered" id="tblShowVoucher">
                                        <thead class="bg-200 text-900">
                                        <tr>
                                            <th class="border-0 text-center">A/C Code</th>
                                            <th class="border-0">Account Title</th>
                                            <th class="border-0">Particular</th>
                                            <th class="border-0 text-center">Debit</th>
                                            <th class="border-0 text-center">Credit</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">Total Amount</th>
                                        <th id="trnsDr" class="text-center"></th>
                                        <th id="trnsCr" class="text-center"></th>
                                        </tfoot>
                                    </table>
                                    <div class="d-flex mt-5">
                                        <div class="col-auto mr-auto text-left font-weight-semi-bold inline ">
                                            Accountant-----------------------------------------------
                                        </div>
                                        <div class="col-auto text-left font-weight-semi-bold">
                                            Received By----------------------------------------------
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-falcon-default mt-3" type="button" id="btnPrint">
                        <span class="fas fa-print fa-lg"></span>
                        Print Voucher
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/accounts/dashboard.blade.php ENDPATH**/ ?>
