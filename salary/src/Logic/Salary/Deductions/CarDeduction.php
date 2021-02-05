<?php
namespace App\Logic\Salary\Deductions;

use \App\Logic\Salary\Type;
use Carbon\Carbon;

class CarDeduction extends Deduction
{

    /**
     * AgeAddition constructor.
     */
    public function __construct($options = [])
    {
        $this->options = array_filter($options, function ($option) {
            if ($option["type"] == Type::companyCar()) {
                return $option;
            }
            return false;
        });
    }

    public function calc($args = []): float
    {
        if (isset($args["rate"]) && isset($args["date"])) {
            $options = array_filter($this->options, function ($item) use ($args) {
                $salaryDate = Carbon::parse($args["date"]);
                $carDate = Carbon::parse($item["value"]);
                return $salaryDate->gte($carDate);
            });
            if (count($options) > 0) {
                $this->value = count($options) * 500;
            }
        }

        return $this->value;
    }
}
