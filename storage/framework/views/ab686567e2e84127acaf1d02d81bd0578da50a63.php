

<?php $__env->startSection('content'); ?>


    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Clientes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                   
                    <div class="d-flex">
                    <?php if(Auth::user()->tipo_usuario == 1): ?> 
                                <a href="/customers/syncCustomers" class="btn btn-danger mr-2"></a>
                                <?php endif; ?>
                                <a href="/customers/create" class="btn btn-info">Agregar Cliente</a>
                            </div>

                       
                        <div class="card-body">
                            <?php echo $__env->make('qb-flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php if(session('error')): ?>
                                <div class="alert alert-danger">
                                    <?php echo e(session('error')); ?>

                                </div>
                            <?php endif; ?>
                            <?php if(session('success')): ?>
                                <div class="alert alert-success">
                                    <?php echo e(session('success')); ?>

                                </div>
                            <?php endif; ?>
                            <table  id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                <th>ID</th>
                                <th>CLIENTE_LP</th>
                                <th>NOMBRE_COMERCIAL</th>
                                <th>STATUS</th>
                                <th>RFC</th>
                                <th>EMPRESA_VENDEDORA</th>
                                <th>CVE_CTE</th>
                                <th>Accion</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($customer->id); ?></td>
                                        <td><?php echo e($customer->CLIENTE_LP); ?></td>
                                        <td><?php echo e($customer->NOMBRE_COMERCIAL); ?></td>
                                        <td><?php echo e($customer->STATUS); ?></td>
                                        <td><?php echo e($customer->RFC); ?></td>
                                        <td><?php echo e($customer->EMPRESA_VENDEDORA); ?></td>
                                        <td><?php echo e($customer->CVE_CTE); ?></td>
                                        <td>
                                            <!-- Edit Button -->
                                            <a href="<?php echo e(route('customers.edit', ['id' => $customer->id])); ?>" class="btn btn-success">
                                                <i class="fas fa-edit"></i>
                                            </a>
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

    <!-- /.content -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\garci\Documents\qbsixtrack\resources\views/customers/index.blade.php ENDPATH**/ ?>