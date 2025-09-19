<?php use Illuminate\Support\Arr;

$__env->startSection('title', 'Item Show'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>
        <link href="<?php echo e(asset('lib/select2/select2.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables-bs4/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/datatables.net-responsive-bs4/responsive.bootstrap4.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/fancybox/jquery.fancybox.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('lib/owl.carousel/owl.carousel.css')); ?>" rel="stylesheet">

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
        <script src="<?php echo e(asset('lib/fancybox/jquery.fancybox.min.js')); ?>"></script>
        <script src="<?php echo e(asset('lib/owl.carousel/owl.carousel.js')); ?>"></script>

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
                            <h3 class="mb-0 font-weight-bold text-2xl text-warning">Item</h3>

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
                                            <?php $__empty_1 = true; $__currentLoopData = $item[0]->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <div class="owl-item current mr-2">
                                                        <div class="item h-100">
                                                            <?php if( str($file->file_name)->explode('.')->last()  != 'pdf'): ?>
                                                                <a class="thumbnail fancybox" data-fancybox="gallery" data-caption="<?php echo e($file->file_name); ?>"
                                                                   href="<?php echo e(asset('/storage/item_images/'.$file->file_name)); ?>">
                                                                    <img class="rounded h-100 border border-400" alt=""
                                                                          src="<?php echo e(asset('/storage/item_images/'.$file->file_name)); ?>"
                                                                         />
                                                                </a>







                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <div class="owl-item current mr-2 text-center">
                                                    <div class="item h-100">
                                                        <a class="thumbnail fancybox" data-fancybox="gallery" data-caption="No-Image"
                                                           href="<?php echo e(asset('/media/default/no-image.png')); ?>">
                                                            <img class="rounded h-100 border border-400" alt=""
                                                                 src="<?php echo e(asset('/media/default/no-image.png')); ?>"
                                                            />
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6" style="min-height: 300px">
                            <h2 class="font-weight-semi-bold fs-2">
                                <?php echo e($item[0]->name); ?>

                            </h2>
                            <span class="fs--1 mb-2 text-info font-weight-semi-bold">Caetgory: </span><strong><?php echo e($item[0]->category->title); ?></strong>
                            <span class="fs--1 mb-2 d-block font-weight-semi-bold text-warning mt-3">Catalogue: </span>
                            <?php $__currentLoopData = $item[0]->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if( str($file->file_name)->explode('.')->last()  == 'pdf'): ?>
                                    <a href="<?php echo e(asset('/storage/item_images/'.$file->file_name)); ?>">
                                        <i class="fa-solid fa-file-pdf fa-xl text-danger"></i>
                                        <span class="text-muted"><?php echo e($file->file_name); ?></span>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <p class="fs--1 mt-3">
                                <span class="fs--1 mb-2 text-info font-weight-semi-bold text-wrap">Description: </span>
                                <?php echo e($item[0]->description); ?>

                            </p>
                            <p class="fs--1 mb-1 mt-3">
                                <span>Purchase Price: </span><strong>Rs. <?php echo e(number_format($item[0]->purchase_price)); ?></strong>
                            </p>
                            <p class="fs--1">
                                Sale Price: <strong class="text-success">Rs. <?php echo e(number_format($item[0]->sale_price)); ?></strong>
                            </p>
                            <div class="row mt-3">
                                <div class="col-auto">
                                    <a class="btn btn-sm btn-primary" href="/item/edit/<?php echo e($item[0]->id); ?>">
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



























































































































































<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/items/show-item.blade.php ENDPATH**/ ?>
