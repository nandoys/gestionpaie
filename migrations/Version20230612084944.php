<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612084944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agent (id INT AUTO_INCREMENT NOT NULL, fonction_id INT NOT NULL, diplome_id INT NOT NULL, etat_civil_id INT NOT NULL, nom VARCHAR(255) NOT NULL, postnom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, debut_contrat DATE NOT NULL, fin_contrat DATE DEFAULT NULL, matricule VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, numero_cnss VARCHAR(255) NOT NULL, nombre_enfant INT NOT NULL, INDEX IDX_268B9C9D57889920 (fonction_id), INDEX IDX_268B9C9D26F859E2 (diplome_id), INDEX IDX_268B9C9D191476EE (etat_civil_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avance_salaire (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, exercice_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, est_cloture TINYINT(1) NOT NULL, date_at DATE DEFAULT NULL, INDEX IDX_6DA8D1053414710B (agent_id), INDEX IDX_6DA8D10589D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avance_salaire_paiement (avance_salaire_id INT NOT NULL, paiement_id INT NOT NULL, INDEX IDX_DF128D3E20A4D832 (avance_salaire_id), INDEX IDX_DF128D3E2A4C4478 (paiement_id), PRIMARY KEY(avance_salaire_id, paiement_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diplome (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, prime_diplome DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_civil (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, debut_annee DATE NOT NULL, fin_annee DATE NOT NULL, est_cloture TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, base_salarial DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indemnite (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, transport DOUBLE PRECISION NOT NULL, logement DOUBLE PRECISION NOT NULL, allocation_familiale DOUBLE PRECISION NOT NULL, autres DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_7FBE8D813414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, cnss DOUBLE PRECISION NOT NULL, ipr DOUBLE PRECISION NOT NULL, avance_salaire DOUBLE PRECISION NOT NULL, pret_logement DOUBLE PRECISION NOT NULL, pret_frais_scolaire DOUBLE PRECISION NOT NULL, pret_deuil DOUBLE PRECISION NOT NULL, pret_autre DOUBLE PRECISION NOT NULL, date_at DATE NOT NULL, base DOUBLE PRECISION NOT NULL, prime_diplome DOUBLE PRECISION NOT NULL, heure_supplementaire DOUBLE PRECISION NOT NULL, transport DOUBLE PRECISION NOT NULL, logement DOUBLE PRECISION NOT NULL, allocation_familiale DOUBLE PRECISION NOT NULL, autres DOUBLE PRECISION NOT NULL, abscence DOUBLE PRECISION DEFAULT NULL, INDEX IDX_B1DC7A1E3414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pret_agent (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, exercice_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, mensualite SMALLINT NOT NULL, mensualite_paye SMALLINT DEFAULT NULL, est_cloture TINYINT(1) NOT NULL, date_at DATE DEFAULT NULL, type_pret VARCHAR(255) NOT NULL, INDEX IDX_56CB8DF83414710B (agent_id), INDEX IDX_56CB8DF889D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pret_agent_paiement (pret_agent_id INT NOT NULL, paiement_id INT NOT NULL, INDEX IDX_6EC1EE6C5F0D1A30 (pret_agent_id), INDEX IDX_6EC1EE6C2A4C4478 (paiement_id), PRIMARY KEY(pret_agent_id, paiement_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remuneration (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, base DOUBLE PRECISION NOT NULL, prime_diplome DOUBLE PRECISION NOT NULL, heure_supplementaire DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_1969358B3414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D57889920 FOREIGN KEY (fonction_id) REFERENCES fonction (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D26F859E2 FOREIGN KEY (diplome_id) REFERENCES diplome (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D191476EE FOREIGN KEY (etat_civil_id) REFERENCES etat_civil (id)');
        $this->addSql('ALTER TABLE avance_salaire ADD CONSTRAINT FK_6DA8D1053414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE avance_salaire ADD CONSTRAINT FK_6DA8D10589D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE avance_salaire_paiement ADD CONSTRAINT FK_DF128D3E20A4D832 FOREIGN KEY (avance_salaire_id) REFERENCES avance_salaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avance_salaire_paiement ADD CONSTRAINT FK_DF128D3E2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE indemnite ADD CONSTRAINT FK_7FBE8D813414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE pret_agent ADD CONSTRAINT FK_56CB8DF83414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE pret_agent ADD CONSTRAINT FK_56CB8DF889D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE pret_agent_paiement ADD CONSTRAINT FK_6EC1EE6C5F0D1A30 FOREIGN KEY (pret_agent_id) REFERENCES pret_agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pret_agent_paiement ADD CONSTRAINT FK_6EC1EE6C2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE remuneration ADD CONSTRAINT FK_1969358B3414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D57889920');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D26F859E2');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D191476EE');
        $this->addSql('ALTER TABLE avance_salaire DROP FOREIGN KEY FK_6DA8D1053414710B');
        $this->addSql('ALTER TABLE avance_salaire DROP FOREIGN KEY FK_6DA8D10589D40298');
        $this->addSql('ALTER TABLE avance_salaire_paiement DROP FOREIGN KEY FK_DF128D3E20A4D832');
        $this->addSql('ALTER TABLE avance_salaire_paiement DROP FOREIGN KEY FK_DF128D3E2A4C4478');
        $this->addSql('ALTER TABLE indemnite DROP FOREIGN KEY FK_7FBE8D813414710B');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E3414710B');
        $this->addSql('ALTER TABLE pret_agent DROP FOREIGN KEY FK_56CB8DF83414710B');
        $this->addSql('ALTER TABLE pret_agent DROP FOREIGN KEY FK_56CB8DF889D40298');
        $this->addSql('ALTER TABLE pret_agent_paiement DROP FOREIGN KEY FK_6EC1EE6C5F0D1A30');
        $this->addSql('ALTER TABLE pret_agent_paiement DROP FOREIGN KEY FK_6EC1EE6C2A4C4478');
        $this->addSql('ALTER TABLE remuneration DROP FOREIGN KEY FK_1969358B3414710B');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE avance_salaire');
        $this->addSql('DROP TABLE avance_salaire_paiement');
        $this->addSql('DROP TABLE diplome');
        $this->addSql('DROP TABLE etat_civil');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE indemnite');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE pret_agent');
        $this->addSql('DROP TABLE pret_agent_paiement');
        $this->addSql('DROP TABLE remuneration');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
