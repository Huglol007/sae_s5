<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116181037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promotion (id SERIAL NOT NULL, year_level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE creneau ADD promotion_id INT NOT NULL');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F9668B5F139DF194 ON creneau (promotion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE creneau DROP CONSTRAINT FK_F9668B5F139DF194');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP INDEX IDX_F9668B5F139DF194');
        $this->addSql('ALTER TABLE creneau DROP promotion_id');
    }
}
