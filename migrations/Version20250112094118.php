<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112094118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE server_setting_value (id UUID NOT NULL, server_setting_id UUID NOT NULL, server_id UUID NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F272B5593CFA2DD5 ON server_setting_value (server_setting_id)');
        $this->addSql('CREATE INDEX IDX_F272B5591844E6B7 ON server_setting_value (server_id)');
        $this->addSql('COMMENT ON COLUMN server_setting_value.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN server_setting_value.server_setting_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN server_setting_value.server_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE server_setting_value ADD CONSTRAINT FK_F272B5593CFA2DD5 FOREIGN KEY (server_setting_id) REFERENCES server_setting (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE server_setting_value ADD CONSTRAINT FK_F272B5591844E6B7 FOREIGN KEY (server_id) REFERENCES server (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE server_setting ADD default_value VARCHAR(255) NOT NULL DEFAULT \'\'');
        $this->addSql('ALTER TABLE server_setting ALTER COLUMN default_value DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE server_setting_value DROP CONSTRAINT FK_F272B5593CFA2DD5');
        $this->addSql('ALTER TABLE server_setting_value DROP CONSTRAINT FK_F272B5591844E6B7');
        $this->addSql('DROP TABLE server_setting_value');
        $this->addSql('ALTER TABLE server_setting DROP default_value');
    }
}
