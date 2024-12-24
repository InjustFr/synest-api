<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222211819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_server (user_id UUID NOT NULL, server_id UUID NOT NULL, PRIMARY KEY(user_id, server_id))');
        $this->addSql('CREATE INDEX IDX_3F3FCECBA76ED395 ON user_server (user_id)');
        $this->addSql('CREATE INDEX IDX_3F3FCECB1844E6B7 ON user_server (server_id)');
        $this->addSql('COMMENT ON COLUMN user_server.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN user_server.server_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE user_server ADD CONSTRAINT FK_3F3FCECBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_server ADD CONSTRAINT FK_3F3FCECB1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX idx_a2f98e4772f5a1aa RENAME TO IDX_A2F98E471844E6B7');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_server DROP CONSTRAINT FK_3F3FCECBA76ED395');
        $this->addSql('ALTER TABLE user_server DROP CONSTRAINT FK_3F3FCECB1844E6B7');
        $this->addSql('DROP TABLE user_server');
        $this->addSql('ALTER INDEX idx_a2f98e471844e6b7 RENAME TO idx_a2f98e4772f5a1aa');
    }
}
