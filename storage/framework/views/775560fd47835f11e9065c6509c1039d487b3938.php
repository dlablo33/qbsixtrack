<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboards</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Gráfica de pastel -->
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Balance</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="graficaPastel"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Resumen</h3>
                        </div>
                        <div class="card-body">
                            <div id="saldoResumen"></div>
                            <p>Facturas o remisiones sin pagar: <?php echo e($facturasSinPagar); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gráfica de barras -->
            <div class="row mt-4">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Número de Facturas por Día (Última Semana)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="graficaBarras"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section> <!-- /.content -->

    <!-- Script para la gráfica de pastel -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
       var ctxPie = document.getElementById('graficaPastel').getContext('2d');
var myPieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['Cuentas por pagar', 'Pagado esta semana', 'Saldo a favor'],
        datasets: [{
            label: 'Balance',
            data: [<?php echo e($totalCuentasPorPagar); ?>, <?php echo e($pagadoEstaSemana); ?>, <?php echo e($saldoAFavor < 0 ? 0 : $saldoAFavor); ?>], // Asegúrate de que el saldo a favor sea positivo
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                '<?php echo e($saldoAFavor < 0 ? "rgba(255, 255, 255, 0)" : "rgba(75, 192, 192, 0.2)"); ?>' // Color transparente si el saldo a favor es negativo
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                '<?php echo e($saldoAFavor < 0 ? "rgba(255, 255, 255, 0)" : "rgba(75, 192, 192, 1)"); ?>' // Color transparente si el saldo a favor es negativo
            ],
            borderWidth: 1
        }]
    },
    options: {
        animation: {
            animateRotate: true,
            animateScale: true
        },
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                fontColor: 'rgb(0, 0, 0)'
            }
        },
        title: {
            display: true,
            text: 'Balance',
            fontColor: 'rgb(0, 0, 0)',
            fontSize: 16,
            padding: 20
        }
    }
});
        // Script para calcular el resumen
        var saldoAFavor = <?php echo e($saldoAFavor); ?>;
        var saldoResumen = document.getElementById('saldoResumen');
        var saldoDiferencia = saldoAFavor - (<?php echo e($totalCuentasPorPagar); ?> - <?php echo e($pagadoEstaSemana); ?>);
        var saldoTexto = saldoDiferencia >= 0 ? 'Saldo a favor: ' : 'Saldo en contra: ';
        saldoResumen.innerHTML = saldoTexto + Math.abs(saldoDiferencia);
        saldoResumen.style.color = saldoDiferencia >= 0 ? 'green' : 'red';

        // Script para la gráfica de barras
        var ctxBar = document.getElementById('graficaBarras').getContext('2d');
        var myBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Número de Facturas',
                    data: <?php echo json_encode($invoicesData); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true
                },
                legend: {
                    display: false,
                    position: 'bottom',
                    labels: {
                        fontColor: 'rgb(0, 0, 0)'
                    }
                },
                title: {
                    display: true,
                    text: 'Número de Facturas por Día (Última Semana)',
                    fontColor: 'rgb(0, 0, 0)',
                    fontSize: 16,
                    padding: 20
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\garci\Documents\qbsixtrack\resources\views/dashboard.blade.php ENDPATH**/ ?>