<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528203034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, jobseeker_id INT DEFAULT NULL, INDEX IDX_A45BDDC1BE04EA9 (job_id), INDEX IDX_A45BDDC14CF2B5A9 (jobseeker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, recruiter_id INT DEFAULT NULL, position VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, entreprise VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, employment_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FBD8E0F8156BE243 (recruiter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC14CF2B5A9 FOREIGN KEY (jobseeker_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8156BE243 FOREIGN KEY (recruiter_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1BE04EA9');
        $this->addSql('ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC14CF2B5A9');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8156BE243');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE job');
        $this->addSql('ALTER TABLE `user` DROP name, DROP last_name');
    }
}
