<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112082214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE renvoie_saisie (id INT AUTO_INCREMENT NOT NULL, fomulaire_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, saisie JSON NOT NULL, INDEX IDX_33B2156C7F069F64 (fomulaire_id_id), INDEX IDX_33B2156C9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE renvoie_saisie ADD CONSTRAINT FK_33B2156C7F069F64 FOREIGN KEY (fomulaire_id_id) REFERENCES formulaire (id)');
        $this->addSql('ALTER TABLE renvoie_saisie ADD CONSTRAINT FK_33B2156C9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE renvoie_saisie DROP FOREIGN KEY FK_33B2156C7F069F64');
        $this->addSql('ALTER TABLE renvoie_saisie DROP FOREIGN KEY FK_33B2156C9D86650F');
        $this->addSql('DROP TABLE renvoie_saisie');
    }
}
