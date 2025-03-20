<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250312110151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneau DROP CONSTRAINT fk_creneau_enseignant');
        $this->addSql('DROP SEQUENCE type_enseignant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE enseignant_id_seq CASCADE');
        $this->addSql('ALTER TABLE enseignant DROP CONSTRAINT enseignant_user_id_fkey');
        $this->addSql('ALTER TABLE enseignant DROP CONSTRAINT enseignant_type_enseignant_id_fkey');
        $this->addSql('DROP TABLE type_enseignant');
        $this->addSql('DROP TABLE enseignant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE type_enseignant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE enseignant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE type_enseignant (id SERIAL NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX type_enseignant_type_key ON type_enseignant (type)');
        $this->addSql('CREATE TABLE enseignant (id SERIAL NOT NULL, user_id INT NOT NULL, type_enseignant_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_81A72FA1A76ED395 ON enseignant (user_id)');
        $this->addSql('CREATE INDEX IDX_81A72FA194B84BF5 ON enseignant (type_enseignant_id)');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT enseignant_user_id_fkey FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT enseignant_type_enseignant_id_fkey FOREIGN KEY (type_enseignant_id) REFERENCES type_enseignant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT fk_creneau_enseignant FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
