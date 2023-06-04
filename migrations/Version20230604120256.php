<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604120256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fonction_id INTEGER NOT NULL, diplome_id INTEGER NOT NULL, etat_civil_id INTEGER NOT NULL, nom VARCHAR(255) NOT NULL, postnom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, debut_contrat DATE NOT NULL, fin_contrat DATE DEFAULT NULL, matricule VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, numero_cnss VARCHAR(255) NOT NULL, nombre_enfant INTEGER NOT NULL, CONSTRAINT FK_268B9C9D57889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_268B9C9D26F859E2 FOREIGN KEY (diplome_id) REFERENCES diplome (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_268B9C9D191476EE FOREIGN KEY (etat_civil_id) REFERENCES etat_civil (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_268B9C9D57889920 ON agent (fonction_id)');
        $this->addSql('CREATE INDEX IDX_268B9C9D26F859E2 ON agent (diplome_id)');
        $this->addSql('CREATE INDEX IDX_268B9C9D191476EE ON agent (etat_civil_id)');
        $this->addSql('CREATE TABLE diplome (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE etat_civil (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE exercice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, debut_annee DATE NOT NULL, fin_annee DATE NOT NULL, est_cloture BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE fiscalite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ipr DOUBLE PRECISION NOT NULL, iere DOUBLE PRECISION NOT NULL, is_local BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE fonction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, base_salarial DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE TABLE indemnite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, transport DOUBLE PRECISION NOT NULL, logement DOUBLE PRECISION NOT NULL, allocation_familiale DOUBLE PRECISION NOT NULL, autres DOUBLE PRECISION NOT NULL, CONSTRAINT FK_7FBE8D813414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7FBE8D813414710B ON indemnite (agent_id)');
        $this->addSql('CREATE TABLE paiement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, cnss DOUBLE PRECISION NOT NULL, ipr DOUBLE PRECISION NOT NULL, avance_salaire DOUBLE PRECISION NOT NULL, pret_logement DOUBLE PRECISION NOT NULL, pret_frais_scolaire DOUBLE PRECISION NOT NULL, pret_deuil DOUBLE PRECISION NOT NULL, pret_autre DOUBLE PRECISION NOT NULL, date_at DATE NOT NULL, base DOUBLE PRECISION NOT NULL, prime_diplome DOUBLE PRECISION NOT NULL, heure_supplementaire DOUBLE PRECISION NOT NULL, transport DOUBLE PRECISION NOT NULL, logement DOUBLE PRECISION NOT NULL, allocation_familiale DOUBLE PRECISION NOT NULL, autres DOUBLE PRECISION NOT NULL, abscence DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_B1DC7A1E3414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B1DC7A1E3414710B ON paiement (agent_id)');
        $this->addSql('CREATE TABLE remuneration (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, agent_id INTEGER NOT NULL, base DOUBLE PRECISION NOT NULL, prime_diplome DOUBLE PRECISION NOT NULL, heure_supplementaire DOUBLE PRECISION NOT NULL, CONSTRAINT FK_1969358B3414710B FOREIGN KEY (agent_id) REFERENCES agent (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1969358B3414710B ON remuneration (agent_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE diplome');
        $this->addSql('DROP TABLE etat_civil');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE fiscalite');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE indemnite');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE remuneration');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
