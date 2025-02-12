<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116180614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE creneau (id SERIAL NOT NULL, matiere_id INT NOT NULL, enseignant_id INT NOT NULL, ressource_id INT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, duree INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F9668B5FF46CD258 ON creneau (matiere_id)');
        $this->addSql('CREATE INDEX IDX_F9668B5FE455FCC0 ON creneau (enseignant_id)');
        $this->addSql('CREATE INDEX IDX_F9668B5FFC6CD52A ON creneau (ressource_id)');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5FF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5FFC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE creneau DROP CONSTRAINT FK_F9668B5FF46CD258');
        $this->addSql('ALTER TABLE creneau DROP CONSTRAINT FK_F9668B5FE455FCC0');
        $this->addSql('ALTER TABLE creneau DROP CONSTRAINT FK_F9668B5FFC6CD52A');
        $this->addSql('DROP TABLE creneau');
    }
}
