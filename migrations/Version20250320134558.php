<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320134558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ressource_semaine ADD mois VARCHAR(255) NOT NULL');
        $this->addSql('ALTER INDEX user_username_key RENAME TO UNIQ_8D93D649F85E0677');
        $this->addSql('ALTER INDEX user_email_key RENAME TO UNIQ_8D93D649E7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER INDEX uniq_8d93d649e7927c74 RENAME TO user_email_key');
        $this->addSql('ALTER INDEX uniq_8d93d649f85e0677 RENAME TO user_username_key');
        $this->addSql('ALTER TABLE ressource_semaine DROP mois');
    }
}
