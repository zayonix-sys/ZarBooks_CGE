@extends('layout')
@section('title', 'Item Show')

@section('content')

    @push('head')
        <link href="{{asset('lib/select2/select2.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')}}" rel="stylesheet">
        <link href="{{asset('lib/fancybox/jquery.fancybox.min.css')}}" rel="stylesheet">
        <link href="{{asset('lib/owl.carousel/owl.carousel.css')}}" rel="stylesheet">

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
        <script src="{{asset('lib/owl.carousel/owl.carousel.js')}}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.owl-carousel').owlCarousel({
                    // loop:true,
                    autoWidth: true,
                    margin:10,
                    nav:true,
                })
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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Item</h3>

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

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="product-slider position-relative">
                                <div class="owl-carousel owl-theme position-lg-absolute h-100 product-images owl-loaded owl-drag">
                                    <div class="owl-stage-outer">
                                        <div class="owl-stage"
                                             style="transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 3003px;">
                                            @forelse($item[0]->images as $key => $file)
                                                    <div class="owl-item current mr-2">
                                                        <div class="item h-100">
                                                            @if( str($file->file_name)->explode('.')->last()  != 'pdf')
                                                                <a class="thumbnail fancybox" data-fancybox="gallery" data-caption="{{ $file->file_name }}"
                                                                   href="{{ asset('/storage/item_images/'.$file->file_name) }}">
                                                                    <img class="rounded h-100 border border-400" alt=""
                                                                          src="{{ asset('/storage/item_images/'.$file->file_name) }}"
                                                                         />
                                                                </a>
{{--                                                                @else--}}
{{--                                                                <a class="thumbnail fancybox" data-fancybox="gallery" data-caption="{{ $file->file_name }}"--}}
{{--                                                                   href="{{ asset('/media/default/no-image.png') }}">--}}
{{--                                                                    <img class="rounded h-100 border border-400" alt=""--}}
{{--                                                                         src="{{ asset('/media/default/no-image.png') }}"--}}
{{--                                                                    />--}}
{{--                                                                </a>--}}
                                                            @endif
                                                        </div>
                                                    </div>
                                            @empty
                                                <div class="owl-item current mr-2 text-center">
                                                    <div class="item h-100">
                                                        <a class="thumbnail fancybox" data-fancybox="gallery" data-caption="No-Image"
                                                           href="{{ asset('/media/default/no-image.png') }}">
                                                            <img class="rounded h-100 border border-400" alt=""
                                                                 src="{{ asset('/media/default/no-image.png') }}"
                                                            />
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6" style="min-height: 300px">
                            <h2 class="font-weight-semi-bold fs-2">
                                {{ $item[0]->name }}
                            </h2>
                            <span class="fs--1 mb-2 text-info font-weight-semi-bold">Caetgory: </span><strong>{{ $item[0]->category->title }}</strong>
                            <span class="fs--1 mb-2 d-block font-weight-semi-bold text-warning mt-3">Catalogue: </span>
                            @foreach($item[0]->images as $key => $file)
                                @if( str($file->file_name)->explode('.')->last()  == 'pdf')
                                    <a href="{{ asset('/storage/item_images/'.$file->file_name) }}">
                                        <i class="fa-solid fa-file-pdf fa-xl text-danger"></i>
                                        <span class="text-muted">{{ $file->file_name }}</span>
                                    </a>
                                @endif
                            @endforeach
                            <p class="fs--1 mt-3">
                                <span class="fs--1 mb-2 text-info font-weight-semi-bold text-wrap">Description: </span>
                                {{ $item[0]->description }}
                            </p>
                            <p class="fs--1 mb-1 mt-3">
                                <span>Purchase Price: </span><strong>Rs. {{ number_format($item[0]->purchase_price) }}</strong>
                            </p>
                            <p class="fs--1">
                                Sale Price: <strong class="text-success">Rs. {{ number_format($item[0]->sale_price) }}</strong>
                            </p>
                            <div class="row mt-3">
                                <div class="col-auto">
                                    <a class="btn btn-sm btn-primary" href="/item/edit/{{$item[0]->id}}">
                                        <span class="fas fa-edit"></span> Edit Item
                                    </a>
                                </div>
                                <div class="col-sm-auto pl-3 pl-sm-0">
                                    <a class="btn btn-sm btn-outline-danger border-300 mr-2 mt-2 mt-sm-0" href="/items">
                                       <span class="far fa-arrow-alt-circle-left"></span> Back </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Details Tables-->
    {{--    <div class="row mt-3">--}}
    {{--        <div class="col-12">--}}
    {{--            <div class="card">--}}
    {{--                <div class="card-header bg-dark">--}}
    {{--                    <div class="row align-items-center">--}}
    {{--                        <div class="col">--}}
    {{--                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">--}}
    {{--                                Edit Item--}}
    {{--                            </h5>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="card-body fs--1">--}}
    {{--                    <form action="/item/update/{{ $item[0]->id }}" method="post" enctype="multipart/form-data">--}}
    {{--                        @csrf--}}
    {{--                        @method('PUT')--}}
    {{--                        <div class="d-flex">--}}
    {{--                            <div class="col-8">--}}
    {{--                                <div class="card">--}}
    {{--                                    <div class="card-body">--}}
    {{--                                        <div class="form-row">--}}
    {{--                                            <div class="col-sm-6 ">--}}
    {{--                                                <label>Item Name</label>--}}
    {{--                                                <input type="text" name="name" class="form-control fs--1"--}}
    {{--                                                       value="{{ $item[0]->name }}">--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-sm-4">--}}
    {{--                                                <label>Model No. / Part No.</label>--}}
    {{--                                                <input type="text" name="part_no" class="form-control fs--1"--}}
    {{--                                                       value="{{ $item[0]->part_no }}">--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-sm-2">--}}
    {{--                                                <label>Unit</label>--}}
    {{--                                                <input type="text" name="unit" class="form-control fs--1"--}}
    {{--                                                       value="{{ $item[0]->unit }}">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="form-row mt-4">--}}
    {{--                                            <div class="col-sm-4">--}}
    {{--                                                <label>Parent Category</label>--}}
    {{--                                                <select class="form-control selectpicker" id="ddParentCategory"--}}
    {{--                                                        name="parent_id">--}}
    {{--                                                    @foreach($parentCategories as $parentCategory)--}}
    {{--                                                        <option--}}
    {{--                                                            value="{{ $parentCategory->id }}"--}}
    {{--                                                            {{ ($parentCategory->id === $item[0]->category->parent_id) ? 'selected' : ''}}>--}}
    {{--                                                            {{ $parentCategory->title }}--}}
    {{--                                                        </option>--}}
    {{--                                                    @endforeach--}}
    {{--                                                </select>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-sm-4">--}}
    {{--                                                <label>Sub Category</label>--}}
    {{--                                                <select class="form-control selectpicker" id="ddSubCategories"--}}
    {{--                                                        name="item_category_id">--}}
    {{--                                                    --}}{{-- Dynamically Display Sub Categories --}}
    {{--                                                </select>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-sm-2">--}}
    {{--                                                <label>Purchase Price</label>--}}
    {{--                                                <input type="number" name="purchase_price" class="form-control fs--1"--}}
    {{--                                                       value="{{ $item[0]->purchase_price }}">--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-sm-2">--}}
    {{--                                                <label>Sale Price</label>--}}
    {{--                                                <input type="number" name="sale_price" class="form-control fs--1"--}}
    {{--                                                       value="{{ $item[0]->sale_price }}">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="form-row mt-4">--}}
    {{--                                            <div class="col-sm-12">--}}
    {{--                                                <label>Description</label>--}}
    {{--                                                <textarea class="form-control fs--1" rows="5"--}}
    {{--                                                          name="description">{{ $item[0]->description }}</textarea>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="form-row mt-4">--}}
    {{--                                            <div class="col-sm-4">--}}
    {{--                                                <label>Select Image/Catalogue</label>--}}
    {{--                                                <input type="file" name="image_files[]" class="form-control-file" multiple>--}}
    {{--                                            </div>--}}
    {{--                                            <div class="col-sm-4 mt-3">--}}
    {{--                                                <button type="submit" class="btn btn-outline-warning" id="btnAdd">Update Item--}}
    {{--                                                </button>--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}

    {{--                            </div>--}}
    {{--                            <div class="col-4">--}}
    {{--                                <div class="card">--}}
    {{--                                    <div class="card-header">--}}
    {{--                                        <h5 class="font-weight-semi-bold">Attached Files</h5>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="card-body pt-0">--}}
    {{--                                        <div class="table-responsive fs--1">--}}
    {{--                                            <table id="dtItems" class="table table-sm border-bottom table-hover">--}}
    {{--                                                <thead class="bg-200 text-900">--}}
    {{--                                                <tr>--}}
    {{--                                                    <th class="align-middle white-space-nowrap no-sort">--}}
    {{--                                                        <div class="custom-control custom-checkbox">--}}
    {{--                                                            <input class="custom-control-input checkbox-bulk-select"--}}
    {{--                                                                   id="checkAllIds" type="checkbox">--}}
    {{--                                                            <label class="custom-control-label" for="checkAllIds"></label>--}}
    {{--                                                        </div>--}}
    {{--                                                    </th>--}}
    {{--                                                    <th class="border-0 ">Select to Remove</th>--}}
    {{--                                                </tr>--}}
    {{--                                                </thead>--}}
    {{--                                                <tbody>--}}
    {{--                                                @foreach($item[0]->images as $key => $file)--}}
    {{--                                                    <tr>--}}
    {{--                                                        <td class="align-middle white-space-nowrap">--}}
    {{--                                                            <div class="custom-control custom-checkbox">--}}
    {{--                                                                <input class="custom-control-input checkbox-bulk-select-target"--}}
    {{--                                                                       type="checkbox"--}}
    {{--                                                                       name="chkFileIds[]"--}}
    {{--                                                                       value="{{ $file->id }}"--}}
    {{--                                                                       id="checkbox{{$loop->index}}">--}}
    {{--                                                                <label class="custom-control-label" for="checkbox{{$loop->index}}"></label>--}}
    {{--                                                            </div>--}}
    {{--                                                        </td>--}}
    {{--                                                        <td>--}}
    {{--                                                            @if( str($file->file_name)->explode('.')->last()  == 'pdf')--}}
    {{--                                                                <a href="{{ asset('/storage/item_images/'.$file->file_name) }}">--}}
    {{--                                                                    <i class="fa-solid fa-file-pdf fa-xl text-danger"></i>--}}
    {{--                                                                    <span class="text-muted">{{ $file->file_name }}</span>--}}
    {{--                                                                </a>--}}
    {{--                                                            @else--}}
    {{--                                                                <a class="thumbnail fancybox" data-fancybox="gallery" data-caption="{{ $file->file_name }}"--}}
    {{--                                                                   href="{{ asset('/storage/item_images/'.$file->file_name) }}">--}}
    {{--                                                                    <img class="img-responsive rounded" alt=""--}}
    {{--                                                                         --}}{{-- src="{{ asset('/storage/item_images/'.$file->file_name) }}"--}}
    {{--                                                                         width="0px" height="0px"/>--}}
    {{--                                                                    <i class="fa-solid fa-images fa-lg text-success"></i>--}}
    {{--                                                                    <span class="text-muted">{{ $file->file_name }}</span>--}}
    {{--                                                                </a>--}}
    {{--                                                            @endif--}}

    {{--                                                        </td>--}}
    {{--                                                    </tr>--}}
    {{--                                                @endforeach--}}
    {{--                                                </tbody>--}}
    {{--                                            </table>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                </div>--}}

    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </form>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
@endsection
