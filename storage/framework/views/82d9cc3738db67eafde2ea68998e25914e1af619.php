<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startPush('head'); ?>
        <link href="<?php echo e(asset('lib/select2/select2.min.css')); ?>" rel="stylesheet">
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('footer'); ?>
        <script src="<?php echo e(asset('lib/select2/select2.min.js')); ?>"></script>
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

                $('select').select2({
                    theme: 'bootstrap4',
                    selectOnClose: true
                });

            });
        </script>

    <?php $__env->stopPush(); ?>

    <div class="card mb-3">
        <div class="bg-holder d-none d-lg-block bg-card"
             style="background-image:url('<?php echo e(asset('media/illustrations/corner-4.png')); ?>');">
        </div>
        <!--/.bg-holder-->
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="mb-0 font-weight-bold text-2xl text-warning">System Configuration</h2>
                    <nav aria-label="breadcrumb">
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

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tabs nav -->
                    <div class="nav nav-pills nav-fill" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link mx-3 p-3 shadow active" id="v-pills-home-tab" data-toggle="pill"
                           href="#tbVouchers" role="tab" aria-controls="v-pills-home" aria-selected="true">
                            <i class="fas fa-dollar-sign fa-lg mr-2"></i>
                            <span class="font-weight-bold text-uppercase">Cash/Bank Book Account</span></a>

                        
                        
                        
                        

                        
                        
                        
                        

                        
                        
                        
                        

                        
                        
                        
                        
                    </div>

                    <!-- Tabs content -->
                    <div class="tab-content mx-3 my-3" id="v-pills-tabContent">

                        
                        <div class="tab-pane fade show active p-3" id="tbVouchers"
                             role="tabpanel" aria-labelledby="v-pills-home-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if($errors->any()): ?>
                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($error); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action=" <?php echo e(route('storeCashPaymentAccount')); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <div class="form-row ">
                                                    <div class="col-sm-12">
                                                        <h2 class="mb-0 font-weight-bold">Select Cash and Bank Book
                                                            Account</h2>
                                                        <br>
                                                        <?php $__currentLoopData = $parentAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assetsAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input"
                                                                       id="<?php echo e('chkDr'.$loop->index); ?>" type="checkbox"
                                                                       value="<?php echo e($assetsAccount->id); ?>"
                                                                       name="cash_payment[]">
                                                                <label class="custom-control-label"
                                                                       for="<?php echo e('chkDr'.$loop->index); ?>"><?php echo e($assetsAccount->title); ?></label>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <br>
                                                        <div class="my-auto">
                                                            <button type="submit" class="btn btn-outline-success"
                                                                    id="btnSaveDr">Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

































                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH I:\OneDrive\Zeeshan Programming\ZarBooks\resources\views/maintain/settings.blade.php ENDPATH**/ ?>