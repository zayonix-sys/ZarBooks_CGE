

<?php use Illuminate\Support\Arr;

$__env->startSection('content'); ?>

<div class="card mb-3">
  <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url('<?php echo e(asset('media/illustrations/corner-4.png')); ?>');"></div>
  <!--/.bg-holder-->
  <div class="card-body">
    <div class="row">
      <div class="col-lg-8">
        <h3 class="mb-0">Dashboard</h3>
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

                  <?php else: ?> <?php echo e(ucwords(str_replace('-',' ',Request::segment($i)))); ?>

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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\resources\views/accounts\dashboard.blade.php ENDPATH**/ ?>
