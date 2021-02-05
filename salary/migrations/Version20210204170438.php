<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204170438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Salary basic tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_option (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, value VARCHAR(255) NOT NULL, person_id INT NOT NULL, updated_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_item (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, month INT NOT NULL, person_id INT NOT NULL, type VARCHAR(60) NOT NULL, value DOUBLE PRECISION NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_rate (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, start_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary_total (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, month INT NOT NULL, person_id INT NOT NULL, person_name VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, additions DOUBLE PRECISION NOT NULL, deductions DOUBLE PRECISION NOT NULL, taxes DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX unique_options ON person_option (name,value,person_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_rates ON salary_rate (person_id,start_at)');
        $this->addSql('CREATE UNIQUE INDEX unique_items ON salary_item (year,month,type,person_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_total ON salary_total (year,month,person_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_option');
        $this->addSql('DROP TABLE salary_item');
        $this->addSql('DROP TABLE salary_rate');
        $this->addSql('DROP TABLE salary_total');
    }
}
