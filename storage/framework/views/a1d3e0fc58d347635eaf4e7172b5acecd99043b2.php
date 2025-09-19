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
    <div class="container-fluid" data-layout="container">
        <nav class="navbar navbar-light navbar-glass navbar-top sticky-kit navbar-expand-lg">
            <button class="btn navbar-toggler-humburger-icon navbar-toggler mr-1 mr-sm-3"
                    type="button"
                    data-toggle="collapse"
                    data-target="#navbarStandard"
                    aria-controls="navbarStandard"
                    aria-expanded="false"
                    aria-label="Toggle Navigation">
                <span class="navbar-toggle-icon">
                  <span class="toggle-line"></span>
                </span>
            </button>
            <a class="navbar-brand mr-1 mr-sm-3" href="https://prium.github.io/falcon/v2.8.2/default/index.html">
                <div class="d-flex align-items-center">
                    <img class="mr-2" src="<?php echo e(asset('media/illustrations/falcon.png')); ?>" alt="" width="40">
                    <span class="text-sans-serif">ZARBOOKS</span>
                </div>
            </a>
            <div class="collapse navbar-collapse" id="navbarStandard">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown dropdown-on-hover">



                        <a class="nav-link dropdown-toggle" id="navbarDropdownMaintain" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa-solid fa-gears fa-lg mr-2"></i> Maintain
                        </a>
                        <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownMaintain">
                            <div class="bg-white rounded-soft py-2">
                                <a class="dropdown-item" href="/fiscalYear">
                                    <i class="fas fa-caret-right"></i> Fiscal Year
                                </a>
                                <a class="dropdown-item" href="/maintain/settings">
                                    <i class="fas fa-caret-right"></i> Settings
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-on-hover">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownReports" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa-solid fa-boxes-stacked fa-lg mr-2"></i> Inventory
                        </a>
                        <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownReports">
                            <div class="bg-white rounded-soft py-2">
                                <a class="dropdown-item" href="/items/itemsCategory">
                                    <i class="fas fa-caret-right"></i> Items Categorizes
                                </a>
                                <a class="dropdown-item" href="/items">
                                    <i class="fas fa-caret-right"></i> Items
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Items Adjustment
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-on-hover">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownReports" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fas fa-money-check fa-lg mr-2"></i> Purchase
                        </a>
                        <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownReports">
                            <div class="bg-white rounded-soft py-2">
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Vendors
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Purchase Order
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Cash Purchase
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Sales Tax Purchase
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Purchase Return
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Purchase Payments
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-on-hover">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownReports" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa-solid fa-money-bill-transfer fa-lg mr-2"></i> Sales
                        </a>
                        <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownReports">
                            <div class="bg-white rounded-soft py-2">
                                <a class="dropdown-item" href="/customer">
                                    <i class="fas fa-caret-right"></i> Customers
                                </a>
                                <a class="dropdown-item" href="/invoice">
                                    <i class="fas fa-caret-right"></i> Cash Invoice
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Sales Tax Invoice
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Sales Returns
                                </a>
                                <a class="dropdown-item" href="">
                                    <i class="fas fa-caret-right"></i> Sales Collections
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown dropdown-on-hover">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownAccounts" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa-solid fa-calculator fa-lg mr-2"></i> Accounts
                        </a>
                        <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownAccounts">
                            <div class="bg-white rounded-soft py-2">
                                <a class="dropdown-item" href="/accounts/dashboard">
                                    <i class="fas fa-caret-right"></i> Dashboard
                                </a>
                                <a class="dropdown-item" href="/accounts/chartOfAccounts">
                                    <i class="fas fa-caret-right"></i> Charts of Accounts
                                </a>
                                <a class="dropdown-item" href="/accounts/voucher/debit">
                                    <i class="fas fa-caret-right"></i> Debit Vouchers (DR)
                                </a>
                                <a class="dropdown-item" href="/accounts/voucher/credit">
                                    <i class="fas fa-caret-right"></i> Credit Vouchers (CR)
                                </a>
                                <a class="dropdown-item" href="/accounts/voucher/journal">
                                    <i class="fas fa-caret-right"></i> Journal Vouchers (JV)
                                </a>
                            </div>
                        </div>
                    </li>


























                    <li class="nav-item dropdown dropdown-on-hover">
                        <a class="nav-link dropdown-toggle"
                           id="navbarDropdownAuthentication"
                           href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa-solid fa-receipt fa-lg mr-2"></i> Reports
                        </a>
                        <div class="dropdown-menu dropdown-menu-card" aria-labelledby="navbarDropdownAuthentication">
                            <div class="card shadow-none navbar-card-auth">
                                <div class="card-body scrollbar perfect-scrollbar max-h-dropdown">
                                    <img class="position-absolute b-0 r-0"
                                         src="<?php echo e(asset('media/illustrations/corner-4.png')); ?>">
                                    <div class="row">
                                        <div class="col-6 col-xxl-3">
                                            <div class="nav-link py-1 text-900 font-weight-bold">Accounts</div>
                                            <div class="nav flex-column">



                                                <a class="dropdown-item" href="/accounts/showLedgerReport">
                                                    <i class="fas fa-caret-right"></i> General Ledger
                                                </a>
                                                <a class="dropdown-item" href="/accounts/report/trialBalance">
                                                    <i class="fas fa-caret-right"></i> Trial Balance
                                                </a>
                                                <a class="dropdown-item" href="/accounts/report/incomeStatement">
                                                    <i class="fas fa-caret-right"></i> Income Statement
                                                </a>
                                                <a class="dropdown-item" href="/accounts/report/balanceSheet">
                                                    <i class="fas fa-caret-right"></i> Balance Sheet
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav navbar-nav-icons ml-auto flex-row align-items-center">
                <li class="nav-item">
                    <a class="nav-link settings-popover" href="#settings-modal" data-toggle="modal">
                        <span class="ripple"></span>
                        <span class="fa-spin position-absolute a-0 d-flex flex-center">
                            <span class="icon-spin position-absolute a-0 d-flex flex-center">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.7369 12.3941L19.1989 12.1065C18.4459 11.7041 18.0843 10.8487 18.0843 9.99495C18.0843 9.14118 18.4459 8.28582 19.1989 7.88336L19.7369 7.59581C19.9474 7.47484 20.0316 7.23291 19.9474 7.03131C19.4842 5.57973 18.6843 4.28943 17.6738 3.20075C17.5053 3.03946 17.2527 2.99914 17.0422 3.12011L16.393 3.46714C15.6883 3.84379 14.8377 3.74529 14.1476 3.3427C14.0988 3.31422 14.0496 3.28621 14.0002 3.25868C13.2568 2.84453 12.7055 2.10629 12.7055 1.25525V0.70081C12.7055 0.499202 12.5371 0.297594 12.2845 0.257272C10.7266 -0.105622 9.16879 -0.0653007 7.69516 0.257272C7.44254 0.297594 7.31623 0.499202 7.31623 0.70081V1.23474C7.31623 2.09575 6.74999 2.8362 5.99824 3.25599C5.95774 3.27861 5.91747 3.30159 5.87744 3.32493C5.15643 3.74527 4.26453 3.85902 3.53534 3.45302L2.93743 3.12011C2.72691 2.99914 2.47429 3.03946 2.30587 3.20075C1.29538 4.28943 0.495411 5.57973 0.0322686 7.03131C-0.051939 7.23291 0.0322686 7.47484 0.242788 7.59581L0.784376 7.8853C1.54166 8.29007 1.92694 9.13627 1.92694 9.99495C1.92694 10.8536 1.54166 11.6998 0.784375 12.1046L0.242788 12.3941C0.0322686 12.515 -0.051939 12.757 0.0322686 12.9586C0.495411 14.4102 1.29538 15.7005 2.30587 16.7891C2.47429 16.9504 2.72691 16.9907 2.93743 16.8698L3.58669 16.5227C4.29133 16.1461 5.14131 16.2457 5.8331 16.6455C5.88713 16.6767 5.94159 16.7074 5.99648 16.7375C6.75162 17.1511 7.31623 17.8941 7.31623 18.7552V19.2891C7.31623 19.4425 7.41373 19.5959 7.55309 19.696C7.64066 19.7589 7.74815 19.7843 7.85406 19.8046C9.35884 20.0925 10.8609 20.0456 12.2845 19.7729C12.5371 19.6923 12.7055 19.4907 12.7055 19.2891V18.7346C12.7055 17.8836 13.2568 17.1454 14.0002 16.7312C14.0496 16.7037 14.0988 16.6757 14.1476 16.6472C14.8377 16.2446 15.6883 16.1461 16.393 16.5227L17.0422 16.8698C17.2527 16.9907 17.5053 16.9504 17.6738 16.7891C18.7264 15.7005 19.4842 14.4102 19.9895 12.9586C20.0316 12.757 19.9474 12.515 19.7369 12.3941ZM10.0109 13.2005C8.1162 13.2005 6.64257 11.7893 6.64257 9.97478C6.64257 8.20063 8.1162 6.74905 10.0109 6.74905C11.8634 6.74905 13.3792 8.20063 13.3792 9.97478C13.3792 11.7893 11.8634 13.2005 10.0109 13.2005Z" fill="#2A7BE4"></path>
                                </svg>
                            </span>
                        </span>
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-on-hover">
                    <a class="nav-link pr-0" id="navbarDropdownUser" href="#"
                        role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <div class="avatar avatar-xl">
                            <img class="rounded-circle" src="<?php echo e(asset('media/team/3-thumb.png')); ?>" alt="">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="navbarDropdownUser">
                        <div class="bg-white rounded-soft py-2">
                            <a class="dropdown-item font-weight-bold text-warning" href="#!"><svg class="svg-inline--fa fa-crown fa-w-20 mr-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="crown" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5.4 5.1.8 7.7.8 26.5 0 48-21.5 48-48s-21.5-48-48-48z"></path></svg><!-- <span class="fas fa-crown mr-1"></span> Font Awesome fontawesome.com --><span>Go Pro</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="https://prium.github.io/falcon/v2.8.2/default/pages/profile.html">Profile &amp; account</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="https://prium.github.io/falcon/v2.8.2/default/pages/settings.html">Change Password</a>
                            <a class="dropdown-item" href="https://prium.github.io/falcon/v2.8.2/default/authentication/basic/logout.html">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <div class="content">

            <?php $__env->startSection('content'); ?>
            <?php echo $__env->yieldSection(); ?>

        </div>

        <footer>
            <div class="row no-gutters justify-content-between fs--1 mt-4 mb-3">
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 text-600">Powered by Zayonix Systems
                        <span class="d-none d-sm-inline-block">| </span>
                        <br class="d-sm-none"> 2022 ©
                        <a href="http://www.zayonix.com/">Zayonix Systems</a>
                    </p>
                </div>
                <div class="col-12 col-sm-auto text-center">
                    <p class="mb-0 text-600">v2.8.2</p>
                </div>
            </div>
        </footer>
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
<?php /**PATH F:\Projects\ZarBooks\resources\views/layout.blade.php ENDPATH**/ ?>