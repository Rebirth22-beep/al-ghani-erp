<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleInvoiceCollection extends ResourceCollection
{
    public $collects = SaleInvoiceResource::class;
}
