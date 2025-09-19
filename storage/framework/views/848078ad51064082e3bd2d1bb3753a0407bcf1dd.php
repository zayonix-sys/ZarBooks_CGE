<?php use Illuminate\Support\Arr;

if(session()->has('notify.message')): ?>

    <?php echo $__env->make('notify::notifications.toast', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('notify::notifications.smiley', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('notify::notifications.drakify', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('notify::notifications.connectify', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('notify::notifications.emotify', Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php endif; ?>

<?php echo e(session()->forget('notify.message')); ?>


<script>
    var notify = {
        timeout: "<?php echo e(config('notify.timeout')); ?>",
    }
</script>
<?php /**PATH F:\Projects\ZarBooks\vendor\mckenziearts\laravel-notify\src/../resources/views/components/notify.blade.php ENDPATH**/ ?>
