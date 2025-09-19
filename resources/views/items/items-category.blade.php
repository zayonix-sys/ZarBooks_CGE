@extends('layout')
@section('title', 'Item Categories')

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
        <script src="{{asset('lib/datatables/js/dataTableSum.js')}}"></script>
        <script src="{{asset('js/number-formatter.js')}}"></script>
        <script src="{{asset('js/printThis.js')}}"></script>

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
                    placeholder: 'Select Parent Category'
                });

                const dtItemCategories = $('#dtItemCategories').DataTable();
                // new $.fn.dataTable.Buttons(dtItemCategories, {
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
                //     ]
                // });
                // dtItemCategories.buttons().container().appendTo('#dtItemCategoriesAction');

                $('#btnPrint').on('click', function () {
                    $('#dtItemCategories').printThis({
                        debug: false,               // show the iframe for debugging
                        importCSS: true,            // import parent page css
                        importStyle: true,         // import style tags
                        printContainer: true,       // print outer container/$.selector
                        loadCSS: "",                // path to additional css file - use an array [] for multiple
                        pageTitle: "",              // add title to print page
                        removeInline: false,        // remove inline styles from print elements
                        removeInlineSelector: "*",  // custom selectors to filter inline styles. removeInline must be true
                        printDelay: 150,            // variable print delay
                        header: '<h5 class="fs-2 py-2 py-xl-0 font-weight-bold text-center">Item Categories</h5>',               // prefix to html
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

                //Showing Category Data to Update
                // var categoryTbl = $('#fyDataTable').DataTable();
                    dtItemCategories.on('click', '.editCategory', function () {
                    var $id = $(this).attr("data-id");

                    $tr = $(this).closest('tr');
                    var data = dtItemCategories.row($tr).data();

                    var categoryTitle = $tr.find("td:eq(1)").text();
                    var status = $tr.find("td:eq(2)").text().replace(/\s+/g, "");

                    $('#categoryTitle').val(categoryTitle);
                    if (status == 'Active') {
                        $('#categoryActive').attr('checked', 'checked');
                    } else {
                        $('#categoryInActive').attr('checked', 'checked');
                    }
                    $('#frmEditCategory').attr('action', '/items/itemsCategory/' + $id);
                    $('#categoryModal').modal('show');
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Items Categories</h3>

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

    <!-- Category Details Tables-->
    <div class="row mt-3">
        <div class="col-4">
            <div class="card">
                <div class="card-header bg-dark">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Add Item Category
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('items/itemsCategory/addItemCategory') }}" method="post">
                        @csrf
                        <div class="form-row ">
                            <div class="col-sm-12">
                                <label>Parent Category</label>
                                <select class="form-control selectpicker" id="ddParentCategory" name="parent_id">
                                    @foreach($parentCategories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}">{{ $parentCategory->title }}</option>
                                    @endforeach
                                </select>
                                <span class="float-right">
								<a href="" class="fs--1" data-toggle="modal"
                                   data-target="#parentCategoryModal">Add New</a>
							</span>
                            </div>

                            <div class="col-sm-12">
                                <label>Item Category</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                            <div class="col-sm-12 mt-3 text-center">
                                <button type="submit" class="btn btn-outline-success" id="btnAdd">Add Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header border-bottom bg-dark">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-6 col-sm-auto d-flex align-items-center pr-0">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Items Categories
                            </h5>
                        </div>
                        <div class="col-6 col-sm-auto ml-auto text-right pl-0">
                            <div id="dtItemCategoriesAction" class="d-inline-flex">
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
                    {{-- Shows Categories --}}
                    <div class="table-responsive fs--1">
                        <table id="dtItemCategories" class="table table-sm border-bottom">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="border-0">S.No</th>
                                    <th class="border-0 ">Item Category</th>
                                    <th class="border-0 ">Status</th>
                                    <th class="border-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($categories)
                                    @foreach($categories as $category)
                                    <tr class="bg-light">
                                        <td class="align-middle">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="align-middle font-weight-semi-bold">
                                            {{ $category->title }}
                                        </td>
                                        <td class="align-middle ">
{{--                                            @if ($category->status == 1 )--}}
{{--                                                <span class="badge rounded-capsule badge-soft-success">Active--}}
{{--                                                    <span class="ml-1 fas fa-check"></span>--}}
{{--                                                            </span>--}}
{{--                                            @else--}}
{{--                                                <span class="badge rounded-capsule badge-soft-danger">In-Active--}}
{{--                                                                <span class="ml-1 fas fa-times"></span>--}}
{{--                                                            </span>--}}
{{--                                            @endif--}}
                                        </td>
                                        <td class="align-middle text-center cursor-pointer">
{{--                                                    <span class="badge rounded-capsule badge-soft-success">Edit--}}
{{--                                                        <span class="ml-1 fas fa-edit fa-lg"></span>--}}
{{--                                                    </span>--}}
                                        </td>
                                    </tr>
                                    {{-- Display Sub Category --}}
                                    @foreach($category->children as $child)
                                        <tr>
                                            <td>{{ $loop->parent->iteration.'-'.$loop->iteration }}</td>
                                            <td class="pl-5">{{ $child->title }}</td>
                                            <td class="align-middle ">
                                                @if ($child->status == 1 )
                                                    <span class="badge rounded-capsule badge-soft-success">Active
                                                    <span class="ml-1 fas fa-check"></span>
                                                            </span>
                                                @else
                                                    <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                                <span class="ml-1 fas fa-times"></span>
                                                            </span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center cursor-pointer">
                                                    <span class="badge rounded-capsule badge-soft-success">
                                                        <a data-id="{{ $child->id }}" class="editCategory">Edit
                                                            <span class="ml-1 fas fa-edit fa-lg"></span>
                                                        </a>
                                                    </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                   @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parent Account Modal-->
        <div class="modal fade" id="parentCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Parent Category</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                class="font-weight-light" aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('items/itemsCategory/addParentCategory') }}" method="post">
                            @csrf
                            <div class="form-row ">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Parent Category</label>
                                        <input type="text" class="form-control" name="title">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 my-auto text-center">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-success">Add Category</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Edit Modal-->
        <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="fyModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fyModalLabel">Edit Category</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                                class="font-weight-light" aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="" id="frmEditCategory">
                            @method('PUT')
                            @csrf
                            <div class="form-row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Category Title</label>
                                        <input type="text" class="form-control form-control-sm" placeholder="Enter Title"
                                               name="title" id="categoryTitle">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="customRadioInline1">Status</label>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" id="categoryActive" type="radio"
                                                   name="is_active" value="1">
                                            <label class="custom-control-label" for="categoryActive">Active</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input" id="categoryInActive" type="radio"
                                                   name="is_active" value="0">
                                            <label class="custom-control-label" for="categoryInActive">In-Active</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 my-auto text-center">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-outline-success ">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

@endsection
