<?php
namespace App\Logic\Salary\Additions;

use \App\Logic\Salary\Type;
use Carbon\Carbon;

class KidAddition extends Addition
{

    /**
     * KidAddition constructor.
     */
    public function __construct($options = [])
    {
        $this->options = array_filter($options, function ($option) {
            if ($option["type"] == Type::kidBirthDate()) {
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
                $birthDate = Carbon::parse($item["value"]);
                return $salaryDate->gte($birthDate);
            });
            if (count($options) >= 3) {
                $this->value = $args["rate"] * 0.02;
            }
        }

        return $this->value;
    }
}
