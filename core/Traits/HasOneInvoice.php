<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Invoices\Models\Invoice;

trait HasOneInvoice
{
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
