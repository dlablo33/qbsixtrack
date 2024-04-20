<!DOCTYPE html>
<html lang="en">
    <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <?php echo $__env->make('layouts.left-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
            </div>

            <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>
</html>
<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/layouts/master.blade.php ENDPATH**/ ?>