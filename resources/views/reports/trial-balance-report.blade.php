@extends('layout')
@section('title', 'Trial Balance')

@section('content')

    @push('head')
        {{--        <link href="{{asset('lib/select2/select2.min.css')}}" rel="stylesheet">--}}
                <link href="{{asset('lib/flatpickr/flatpickr.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')}}" rel="stylesheet">
    @endpush

    @push('footer')
        {{--        <script src="{{asset('lib/select2/select2.min.js')}}"></script>--}}
                <script src="{{asset('lib/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{asset('lib/datatables/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('lib/datatables.net-responsive-bs4/dataTables.responsive.js')}}"></script>
        <script src="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.js')}}"></script>
        <script src="{{ asset('lib/datatables/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/jszip.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/pdfmake.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/vfs_fonts.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/buttons.print.min.js') }}"></script>
        <script src="{{asset('lib/datatables/js/dataTableSum.js')}}"></script>
        <script src="{{asset('js/number-formatter.js')}}"></script>
        <script src="{{asset('lib/date-js/date.js')}}"></script>
        <script src="{{asset('js/moment.js')}}"></script>
        <script src="{{asset('js/printThis.js')}}"></script>

        <script type="text/javascript">
            $(document).ready(function () {

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
                const dtTrialBalance = $('#dtTrialBalance').DataTable({
                    "pageLength": 50,
                });

                //Hide Account Id Column
                //dtTrialBalance.column(1).visible(false);

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
                        {{--{--}}
                        {{--    extend: 'pdf',--}}
                        {{--    className: 'btn btn-falcon-danger btn-sm mr-1',--}}
                        {{--    text: '<span class="far fa-file-pdf fa-lg"></span> Export Pdf',--}}
                        {{--    title: 'General Ledger - {{ isset($trns[0]->title) ? $trns[0]->title : "" }}',--}}
                        {{--    footer: true--}}
                        {{--},--}}
                        {{--{--}}
                        {{--    extend: 'print',--}}
                        {{--    className: 'btn btn-falcon-info btn-sm',--}}
                        {{--    text: '<span class="fas fa-print fa-lg"></span> Print',--}}
                        {{--    title: 'General Ledger - {{ isset($trns[0]->title) ? $trns[0]->title : "" }}',--}}
                        {{--    footer: true,--}}
                        {{--    customize: function ( win ) {--}}
                        {{--        $(win.document.body)--}}
                        {{--            .css( 'font-size', '11pt' )--}}
                        {{--        $(win.document.body).find( 'table' )--}}
                        {{--            .addClass( 'compact' )--}}
                        {{--            .css( 'font-size', 'inherit' );--}}
                        {{--    }--}}
                        {{--},--}}
                    ]
                });
                dtTrialBalance.buttons().container().appendTo('#dtTrialBalanceActions');

                //Trial Balance Table Footer Total
                let drTotal = dtTrialBalance.column(3).data().sum();
                let crTotal = dtTrialBalance.column(4).data().sum();

                $('#drTotal').text(drTotal).formatNumber();
                $('#crTotal').text(crTotal).formatNumber();
                $('#differ').text(drTotal - crTotal).formatNumber();

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

                    console.log(drSum);
                    console.log(crSum);

                    //Display Total
                    $('#drLedgerTotal').text(thousandSeperator(drSum));
                    $('#crLedgerTotal').text(thousandSeperator(crSum));
                    $('#balTotal').text(thousandSeperator(drSum-crSum));

                    dtTrns.destroy();
                }

                //Getting Fist Day of Month
                function getFirstDayOfMonth() {
                    return new Date(new Date().getFullYear(), new Date().getMonth(), 1);
                }

                //Getting Last Day of Month
                function getLastDayOfMonth() {
                    return new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);
                }


                //Display Input Dates Modal
                var $accountId;
                var $accountTitle;
                dtTrialBalance.on('click', '.showLedger', function (e) {
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
                            'from_date': $('#dpStartDate').val(),
                            'to_date': $('#dpEndDate').val(),
                            'request_from': 'trialBalance'
                        },
                        success: function (response) {

                            //$('#showLedgerDatesModal').modal('hide');

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
                                    '<td class="col-1 text-center" width="10%">' + moment(value.trn_date).format('DD-MMM-YYYY')  + '</td>' +
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

                            // $('.modal-body').load(SelfBalancingDataTableColumn(), function(){
                            //     $('#showLedgerModal').modal('show')
                            // });
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });

                });
            });

            //Print Trial Balance
            $('#btnPrint').on('click', function () {
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
                        'As On {{ date_format(today(), 'd-M-Y') }}' +
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
    @endpush

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="bg-holder d-none d-lg-block bg-card"
                     style="background-image:url('{{asset('media/illustrations/corner-4.png')}}');">
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

                                    {{ $link = "" }}
                                    @for($i = 1; $i <= count(Request::segments()); $i++)

                                        @if($i < count(Request::segments()) & $i > 0)
                                            <!-- {{ $link .= "/" . Request::segment($i) }} -->
                                            <li class="breadcrumb-item">
                                                <a href="<?= $link ?>">{{ ucwords(str_replace('-',' ',Request::segment($i)))}}</a>
                                            </li>
                                            <i class="fas fa-long-arrow-alt-right m-1"></i>

                                        @else
                                            {{ucwords(str_replace('-',' ',Request::segment($i)))}}
                                        @endif
                                    @endfor
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Display Errors    --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="printTbl">
                <div class="card-header border-bottom bg-dark">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-sm-auto d-flex align-items-center pr-0">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Trail Balance
                                <span
                                    class="text-light fs--1 d-block mt-1">As On {{ date_format(today(), 'd-M-Y') }}</span>
                            </h5>
                        </div>
                        <div class="col-6 col-sm-auto ml-auto text-right pl-0">
                            <div id="dtTrialBalanceActions" class="d-inline-flex">
                                {{-- Showing Table Action Buttons --}}
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
                        <table id="dtTrialBalance" class="table table-striped table-hover border table-sm">
                            <thead class="bg-200 text-900">
                            <tr>
                                <th class="border-0 text-center align-middle" width="5%">S.No.</th>
                                {{--                                <th class="border-0 text-center align-middle" width="2%">Account Id</th>--}}
                                <th class="border-0 text-center align-middle" width="10%">Account Code</th>
                                <th class="border-0">Controlling Account</th>
                                <th class="border-0 text-right align-middle" width="15%">Debit (DR)</th>
                                <th class="border-0 text-right align-middle" width="15%">Credit (CR)</th>
                                <th class="border-0 text-right align-right" width="15%">Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($accounts as $trialAccount)
                                <tr>
                                    <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                    {{--                                    <td class="text-center align-middle">{{ $trialAccount->id }}</td>--}}
                                    <td class="text-center align-middle">
                                        <a href="#" class="showLedger" data-id="{{$trialAccount->id}}" data-name="{{ $trialAccount->title }}">
                                            {{ $trialAccount->code }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="showLedger" data-id="{{$trialAccount->id}}" data-name="{{ $trialAccount->title }}">
                                            {{ $trialAccount->title }}
                                        </a>
                                    </td>
                                    <td class="text-right align-middle">{{ number_format($trialAccount->dr_amount) }}</td>
                                    <td class="text-right align-middle">{{ number_format($trialAccount->cr_amount) }}</td>
                                    <td class="text-right align-middle pr-4">{{ number_format($trialAccount->balance) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-200">
                            <tr>
                                <th></th>
                                {{--                                <th></th>--}}
                                <th></th>
                                <th class="text-right">Total Amount</th>
                                <th id="drTotal" class="text-right align-middle"></th>
                                <th id="crTotal" class="text-right align-middle"></th>
                                <th id="differ" class="text-center align-middle text-danger font-weight-bold fs-1"></th>
                            </tr>
                            </tfoot>
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
                {{--                <div class="modal-header bg-gradient">--}}
                {{--                    <h5 class="modal-title font-weight-bold text-2xl text-white" id="exampleModalLabel">View Transaction</h5>--}}
                {{--                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span--}}
                {{--                            class="font-weight-light text-white" aria-hidden="true">&times;</span></button>--}}
                {{--                </div>--}}
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
                                        <input class="form-control datetimepicker flatpickr-input fs-0" id="dpStartDate"
                                               type="text" name="from_date">
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label for="datepicker">End Date</label>
                                        <input class="form-control datetimepicker flatpickr-input fs-0" id="dpEndDate"
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
                {{--                <div class="modal-header bg-gradient">--}}
                {{--                    <h5 class="modal-title font-weight-bold text-2xl text-white" id="exampleModalLabel">View Transaction</h5>--}}
                {{--                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span--}}
                {{--                            class="font-weight-light text-white" aria-hidden="true">&times;</span></button>--}}
                {{--                </div>--}}
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
                                            {{-- Showing Table Action Buttons --}}
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
                                                <th class="col-1 border-0 text-center align-middle" width="10%">Trn Date</th>
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
                                                <th class="col-1 border-0 text-center align-middle" width="10%"></th>
                                                <th class="col-1 border-0 text-center align-middle"></th>
                                                <th class="col-6 border-0 align-middle text-right">Total Amount</th>
                                                <th class="col-1 border-0 text-center align-middle" id="drLedgerTotal"></th>
                                                <th class="col-1 border-0 text-center align-middle" id="crLedgerTotal"></th>
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
            {{--                <div class="modal-footer">--}}
            {{--                   <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Close</button>--}}
            {{--                   <button class="btn btn-primary btn-sm" id="getTotal" type="button">Save changes</button>--}}
            {{--             </div>--}}

        </div>
    </div>
@endsection
