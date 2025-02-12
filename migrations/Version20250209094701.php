<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209094701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE creneau ALTER COLUMN matiere_id DROP NOT NULL');
        $this->addSql('ALTER TABLE creneau ALTER COLUMN enseignant_id DROP NOT NULL');
        $this->addSql('ALTER TABLE creneau ALTER COLUMN ressource_id DROP NOT NULL');
        $this->addSql('ALTER TABLE creneau ALTER COLUMN promotion_id DROP NOT NULL');
    }



    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
