<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230116081959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE champs ADD utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE champs ADD CONSTRAINT FK_B34671BEFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_B34671BEFB88E14F ON champs (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE champs DROP FOREIGN KEY FK_B34671BEFB88E14F');
        $this->addSql('DROP INDEX IDX_B34671BEFB88E14F ON champs');
        $this->addSql('ALTER TABLE champs DROP utilisateur_id');
    }
}
