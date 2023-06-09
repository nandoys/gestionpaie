<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230609080229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pret_agent_paiement (pret_agent_id INTEGER NOT NULL, paiement_id INTEGER NOT NULL, PRIMARY KEY(pret_agent_id, paiement_id), CONSTRAINT FK_6EC1EE6C5F0D1A30 FOREIGN KEY (pret_agent_id) REFERENCES pret_agent (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6EC1EE6C2A4C4478 FOREIGN KEY (paiement_id) REFERENCES paiement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6EC1EE6C5F0D1A30 ON pret_agent_paiement (pret_agent_id)');
        $this->addSql('CREATE INDEX IDX_6EC1EE6C2A4C4478 ON pret_agent_paiement (paiement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pret_agent_paiement');
    }
}
