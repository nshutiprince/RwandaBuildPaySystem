<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache as FacadesCache;

class PayController extends Controller
{
    public $user = ["name" => "nshuti", "status" => "member"];
    public $totalPrice = 0;
    public $couponDiscount = 0;
    public $discountPrice = 0;

    public function pay()
    {
        $price = config('services.product')['price'];
        $quantity = 4;
        $discount = config('services.discount')['%'];
        $coupon = config('services.coupon')['code'];

        //first check quantity if its available
        if ($quantity > config('services.product')['quantity']) {
            dd('quantity exceeds the available');
        }

        //then calculate the total price
        $this->calculateTotalPrice($price, $quantity);

        //then calculate the Vat
        $this->calculateVat($this->totalPrice);

        //then we check if there user is a member
        if ($this->user['status'] == 'member') {
            //then we calculate price after removing the discount
            $this->calculateDiscount($this->totalPrice, $discount);
        }
        //then we check if there is a coupon
        if ($coupon == 'RWB') {
            //then we keep the coupon discount in the discount variable
            $discount = config('services.coupon')['%'];
            //then we calculate price after removing the coupon discount
            $this->calculateCoupon($this->totalPrice, $discount);
        }
        //then we take the remaining prince we add the vat
        $this->setTotalPrice($this->totalPrice, $this->vat);
        //the we display the total price to pay
        $this->save($price, $quantity);
    }
    public function setTotalPrice($price, $vat)
    {
        $this->totalPrice += $vat;
    }

    public function calculateVat($price)
    {
        $this->vat = ($price * 18) / 100;
    }
    public function calculateTotalPrice($price, $quantity)
    {
        $this->totalPrice = $price * $quantity;
    }
    public function calculateDiscount($price, $discount)
    {
        $this->discountPrice = ($price * $discount) / 100;
        $this->totalPrice -= $this->discountPrice;
    }
    public function calculateCoupon($price, $discount)
    {
        $this->couponDiscount = ($price * $discount) / 100;
        $this->totalPrice -= $this->couponDiscount;
    }
    public function save($price, $quantity)
    {
        $arr = [
            'user' => $this->user['name'],
            'product name' => config('services.product')['name'],
            'quantity' => $quantity,
            'unity price' => $price,
            'total price' => $price * $quantity,
            'discount Price' => $this->discountPrice,
            'coupon discount' => $this->couponDiscount,
            'Vat' => $this->vat,
            'pay price' => $this->totalPrice,
        ];
        FacadesCache::add('receipt', $arr);
        $value = FacadesCache::pull('receipt');
        dd($value);
    }
}
