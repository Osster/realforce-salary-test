<?php
namespace App\Logic\Salary\Taxes;

use \App\Logic\Salary\Type;
use Carbon\Carbon;

class CountryTax extends Tax
{

    /**
     * CountryTax constructor.
     */
    public function __construct()
    {
        //
    }

    public function calc($args = []): float
    {
        if (isset($args["salary"])) {
            $this->value = (float) $args["salary"] * 0.2;
        }

        return $this->value;
    }
}
