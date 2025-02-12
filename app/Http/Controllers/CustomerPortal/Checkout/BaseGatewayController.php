<?php

namespace App\Http\Controllers\CustomerPortal\Checkout;

use App\Http\Controllers\Controller;
use App\Traits\SavesInvoicePayment;

class BaseGatewayController extends Controller
{
    use SavesInvoicePayment;
}