<?php
namespace App\Logic\Salary\Additions;

use \App\Logic\Salary\Type;

class AgeAddition extends Addition
{

    /**
     * AgeAddition constructor.
     */
    public function __construct($options = [])
    {
        $this->options = array_filter($options, function ($option) {
            if ($option["type"] == Type::birthDate()) {
                return $option;
            }
            return false;
        });
    }

    public function calc($args = []): float
    {
        if (isset($args["rate"])) {
            $this->value = $args["rate"] * 0.07;
        }

        return $this->value;
    }
}
