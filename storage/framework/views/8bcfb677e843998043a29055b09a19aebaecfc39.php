<?php use Illuminate\Support\Arr;

$__env->startSection('title', __('Service Unavailable')); ?>
<?php $__env->startSection('code', '503'); ?>
<?php $__env->startSection('message', __('Service Unavailable')); ?>

<?php echo $__env->make('errors::minimal', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\vendor\laravel\framework\src\Illuminate\Foundation\Exceptions/views/503.blade.php ENDPATH**/ ?>
