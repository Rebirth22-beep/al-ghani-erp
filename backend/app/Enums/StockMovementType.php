<?php

namespace App\Enums;

enum StockMovementType: string
{
    case SaleOut        = 'sale_out';
    case PurchaseIn     = 'purchase_in';
    case ReturnIn       = 'return_in';
    case ReturnOut      = 'return_out';
    case AdjustmentIn   = 'adjustment_in';
    case AdjustmentOut  = 'adjustment_out';
}
