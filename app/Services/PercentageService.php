<?php

namespace App\Services;



class PercentageService
{
    /**
     * @param $value
     * In charge of converting two types of percentage form to float to be saved in the database
     * @return float
     */
    public function convertToPercentage($value){
        $percentage =$value;
        if (str_contains((string)$value, '/')) {
            $percentageParts = explode("/", $value);
            $percentage = (float)((int)$percentageParts[0] / (int)$percentageParts[1]);
        }
        if (str_contains((string)$value, '%')) {
            $percentageParts = explode("%", $value);
            $percentage = (float)((int)$percentageParts[0] / 100);
        }
        return $percentage;
    }
}
