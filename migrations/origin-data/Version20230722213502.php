<?php

declare(strict_types=1);

namespace OriginDataMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230722213502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employees_projects (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_project_employee (employee_project_id INT NOT NULL, employee_id INT NOT NULL, INDEX IDX_B13AC03F8EBAAF48 (employee_project_id), INDEX IDX_B13AC03F8C03F15C (employee_id), PRIMARY KEY(employee_project_id, employee_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_project_project (employee_project_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_E9F4682F8EBAAF48 (employee_project_id), INDEX IDX_E9F4682F166D1F9C (project_id), PRIMARY KEY(employee_project_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee_project_employee ADD CONSTRAINT FK_B13AC03F8EBAAF48 FOREIGN KEY (employee_project_id) REFERENCES employees_projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee_project_employee ADD CONSTRAINT FK_B13AC03F8C03F15C FOREIGN KEY (employee_id) REFERENCES employees (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee_project_project ADD CONSTRAINT FK_E9F4682F8EBAAF48 FOREIGN KEY (employee_project_id) REFERENCES employees_projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employee_project_project ADD CONSTRAINT FK_E9F4682F166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee_project_employee DROP FOREIGN KEY FK_B13AC03F8EBAAF48');
        $this->addSql('ALTER TABLE employee_project_employee DROP FOREIGN KEY FK_B13AC03F8C03F15C');
        $this->addSql('ALTER TABLE employee_project_project DROP FOREIGN KEY FK_E9F4682F8EBAAF48');
        $this->addSql('ALTER TABLE employee_project_project DROP FOREIGN KEY FK_E9F4682F166D1F9C');
        $this->addSql('DROP TABLE employees_projects');
        $this->addSql('DROP TABLE employee_project_employee');
        $this->addSql('DROP TABLE employee_project_project');
    }
}
