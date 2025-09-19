<?php use Illuminate\Support\Arr;

$__env->startSection('title', __('Not Found')); ?>
<?php $__env->startSection('code', '404'); ?>
<?php $__env->startSection('message', __('Not Found')); ?>

<?php echo $__env->make('errors::minimal', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Projects\ZarBooks\vendor\laravel\framework\src\Illuminate\Foundation\Exceptions/views/404.blade.php ENDPATH**/ ?>
