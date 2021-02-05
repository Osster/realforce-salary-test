<?php

namespace App\Command;

use App\Entity\Person;
use App\Entity\PersonOption;
use App\Entity\SalaryItem;
use App\Entity\SalaryRate;
use App\Entity\SalaryTotal;
use App\Logic\Salary\Salary;
use App\Repository\PersonOptionRepository;
use App\Repository\PersonRepository;
use App\Repository\SalaryRateRepository;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ConstraintViolation;

class SalaryCalcCommand extends Command
{
    private $container;

    private $io;

    protected static $defaultName = 'salary:calc';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('year', InputArgument::REQUIRED, 'Salary year')
            ->addArgument('month', InputArgument::REQUIRED, 'Salary month');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->container = $this->getApplication()->getKernel()->getContainer();

        $this->io = new SymfonyStyle($input, $output);

        $year = $input->getArgument('year');

        $month = $input->getArgument('month');

        if ($year && $month) {
            $this->io->note(sprintf('You passed an period: %s.%s', $year, $month));
        }

        $salary = [];

        $persons = $this->getPersons();

        foreach ($persons as $person) {

            $options = $this->getPersonOptions($person["id"]);

            $rate = $this->getRate($person["id"]);

            $salaryItem = new Salary($year, $month, $person, $options, $rate);

            if ($salaryItem->valid()) {

                $salary[] = $salaryItem;

            } else {

                $errors = $salaryItem->getErrors();

                foreach ($errors as $error) {

                    $this->io->error($error);

                }
            }
        }

        foreach ($salary as $k => $item) {
            if ($item instanceof Salary) {

                $item->calc();

                $this->storeItems($item->items());

                $this->storeTotals($item->totals());

            }
        }

        $this->io->success('Salary calculated.');

        return Command::SUCCESS;
    }

    private function getPersons(): ?Array
    {
        $repo = $this->container->get('doctrine')
            ->getRepository(Person::class);

        if ($repo instanceof PersonRepository) {
            $persons = $repo->findBy([
                "active" => true
            ]);

            $res = [];
            foreach ($persons as $person) {
                $res[] = [
                    "id" => $person->getId(),
                    "name" => $person->getName(),
                ];
            }
            return $res;
        }
        return null;
    }

    private function getPersonOptions(int $person_id): ?Array
    {
        $repo = $this->container->get('doctrine')
            ->getRepository(PersonOption::class);

        if ($repo instanceof PersonOptionRepository) {
            $options = $repo->findBy([
                "person_id" => $person_id
            ]);
            $res = [];
            foreach ($options as $k => $option) {
                $res[] = [
                    "type" => $option->getName(),
                    "value" => $option->getValue(),
                ];
            }
            return $res;
        }
        return null;
    }

    private function getRate($person_id): ?float
    {
        $repo = $this->container->get('doctrine')
            ->getRepository(SalaryRate::class);

        if ($repo instanceof SalaryRateRepository) {
            $rate = $repo->getActual($person_id, Carbon::now()->toDate());
            return ($rate instanceof SalaryRate) ? $rate->getValue() : 0;
        }
        return null;
    }

    private function storeItems(array $items = [])
    {
        $manager = $this->container->get('doctrine')->getManager();

        $validator = $this->container->get('validator');

        $errors = [];

        $rows = [];

        foreach ($items as $item) {

            $is_new = false;

            $salaryItem = $manager->getRepository(SalaryItem::class)->findOneBy([
                "year" => $item["year"],
                "month" => $item["month"],
                "person_id" => $item["person_id"],
                "type" => $item["type"],
            ]);

            if (!$salaryItem) {

                $is_new = true;

                $salaryItem = new SalaryItem();

            }

            $salaryItem->setYear($item["year"]);

            $salaryItem->setMonth($item["month"]);

            $salaryItem->setPersonId($item["person_id"]);

            $salaryItem->setType($item["type"]);

            $salaryItem->setValue($item["value"]);

            $salaryItem->setUpdatedAt($item["updated_at"]);

            $salaryItemErrors = $validator->validate($salaryItem);

            if (count($salaryItemErrors) > 0) {

                foreach ($salaryItemErrors as $salaryItemError) {
                    if ($salaryItemError instanceof ConstraintViolation) {

                        $message = $salaryItemError->getMessage();

                        $errors[] = $message;

                        $this->io->note(sprintf('Error: %s', $message));
                    }
                }

            } else {

                if ($is_new) {
                    $manager->persist($salaryItem);
                }

                $rows[] = $salaryItem;

            }

            $manager->flush();

        }

        return $rows;
    }

    private function storeTotals(array $item = [])
    {
        $manager = $this->container->get('doctrine')->getManager();

        $validator = $this->container->get('validator');

        $errors = [];

        $rows = [];

        $is_new = false;

        $salaryTotal = $manager->getRepository(SalaryTotal::class)->findOneBy([
            "year" => $item["year"],
            "month" => $item["month"],
            "person_id" => $item["person_id"],
        ]);

        if (!$salaryTotal) {

            $is_new = true;

            $salaryTotal = new SalaryTotal();

        }

        $salaryTotal->setYear($item["year"]);

        $salaryTotal->setMonth($item["month"]);

        $salaryTotal->setPersonId($item["person_id"]);

        $salaryTotal->setPersonName($item["person_name"]);

        $salaryTotal->setRate($item["rate"]);

        $salaryTotal->setAdditions($item["additions"]);

        $salaryTotal->setDeductions($item["deductions"]);

        $salaryTotal->setTaxes($item["taxes"]);

        $salaryTotal->setTotal($item["total"]);

        $salaryTotal->setUpdatedAt($item["updated_at"]);

        $salaryTotalErrors = $validator->validate($salaryTotal);

        if (count($salaryTotalErrors) > 0) {

            foreach ($salaryTotalErrors as $salaryTotalError) {
                if ($salaryTotalError instanceof ConstraintViolation) {

                    $message = $salaryTotalError->getMessage();

                    $errors[] = $message;

                    $this->io->note(sprintf('Error: %s', $message));
                }
            }

        } else {

            if ($is_new) {
                $manager->persist($salaryTotal);
            }

            $rows[] = $salaryTotal;

        }

        $manager->flush();

        return $rows;
    }
}
