

<?php $__env->startSection('content'); ?>
    <style>
        /* Estilos para la tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .row-highlight {
            background-color: #ffcccc;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Estilo para el filtro */
        .filter-form {
            margin-bottom: 20px;
        }
    </style>

    <div class="container">
        <h1>Listado de Bluewing</h1>
        <div class="text-right mb-3">
            <a href="<?php echo e(route('bluewi.upload.form')); ?>" class="btn btn-primary">Subir archivo</a>
        </div>
        <div class="text-right mb-3">
            <a href="<?php echo e(route('bluewi.compare.bol')); ?>" class="btn btn-primary">Comparar con Invoice</a>
        </div>

        <form action="<?php echo e(route('bluewi.index')); ?>" method="GET" class="filter-form">
            <div class="form-group">
                <label for="filter">Bol no creados</label>
                <input type="checkbox" id="filter" name="filter" value="1" <?php echo e(request('filter') ? 'checked' : ''); ?>>
                <button type="submit" class="btn btn-primary">Aplicar filtro</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>BOL#</th>
                        <th>BOL Ver.</th>
                        <th>Order Type</th>
                        <th>Status</th>
                        <th>BOL Date</th>
                        <th>Position Holder</th>
                        <th>Supplier</th>
                        <th>Customer</th>
                        <th>Destination</th>
                        <th>Carrier</th>
                        <th>PO</th>
                        <th>Truck</th>
                        <th>Trailer</th>
                        <th>Bay</th>
                        <th>Product</th>
                        <th>Scheduled Amount (USG)</th>
                        <th>Gross(USG)</th>
                        <th>Net(USG)</th>
                        <th>Temperature</th>
                        <th>Gravity</th>
                        <th>Tank</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $bluewi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e(!isset($row->bol_number) || trim($row->bol_number) === '' ? 'row-highlight' : ''); ?>">
                            <td><?php echo e($row->order_number); ?></td>
                            <td><?php echo e($row->bol_number != null ? ($row->bol_number) : ''); ?></td>
                            <td><?php echo e($row->bol_version); ?></td>
                            <td><?php echo e($row->order_type); ?></td>
                            <td><?php echo e($row->status); ?></td>
                            <td><?php echo e($row->bol_date); ?></td>
                            <td><?php echo e($row->position_holder); ?></td>
                            <td><?php echo e($row->supplier); ?></td>
                            <td><?php echo e($row->customer); ?></td>
                            <td><?php echo e($row->destination); ?></td>
                            <td><?php echo e($row->carrier); ?></td>
                            <td><?php echo e($row->po); ?></td>
                            <td><?php echo e($row->truck); ?></td>
                            <td><?php echo e($row->trailer); ?></td>
                            <td><?php echo e($row->bay); ?></td>
                            <td><?php echo e($row->product); ?></td>
                            <td><?php echo e($row->scheduled_amount_usg); ?></td>
                            <td><?php echo e($row->gross_usg); ?></td>
                            <td><?php echo e($row->net_usg); ?></td>
                            <td><?php echo e($row->temperature); ?></td>
                            <td><?php echo e($row->gravity); ?></td>
                            <td><?php echo e($row->tank); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php echo e($bluewi->appends(request()->input())->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/bluewi/index.blade.php ENDPATH**/ ?>