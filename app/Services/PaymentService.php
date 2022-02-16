<?php

namespace App\Services;

use App\Models\Config;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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
     * @var float discount
     * contains all discount attributes from services.php
     */
    public float  $discount;

    /**
     * @var float coupon
     * contains all coupon attributes from services.php
     */
    public float  $coupon;

    /**
     * @var Product product
     * contains all product attributes from the database
     */
    public Product  $product;

    /**
     * @var float vat
     * contains vat percentage
     */
    public float  $vat;

    public LoyaltyRewardService  $loyaltyRewardService;


    /**
     * In charge of accessing all needed variables
     */
    public function __construct()
    {
        $this->product = Product::first();
        $this->discount = Config::where('name', 'discount')->pluck('value')->first();
        $this->coupon = Config::where('name', 'coupon')->pluck('value')->first();
        $this->vat = Config::where('name', 'vat')->pluck('value')->first();
        $this->quantity = 4;
        $this->discountPrice=0;
    }

    /**
     * In charge of caclculating the net amount
     */
    public function computeNetAmount()
    {
        $this
            ->calculateTotalPrice()
            ->calculateDiscount()
            ->calculateCoupon()
            ->calculateVat()
            ->calculateNetAmount()
            ->display();
    }

    /**
     * In charge of calculating the total price by multiplying the quantity and the unity price
     */
    public function calculateTotalPrice()
    {
        $this->totalPrice = $this->product->price * $this->quantity;
        $this->netAmount = $this->totalPrice;
        return $this;
    }

    /**
     * In charge of calculating the discount if activated
     */
    public function calculateDiscount()
    {
        if ($this->discount && Auth::user()->is_member) {
            $this->discountPrice = $this->totalPrice * $this->discount;
            $this->netAmount -= $this->discountPrice;
        }

        return $this;
    }

    /**
     * In charge of calculating coupon discount if activated
     */
    public function calculateCoupon()
    {
        if ($this->coupon) {
            $this->couponDiscount = $this->totalPrice * $this->coupon;
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
        $this->loyaltyRewardService = new LoyaltyRewardService($this->netAmount);
        $this->loyaltyRewardService
            ->applyLoyaltyPrice()
            ->addLoyaltyPoint();
        $this->netAmount = $this->loyaltyRewardService->netAmount;
        return $this;
    }

    /**
     * In charge of dumping the data to the user
     */
    public function display()
    {
        $arr = [
            'product_name' => $this->product->name,
            'quantity' => $this->quantity,
            'unity_price' => $this->product->price,
            'total_price' => $this->totalPrice,
            'discount_Price' => $this->discountPrice,
            'coupon_discount' => $this->couponDiscount,
            'vat_price' => $this->vatPrice,
            'loyalty_amount' => $this->loyaltyRewardService->loyaltyAmount,
            'pay_price' => $this->netAmount,
        ];
        dd($arr);
    }
}
