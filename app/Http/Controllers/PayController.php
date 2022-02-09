<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache as FacadesCache;

class PayController extends Controller
{
    private $paymentService;
    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    public function pay()
    {
        $this->paymentService->setQuantity(4);
        $this->paymentService->computeNetAmount();
    }
}
