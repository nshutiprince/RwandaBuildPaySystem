<?php

namespace App\Services;

class PaymentService
{

    public  $totalPrice;
    public  $netAmount;
    public  $couponDiscount;
    public  $discountPrice;
    public  $price;
    public  $quantity;
    public  $discount;
    public  $coupon;
    public  $product;
    public  $vat;


     public function __construct(){
        $this->product = config('services.product');
        $this->discount = config('services.discount');
        $this->coupon = config('services.coupon');
     }


     public function computeNetAmount(){
        $this->checkQuantity()
        ->calculateTotalPrice()
        ->calculateDiscount()
        ->calculateCoupon()
        ->calculateVat()
        ->calculateNetAmount()
        ->display();
     }
     public function setQuantity($quantity){
        $this->quantity = $quantity;
     }

     public function checkQuantity(){
        if ($this->quantity > $this->product['quantity']) {
            dd('quantity exceeds the available');
        }
        return $this;
     }

     public function calculateTotalPrice()
    {
        $this->totalPrice = $this->product['price'] * $this->quantity;
        $this->netAmount = $this->totalPrice;
        return $this;
    }
    public function calculateDiscount()
    {
        if($this->discount['activated']){
        $this->discountPrice = ($this->totalPrice * $this->discount['%']) / 100;
        $this->netAmount -= $this->discountPrice;
        }

        return $this;
    }

     public function calculateCoupon()
    {
        if($this->coupon['activated']){
            $this->couponDiscount = ($this->totalPrice * $this->coupon['%']) / 100;
            $this->netAmount -= $this->couponDiscount;
        }

        return $this;
    }

     public function calculateVat()
    {
        $this->vat = ($this->netAmount * 18) / 100;
        return $this;
    }
    public function calculateNetAmount()
    {
        $this->netAmount += $this->vat;
        return $this;
    }

    public function display(){
        $arr = [
            'product name' => $this->product['name'],
            'quantity' => $this->quantity,
            'unity price' => $this->product['price'],
            'total price' => $this->totalPrice,
            'discount Price' => $this->discountPrice,
            'coupon discount' => $this->couponDiscount,
            'Vat' => $this->vat,
            'pay price' => $this->netAmount,
        ];
        dd($arr);
    }




}
