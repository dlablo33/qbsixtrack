

<?php $__env->startSection('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Lista de Facturas Molecula 2</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                    <li class="breadcrumb-item active">Facturas Molecula 2</li>
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
                        <?php if($invoices->count() > 0): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>bol</th>
                                        <th>Trailer</th>
                                        <th>Servicio</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($invoice->id); ?></td>
                                            <td><?php echo e($invoice->bol); ?></td>
                                            <td><?php echo e($invoice->Trailer); ?></td>
                                            <td><?php echo e($invoice->item_names); ?></td>
                                            <td><?php echo e($invoice->client->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($invoice->last_updated_time); ?></td>
                                            <td>$<?php echo e(number_format(number_format($invoice->total_amt, 2, '.', ''), 0, ',', ',')); ?></td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No se encontraron facturas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/invoice/mole2.blade.php ENDPATH**/ ?>