<?php

namespace App\Services;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;

class LoyaltyRewardService
{
    /**
     * @var float netAmount
     * is the net amount to be paid
     */
    public float  $netAmount;

    /**
     * @var int loyaltyPoints
     * is the user loyalty point
     */
    public int $loyaltyPoints;

    /**
     * @var mixed loyaltyPrice
     * is the value of 10 loyalty points
     */
    public mixed  $loyaltyPrice;

    /**
     * @var float loyaltyAmount
     * is the amount of money reduced from the net amount because of loyalty points
     */
    public float  $loyaltyAmount;
    /**
     * @var UserService userService
     * is the object of the UserService class
     */
    public UserService  $userService;

    /**
     * @param $netAmount
     * In charge of accessing all needed variables
     */
    public function __construct($netAmount)
    {
        $this->netAmount = $netAmount;
        $this->loyaltyPoints = Auth::user()->loyalty_points;
        $this->loyaltyPrice = Config::where('name', 'loyalty Price')->pluck('value')->first();
        $this->userService = new UserService();
        $this->loyaltyAmount = 0;
    }

    /**
     * In charge of applying the loyalty price if the user has more than 10 loyalty point
     */
    public function applyLoyaltyPrice()
    {
        if ($this->loyaltyPrice && $this->loyaltyPoints >= 10) {
            if ($this->netAmount >= $this->loyaltyPrice) {
                $this->netAmount -= $this->loyaltyPrice;
                $this->loyaltyAmount += $this->loyaltyPrice;
                $this->loyaltyPoints -= 10;
            }
        }
        return $this;
    }

    /**
     * In charge of adding a loyalty point if net amount is greater or equal than 3000
     * and then call the UserService to update the user points
     */
    public function addLoyaltyPoint()
    {
        if ($this->netAmount >= 3000) {
            $this->loyaltyPoints += 1;
        }
        $this->userService->updateUserLoyaltyPoints($this->loyaltyPoints);
        return $this;
    }
}
