<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110153844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE champs (id INT AUTO_INCREMENT NOT NULL, id_type_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_B34671BE1BD125E3 (id_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE champs_formulaire (champs_id INT NOT NULL, formulaire_id INT NOT NULL, INDEX IDX_D911E13A1ABA8B (champs_id), INDEX IDX_D911E13A5053569B (formulaire_id), PRIMARY KEY(champs_id, formulaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formulaire (id INT AUTO_INCREMENT NOT NULL, relation_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_5BDD01A83256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_champs (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, typage VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE champs ADD CONSTRAINT FK_B34671BE1BD125E3 FOREIGN KEY (id_type_id) REFERENCES type_champs (id)');
        $this->addSql('ALTER TABLE champs_formulaire ADD CONSTRAINT FK_D911E13A1ABA8B FOREIGN KEY (champs_id) REFERENCES champs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE champs_formulaire ADD CONSTRAINT FK_D911E13A5053569B FOREIGN KEY (formulaire_id) REFERENCES formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire ADD CONSTRAINT FK_5BDD01A83256915B FOREIGN KEY (relation_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE champs DROP FOREIGN KEY FK_B34671BE1BD125E3');
        $this->addSql('ALTER TABLE champs_formulaire DROP FOREIGN KEY FK_D911E13A1ABA8B');
        $this->addSql('ALTER TABLE champs_formulaire DROP FOREIGN KEY FK_D911E13A5053569B');
        $this->addSql('ALTER TABLE formulaire DROP FOREIGN KEY FK_5BDD01A83256915B');
        $this->addSql('DROP TABLE champs');
        $this->addSql('DROP TABLE champs_formulaire');
        $this->addSql('DROP TABLE formulaire');
        $this->addSql('DROP TABLE type_champs');
    }
}
