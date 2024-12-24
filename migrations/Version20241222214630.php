<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222214630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE server ADD owner_id UUID NOT NULL DEFAULT \'0193a58a-dabb-33ac-31a1-22d8e511fe2c\'::UUID');
        $this->addSql('COMMENT ON COLUMN server.owner_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F67E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5A6DD5F67E3C61F9 ON server (owner_id)');
        $this->addSql('ALTER TABLE server ALTER COLUMN owner_id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE server DROP CONSTRAINT FK_5A6DD5F67E3C61F9');
        $this->addSql('DROP INDEX IDX_5A6DD5F67E3C61F9');
        $this->addSql('ALTER TABLE server DROP owner_id');
    }
}
