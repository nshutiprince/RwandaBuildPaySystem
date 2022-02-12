<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache as FacadesCache;

class PayController extends Controller
{
    /**
     * @var PaymentService $paymentService
     * an object of PaymentService
     */
    private PaymentService $paymentService;

    /**
     * In charge of creating an object of PaymentService
     */
    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    /**
     * uses the PaymentService object to access functions
     */
    public function pay()
    {
        $this->paymentService->computeNetAmount();
    }
}
