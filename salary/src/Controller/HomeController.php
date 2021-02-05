<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\SalaryItem;
use App\Entity\SalaryTotal;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $persons = $entityManager->getRepository(Person::class)
            ->findBy([
                "active" => true,
            ]);

        $entityManager->flush();

        $formData = [
            "period" => [],
        ];

        for ($i = Carbon::now()->year; $i > Carbon::now()->year - 10; $i--) {
            for ($j = 12; $j >= 1; $j--) {
                $formData["period"][$i . "." . $j] = $i . "." . $j;
            }
        }

        return $this->render("pages/home.html.twig", [
            "persons" => $persons,
            "formData" => $formData,
        ]);
    }

    public function salary(int $year, int $month): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $totals = $entityManager->getRepository(SalaryTotal::class)
            ->findBy(
                [
                    "year" => $year,
                    "month" => $month,
                ],
                [
                    "person_name" => "asc"
                ]
            );

        $entityManager->flush();

        return $this->render("pages/salary.html.twig", [
            "period" => "{$year}.{$month}",
            "salary" => $totals,
        ]);
    }

    public function personSalary(int $person_id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $person = $entityManager->getRepository(Person::class)
            ->find($person_id);

        $entityManager->flush();

        $totals = $entityManager->getRepository(SalaryTotal::class)
            ->findBy(
                [
                    "person_id" => $person_id
                ],
                [
                    "year" => "desc",
                    "month" => "desc"
                ]
            );

        $entityManager->flush();

        return $this->render("pages/person-salary.html.twig", [
            "person" => $person,
            "salary" => $totals,
        ]);
    }

    public function personSalaryDetail(int $person_id, int $salary_id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $person = $entityManager->getRepository(Person::class)
            ->find($person_id);

        $entityManager->flush();

        $totals = $entityManager->getRepository(SalaryTotal::class)
            ->findOneBy(
                [
                    "id" => $salary_id,
                    "person_id" => $person_id,
                ]
            );

        $entityManager->flush();

        $items = $entityManager->getRepository(SalaryItem::class)
            ->findBy(
                [
                    "person_id" => $person_id,
                    "year" => $totals->getYear(),
                    "month" => $totals->getMonth(),
                ],
                [
                    "type" => "asc"
                ]
            );

        $entityManager->flush();

        return $this->render("pages/person-salary-detail.html.twig", [
            "person" => $person,
            "salary" => $totals,
            "items" => $items,
        ]);
    }

    public function calc(KernelInterface $kernel): Response
    {
        $request = Request::createFromGlobals();

        $period = $request->get('period');

        if ($period) {
            [$year, $month] = explode(".", $period);

            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput([
                'command' => 'salary:calc',
                'year' => $year,
                'month' => $month,
            ]);

            $output = new BufferedOutput();

            try {

                $application->run($input, $output);

            } catch (\Exception $e) {
                // handle it...
            }

            return $this->redirect("/salary/{$year}/{$month}");
        }

        return $this->redirect("/");
    }


}
