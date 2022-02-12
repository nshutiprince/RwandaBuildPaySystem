<?php

namespace App\Services;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;

class RoyaltyRewardService{
    /**
     * @var float netAmount
     * is the net amount to be paid
     */
    public float  $netAmount;

    /**
     * @var int royaltyPoints
     * is the user royalty point
     */
    public int $royaltyPoints;

    /**
     * @var mixed royaltyPrice
     * is the value of 10 royalty points
     */
    public mixed  $royaltyPrice;

    /**
     * @var float royaltyAmount
     * is the amount of money reduced from the net amount because of royalty points
     */
    public float  $royaltyAmount;
    /**
     * @var UserService userService
     * is the object of the UserService class
     */
    public UserService  $userService;

    /**
     * In charge of accessing all needed variables
     */
    public function __construct($netAmount)
    {
        $this->netAmount = $netAmount;
        $this->royaltyPoints = Auth::user()->royalty_points;
        $this->royaltyPrice = Config::where('name','royalty Price')->pluck('value')->first();
        $this->userService = new UserService();
        $this->royaltyAmount =0;
    }

    /**
     * In charge of applying the royalty price if the user has more than 10 royalty point
     */
    public function applyRoyaltyPrice(){
        if($this->royaltyPrice){
            while ($this->royaltyPoints >= 10) {
                if ($this->netAmount >= $this->royaltyPrice) {
                    $this->netAmount -= $this->royaltyPrice;
                    $this->royaltyAmount += $this->royaltyPrice;
                    $this->royaltyPoints -= 10;
                }
            }
        }
        return $this;
    }

    /**
     * In charge of adding a royalty point if net amount is greater or equal than 3000
     * and then call the UserService to update the user points
     */
    public function addRoyaltyPoint()
    {
        if ($this->netAmount >=3000) {
            $this->royaltyPoints +=1;
        }
        $this->userService->updateUserRoyaltyPoints($this->royaltyPoints);
        return $this;
    }




}
