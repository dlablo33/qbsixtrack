

<?php $__env->startSection('content'); ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Transportes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li class="breadcrumb-item active">Transportes</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex mb-2">
                    <a href="<?php echo e(route('transporte.create')); ?>" class="btn btn-info">Agregar Transporte</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Transportista</th>
                                    <th>Destino</th>
                                    <th>Tarifa USA</th>
                                    <th>Tarifa México</th>
                                    <th>Retención</th>
                                    <th>Moneda</th>
                                    <th>TC Fijo</th>
                                    <th>Total a Pagar</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tarifas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tarifa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($tarifa->id); ?></td>
                                        <td><?php echo e($tarifa->transportista->nombre); ?></td>
                                        <td><?php echo e($tarifa->destino->nombre); ?></td>
                                        <td><?php echo e($tarifa->tar_usa); ?></td>
                                        <td><?php echo e($tarifa->tar_mex); ?></td>
                                        <td><?php echo e($tarifa->retencion); ?></td>
                                        <td><?php echo e($tarifa->moneda); ?></td>
                                        <td><?php echo e($tarifa->tc_fijo); ?></td>
                                        <td><?php echo e($tarifa->iva); ?></td>
                                        <td>
                                        <a href="<?php echo e(route('transporte.edit', $tarifa->id)); ?>" class="btn btn-success">
                                            <i class="fas fa-edit"></i>
                                        </a> 
                                        <form action="<?php echo e(route('transporte.destroy', $tarifa->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este transporte?')">
                                            <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/transporte/index.blade.php ENDPATH**/ ?>