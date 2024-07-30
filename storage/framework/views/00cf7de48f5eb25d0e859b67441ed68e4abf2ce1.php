<?php if($message = Session::get('success')): ?>
<div style="color:green;">	
        <strong><?php echo e($message); ?></strong>
</div>
<?php endif; ?>
<?php if($message = Session::get('error')): ?>
<div style="color:red;">	
        <strong><?php echo e($message); ?></strong>
</div>
<?php endif; ?>



<?php if($errors->any()): ?>
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	Please check the form below for errors
</div>
<?php endif; ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/qb-flash-message.blade.php ENDPATH**/ ?>