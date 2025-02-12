<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116174120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere (id SERIAL NOT NULL, enseignant_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, volume_horaire INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9014574AE455FCC0 ON matiere (enseignant_id)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE matiere DROP CONSTRAINT FK_9014574AE455FCC0');
        $this->addSql('DROP TABLE matiere');
    }
}
