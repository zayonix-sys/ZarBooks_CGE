@extends('layout')
@section('title', 'Non-Taxable Invoice')

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
        <script src="{{ asset('lib/datatables/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/jszip.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/pdfmake.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/vfs_fonts.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('lib/datatables/js/buttons.print.min.js') }}"></script>
        <script src="{{asset('lib/datatables/js/dataTableSum.js')}}"></script>
        <script src="{{asset('lib/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{asset('js/number-formatter.js')}}"></script>
        <script src="{{asset('lib/date-js/date.js')}}"></script>

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

                //Initialize Invoice Date Picker
                $('#dpInvoiceDate').flatpickr({
                    dateFormat: 'd-M-Y',
                    defaultDate: 'today'
                });

                //Initialize Purchase Order Date Picker
                $('#dpPurchaseOrderDate').flatpickr({
                    dateFormat: 'd-M-Y',
                });

                //Function to Add More Account Row
                function AddAccountRow() {
                    //Destroy Select Picker
                    $('.selectItem').select2("destroy");

                    $tableBody = $('#tblItemEntry').find("tbody");
                    $trLast = $tableBody.find("tr:last");
                    $trNew = $trLast.clone();
                    $trLast.after($trNew);

                    $tableBody.find("tr:last td:nth-child(2) input[type='text']").val('');
                    $tableBody.find("tr:last td:nth-child(3) input[type='number']").val('');
                    $tableBody.find("tr:last td:nth-child(4) input[type='number']").val('');
                    $tableBody.find("tr:last td:nth-child(5) input[type='number']").val('');
                    $tableBody.find("tr:last td:nth-child(6) input[type='number']").val('');

                    //Re-Initialized Select Picker
                    $('.selectItem').select2({
                        theme: 'bootstrap4',
                        selectOnClose: true,
                    });
                }

                //Add New Row in Entry Table
                $('#tblItemEntry').on('keyup', 'td:nth-child(6)', function () {
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                        AddAccountRow();
                    }
                    calculateRowSum();
                });

                $('#btnAddItem').on('click', function () {
                    AddAccountRow();
                });

                //Remove Row in Entry Table
                $('#tblItemEntry').on('click', '.deleteRow', function () {
                    $rowCount = $('#tblItemEntry tbody tr').length;
                    if ($rowCount > 1) {
                        $(this).closest('tr').remove();
                    }
                    calculateRowSum();
                });


                // const dtCustomers = $('#dtCustomers').DataTable();
                // new $.fn.dataTable.Buttons(dtCustomers, {
                //     buttons: [
                //         {
                //             extend: 'copy',
                //             className: 'btn btn-falcon-default btn-sm mr-1',
                //             text: '<span class="far fa-copy fa-lg"></span> Copy',
                //             footer: true
                //         },
                //         {
                //             extend: 'excel',
                //             className: 'btn btn-falcon-success btn-sm mr-1',
                //             text: '<span class="far fa-file-excel fa-lg"></span> Export Excel',
                //             footer: true
                //         },
                //         {
                //             extend: 'print',
                //             className: 'btn btn-falcon-warning btn-sm',
                //             text: '<span class="fas fa-print fa-lg"></span> Print',
                //             title: 'Transactions Summary',
                //             exportOptions: {
                //                 columns: [0, 1, 2, 3, 4, 5, 6, 7]
                //             },
                //             customize: function (win) {
                //                 $(win.document.body)
                //                     .css('font-size', '11pt')
                //                 $(win.document.body).find('table')
                //                     .addClass('compact')
                //                     .css('font-size', 'inherit');
                //             }
                //         },
                //     ]
                // });
                // dtCustomers.buttons().container().appendTo('#dtCustomersAction');

                //Getting Customer Info for Update
                // dtCustomers.on('click', '.editCustomer', function (e) {
                //     e.preventDefault();
                //
                //     var id = $(this).attr('data-id');
                //     let url = '/customer/'+id+'/edit';
                //
                //     $.get(url, function (customer) {
                //         console.log(customer);
                //         $('#txtName').val(customer.name);
                //         $('#txtContact').val(customer.contact);
                //         $('#txtCNIC').val(customer.cnic);
                //         $('#txtNTN').val(customer.ntn);
                //         $('#txtSTRN').val(customer.strn);
                //         $('#txtAddress').val(customer.address);
                //         $('#txtEmail').val(customer.email);
                //
                //         if (customer.status == 1) {
                //             $('#customerActive').attr('checked', 'checked');
                //         } else {
                //             $('#customerInActive').attr('checked', 'checked');
                //         }
                //
                //         $('#frmEditCustomer').attr('action', '/customer/'+customer.id);
                //
                //         $('#editCustomerModal').modal('show');
                //     })
                // });

                // // Display Add Item Modal
                // $('#addCustomer').on('click', function (){
                //     $('#addCustomerModal').modal('show');
                // });
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Sales Invoice (Non-Taxable)</h3>

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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{--    <div class="row">--}}
    {{--        <div class="col-sm-6">--}}
    {{--            <div class="card">--}}
    {{--                <div class="card-header">Billed To</div>--}}
    {{--                <label>Select Customer</label>--}}
    {{--                <select class="form-control selectpicker" id="ddBilled" name="customer_billed">--}}
    {{--                    <option value="Trading">Trading</option>--}}
    {{--                    <option value="Services">Services</option>--}}
    {{--                    <option value="Rental">Rental</option>--}}
    {{--                </select>--}}
    {{--                <div class="card-body"></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="col-sm-6">--}}
    {{--            <div class="card">--}}
    {{--                <div class="card-header">Shipped To</div>--}}
    {{--                <div class="card-body"></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{--                <div class="card-header border-bottom bg-dark">--}}
                {{--                    <div class="row align-items-center justify-content-between">--}}
                {{--                        <div class="col-3 col-sm-auto d-flex align-items-center pr-0">--}}
                {{--                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-success font-weight-semi-bold">--}}
                {{--                                Customers--}}
                {{--                            </h5>--}}
                {{--                        </div>--}}
                {{--                        <div class="col-6 col-sm-auto text-center pl-0">--}}
                {{--                            <div id="dtCustomersAction" class="d-inline-flex">--}}
                {{--                                --}}{{-- Showing Table Action Buttons --}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                        <div class="col-3 col-sm-auto d-flex align-items-right pr-3">--}}
                {{--                            <button class="btn btn-falcon-success btn-sm mr-2" id="addCustomer">--}}
                {{--                                <i class="fa-solid fa-user-plus fa-lg"></i> Add New Customer--}}
                {{--                            </button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-row ">
                            <div class="col-sm-3">
                                <label>Customer</label>
                                <select class="form-control selectpicker fs--1" id="ddCustomer" name="customer">
                                    <option value="#">Select Customer</option>
                                    @isset($customers)
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="dpInvoiceDate">Invoice Date</label>
                                <input class="form-control datetimepicker flatpickr-input fs--1" id="dpInvoiceDate"
                                       type="text"
                                       name="invoice_date">
                            </div>
                            <div class="col-sm-1">
                                <label>Invoice Type</label>
                                <select class="form-control selectpicker fs--1" id="ddInvoiceType" name="invoice_type">
                                    <option value="Trading">Trading</option>
                                    <option value="Services">Services</option>
                                    <option value="Rental">Rental</option>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <label>Term</label>
                                <select class="form-control selectpicker fs--1" id="ddPaymentTerm" name="payment_term">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit">Credit</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="dpPurchaseOrderDate">Purchase Order Date</label>
                                <input class="form-control datetimepicker flatpickr-input fs--1"
                                       id="dpPurchaseOrderDate"
                                       placeholder="Select P.O Date"
                                       type="text"
                                       name="purchase_order_date">
                            </div>
                            <div class="col-sm-2">
                                <label>P.O. No.</label>
                                <input type="text" class="form-control fs--1" name="purchase_order_no"
                                       value="{{ old('payee') }}" required autofocus>
                            </div>
                            <div class="col-sm-1">
                                <label>D. C. No.</label>
                                <input type="text" class="form-control fs--1" name="dc_no" value="{{ old('payee') }}">
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Invoice Items Details --}}
                <div class="row mx-1 my-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive fs--1">
                                    <div class="d-flex">
                                        <p class="text-warning">- Press Enter On Amount Column to Add More Item or Use
                                            Button</p>
                                        <button
                                            class="btn btn-falcon-default btn-sm rounded-capsule ml-auto my-2 mx-1"
                                            type="button" id="btnAddItem">
                                            <span class="fas fa-plus mr-1" data-fa-transform="shrink-3"></span>Add
                                            Item
                                        </button>
                                    </div>
                                    <table id="tblItemEntry" class="table table-striped border">
                                        <thead class="bg-200 text-900">
                                            <tr class="text-center">
                                                <th class="border-0" width="20%">Item</th>
                                                <th class="border-0">Description</th>
                                                <th class="border-0" width="8%">Qty</th>
                                                <th class="border-0" width="10%">Rate</th>
                                                <th class="border-0" width="10%">Gross Amount</th>
                                                <th class="border-0" width="10%">Disc</th>
                                                <th class="border-0" width="10%">Net Amount</th>
                                                <th class="border-0 text-center" width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td class="align-middle">
                                                <select
                                                    class="form-control form-control-sm selectpicker selectItem"
                                                    name="item_id[]">
                                                    @isset($items)
                                                        @foreach($items as $item)
                                                            <option
                                                                value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </td>
                                            <td class="align-middle">
                                                <textarea class="form-control form-control-sm fs--1" rows="1"
                                                          name="description[]"
                                                          type="text" placeholder="Enter Particular" required
                                                          autocomplete="true">
                                                </textarea>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="qty[]"
                                                       type="number" min="0" required>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="rate[]"
                                                       type="number" min="0" required>
                                            </td>
                                            <td class="align-left">
                                                <input class="form-control form-control-sm"
                                                       name="gross_amount[]"
                                                       type="number" min="0" required
                                                       readonly
                                                       onkeydown="return event.key != 'Enter';">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm"
                                                       name="disc[]"
                                                       type="number" min="0">
                                            </td>
                                            <td class="align-left">
                                                <input class="form-control form-control-sm transAmount"
                                                       name="net_amount[]"
                                                       type="number" min="0" required
                                                       readonly
                                                       onkeydown="return event.key != 'Enter';">
                                            </td>
                                            <td class="deleteRow align-middle text-center cursor-pointer">
                                                <span class="badge rounded-capsule badge-soft-danger cursor-pointer">
                                                    Delete <span class="ml-1 fas fa-window-close fa-lg"></span>
                                                </span>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot class="bg-200">
                                            <tr>
                                            <th></th>
                                            <th class="text-right">Total Amount</th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold"></th>
                                            <th class="font-weight-bold" id="txtTotalAmount"></th>
                                            <th><input type="hidden" id="drTotal" name="trn_amount"></th>
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
    </div>

    <!-- Add Customer Modal-->
    {{--    <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"--}}
    {{--         aria-hidden="true">--}}
    {{--        <div class="modal-dialog modal-lg" role="document">--}}
    {{--            <div class="modal-content">--}}
    {{--                <div class="modal-header border-bottom bg-dark">--}}
    {{--                    <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">--}}
    {{--                        Add Customer--}}
    {{--                    </h5>--}}
    {{--                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span--}}
    {{--                            class="font-weight-light" aria-hidden="true">&times;</span></button>--}}
    {{--                </div>--}}
    {{--                <div class="modal-body">--}}
    {{--                    <div class="card">--}}
    {{--                        <div class="card-body">--}}
    {{--                            <form action="/customer" method="POST">--}}
    {{--                                @csrf--}}
    {{--                                <div class="form-row">--}}
    {{--                                    <div class="col-sm-8">--}}
    {{--                                        <label class="mb-0">Customer Name</label>--}}
    {{--                                        <input type="text" name="name" class="form-control"--}}
    {{--                                               value="{{ old('name') }}">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">Contact No.</label>--}}
    {{--                                        <input type="text" name="contact" class="form-control"--}}
    {{--                                               value="{{ old('contact') }}">--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}

    {{--                                <div class="form-row mt-3">--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">CNIC No.</label>--}}
    {{--                                        <input type="text" name="cnic" class="form-control"--}}
    {{--                                               value="{{ old('cnic') }}">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">NTN No.</label>--}}
    {{--                                        <input type="text" name="ntn" class="form-control"--}}
    {{--                                               value="{{ old('ntn') }}">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">STRN No.</label>--}}
    {{--                                        <input type="text" name="strn" class="form-control"--}}
    {{--                                               value="{{ old('strn') }}">--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="form-row mt-3">--}}
    {{--                                    <div class="col-sm-12">--}}
    {{--                                        <label class="mb-0">Address</label>--}}
    {{--                                        <textarea class="form-control" rows="3" name="address" >{{ old('address') }}</textarea>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="form-row mt-3">--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">Email</label>--}}
    {{--                                        <input type="text" name="email" class="form-control"--}}
    {{--                                               value="{{ old('email') }}">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">Select Associate Account</label>--}}
    {{--                                        <select class="form-control selectpicker"--}}
    {{--                                                name="parent_account_id">--}}
    {{--                                            @foreach($parentAccounts as $parentAccount)--}}
    {{--                                                <option--}}
    {{--                                                    value="{{ $parentAccount->id }}">{{ $parentAccount->title }}</option>--}}
    {{--                                            @endforeach--}}
    {{--                                        </select>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4 mt-3">--}}
    {{--                                        <button type="submit" class="btn btn-outline-success btn-block" id="btnAdd">Add--}}
    {{--                                            Customer--}}
    {{--                                        </button>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </form>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <!--Edit Customer Modal-->
    {{--    <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"--}}
    {{--         aria-hidden="true">--}}
    {{--        <div class="modal-dialog modal-lg" role="document">--}}
    {{--            <div class="modal-content">--}}
    {{--                <div class="modal-header border-bottom bg-soft-warning">--}}
    {{--                    <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">--}}
    {{--                        Edit Customer--}}
    {{--                    </h5>--}}
    {{--                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span--}}
    {{--                            class="font-weight-light" aria-hidden="true">&times;</span></button>--}}
    {{--                </div>--}}
    {{--                <div class="modal-body">--}}
    {{--                    <div class="card">--}}
    {{--                        <div class="card-body">--}}
    {{--                            <form action="" method="POST" id="frmEditCustomer">--}}
    {{--                                @csrf--}}
    {{--                                @method('PUT')--}}
    {{--                                <div class="form-row">--}}
    {{--                                    <div class="col-sm-8">--}}
    {{--                                        <label class="mb-0">Customer Name</label>--}}
    {{--                                        <input type="text" name="name" class="form-control" id="txtName">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">Contact No.</label>--}}
    {{--                                        <input type="text" name="contact" class="form-control" id="txtContact">--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}

    {{--                                <div class="form-row mt-3">--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">CNIC No.</label>--}}
    {{--                                        <input type="text" name="cnic" class="form-control" id="txtCNIC">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">NTN No.</label>--}}
    {{--                                        <input type="text" name="ntn" class="form-control" id="txtNTN">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">STRN No.</label>--}}
    {{--                                        <input type="text" name="strn" class="form-control" id="txtSTRN">--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="form-row mt-3">--}}
    {{--                                    <div class="col-sm-12">--}}
    {{--                                        <label class="mb-0">Address</label>--}}
    {{--                                        <textarea class="form-control" rows="3" name="address" id="txtAddress"></textarea>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                                <div class="form-row mt-3">--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">Email</label>--}}
    {{--                                        <input type="text" name="email" class="form-control" id="txtEmail">--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-5 mt-4">--}}
    {{--                                        <div class="form-group">--}}
    {{--                                            <label for="customRadioInline1">Status</label>--}}
    {{--                                            <div class="custom-control custom-radio custom-control-inline">--}}
    {{--                                                <input class="custom-control-input" id="customerActive" type="radio"--}}
    {{--                                                       name="status" value="1">--}}
    {{--                                                <label class="custom-control-label" for="customerActive">Active</label>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="custom-control custom-radio custom-control-inline">--}}
    {{--                                                <input class="custom-control-input" id="customerInActive" type="radio"--}}
    {{--                                                       name="status" value="0">--}}
    {{--                                                <label class="custom-control-label" for="customerInActive">In-Active</label>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-4">--}}
    {{--                                        <label class="mb-0">Select Associate Account</label>--}}
    {{--                                        <select class="form-control selectpicker" id="ddParentAccount"--}}
    {{--                                                name="parent_account_id">--}}
    {{--                                            @foreach($parentAccounts as $parentAccount)--}}
    {{--                                                <option--}}
    {{--                                                    value="{{ $parentAccount->id }}">{{ $parentAccount->title }}</option>--}}
    {{--                                            @endforeach--}}
    {{--                                        </select>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-3 mt-3">--}}
    {{--                                        <button type="submit" class="btn btn-sm btn-outline-success btn-block" id="btnAdd">Update--}}
    {{--                                            Customer--}}
    {{--                                        </button>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}
    {{--                            </form>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

@endsection
