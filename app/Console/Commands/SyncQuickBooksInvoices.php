<?php
// app/Console/Commands/SyncQuickBooksInvoices.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\QuickBooksController;

class SyncQuickBooksInvoices extends Command
{
    protected $signature = 'quickbooks:sync-quickbook';
    protected $description = 'Sync invoices from QuickBooks to the local database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $controller = new QuickBooksController();
        $controller->fetchInvoices();
        $this->info('Invoices have been synchronized successfully.');
    }
}
