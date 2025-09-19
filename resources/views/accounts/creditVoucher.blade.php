@extends('layout')
@section('title', 'Credit Voucher')

@section('content')

    @push('head')
        <link href="{{asset('lib/select2/select2.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')}}" rel="stylesheet">
        <link href="{{asset('lib/flatpickr/flatpickr.min.css')}}" rel="stylesheet">
    @endpush

    @push('footer')
        <script src="{{asset('lib/select2/select2.min.js')}}"></script>
        <script src="{{asset('lib/datatables/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('lib/datatables.net-responsive-bs4/dataTables.responsive.js')}}"></script>
        <script src="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.js')}}"></script>
        <script src="{{asset('lib/datatables/js/dataTableSum.js')}}"></script>
        <script src="{{asset('lib/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{asset('js/number-formatter.js')}}"></script>

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

                //Initialize Transaction Date Picker
                $('#dpVoucherDate').flatpickr({
                    dateFormat: 'd-M-Y',
                    defaultDate: 'today'
                });

                //Function to Add More Account Row
                function AddAccountRow() {
                    //Destroy Select Picker
                    $('.selectCreditAccount').select2("destroy");

                    $tableBody = $('#tblTransEntry').find("tbody");
                    $trLast = $tableBody.find("tr:last");
                    $trNew = $trLast.clone();
                    //$trLast.after('<tr><td class="align-middle"><select class="form-control form-control-sm selectpicker"><option>Cash In Hand</option><option>Banks</option><option>Receivables</option><option>Paybale</option><option>General Expenses</option><option>Revenues</option></select></td><td class="align-middle"><input class="form-control form-control-sm" name="txtParticular" type="text" placeholder="Enter Particular"></td><td class="align-left"><input class="form-control form-control-sm transAmount" name="txtEntryAmnt" type="number" placeholder="Enter Amount"></td><td class="deleteRow align-middle text-center cursor-pointer"><span class="badge rounded-capsule badge-soft-danger cursor-pointer">Delete<span class="ml-1 fas fa-window-close fa-lg"></span></span></td></tr>');
                    $trLast.after($trNew);

                    $tableBody.find("tr:last td:nth-child(2) input[type='text']").val('');
                    $tableBody.find("tr:last td:nth-child(3) input[type='number']").val('');

                    //Re-Initialized Select Picker
                    $('.selectCreditAccount').select2({
                        theme: 'bootstrap4',
                        selectOnClose: true,
                    });
                }

                //Add New Row in Entry Table
                $('#tblTransEntry').on('keyup', 'td:nth-child(3)', function () {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        AddAccountRow();
                    }
                    calculateRowSum();
                });

                $('#btnAddAccount').on('click', function () {
                    AddAccountRow();
                });

                //Remove Row in Entry Table
                $('#tblTransEntry').on('click', '.deleteRow', function () {
                    $rowCount = $('#tblTransEntry tbody tr').length;
                    if ($rowCount > 1) {
                        $(this).closest('tr').remove();
                    }
                    calculateRowSum();
                });

                //Display Total Sum of Accounts
                function calculateRowSum() {
                    var sum = 0;
                    $('.transAmount').each(function () {
                        sum += parseFloat($(this).val() || 0);

                        $('#txtTrnsTotal').text(sum).formatNumber();
                        $('#drTotal').val(sum);
                    });
                }

                //Generate Cash Receipt Parent Account DropDown
                $('#ddParentAccounts').on('keyup keypress blur change', function (e) {
                    e.preventDefault();

                    var $parentAccount = $(this).val();
                    let url = "{{ route('getAccounts', ':parentAccount') }}";
                    url = url.replace(':parentAccount', $parentAccount);

                    $.get(url, function (parentAccounts) {
                        console.log(parentAccounts);
                        $("#ddControllingAccount").empty().trigger('change');

                        for (let i = 0; i < parentAccounts.length; i++) {
                            $('#ddControllingAccount').append(
                                '<option value=' + parentAccounts[i].id + '>' + parentAccounts[i].title + '</option>'
                            );
                        }
                    });
                }).triggerHandler('keyup');
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-success">Credit Voucher</h3>

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
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('accounts/crVoucher/store') }}" method="POST" id="frmCrEntry">
                        @csrf
                        <div class="form-row ">
                            <div class="col-sm-2">
                                <label for="datepicker">Transaction Date</label>
                                <input class="form-control form-control-sm  datetimepicker flatpickr-input" id="dpVoucherDate"
                                       type="text"
                                       name="trn_date">
                            </div>
                            <div class="col-sm-3">
                                <label>Received From</label>
                                <input type="text" class="form-control form-control-sm " name="payee" value="{{ old('payee') }}" required autofocus>
                            </div>
                            <div class="col-sm-7">
                                <label>Messer</label>
                                <input type="text" class="form-control form-control-sm " name="messer" value="{{ old('messer') }}" required>
                            </div>
                        </div>
                        <div class="form-row mt-3">
                            <div class="col-sm-2">
                                <label>Select Receipt Account</label>
                                <select class="form-control form-control-sm  selectpicker" id="ddParentAccounts" name="receipt_account">
                                    @foreach($parentAccounts as $parentAccount)
                                        <option value="{{ $parentAccount->id }}">{{ $parentAccount->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <label>Select Debit Account</label>
                                <select class="form-control form-control-sm selectpicker" id="ddControllingAccount"
                                        name="debit_account">
                                    {{-- Display Controlling Account --}}
                                </select>
                            </div>
                            <div class="col-sm-7">
                                <label>Description</label>
                                <input type="text" class="form-control form-control-sm" name="description"
                                       value="{{ old('description') }}" required>
                            </div>
                        </div>

                        {{-- Add Accout Entry --}}
                        <div class="row mx-1 my-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive fs--1">
                                            <div class="d-flex">
                                                <p class="text-warning">- Press Enter On Amount Column to Add More Credit
                                                    Account or Use Button</p>
                                                <button
                                                    class="btn btn-falcon-default btn-sm rounded-capsule ml-auto my-2 mx-1"
                                                    type="button" id="btnAddAccount">
                                                    <span class="fas fa-plus mr-1" data-fa-transform="shrink-3"></span>Add
                                                    Credit Account
                                                </button>
                                            </div>

                                            <table id="tblTransEntry" class="table table-striped border">
                                                <thead class="bg-200 text-900">
                                                <tr>
                                                    <th class="border-0" width="20%">Credited Account</th>
                                                    <th class="border-0">Particular</th>
                                                    <th class="border-0" width="20%">Amount (CR)</th>
                                                    <th class="border-0 text-center" width="10%">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="align-middle py-1">
                                                        <select
                                                            class="form-control form-control-sm selectpicker selectCreditAccount"
                                                            name="controlling_account_id[]">
                                                            @foreach($controllingAccounts as $controllingAccount)
                                                                <option
                                                                    value="{{ $controllingAccount->id }}">{{ $controllingAccount->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-middle py-1">
                                                        <input class="form-control form-control-sm"
                                                               name="particular[]"
                                                               type="text" placeholder="Enter Particular" required autocomplete="true">
                                                    </td>
                                                    <td class="align-left py-1">
                                                        <input class="form-control form-control-sm transAmount"
                                                               name="cr_amount[]"
                                                               type="number" placeholder="Enter Amount" required
                                                               onkeydown="return event.key != 'Enter';">
                                                    </td>
                                                    <td class="deleteRow align-middle py-1 text-center cursor-pointer">
                                                            <span
                                                                class="badge rounded-capsule badge-soft-danger cursor-pointer">Delete
                                                                <span class="ml-1 fas fa-window-close fa-lg"></span>
                                                            </span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot class="bg-200">
                                                <tr>
                                                    <th></th>
                                                    <th class="text-right">Total Credit Amount</th>
                                                    <th class="font-weight-bold" id="txtTrnsTotal"></th>
                                                    <th><input type="hidden" id="drTotal" name="trn_amount"></th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-row-reverse mr-3">
                            <button type="submit" class="btn btn-success mr-1 mb-1 w-25" id="btnSubmit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
