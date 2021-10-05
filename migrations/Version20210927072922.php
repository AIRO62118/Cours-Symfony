<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927072922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_1D1C63B3F915CFE ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP fichier_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur ADD fichier_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_1D1C63B3F915CFE ON utilisateur (fichier_id)');
    }
}
