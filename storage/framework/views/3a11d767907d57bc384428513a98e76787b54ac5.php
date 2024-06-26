

<?php $__env->startSection('content'); ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Listado BOL</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li class="breadcrumb-item active">Facturas Agrupadas</li>
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
                        <?php if($invoices->isEmpty()): ?>
                            <div class="alert alert-warning">
                                No se encontraron facturas.
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>BOL</th>
                                        <th>Trailer</th>
                                        <th>Molecula 1</th>
                                        <th>Molecula 2</th>
                                        <th>Molecula 3</th>
                                        <th>Cliente</th>
                                        <th>Transporte</th>
                                        <th>Costo de Transporte</th>
                                        <th>Total Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bol => $groupedInvoices): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($bol); ?></td>
                                            <td><?php echo e($groupedInvoices->first()->Trailer); ?></td>
                                            <td>
                                                <?php $__currentLoopData = $groupedInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($invoice->item_names == 'PETROLEUM DISTILLATES'): ?>
                                                    Numero de Factura:<?php echo e($invoice->NumeroFactura); ?><br>
                                                        $<?php echo e($invoice->total_amt); ?><br>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td>
                                                <?php $__currentLoopData = $groupedInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($invoice->item_names == 'TRANSPORTATION FEE,SERVICE FEE,WEIGHT CONTROL'): ?>
                                                    Numero de Factura: <?php echo e($invoice->NumeroFactura); ?><br>
                                                        $<?php echo e($invoice->total_amt); ?><br>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td>
                                                <?php $__currentLoopData = $groupedInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($invoice->item_names == 'OPERATION ADJUSTED'): ?>
                                                    Numero de Factura:<?php echo e($invoice->NumeroFactura); ?><br>
                                                        $<?php echo e($invoice->total_amt); ?><br>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td>

                                            </td>
                                            <td>

                                            </td>
                                            <td>

                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/bol/index.blade.php ENDPATH**/ ?>