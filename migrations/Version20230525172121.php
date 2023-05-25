<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525172121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiscalite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ipr DOUBLE PRECISION NOT NULL, iere DOUBLE PRECISION NOT NULL, is_local BOOLEAN NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fonction AS SELECT id, titre, base_salarial FROM fonction');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('CREATE TABLE fonction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, base_salarial DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO fonction (id, titre, base_salarial) SELECT id, titre, base_salarial FROM __temp__fonction');
        $this->addSql('DROP TABLE __temp__fonction');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fiscalite');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fonction AS SELECT id, titre, base_salarial FROM fonction');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('CREATE TABLE fonction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, base_salarial DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO fonction (id, titre, base_salarial) SELECT id, titre, base_salarial FROM __temp__fonction');
        $this->addSql('DROP TABLE __temp__fonction');
    }
}
