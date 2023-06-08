<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608111155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fiscalite');
        $this->addSql('ALTER TABLE avance_salaire ADD COLUMN date_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE pret_agent ADD COLUMN date_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE pret_agent ADD COLUMN type_pret VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiscalite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ipr DOUBLE PRECISION NOT NULL, iere DOUBLE PRECISION NOT NULL, is_local BOOLEAN NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__avance_salaire AS SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture FROM avance_salaire');
        $this->addSql('DROP TABLE avance_salaire');
        $this->addSql('CREATE TABLE avance_salaire (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, exercice_id INTEGER NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, mensualite_paye SMALLINT NOT NULL, est_cloture BOOLEAN NOT NULL, CONSTRAINT FK_6DA8D1053414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DA8D10589D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO avance_salaire (id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture) SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture FROM __temp__avance_salaire');
        $this->addSql('DROP TABLE __temp__avance_salaire');
        $this->addSql('CREATE INDEX IDX_6DA8D1053414710B ON avance_salaire (agent_id)');
        $this->addSql('CREATE INDEX IDX_6DA8D10589D40298 ON avance_salaire (exercice_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__pret_agent AS SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture FROM pret_agent');
        $this->addSql('DROP TABLE pret_agent');
        $this->addSql('CREATE TABLE pret_agent (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, exercice_id INTEGER NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, mensualite_paye SMALLINT DEFAULT NULL, est_cloture BOOLEAN NOT NULL, CONSTRAINT FK_56CB8DF83414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_56CB8DF889D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pret_agent (id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture) SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture FROM __temp__pret_agent');
        $this->addSql('DROP TABLE __temp__pret_agent');
        $this->addSql('CREATE INDEX IDX_56CB8DF83414710B ON pret_agent (agent_id)');
        $this->addSql('CREATE INDEX IDX_56CB8DF889D40298 ON pret_agent (exercice_id)');
    }
}
