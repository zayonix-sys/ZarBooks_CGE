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

                //Generate Parent Account DropDown on Basis of Account Group
                $('#ddParentCategory').on('change', function (e) {
                    e.preventDefault();

                    var $id = $(this).val();
                    let url = "<?php echo e(route('items/getSubCategories', ':id')); ?>";
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
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="fs-2 mb-0 text-nowrap py-2 py-xl-0 text-warning font-weight-semi-bold">
                                Add Item
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body fs--1">
                    <form action="/items" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="d-flex">
                            <div class="col-8">
                                <div class="form-row ">
                                    <div class="col-sm-6">
                                        <label>Item Name</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Model No. / Part No.</label>
                                        <input type="text" name="part_no" class="form-control" value="<?php echo e(old('part_no')); ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Unit</label>
                                        <input type="text" name="unit" class="form-control" value="<?php echo e(old('unit')); ?>">
                                    </div>
                                </div>
                                <div class="form-row mt-4">
                                    <div class="col-sm-4">
                                        <label>Parent Category</label>
                                        <select class="form-control selectpicker" id="ddParentCategory"
                                                name="parent_id">
                                            <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($parentCategory->id); ?>"><?php echo e($parentCategory->title); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Sub Category</label>
                                        <select class="form-control selectpicker" id="ddSubCategories" name="item_category_id">

                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Purchase Price</label>
                                        <input type="number" name="purchase_price" class="form-control" value="<?php echo e(old('purchase_price')); ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Sale Price</label>
                                        <input type="number" name="sale_price" class="form-control" value="<?php echo e(old('sale_price')); ?>">
                                    </div>
                                </div>
                                <div class="form-row mt-4">
                                    <div class="col-sm-4">
                                        <label>Select Image/Catalogue</label>
                                        <input type="file" name="image_files[]" class="form-control-file" multiple>
                                    </div>
                                    <div class="col-sm-4 mt-3">
                                        <button type="submit" class="btn btn-outline-success" id="btnAdd">Add Items</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <textarea class="form-control" rows="5" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
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
        <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="fyModalLabel"
             aria-hidden="true">
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
                                        <input type="text" class="form-control form-control-sm"
                                               placeholder="Enter Title"
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

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/items/edit-item.blade.php ENDPATH**/ ?>
