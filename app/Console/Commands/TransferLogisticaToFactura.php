<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\FacturaController;

class TransferLogisticaToFactura extends Command
{
    protected $signature = 'logistica:transfer';
    protected $description = 'Transfiere los registros de Logistica a Factura';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $facturaController = new FacturaController();
        $facturaController->transferLogisticaToFactura();
        $this->info('Datos transferidos con éxito.');
    }

}
