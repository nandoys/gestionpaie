<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526094106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paiement AS SELECT id, net, cnss, ipr, avance_salaire, pret_logement, pret_frais_scolaire, pret_deuil, pret_autre FROM paiement');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('CREATE TABLE paiement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, net DOUBLE PRECISION NOT NULL, cnss DOUBLE PRECISION NOT NULL, ipr DOUBLE PRECISION NOT NULL, avance_salaire DOUBLE PRECISION NOT NULL, pret_logement DOUBLE PRECISION NOT NULL, pret_frais_scolaire DOUBLE PRECISION NOT NULL, pret_deuil DOUBLE PRECISION NOT NULL, pret_autre DOUBLE PRECISION NOT NULL, datepaiement DATE NOT NULL, base DOUBLE PRECISION NOT NULL, prime_diplome DOUBLE PRECISION NOT NULL, heure_supplementaire DOUBLE PRECISION NOT NULL, transport DOUBLE PRECISION NOT NULL, logement DOUBLE PRECISION NOT NULL, allocation_familiale DOUBLE PRECISION NOT NULL, autres DOUBLE PRECISION NOT NULL, CONSTRAINT FK_B1DC7A1E3414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO paiement (id, net, cnss, ipr, avance_salaire, pret_logement, pret_frais_scolaire, pret_deuil, pret_autre) SELECT id, net, cnss, ipr, avance_salaire, pret_logement, pret_frais_scolaire, pret_deuil, pret_autre FROM __temp__paiement');
        $this->addSql('DROP TABLE __temp__paiement');
        $this->addSql('CREATE INDEX IDX_B1DC7A1E3414710B ON paiement (agent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__paiement AS SELECT id, net, cnss, ipr, avance_salaire, pret_logement, pret_frais_scolaire, pret_deuil, pret_autre FROM paiement');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('CREATE TABLE paiement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, net DOUBLE PRECISION NOT NULL, cnss DOUBLE PRECISION NOT NULL, ipr DOUBLE PRECISION NOT NULL, avance_salaire DOUBLE PRECISION NOT NULL, pret_logement DOUBLE PRECISION NOT NULL, pret_frais_scolaire DOUBLE PRECISION NOT NULL, pret_deuil DOUBLE PRECISION NOT NULL, pret_autre DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO paiement (id, net, cnss, ipr, avance_salaire, pret_logement, pret_frais_scolaire, pret_deuil, pret_autre) SELECT id, net, cnss, ipr, avance_salaire, pret_logement, pret_frais_scolaire, pret_deuil, pret_autre FROM __temp__paiement');
        $this->addSql('DROP TABLE __temp__paiement');
    }
}
