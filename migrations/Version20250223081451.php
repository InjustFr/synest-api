<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223081451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE channel ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE channel ALTER type TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN channel.name IS \'(DC2Type:non_empty_string)\'');
        $this->addSql('COMMENT ON COLUMN channel.type IS \'(DC2Type:non_empty_string)\'');
        $this->addSql('ALTER TABLE file ALTER path TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN file.path IS \'(DC2Type:non_empty_string)\'');
        $this->addSql('ALTER TABLE message ALTER channel_id SET NOT NULL');
        $this->addSql('ALTER TABLE message ALTER content TYPE TEXT');
        $this->addSql('ALTER TABLE message ALTER username TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN message.content IS \'(DC2Type:non_empty_text)\'');
        $this->addSql('COMMENT ON COLUMN message.username IS \'(DC2Type:non_empty_string)\'');
        $this->addSql('ALTER TABLE server ALTER name TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN server.name IS \'(DC2Type:non_empty_string)\'');
        $this->addSql('ALTER TABLE server_setting ALTER key TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE server_setting ALTER type TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE server_setting ALTER description TYPE TEXT');
        $this->addSql('COMMENT ON COLUMN server_setting.key IS \'(DC2Type:non_empty_string)\'');
        $this->addSql('COMMENT ON COLUMN server_setting.type IS \'(DC2Type:non_empty_string)\'');
        $this->addSql('COMMENT ON COLUMN server_setting.description IS \'(DC2Type:non_empty_text)\'');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN "user".email IS \'(DC2Type:non_empty_string)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE server ALTER name TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN server.name IS NULL');
        $this->addSql('ALTER TABLE "user" ALTER email TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN "user".email IS NULL');
        $this->addSql('ALTER TABLE file ALTER path TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN file.path IS NULL');
        $this->addSql('ALTER TABLE server_setting ALTER key TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE server_setting ALTER type TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE server_setting ALTER description TYPE TEXT');
        $this->addSql('COMMENT ON COLUMN server_setting.key IS NULL');
        $this->addSql('COMMENT ON COLUMN server_setting.type IS NULL');
        $this->addSql('COMMENT ON COLUMN server_setting.description IS NULL');
        $this->addSql('ALTER TABLE channel ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE channel ALTER type TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN channel.name IS NULL');
        $this->addSql('COMMENT ON COLUMN channel.type IS NULL');
        $this->addSql('ALTER TABLE message ALTER channel_id DROP NOT NULL');
        $this->addSql('ALTER TABLE message ALTER content TYPE TEXT');
        $this->addSql('ALTER TABLE message ALTER username TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN message.content IS NULL');
        $this->addSql('COMMENT ON COLUMN message.username IS NULL');
    }
}
