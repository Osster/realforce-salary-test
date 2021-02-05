<?php
namespace App\Logic\Salary\Taxes;


abstract class Tax
{
    /**
     * @var int
     */
    protected $value = 0;

    /**
     * @param array $args
     * @return float
     */
    public abstract function calc($args = []): float;
}
