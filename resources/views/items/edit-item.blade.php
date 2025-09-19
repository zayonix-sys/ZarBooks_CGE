@extends('layout')
@section('title', 'Edit Item')

@section('content')

    @push('head')
        <link href="{{asset('lib/select2/select2.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')}}" rel="stylesheet">
        <link href="{{asset('lib/fancybox/jquery.fancybox.min.css')}}" rel="stylesheet">

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
        <script src="{{asset('lib/fancybox/jquery.fancybox.min.js')}}"></script>

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

                //Generate Parent Account DropDown on Basis of Account Group
                $('#ddParentCategory').on('change focus load', function (e) {
                    e.preventDefault();

                    let $id = $(this).val();
                    let url = "{{ route('items/getSubCategories', ':id') }}";
                    url = url.replace(':id', $id);

                    $.get(url, function (subCategories) {
                        {{--console.log(subCategories);--}}
                        {{--console.log({{$item[0]->category->id}});--}}
                        $("#ddSubCategories").empty().trigger('change');
                        for (let i = 0; i < subCategories.length; i++) {
                            $('#ddSubCategories').append(
                                '<option value=' +subCategories[i].id+'>' +subCategories[i].title + '</option>'
                            );

                            //Set Selected Value from Controller
                            $('#ddSubCategories').val({{$item[0]->category->id}});
                            $('#ddSubCategories').select2().trigger('change');
                        }
                    });
                }).triggerHandler('load');

                //Bulk Selection
                $("#checkAllIds").on('click', function () {
                    $('input:checkbox').not(this).prop('checked', this.checked);
                });

                {{--$('#ddParentCategory').select2('data', {id: '{{ $item[0]->category->parent_id }}', text: '{{ $item[0]->category->title }}'});--}}
                {{--alert({{ $item[0]->category->parent_id }});--}}
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

    <!-- Category Details Tables-->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Edit Item
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body fs--1">
                    <form action="/item/update/{{ $item[0]->id }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="d-flex">
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-sm-6 ">
                                                <label>Item Name</label>
                                                <input type="text" name="name" class="form-control fs--1"
                                                       value="{{ $item[0]->name }}">
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Model No. / Part No.</label>
                                                <input type="text" name="part_no" class="form-control fs--1"
                                                       value="{{ $item[0]->part_no }}">
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Unit</label>
                                                <input type="text" name="unit" class="form-control fs--1"
                                                       value="{{ $item[0]->unit }}">
                                            </div>
                                        </div>
                                        <div class="form-row mt-4">
                                            <div class="col-sm-4">
                                                <label>Parent Category</label>
                                                <select class="form-control selectpicker" id="ddParentCategory"
                                                        name="parent_id">
                                                    @foreach($parentCategories as $parentCategory)
                                                        <option
                                                            value="{{ $parentCategory->id }}"
                                                            {{ ($parentCategory->id === $item[0]->category->parent_id) ? 'selected' : ''}}>
                                                            {{ $parentCategory->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label>Sub Category</label>
                                                <select class="form-control selectpicker" id="ddSubCategories"
                                                        name="item_category_id">
                                                    {{-- Dynamically Display Sub Categories --}}
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Purchase Price</label>
                                                <input type="number" name="purchase_price" class="form-control fs--1"
                                                       value="{{ $item[0]->purchase_price }}">
                                            </div>
                                            <div class="col-sm-2">
                                                <label>Sale Price</label>
                                                <input type="number" name="sale_price" class="form-control fs--1"
                                                       value="{{ $item[0]->sale_price }}">
                                            </div>
                                        </div>
                                        <div class="form-row mt-4">
                                            <div class="col-sm-12">
                                                <label>Description</label>
                                                <textarea class="form-control fs--1" rows="5"
                                                          name="description">{{ $item[0]->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-row mt-4">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="custom-control-input" id="itemActive" type="radio" name="status"
                                                               value="1" {{ ($item[0]->status) == 1 ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="itemActive">Active</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="custom-control-input" id="itemInActive" type="radio"
                                                               name="status" value="0" {{ ($item[0]->status) == 0 ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="itemInActive">In-Active</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <label>Select Image/Catalogue</label>
                                                <input type="file" name="image_files[]" class="form-control-file" multiple>
                                            </div>
                                            <div class="col-sm-3 mt-3">
                                                <button type="submit" class="btn btn-outline-warning btn-block" id="btnAdd">Update Item
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="font-weight-semi-bold">Attached Files</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="table-responsive fs--1">
                                            <table id="dtItems" class="table table-sm border-bottom table-hover">
                                                <thead class="bg-200 text-900">
                                                <tr>
                                                    <th class="align-middle white-space-nowrap no-sort">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input checkbox-bulk-select"
                                                                   id="checkAllIds" type="checkbox">
                                                            <label class="custom-control-label" for="checkAllIds"></label>
                                                        </div>
                                                    </th>
                                                    <th class="border-0 ">Select to Remove</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($item[0]->images as $key => $file)
                                                    <tr>
                                                        <td class="align-middle white-space-nowrap">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input checkbox-bulk-select-target"
                                                                       type="checkbox"
                                                                       name="chkFileIds[]"
                                                                       value="{{ $file->id }}"
                                                                       id="checkbox{{$loop->index}}">
                                                                <label class="custom-control-label" for="checkbox{{$loop->index}}"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if( str($file->file_name)->explode('.')->last()  == 'pdf')
                                                                <a href="{{ asset('/storage/item_images/'.$file->file_name) }}">
                                                                    <i class="fa-solid fa-file-pdf fa-xl text-danger"></i>
                                                                    <span class="text-muted">{{ $file->file_name }}</span>
                                                                </a>
                                                            @else
                                                                <a class="thumbnail fancybox" data-fancybox="gallery" data-caption="{{ $file->file_name }}"
                                                                   href="{{ asset('/storage/item_images/'.$file->file_name) }}">
                                                                    <img class="img-responsive rounded" alt=""
                                                                         {{-- src="{{ asset('/storage/item_images/'.$file->file_name) }}"--}}
                                                                         width="0px" height="0px"/>
                                                                    <i class="fa-solid fa-images fa-lg text-success"></i>
                                                                    <span class="text-muted">{{ $file->file_name }}</span>
                                                                </a>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
