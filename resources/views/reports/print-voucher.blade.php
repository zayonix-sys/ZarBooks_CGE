@extends('layout')

@section('content')

    @push('head')
        <link href="{{asset('lib/select2/select2.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/flatpickr/flatpickr.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')}}" rel="stylesheet">
    @endpush

    @push('footer')
        <script src="{{asset('lib/select2/select2.min.js')}}"></script>
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

        <script type="text/javascript">
            $(document).ready(function () {

                $('#showVoucherModal').modal('show');

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
                calculateRowSum();
                printVoucher();

            });

            function printVoucher()
            {
                document.body.onload = window.print();
            }
        </script>
    @endpush

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
            <div class="card" >
                @isset($trnsDetails)
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-2" id="showTrnsType">
                                @switch($trnsDetails[0]->trn_type)
                                    @case('DR')
                                    <span class="badge badge-soft-danger fs-1 pl-5 pr-4">
                                        Debit Voucher
                                    </span>
                                    @break

                                    @case('CR')
                                    <span class="badge badge-soft-success fs-1 pl-5 pr-4">
                                        Credit Voucher
                                    </span>
                                    @break

                                    @case('JV')
                                    <span class="badge badge-soft-warning fs-1 pl-5 pr-4">
                                        Journal Voucher
                                    </span>
                                    @break
                                @endswitch
                            </div>
                            <div class="table-responsive mb-0">
                                <table class="table table-sm border fs--1">
                                    <tr>
                                        <td class="align-middle font-weight-bold" width="10%">Payee:</td>
                                        <td class="align-middle" width="60%">{{ $trnsDetails[0]->payee }}</td>
                                        <td class="align-middle font-weight-bold"  width="15%">Trn Date:</td>
                                        <td class="align-middle"  width="15%">{{ date('d-M-Y', strtotime($trnsDetails[0]->trn_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle font-weight-bold">Messer:</td>
                                        <td class="align-middle ">{{ $trnsDetails[0]->messer }}</td>
                                        <td class="align-middle font-weight-bold">Voucher No.:</td>
                                        <td class="align-middle ">{{ $trnsDetails[0]->id }}</td>
                                    </tr>

                                </table>
                            </div>
                            <div class="table-responsive ">
                                <table class="table table-sm table-striped border fs--1 " id="tblShowVoucher">
                                    <thead class="bg-200 text-900">
                                    <tr>
                                        <th class="border-0">A/C Code</th>
                                        <th class="border-0">Account Title</th>
                                        <th class="border-0">Particular</th>
                                        <th class="border-0 text-center">Debit</th>
                                        <th class="border-0 text-center">Credit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($trnsDetails as $trn)
                                        <tr>
                                            <td class="align-middle" width="15%">{{ $trn->code }}</td>
                                            <td class="align-middle text-word-break" width="20%">{{ $trn->account }}</td>
                                            <td class="align-middle text-word-break" width="45%">{{ $trn->particular }}</td>
                                            <td class="align-middle text-center drTrnsAmount" width="10%">
                                                {{ $trn->dr_amount == 0 ? '-' : number_format($trn->dr_amount) }}
                                            </td>
                                            <td class="align-middle text-center crTrnsAmount" width="10%">
                                                {{ $trn->cr_amount == 0 ? '-' : number_format($trn->cr_amount) }}
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                    <tfoot class="bg-200">
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">Total Amount</th>
                                    <th class="text-center" id="trnsDr"></th>
                                    <th class="text-center" id="trnsCr"></th>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-6 text-left pl-5 font-weight-semi-bold inline ">
                                Accountant------------------------------------------------------------------
                            </div>
                            <div class="col-sm-6 text-left pr-5 font-weight-semi-bold">
                                Received By------------------------------------------------------------------
                            </div>
                        </div>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    <!-- Individual Transaction Modal-->
    <div class="modal fade" id="showVoucherModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gradient">
                    <h5 class="modal-title font-weight-bold text-2xl text-white" id="exampleModalLabel">Print Transaction</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light text-white" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('getVoucherTrns') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <label for="trn-id">Enter Transaction No.</label>
                                <input type="number" class="form-control" name="trn_id" id="trn_id">
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <input type="submit" class="btn btn-outline-success">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
