<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>Zarbooks | <?php echo $__env->yieldContent('title'); ?></title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('media/favicons/apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('media/favicons/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('media/favicons/favicon-16x16.png')); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('media/favicons/favicon.ico')); ?>">
    <!-- <link rel="manifest" href="<?php echo e(asset('media/favicons/manifest.json')); ?>"> -->
    <meta name="msapplication-TileImage" content="<?php echo e(asset('media/favicons/mstile-150x150.png')); ?>">
    <meta name="theme-color" content="#ffffff">

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:100,200,300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="<?php echo e(asset('lib/perfect-scrollbar/perfect-scrollbar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('lib/prism/prism.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('lib/fontawesome6/css/all.min.css')); ?>" rel="stylesheet">

    <?php echo $__env->yieldPushContent('head'); ?>

    <link href="<?php echo e(asset('css/theme.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/customStyle.css')); ?>" rel="stylesheet">
    <?php echo notifyCss(); ?>
</head>

<body>
<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
    <div class="container" data-layout="container">
        <div class="content">
            <div class="row flex-center min-vh-100 py-6">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="d-flex flex-center mb-4">
                        <img class="mr-2" src="<?php echo e(asset('media/illustrations/ZS.png')); ?>" alt="" width="40">
                        <span class="text-green-600 font-weight-extra-bold fs-5 d-inline-block ">ZARBOOKS</span>
                    </div>
                    <div class="card">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="card-body p-4 p-sm-5">
                            <form action="<?php echo e(route('login.auth')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <input class="form-control" name="email" type="email" placeholder="Email address" value="<?php echo e(old('email')); ?>">
                                    <?php if($errors->has('email')): ?>
                                        <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group pt-3">
                                    <input class="form-control" name="password" type="password" placeholder="Password">
                                    <?php if($errors->has('password')): ?>
                                        <span class="text-danger"><?php echo e($errors->first('password')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group pt-3">
                                    <label for="ddParentAccounts">Select Fiscal Year</label>
                                    <select class="form-control selectpicker fs--1" id="ddParentAccounts" name="fiscal_year">
                                        <?php $__empty_1 = true; $__currentLoopData = $fiscalYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fiscalYear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($fiscalYear->id); ?>"><?php echo e($fiscalYear->fy_title); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <option value="">No Record</option>
                                        <?php endif; ?>
                                    </select>



                                </div>
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" name="remember_me" type="checkbox" id="basic-checkbox" checked="checked">
                                            <label class="custom-control-label" for="basic-checkbox">Remember me</label></div>
                                    </div>
                                    <div class="col-auto">
                                        <a class="fs--1" href="../../authentication/basic/forgot-password.html">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="form-group pt-3">
                                    <button class="btn btn-primary btn-block mt-3" type="submit" name="submit">Log in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

<script>
    var isFluid = JSON.parse(localStorage.getItem('isFluid'));
    if (isFluid) {
        var container = document.querySelector('[data-layout]');
        container.classList.remove('container');
        container.classList.add('container-fluid');
    }
</script>

<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/config.navbar-vertical.min.js')); ?>"></script>

<script src="<?php echo e(asset('lib/fontawesome6/js/all.min.js')); ?>"></script>
<script src="<?php echo e(asset('lib/stickyfilljs/stickyfill.min.js')); ?>"></script>
<script src="<?php echo e(asset('lib/sticky-kit/sticky-kit.min.js')); ?>"></script>
<script src="<?php echo e(asset('lib/is_js/is.min.js')); ?>"></script>
<script src="<?php echo e(asset('lib/lodash/lodash.min.js')); ?>"></script>
<script src="<?php echo e(asset('lib/perfect-scrollbar/perfect-scrollbar.js')); ?>"></script>



<script src="<?php echo e(asset('lib/prism/prism.js')); ?>"></script>
<script src="<?php echo e(asset('js/custom-file-input.min.js')); ?>"></script>


<?php echo $__env->yieldPushContent('footer'); ?>
<?php if (isset($component)) { $__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c = $component; } ?>
<?php $component = $__env->getContainer()->make(Mckenziearts\Notify\NotifyComponent::class, [] + (isset($attributes) ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('notify-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $constructor = (new ReflectionClass(Mckenziearts\Notify\NotifyComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c)): ?>
<?php $component = $__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c; ?>
<?php unset($__componentOriginalf6d1e1ab7a8df2de5d0bdc28df8775f180a35b0c); ?>
<?php endif; ?>
<?php echo notifyJs(); ?>
</body>

</html>
<?php /**PATH I:\OneDrive\Zeeshan Programming\ZarBooks\resources\views/auth/login.blade.php ENDPATH**/ ?>