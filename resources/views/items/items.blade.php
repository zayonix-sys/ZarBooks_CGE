@extends('layout')
@section('title', 'Items List')

@section('content')

    @push('head')
        <link href="{{asset('lib/select2/select2.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')}}" rel="stylesheet">
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

                /**
                 * Initialize Select Picker
                 */

                $('select').select2({
                    theme: 'bootstrap4',
                    placeholder: 'Select Parent Category',
                    dropdownParent: $('#addItemModal')
                });

                const dtItems = $('#dtItems').DataTable();
                new $.fn.dataTable.Buttons(dtItems, {
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
                            extend: 'print',
                            className: 'btn btn-falcon-warning btn-sm',
                            text: '<span class="fas fa-print fa-lg"></span> Print',
                            title: 'Transactions Summary',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
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
                dtItems.buttons().container().appendTo('#dtItemsAction');

                //Generate Sub Categories DropDown on Basis of Parent Category
                $('#ddParentCategory').on('change', function (e) {
                    e.preventDefault();

                    var $id = $(this).val();
                    let url = "{{ route('items/getSubCategories', ':id') }}";
                    url = url.replace(':id', $id);

                    $.get(url, function (subCategories) {
                        console.log(subCategories);
                        $("#ddSubCategories").empty().trigger('change');
                        for (let i = 0; i < subCategories.length; i++) {
                            $('#ddSubCategories').append(
                                '<option value=' + subCategories[i].id + '>' + subCategories[i].title + '</option>'
                            );
                        }
                    })
                });

                // Display Add Item Modal
                $('#addItem').on('click', function (){
                    $('#addItemModal').modal('show');
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Items</h3>

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

    <div class="row">
        <!-- Items Details Tables-->
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom bg-dark">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-3 col-sm-auto d-flex align-items-center pr-0">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Items
                            </h5>
                        </div>
                        <div class="col-6 col-sm-auto text-center pl-0">
                            <div id="dtItemsAction" class="d-inline-flex">
                                {{-- Showing Table Action Buttons --}}
                            </div>
                        </div>
                        <div class="col-3 col-sm-auto d-flex align-items-right pr-3">
                            <button class="btn btn-falcon-success btn-sm mr-2" id="addItem">
                                <i class="fa-solid fa-file-circle-plus fa-lg"></i> Add Item
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Shows Categories --}}
                    <div class="table-responsive fs--1">
                        <table id="dtItems" class="table table-sm border-bottom table-hover">
                            <thead class="bg-200 text-900">
                            <tr>
                                <th class="border-0">S.No</th>
                                <th class="border-0 ">Item Category</th>
                                <th class="border-0 ">Item</th>
                                <th class="border-0 ">Model No.</th>
                                <th class="border-0 text-center">Unit</th>
                                <th class="border-0 text-center">Purchase Price</th>
                                <th class="border-0 text-center">Sale Price</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->category->title }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->part_no }}</td>
                                    <td class="text-center">{{ $item->unit }}</td>
                                    <td class="text-center">{{ number_format($item->purchase_price) }}</td>
                                    <td class="text-center">{{ number_format($item->sale_price) }}</td>
                                    <td class="text-center">
                                        @if ($item->status === 1 )
                                            <span class="badge rounded-capsule badge-soft-success">Active
                                                <span class="ml-1 fas fa-check"></span>
                                            </span>
                                        @else
                                            <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                <span class="ml-1 fas fa-times"></span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center cursor-pointer ">
                                            <span class="badge rounded-capsule badge-soft-warning">
                                                <a href="/item/show/{{ $item->id }}" class="viewItem">
                                                    <i class="fa-solid fa-eye fa-lg px-1"></i>
                                                </a>
                                            </span>
                                            <span class="badge rounded-capsule badge-soft-info ml-2">
                                                <a href="/item/edit/{{ $item->id }}" class="editItem">
                                                    <span class="ml-1 fas fa-edit fa-lg px-1"></span>
                                                </a>
                                            </span>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Item  Modal-->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom bg-dark">
                    <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                        Add Item
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            class="font-weight-light" aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body fs--1">
                            <form action="/items" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="col-sm-6">
                                        <label class="mb-0">Item Name</label>
                                        <input type="text" name="name" class="form-control"
                                               value="{{ old('name') }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">Model No. / Part No.</label>
                                        <input type="text" name="part_no" class="form-control"
                                               value="{{ old('part_no') }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="mb-0">Unit</label>
                                        <input type="text" name="unit" class="form-control"
                                               value="{{ old('unit') }}">
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-sm-12">
                                        <label class="mb-0">Description</label>
                                        <textarea class="form-control fs--1" rows="4" name="description" >{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-sm-4">
                                        <label class="mb-0">Parent Category</label>
                                        <select class="form-control selectpicker" id="ddParentCategory"
                                                name="parent_id">
                                            @foreach($parentCategories as $parentCategory)
                                                <option
                                                    value="{{ $parentCategory->id }}">{{ $parentCategory->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="mb-0">Sub Category</label>
                                        <select class="form-control selectpicker" id="ddSubCategories"
                                                name="item_category_id">
                                            {{-- Dynamically Display Sub Categories --}}
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="mb-0">Purchase Price</label>
                                        <input type="number" name="purchase_price" class="form-control"
                                               value="{{ old('purchase_price') }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="mb-0">Sale Price</label>
                                        <input type="number" name="sale_price" class="form-control"
                                               value="{{ old('sale_price') }}">
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-sm-6">
                                        <label class="mb-0">Select Image/Catalogue</label>
                                        <input type="file" name="image_files[]" class="form-control-file" multiple>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <button type="submit" class="btn btn-outline-success btn-block" id="btnAdd">Add
                                            Items
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Edit Modal-->
{{--    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="fyModalLabel"--}}
{{--         aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="fyModalLabel">Edit Item</h5>--}}
{{--                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span--}}
{{--                            class="font-weight-light" aria-hidden="true">&times;</span></button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <form action="/items" method="post" enctype="multipart/form-data">--}}
{{--                        @csrf--}}
{{--                        @method('PUT')--}}
{{--                        <div class="form-row">--}}
{{--                            <div class="col-sm-6">--}}
{{--                                <label class="mb-0">Item Name</label>--}}
{{--                                <input type="text" name="name" class="form-control"--}}
{{--                                       value="{{ old('name') }}">--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-4">--}}
{{--                                <label class="mb-0">Model No. / Part No.</label>--}}
{{--                                <input type="text" name="part_no" class="form-control"--}}
{{--                                       value="{{ old('part_no') }}">--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-2">--}}
{{--                                <label class="mb-0">Unit</label>--}}
{{--                                <input type="text" name="unit" class="form-control"--}}
{{--                                       value="{{ old('unit') }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-row mt-3">--}}
{{--                            <div class="col-sm-12">--}}
{{--                                <label class="mb-0">Description</label>--}}
{{--                                <textarea class="form-control fs--1" rows="4" name="description" >{{ old('description') }}</textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-row mt-3">--}}
{{--                            <div class="col-sm-4">--}}
{{--                                <label class="mb-0">Parent Category</label>--}}
{{--                                <select class="form-control selectpicker" id="ddParentCategory"--}}
{{--                                        name="parent_id">--}}
{{--                                    @foreach($parentCategories as $parentCategory)--}}
{{--                                        <option--}}
{{--                                            value="{{ $parentCategory->id }}">{{ $parentCategory->title }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-4">--}}
{{--                                <label class="mb-0">Sub Category</label>--}}
{{--                                <select class="form-control selectpicker" id="ddSubCategories"--}}
{{--                                        name="item_category_id">--}}
{{--                                    --}}{{-- Dynamically Display Sub Categories --}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-2">--}}
{{--                                <label class="mb-0">Purchase Price</label>--}}
{{--                                <input type="number" name="purchase_price" class="form-control"--}}
{{--                                       value="{{ old('purchase_price') }}">--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-2">--}}
{{--                                <label class="mb-0">Sale Price</label>--}}
{{--                                <input type="number" name="sale_price" class="form-control"--}}
{{--                                       value="{{ old('sale_price') }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-row mt-3">--}}
{{--                            <div class="col-sm-6">--}}
{{--                                <label class="mb-0">Select Image/Catalogue</label>--}}
{{--                                <input type="file" name="image_files[]" class="form-control-file" multiple>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-6 mt-3">--}}
{{--                                <button type="submit" class="btn btn-outline-success btn-block" id="btnAdd">Add--}}
{{--                                    Items--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
