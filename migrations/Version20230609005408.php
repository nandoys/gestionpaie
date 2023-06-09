<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609005408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avance_salaire_paiement (avance_salaire_id INTEGER NOT NULL, paiement_id INTEGER NOT NULL, PRIMARY KEY(avance_salaire_id, paiement_id), CONSTRAINT FK_DF128D3E20A4D832 FOREIGN KEY (avance_salaire_id) REFERENCES avance_salaire (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DF128D3E2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DF128D3E20A4D832 ON avance_salaire_paiement (avance_salaire_id)');
        $this->addSql('CREATE INDEX IDX_DF128D3E2A4C4478 ON avance_salaire_paiement (paiement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE avance_salaire_paiement');
    }
}
