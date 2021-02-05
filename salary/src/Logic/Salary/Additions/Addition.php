<?php
namespace App\Logic\Salary\Additions;


abstract class Addition
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
