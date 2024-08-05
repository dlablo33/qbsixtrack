<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\ClienteBanco;

class DevolucionCreada
{
    use Dispatchable, SerializesModels;

    public $deposito;

    /**
     * Create a new event instance.
     *
     * @param  \App\ClienteBanco  $deposito
     * @return void
     */
    public function __construct(ClienteBanco $deposito)
    {
        $this->deposito = $deposito;
    }
}
