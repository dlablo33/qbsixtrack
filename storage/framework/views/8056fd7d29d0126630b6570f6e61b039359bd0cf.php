<!-- Modal para ingresar el tipo de cambio -->
<div class="modal fade" id="tipoCambioModal" tabindex="-1" aria-labelledby="tipoCambioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipoCambioModalLabel">Asignar Tipo de Cambio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('aduana.assignTipoCambio')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tipo_de_cambio_global">Tipo de Cambio</label>
                        <input type="number" name="tipo_de_cambio_global" class="form-control" placeholder="Ingresa el tipo de cambio" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Tipo de Cambio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\sauce\sixtrackqb\resources\views/aduana/formulario.blade.php ENDPATH**/ ?>