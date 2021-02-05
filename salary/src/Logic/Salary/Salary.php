<?php

namespace App\Logic\Salary;

use App\Logic\Salary\Additions\Addition;
use App\Logic\Salary\Deductions\Deduction;
use App\Logic\Salary\Taxes\Tax;
use Carbon\Carbon;

/**
 * Class Salary
 * @package App\Logic\Salary
 */
class Salary
{
    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @var array
     */
    private $person;

    /**
     * @var array
     */
    private $options;

    /**
     * @var float
     */
    private $rate;

    /**
     * @var float
     */
    private $additions_value;

    /**
     * @var float
     */
    private $deductions_value;

    /**
     * @var float
     */
    private $taxes_value;

    /**
     * @var float
     */
    private $total_value;

    /**
     * @var string
     */
    private $updated_at;

    /**
     * @var array
     */
    protected $additions = [];

    /**
     * @var array
     */
    protected $deductions = [];

    /**
     * @var array
     */
    protected $taxes = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Salary constructor.
     */
    public function __construct($year, $month, $person, $options = [], $rate = 0)
    {
        $this->year = $year;

        $this->month = $month;

        $this->person = $person;

        $this->options = $options;

        $this->rate = $rate;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        // ?Check calc bounds
        if ($this->year > intval(Carbon::now()->year) + 1 || $this->year < 2000) {
            // add exception
            $this->errors[] = "Incorrect year selected '$this->year'";

            return false;
        }

        if ($this->month > 12 || $this->month < 1) {
            // add exception
            $this->errors[] = "Incorrect month selected '$this->month'";

            return false;
        }

        // check empl_date
        $emplDateOption = array_filter($this->options, function ($option) {
            if ($option["type"] == Type::emplDate()) {
                return true;
            }

            return false;
        });

        if (!empty($emplDateOption)) {
            $emplDateOption = $emplDateOption[0];
        }

        if ($emplDateOption) {
            $salaryDate = Carbon::now()->setYear($this->year)->setMonth($this->month)->startOfMonth();

            $emplDate = Carbon::parse($emplDateOption['value']);

            return $salaryDate->gte($emplDate);
        }

        $this->errors[] = "Empl date option not found";

        return false;
    }

    /**
     * @return float
     */
    public function calc(): float
    {
        $rate = $this->rate();

        $additions = $this->additions();

        $deductions = $this->deductions();

        $salary = $rate + $additions - $deductions;

        $taxes = $this->taxes($salary);

        $this->total_value = $salary - $taxes;

        $this->updated_at = Carbon::now()->toDateTimeString();

        return $this->total_value;
    }

    /**
     * @return array
     */
    public function totals(): array
    {
        $totals = [
            "year" => $this->year,
            "month" => $this->month,
            "person_id" => $this->person ? $this->person["id"] : null,
            "person_name" => $this->person ? $this->person["name"] : null,
            "rate" => $this->rate(),
            "additions" => $this->additions_value,
            "deductions" => $this->deductions_value,
            "taxes" => $this->taxes_value,
            "total" => $this->total_value,
            "updated_at" => Carbon::parse($this->updated_at)->toDateTime(),
        ];

        return $totals;
    }

    /**
     * @return array
     */
    public function items(): array
    {
        $items = [];

        $updatedAt = Carbon::parse($this->updated_at)->toDateTime();

        $items[] = [
            "year" => $this->year,
            "month" => $this->month,
            "person_id" => $this->person ? $this->person["id"] : null,
            "type" => Type::salaryRate(),
            "value" => $this->rate,
            "updated_at" => $updatedAt,
        ];

        foreach ($this->additions as $type => $value) {
            $items[] = [
                "year" => $this->year,
                "month" => $this->month,
                "person_id" => $this->person ? $this->person["id"] : null,
                "type" => $type,
                "value" => $value,
                "updated_at" => $updatedAt,
            ];
        }

        foreach ($this->deductions as $type => $value) {
            $items[] = [
                "year" => $this->year,
                "month" => $this->month,
                "person_id" => $this->person ? $this->person["id"] : null,
                "type" => $type,
                "value" => $value * -1,
                "updated_at" => $updatedAt,
            ];
        }

        foreach ($this->taxes as $type => $value) {
            $items[] = [
                "year" => $this->year,
                "month" => $this->month,
                "person_id" => $this->person ? $this->person["id"] : null,
                "type" => $type,
                "value" => $value * -1,
                "updated_at" => $updatedAt,
            ];
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return float
     */
    protected function rate(): float
    {
        return floatval($this->rate);
    }

    /**
     * @return float
     */
    protected function deductions(): float
    {
        $this->deductions = [];

        foreach (Type::deductions() as $type => $cls) {
            if ($cls) {
                $this->deductions[$type] = new $cls($this->options);
            }
        }

        $salaryDate = Carbon::now()
            ->setYear($this->year)
            ->setMonth($this->month)
            ->startOfMonth()
            ->toDateString();

        foreach ($this->deductions as $k => $deduction) {
            if ($deduction instanceof Deduction) {
                $this->deductions[$k] = $deduction->calc([
                    "date" => $salaryDate,
                    "rate" => $this->rate,
                ]);
            }
        }

        $this->deductions_value = array_reduce($this->deductions, function ($t, $v) {
            return $t + $v;
        });

        return $this->deductions_value;
    }

    /**
     * @return float
     */
    protected function additions(): float
    {
        $this->additions = [];

        foreach (Type::additions() as $type => $cls) {
            if ($cls) {
                $this->additions[$type] = new $cls($this->options);
            }
        }

        $salaryDate = Carbon::now()
            ->setYear($this->year)
            ->setMonth($this->month)
            ->startOfMonth()
            ->toDateString();

        foreach ($this->additions as $k => $addition) {
            if ($addition instanceof Addition) {
                $this->additions[$k] = $addition->calc([
                    "date" => $salaryDate,
                    "rate" => $this->rate,
                ]);
            }
        }

        $this->additions_value = array_reduce($this->additions, function ($t, $v) {
            return $t + $v;
        });

        return $this->additions_value;
    }

    /**
     * @return float
     */
    protected function taxes(float $salary): float
    {
        $this->taxes = [];

        foreach (Type::taxes() as $type => $cls) {
            if ($cls) {
                $this->taxes[$type] = new $cls($this->options);
            }
        }

        foreach ($this->taxes as $k => $tax) {
            if ($tax instanceof Tax) {
                $this->taxes[$k] = $tax->calc([
                    "salary" => $salary,
                ]);
            }
        }

        $this->taxes_value = array_reduce($this->taxes, function ($t, $v) {
            return $t + $v;
        });

        return $this->taxes_value;
    }
}
