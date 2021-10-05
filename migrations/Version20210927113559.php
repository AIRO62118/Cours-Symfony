<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927113559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_9B76551FFB88E14F ON fichier (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3BD7BF362');
        $this->addSql('DROP INDEX IDX_1D1C63B3BD7BF362 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP fichiers_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551FFB88E14F');
        $this->addSql('DROP INDEX IDX_9B76551FFB88E14F ON fichier');
        $this->addSql('ALTER TABLE fichier DROP utilisateur_id');
        $this->addSql('ALTER TABLE utilisateur ADD fichiers_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3BD7BF362 FOREIGN KEY (fichiers_id) REFERENCES fichier (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3BD7BF362 ON utilisateur (fichiers_id)');
    }
}
