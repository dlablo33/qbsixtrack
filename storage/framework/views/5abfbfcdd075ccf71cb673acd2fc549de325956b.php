<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Listado de Usuarios</h1>
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Tipo de Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($setting->name); ?></td>
                        <td><?php echo e($setting->email); ?></td>
                        <td><?php echo e($setting->tipo_usuario); ?></td>
                        <td>
                            <a href="<?php echo e(route('cardknox.edit', $setting->id)); ?>" class="btn btn-primary btn-sm">Editar</a> <!-- Botón Editar -->
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/cardknox/index.blade.php ENDPATH**/ ?>