

<?php $__env->startSection('content'); ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Transporte</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('transporte.index')); ?>">Transportes</a></li>
                    <li class="breadcrumb-item active">Editar Transporte</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo e(route('transporte.update', $tarifa->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="form-group">
                                <label for="transportista_id">Transportista</label>
                                <select class="form-control" id="transportista_id" name="transportista_id" required>
                                    <option value="">Seleccione un transportista</option>
                                    <?php $__currentLoopData = $transportistas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transportista): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($transportista->id); ?>" <?php echo e($transportista->id == $tarifa->transportista_id ? 'selected' : ''); ?>>
                                            <?php echo e($transportista->nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="destino_id">Destino</label>
                                <select class="form-control" id="destino_id" name="destino_id" required>
                                    <option value="">Seleccione un destino</option>
                                    <?php $__currentLoopData = $destinos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $destino): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($destino->id); ?>" <?php echo e($destino->id == $tarifa->destino_id ? 'selected' : ''); ?>>
                                            <?php echo e($destino->nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tar_usa">Tarifa USA</label>
                                <input type="float" class="form-control" id="tar_usa" name="tar_usa" value="<?php echo e(old('tar_usa', $tarifa->tar_usa)); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="tar_mex">Tarifa México</label>
                                <input type="float" class="form-control" id="tar_mex" name="tar_mex" value="<?php echo e(old('tar_mex', $tarifa->tar_mex)); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="retencion">Retención</label>
                                <input type="float" class="form-control" id="retencion" name="retencion" value="<?php echo e(old('retencion', $tarifa->retencion)); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="moneda">Moneda</label>
                                <input type="text" class="form-control" id="moneda" name="moneda" value="<?php echo e(old('moneda', $tarifa->moneda)); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="tc_fijo">TC Fijo</label>
                                <input type="float" class="form-control" id="tc_fijo" name="tc_fijo" value="<?php echo e(old('tc_fijo', $tarifa->tc_fijo)); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="iva">Total a pagar</label>
                                <input type="float" class="form-control" id="iva" name="iva" value="<?php echo e(old('iva', $tarifa->iva)); ?>" >
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="<?php echo e(route('transporte.index')); ?>" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/transporte/edit.blade.php ENDPATH**/ ?>