<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250315143102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ressource_semaine (id SERIAL NOT NULL, ressource_id INT DEFAULT NULL, semaine INT NOT NULL, cm DOUBLE PRECISION NOT NULL, td DOUBLE PRECISION NOT NULL, tp DOUBLE PRECISION NOT NULL, ds DOUBLE PRECISION NOT NULL, sae DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CE770508FC6CD52A ON ressource_semaine (ressource_id)');
        $this->addSql('ALTER TABLE ressource_semaine ADD CONSTRAINT FK_CE770508FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ressource_semaine DROP CONSTRAINT FK_CE770508FC6CD52A');
        $this->addSql('DROP TABLE ressource_semaine');
    }
}
