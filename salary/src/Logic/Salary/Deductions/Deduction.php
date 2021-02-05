<?php
namespace App\Logic\Salary\Deductions;


abstract class Deduction
{
    /**
     * @var array
     */
    protected $options = [];

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
