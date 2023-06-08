<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608170005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pret_agent AS SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at, type_pret FROM pret_agent');
        $this->addSql('DROP TABLE pret_agent');
        $this->addSql('CREATE TABLE pret_agent (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, exercice_id INTEGER NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, mensualite_paye SMALLINT DEFAULT NULL, est_cloture BOOLEAN NOT NULL, date_at DATE DEFAULT NULL, type_pret VARCHAR(255) NOT NULL, CONSTRAINT FK_56CB8DF83414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_56CB8DF889D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pret_agent (id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at, type_pret) SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at, type_pret FROM __temp__pret_agent');
        $this->addSql('DROP TABLE __temp__pret_agent');
        $this->addSql('CREATE INDEX IDX_56CB8DF83414710B ON pret_agent (agent_id)');
        $this->addSql('CREATE INDEX IDX_56CB8DF889D40298 ON pret_agent (exercice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__pret_agent AS SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at, type_pret FROM pret_agent');
        $this->addSql('DROP TABLE pret_agent');
        $this->addSql('CREATE TABLE pret_agent (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, exercice_id INTEGER NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, mensualite_paye SMALLINT DEFAULT NULL, est_cloture BOOLEAN NOT NULL, date_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , type_pret VARCHAR(255) NOT NULL, CONSTRAINT FK_56CB8DF83414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_56CB8DF889D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO pret_agent (id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at, type_pret) SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at, type_pret FROM __temp__pret_agent');
        $this->addSql('DROP TABLE __temp__pret_agent');
        $this->addSql('CREATE INDEX IDX_56CB8DF83414710B ON pret_agent (agent_id)');
        $this->addSql('CREATE INDEX IDX_56CB8DF889D40298 ON pret_agent (exercice_id)');
    }
}
