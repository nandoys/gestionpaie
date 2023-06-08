<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230608145753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__avance_salaire AS SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at FROM avance_salaire');
        $this->addSql('DROP TABLE avance_salaire');
        $this->addSql('CREATE TABLE avance_salaire (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, exercice_id INTEGER NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, mensualite_paye SMALLINT DEFAULT NULL, est_cloture BOOLEAN NOT NULL, date_at DATE DEFAULT NULL, CONSTRAINT FK_6DA8D1053414710B FOREIGN KEY (agent_id) REFERENCES agent (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DA8D10589D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO avance_salaire (id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at) SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at FROM __temp__avance_salaire');
        $this->addSql('DROP TABLE __temp__avance_salaire');
        $this->addSql('CREATE INDEX IDX_6DA8D1053414710B ON avance_salaire (agent_id)');
        $this->addSql('CREATE INDEX IDX_6DA8D10589D40298 ON avance_salaire (exercice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__avance_salaire AS SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at FROM avance_salaire');
        $this->addSql('DROP TABLE avance_salaire');
        $this->addSql('CREATE TABLE avance_salaire (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, exercice_id INTEGER NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, mensualite_paye SMALLINT DEFAULT NULL, est_cloture BOOLEAN NOT NULL, date_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_6DA8D1053414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DA8D10589D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO avance_salaire (id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at) SELECT id, agent_id, exercice_id, montant, mensualite, mensualite_paye, est_cloture, date_at FROM __temp__avance_salaire');
        $this->addSql('DROP TABLE __temp__avance_salaire');
        $this->addSql('CREATE INDEX IDX_6DA8D1053414710B ON avance_salaire (agent_id)');
        $this->addSql('CREATE INDEX IDX_6DA8D10589D40298 ON avance_salaire (exercice_id)');
    }
}
