<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222192307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE server (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO server (id, name) VALUES (\'0193efdd-0a4a-e267-778f-38f9ae30ac6e\'::UUID, \'test\')');
        $this->addSql('COMMENT ON COLUMN server.id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE channel ADD server_id UUID NOT NULL DEFAULT \'0193efdd-0a4a-e267-778f-38f9ae30ac6e\'::UUID');
        $this->addSql('COMMENT ON COLUMN channel.server_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE channel ADD CONSTRAINT FK_A2F98E4772F5A1AA FOREIGN KEY (server_id) REFERENCES server (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A2F98E4772F5A1AA ON channel (server_id)');
        $this->addSql('ALTER TABLE channel ALTER COLUMN server_id DROP DEFAULT');
        $this->addSql('ALTER TABLE message ALTER channel_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE channel DROP CONSTRAINT FK_A2F98E4772F5A1AA');
        $this->addSql('DROP TABLE server');
        $this->addSql('ALTER TABLE message ALTER channel_id DROP NOT NULL');
        $this->addSql('DROP INDEX IDX_A2F98E4772F5A1AA');
        $this->addSql('ALTER TABLE channel DROP channel_id');
    }
}
