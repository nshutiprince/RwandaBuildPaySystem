<?php

namespace App\Services;

class PaymentService
{
    /**
     * @var float total price
     */
    public float  $totalPrice;

    /**
     * @var float net amount
     */
    public float  $netAmount;

    /**
     * @var float coupon discount
     */
    public float  $couponDiscount;

    /**
     * @var float discount price
     */
    public float  $discountPrice;

    /**
     * @var float vat tax
     */
    public float  $vatPrice;

    /**
     * @var int quantity
     * is the quantity that the user is buying
     */
    public int    $quantity;

    /**
     * @var array discount
     * contains all discount attributes from services.php
     */
    public array  $discount;

    /**
     * @var array coupon
     * contains all coupon attributes from services.php
     */
    public array  $coupon;

    /**
     * @var array product
     * contains all product attributes from services.php
     */
    public array  $product;

    /**
     * @var float vat
     * contains vat percentage
     */
    public float  $vat;

    /**
     * In charge of accessing all needed variables
     */
    public function __construct()
    {
        $this->product = config('services.product');
        $this->discount = config('services.discount');
        $this->coupon = config('services.coupon');
        $this->vat = config('services.vat%');
    }

    /**
     * In charge of caclculating the net amount
     */
    public function computeNetAmount()
    {
        $this->checkQuantity()
            ->calculateTotalPrice()
            ->calculateDiscount()
            ->calculateCoupon()
            ->calculateVat()
            ->calculateNetAmount()
            ->display();
    }

    /**
     * In charge of setting the quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * In charge of checking the quantity by comparing the one in the database and the one the user adds
     */
    public function checkQuantity()
    {
        if ($this->quantity > $this->product['quantity']) {
            dd('quantity exceeds the available');
        }
        return $this;
    }

    /**
     * In charge of calculating the total price by multiplying the quantity and the unity price
     */
    public function calculateTotalPrice()
    {
        $this->totalPrice = $this->product['price'] * $this->quantity;
        $this->netAmount = $this->totalPrice;
        return $this;
    }

    /**
     * In charge of calculating the discount if activated
     */
    public function calculateDiscount()
    {
        if ($this->discount['activated']) {
            $this->discountPrice = $this->totalPrice * $this->discount['%'];
            $this->netAmount -= $this->discountPrice;
        }

        return $this;
    }

    /**
     * In charge of calculating coupon discount if activated
     */
    public function calculateCoupon()
    {
        if ($this->coupon['activated']) {
            $this->couponDiscount = $this->totalPrice * $this->coupon['%'];
            $this->netAmount -= $this->couponDiscount;
        }

        return $this;
    }

    /**
     * In charge of calculating the vat of 18% of the net amount after reducting all discount
     */
    public function calculateVat()
    {
        $this->vatPrice = $this->netAmount * $this->vat;
        return $this;
    }

    /**
     * In charge of calculating the final net amount by taking net amount and add the vat tax
     */
    public function calculateNetAmount()
    {
        $this->netAmount += $this->vatPrice;
        return $this;
    }

    /**
     * In charge of dumping the data to the user
     */
    public function display()
    {
        $arr = [
            'product_name' => $this->product['name'],
            'quantity' => $this->quantity,
            'unity_price' => $this->product['price'],
            'total_price' => $this->totalPrice,
            'discount_Price' => $this->discountPrice,
            'coupon_discount' => $this->couponDiscount,
            'vat_price' => $this->vatPrice,
            'pay_price' => $this->netAmount,
        ];
        dd($arr);
    }
}
