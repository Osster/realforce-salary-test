<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Logic\Salary\Type;
use Carbon\Carbon;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204170950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->upAlise();
        $this->upBob();
        $this->upCharlie();

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE person");
        $this->addSql("TRUNCATE person_option");
        $this->addSql("TRUNCATE salary_rate");
    }

    private function upAlise() {
        $person_id = 1;
        $person_age = 26;
        $salary_rate = 6000;
        $empl_date = "2010-01-01";
        $this->addSql(
            "INSERT INTO person (id, name, active) values (:id, :name, :active)",
            ["id" => $person_id, "name" => "Alice", "active" => true],
            [\PDO::PARAM_INT, \PDO::PARAM_STR, \PDO::PARAM_BOOL]
        );


        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::emplDate(), "value" => $empl_date, "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::birthDate(), "value" => Carbon::now()->subYears($person_age)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::kidBirthDate(), "value" => Carbon::now()->subYears(5)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::kidBirthDate(), "value" => Carbon::now()->subYears(3)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );

        $this->addSql(
            "INSERT INTO salary_rate (person_id, value, start_at) values (:person_id, :value, :start_at)",
            ["person_id" => $person_id, "value" => $salary_rate, "start_at" => Carbon::now()->startOfYear()->toDateString()]
        );
    }

    private function upBob() {
        $person_id = 2;
        $person_age = 52;
        $salary_rate = 4000;
        $empl_date = "2010-01-01";
        $this->addSql(
            "INSERT INTO person (id, name, active) values (:id, :name, :active)",
            ["id" => $person_id, "name" => "Bob", "active" => true],
            [\PDO::PARAM_INT, \PDO::PARAM_STR, \PDO::PARAM_BOOL]
        );

        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::emplDate(), "value" => $empl_date, "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::birthDate(), "value" => Carbon::now()->subYears($person_age)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::companyCar(), "value" => Carbon::now()->subYears(5)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );

        $this->addSql(
            "INSERT INTO salary_rate (person_id, value, start_at) values (:person_id, :value, :start_at)",
            ["person_id" => $person_id, "value" => $salary_rate, "start_at" => Carbon::now()->startOfYear()->toDateString()]
        );
    }


    private function upCharlie() {
        $person_id = 3;
        $person_age = 36;
        $salary_rate = 5000;
        $empl_date = "2010-01-01";
        $this->addSql(
            "INSERT INTO person (id, name, active) values (:id, :name, :active)",
            ["id" => $person_id, "name" => "Charlie", "active" => true],
            [\PDO::PARAM_INT, \PDO::PARAM_STR, \PDO::PARAM_BOOL]
        );


        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::emplDate(), "value" => $empl_date, "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::birthDate(), "value" => Carbon::now()->subYears($person_age)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::kidBirthDate(), "value" => Carbon::now()->subYears(5)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::kidBirthDate(), "value" => Carbon::now()->subYears(4)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::kidBirthDate(), "value" => Carbon::now()->subYears(3)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );
        $this->addSql(
            "INSERT INTO person_option (name, value, person_id, updated_at) values (:name, :value, :person_id, :updated_at)",
            ["name" => Type::companyCar(), "value" => Carbon::now()->subYears(3)->toDateString(), "person_id" => $person_id, "updated_at" => Carbon::now()->toDateString()]
        );

        $this->addSql(
            "INSERT INTO salary_rate (person_id, value, start_at) values (:person_id, :value, :start_at)",
            ["person_id" => $person_id, "value" => $salary_rate, "start_at" => Carbon::now()->startOfYear()->toDateString()]
        );
    }
}
