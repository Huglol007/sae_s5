<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250315135113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enseignant (id SERIAL NOT NULL, utilisateur_id INT NOT NULL, type_enseignant_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81A72FA1FB88E14F ON enseignant (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_81A72FA194B84BF5 ON enseignant (type_enseignant_id)');
        $this->addSql('CREATE TABLE type_enseignant (id SERIAL NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA1FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA194B84BF5 FOREIGN KEY (type_enseignant_id) REFERENCES type_enseignant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE enseignant DROP CONSTRAINT FK_81A72FA1FB88E14F');
        $this->addSql('ALTER TABLE enseignant DROP CONSTRAINT FK_81A72FA194B84BF5');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE type_enseignant');
    }
}
