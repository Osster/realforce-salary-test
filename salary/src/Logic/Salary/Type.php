<?php
namespace App\Logic\Salary;

use App\Logic\Salary\Additions\AgeAddition;
use App\Logic\Salary\Additions\KidAddition;
use App\Logic\Salary\Deductions\CarDeduction;
use App\Logic\Salary\Taxes\CountryTax;
use Symfony\Bundle\MakerBundle\Str;

class Type
{
    private static $base = [
        "empl_date" => null,
        "salary_rate" => null,
    ];

    private static $additions = [
        "birth_date" => AgeAddition::class,
        "kid_birth_date" => KidAddition::class,
    ];

    private static $deductions = [
        "company_car" => CarDeduction::class,
    ];

    private static $taxes = [
        "country_tax" => CountryTax::class,
    ];

    public static function all() {
        return array_merge(self::$base, self::$additions, self::$deductions, self::$taxes);
    }

    public static function base() {
        return self::$base;
    }

    public static function additions() {
        return self::$additions;
    }

    public static function deductions() {
        return self::$deductions;
    }

    public static function taxes() {
        return self::$taxes;
    }

    public static function __callStatic($name, $arguments) {
        $types = [];
        foreach (self::all() as $type => $cls) {
            $types[Str::asLowerCamelCase($type)] = $type;
        }
        if (isset($types[$name])) {
            return $types[$name];
        }
        throw new \Exception("Option Type '$name' is not exists");
    }
}
