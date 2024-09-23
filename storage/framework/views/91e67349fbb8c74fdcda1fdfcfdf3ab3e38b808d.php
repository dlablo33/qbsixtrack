<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
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
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-info">
                        <div class="card-header text-center">
                            <h3 class="card-title">Crear Clientes</h3>
                        </div>

                        <form class="card-body" style="margin: 10px" action="<?php echo e(route('customers.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo $__env->make('qb-flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Cliente LP</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="clp" id="clp" placeholder=" " required="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Nombre Comercial</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nc" id="nc" placeholder="">
                                </div>
                            </div>

                           <!-- ===================================================================================================== --> 
                           <div class="form-group row">
                                <label for="STATUS" class="col-sm-3 col-form-label">Status</label>
                                  <div class="col-sm-9">
                                       <div class="custom-control custom-switch" >
                                       <input type="checkbox" class="custom-control-input" id="STATUS" name="STATUS" checked>
                                     <label class="custom-control-label" for="STATUS">Activo</label>
                                 </div>
                             </div>
                        </div>

                        <!-- ===================================================================================================== -->

                        <div class="form-group row">
                                <label for="phone" class="col-sm-3 col-form-label">RFC</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="rfc" id="rfc" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-sm-3 col-form-label">Razon Social</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="rs" name="rs" placeholder=""></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="city" class="col-sm-3 col-form-label">Empresa Vendedora</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="ev" id="ev" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country" class="col-sm-3 col-form-label">Codigo Cliente Contable</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="ccc" id="ccc" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="state" class="col-sm-3 col-form-label">Codigo Cliente Comercial</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cco" id="cco" placeholder="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="zip" class="col-sm-3 col-form-label">Denominacion Serial</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="ds" id="ds" placeholder="">
                                </div>
                            </div>

                            

                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <button type="submit" class="btn btn-info">Crear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/customers/create.blade.php ENDPATH**/ ?>