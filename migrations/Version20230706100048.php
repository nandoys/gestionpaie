<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706100048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent CHANGE date_naissance date_naissance DATE DEFAULT NULL, CHANGE debut_contrat debut_contrat DATE DEFAULT NULL, CHANGE matricule matricule VARCHAR(255) DEFAULT NULL, CHANGE numero_cnss numero_cnss VARCHAR(255) DEFAULT NULL, CHANGE nombre_enfant nombre_enfant INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent CHANGE date_naissance date_naissance DATE NOT NULL, CHANGE debut_contrat debut_contrat DATE NOT NULL, CHANGE matricule matricule VARCHAR(255) NOT NULL, CHANGE numero_cnss numero_cnss VARCHAR(255) NOT NULL, CHANGE nombre_enfant nombre_enfant INT NOT NULL');
    }
}
