<?php use Illuminate\Support\Arr;

$__env->startSection('title', 'Item Categories'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>
        <link href="<?php echo e(asset('lib/select2/select2.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('footer'); ?>
        <script src="<?php echo e(asset('lib/select2/select2.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/jquery.dataTables.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables.net-responsive-bs4/dataTables.responsive.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/datatables/js/dataTableSum.js')); ?>"></script>
        <script src="<?php echo e(asset('js/number-formatter.js')); ?>"></script>
        <script src="<?php echo e(asset('js/printThis.js')); ?>"></script>

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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Items Categories</h3>

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

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

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
                    <form action="<?php echo e(route('items/itemsCategory/addItemCategory')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="form-row ">
                            <div class="col-sm-12">
                                <label>Parent Category</label>
                                <select class="form-control selectpicker" id="ddParentCategory" name="parent_id">
                                    <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($parentCategory->id); ?>"><?php echo e($parentCategory->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <?php if(isset($categories)): ?>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="bg-light">
                                        <td class="align-middle">
                                            <?php echo e($loop->iteration); ?>

                                        </td>
                                        <td class="align-middle font-weight-semi-bold">
                                            <?php echo e($category->title); ?>

                                        </td>
                                        <td class="align-middle ">









                                        </td>
                                        <td class="align-middle text-center cursor-pointer">



                                        </td>
                                    </tr>

                                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->parent->iteration.'-'.$loop->iteration); ?></td>
                                            <td class="pl-5"><?php echo e($child->title); ?></td>
                                            <td class="align-middle ">
                                                <?php if($child->status == 1 ): ?>
                                                    <span class="badge rounded-capsule badge-soft-success">Active
                                                    <span class="ml-1 fas fa-check"></span>
                                                            </span>
                                                <?php else: ?>
                                                    <span class="badge rounded-capsule badge-soft-danger">In-Active
                                                                <span class="ml-1 fas fa-times"></span>
                                                            </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle text-center cursor-pointer">
                                                    <span class="badge rounded-capsule badge-soft-success">
                                                        <a data-id="<?php echo e($child->id); ?>" class="editCategory">Edit
                                                            <span class="ml-1 fas fa-edit fa-lg"></span>
                                                        </a>
                                                    </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
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
                        <form action="<?php echo e(route('items/itemsCategory/addParentCategory')); ?>" method="post">
                            <?php echo csrf_field(); ?>
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
                            <?php echo method_field('PUT'); ?>
                            <?php echo csrf_field(); ?>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/items/items-category.blade.php ENDPATH**/ ?>
