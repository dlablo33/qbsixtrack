<!-- Navbar -->
<nav class="main-header navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('home')); ?>" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    <?php echo e(Auth::user()->name); ?>

                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <?php echo e(__('Logout')); ?>

                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="container">
        <!-- Brand Logo -->
        <a href="<?php echo e(route('home')); ?>" class="brand-link">
            <img src="<?php echo e(asset('admin/dist/img/AdminLTELogo.png')); ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">STenergy QB</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="#" class="nav-link <?php echo e($menu == 'home' ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

<!--=============================================================================================================================================================================================== -->               
                    <!-- qb confi -->
                    <?php if(Auth::user()->tipo_usuario == 1): ?>  
                    <li class="nav-item has-treeview <?php echo e($menu == 'settings' ? 'menu-is-opening menu-open' : ''); ?>">
      <a href="#" class="nav-link">
        <i class="nav-icon fas fa-cogs"></i>
        <p>
          Configuracion
          <i class="fas fa-angle-left right"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item ml-2">
          <a href="<?php echo e(route('tipocambio.index')); ?>" class="nav-link">
            <p>Tipo de cambio ( USD => MXN)</p>
          </a>
        </li>

        <li class="nav-item ml-2">
          <a href="<?php echo e(route('cardknox')); ?>" class="nav-link">
            <p>Usuarios</p>
          </a>
        </li>
        <li class="nav-item ml-2">
          <a href="<?php echo e(route('change.password')); ?>" class="nav-link">
            <p>Proximamente</p>
          </a>
        </li>
      </ul>
    </li>
  <?php endif; ?>
<!--=============================================================================================================================================================================================== -->               

<!--=============================================================================================================================================================================================== -->               
                    <!-- Customers Section -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Clientes
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('customers.index')); ?>" class="nav-link">
                                    <p>Lista de Clientes</p>
                                </a>
                            </li>
                            <?php if(Auth::user()->tipo_usuario == 1): ?>  
                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('customers.create')); ?>" class="nav-link">
                                    <p>Agregar Cliente</p>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
<!--=============================================================================================================================================================================================== -->               

<!--=============================================================================================================================================================================================== -->               
<!-- Products Section -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Productos Y Servicios
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('products.index')); ?>" class="nav-link">
                                    <p>Lista de productos </p>
                                </a>
                            </li>
                            <?php if(Auth::user()->tipo_usuario == 1): ?>  
                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('products.create')); ?>" class="nav-link">
                                    <p>Agregar Producto</p>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
<!--=============================================================================================================================================================================================== -->               

<!--=============================================================================================================================================================================================== -->               

                    <!-- Payment Section -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>
                                Compras
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('moleculas.molecula1')); ?>" class="nav-link">
                                    <p>Molecula 1</p>
                                </a>
                            </li>

                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('moleculas.molecula2')); ?>" class="nav-link">
                                    <p>Molecula 2</p>
                                </a>
                            </li>

                            <li class="nav-item ml-2">
                                <a href="" class="nav-link">
                                    <p>Molecula 3</p>
                                </a>
                            </li>

                            <li class="nav-item ml-2">
                                <a href="" class="nav-link">
                                    <p>Agente Aduanal</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    
<!--=============================================================================================================================================================================================== -->               

                        <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                            <p>
                                Comercial
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                        <?php if(Auth::user()->tipo_usuario == 1): ?>  
                            <li class="nav-item ml-2">
                            <a href="<?php echo e(route('facturas.index')); ?>" class="nav-link">
                                    <p>Remisiones Y Facturas</p>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->tipo_usuario == 1): ?>  
                            <li class="nav-item ml-2">
                            <a href="<?php echo e(route('cuentas.index')); ?>" class="nav-link">
                                    <p>Cartera De Clientes</p>
                                </a>
                            </li>
                            <?php endif; ?>

                        </ul>
                    </li>
<!--=============================================================================================================================================================================================== -->               
                        
                        <?php if(Auth::user()->id >= 1): ?>
                        <!-- Merchant Section -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                                <p>
                                    Traking
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('invoice.petrolio')); ?>" class="nav-link">
                                    <p>Traking</p>
                                </a>
                            </li>
                            <!--<li class="nav-item ml-2">
                                <a href="<?php echo e(route('bol.index')); ?>" class="nav-link">
                                    <p>Deuda</p>
                                </a>
                            </li>-->
                            </ul>
                        </li>
                    <?php endif; ?>

<!--=============================================================================================================================================================================================== -->               
                    <?php if(Auth::user()->id >= 1): ?>
                        <!-- Merchant Section -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-store"></i>
                                <p>
                                    Precios
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ml-2">
                                    <a href="<?php echo e(route('marchants.index')); ?>" class="nav-link">
                                        <p>Lista de Precios</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a href="<?php echo e(route('marchants.create')); ?>" class="nav-link">
                                        <p>Nuevo Precio</p>
                                    </a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a href="<?php echo e(route('transporte.index')); ?>" class="nav-link">
                                    <p>Tarifa Trasporte</p>
                                    </a>
                                </li>
                            <li class="nav-item ml-2">
                                <a href="<?php echo e(route('moleculas.index')); ?>" class="nav-link">
                                    <p>Precios Molecula</p>
                                </a>
                            </li>
                            </ul>
                        </li>
                    <?php endif; ?>
<!--=============================================================================================================================================================================================== -->               
    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                Administracion
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                        <?php if(Auth::user()->tipo_usuario == 1): ?>  
                            <li class="nav-item ml-2">
                            <a href="<?php echo e(route('Admin.index')); ?>" class="nav-link">
                                    <p>Bancos</p>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                            <a href="<?php echo e(route('empresa_cuenta.index')); ?>" class="nav-link">
                                    <p>Gastos</p>
                                </a>
                            </li>
                            <?php endif; ?>


                        </ul>
                    </li>
<!-- ============================================================================================================================================================================================ -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                Operaciones
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                        <?php if(Auth::user()->tipo_usuario >= 1): ?>  
                            <li class="nav-item ml-2">
                            <a href="<?php echo e(Route('logistica.index')); ?>" class="nav-link">
                                    <p>Logistica</p>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                            <a href="<?php echo e(Route('bluewi.index')); ?>" class="nav-link">
                                    <p>Bluewing</p>
                                </a>
                            </li>
                            <?php endif; ?>


                        </ul>
                    </li>
                    <!-- ====================================================================================================================== -->
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </div>
</aside><?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/layouts/left-nav.blade.php ENDPATH**/ ?>