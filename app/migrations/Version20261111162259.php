<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261111162259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE event (id UUID NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_all_day BOOLEAN NOT NULL, groupe_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA77A45358C ON event (groupe_id)');
        $this->addSql('CREATE TABLE event_game (event_id UUID NOT NULL, game_id UUID NOT NULL, PRIMARY KEY (event_id, game_id))');
        $this->addSql('CREATE INDEX IDX_3CE07D2771F7E88B ON event_game (event_id)');
        $this->addSql('CREATE INDEX IDX_3CE07D27E48FD905 ON event_game (game_id)');
        $this->addSql('CREATE TABLE event_attendee (status VARCHAR(255) NOT NULL, event_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY (event_id, user_id))');
        $this->addSql('CREATE INDEX IDX_57BC3CB771F7E88B ON event_attendee (event_id)');
        $this->addSql('CREATE INDEX IDX_57BC3CB7A76ED395 ON event_attendee (user_id)');
        $this->addSql('CREATE TABLE game (id UUID NOT NULL, name VARCHAR(255) NOT NULL, min_players INT NOT NULL, max_players INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE game_category (game_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY (game_id, category_id))');
        $this->addSql('CREATE INDEX IDX_AD08E6E7E48FD905 ON game_category (game_id)');
        $this->addSql('CREATE INDEX IDX_AD08E6E712469DE2 ON game_category (category_id)');
        $this->addSql('CREATE TABLE groupe (id UUID NOT NULL, title VARCHAR(255) NOT NULL, color_hex VARCHAR(7) NOT NULL, description TEXT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE groupe_role (id UUID NOT NULL, name VARCHAR(255) NOT NULL, permissions VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE reminder (id UUID NOT NULL, minutes_before INT NOT NULL, event_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_40374F4071F7E88B ON reminder (event_id)');
        $this->addSql('CREATE TABLE role (id UUID NOT NULL, name VARCHAR(255) NOT NULL, user_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6AA76ED395 ON role (user_id)');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password_hash TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE user_details (display_name VARCHAR(80) NOT NULL, avatar_url VARCHAR(255) DEFAULT NULL, bio VARCHAR(255) DEFAULT NULL, user_id UUID NOT NULL, PRIMARY KEY (user_id))');
        $this->addSql('CREATE TABLE user_groupe (user_id UUID NOT NULL, groupe_id UUID NOT NULL, role_id UUID NOT NULL, PRIMARY KEY (user_id, groupe_id))');
        $this->addSql('CREATE INDEX IDX_61EB971CA76ED395 ON user_groupe (user_id)');
        $this->addSql('CREATE INDEX IDX_61EB971C7A45358C ON user_groupe (groupe_id)');
        $this->addSql('CREATE INDEX IDX_61EB971CD60322AC ON user_groupe (role_id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE event_game ADD CONSTRAINT FK_3CE07D2771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_game ADD CONSTRAINT FK_3CE07D27E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_attendee ADD CONSTRAINT FK_57BC3CB771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE event_attendee ADD CONSTRAINT FK_57BC3CB7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE game_category ADD CONSTRAINT FK_AD08E6E7E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game_category ADD CONSTRAINT FK_AD08E6E712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reminder ADD CONSTRAINT FK_40374F4071F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE user_details ADD CONSTRAINT FK_2A2B1580A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE user_groupe ADD CONSTRAINT FK_61EB971CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE user_groupe ADD CONSTRAINT FK_61EB971C7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE user_groupe ADD CONSTRAINT FK_61EB971CD60322AC FOREIGN KEY (role_id) REFERENCES groupe_role (id) NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA77A45358C');
        $this->addSql('ALTER TABLE event_game DROP CONSTRAINT FK_3CE07D2771F7E88B');
        $this->addSql('ALTER TABLE event_game DROP CONSTRAINT FK_3CE07D27E48FD905');
        $this->addSql('ALTER TABLE event_attendee DROP CONSTRAINT FK_57BC3CB771F7E88B');
        $this->addSql('ALTER TABLE event_attendee DROP CONSTRAINT FK_57BC3CB7A76ED395');
        $this->addSql('ALTER TABLE game_category DROP CONSTRAINT FK_AD08E6E7E48FD905');
        $this->addSql('ALTER TABLE game_category DROP CONSTRAINT FK_AD08E6E712469DE2');
        $this->addSql('ALTER TABLE reminder DROP CONSTRAINT FK_40374F4071F7E88B');
        $this->addSql('ALTER TABLE role DROP CONSTRAINT FK_57698A6AA76ED395');
        $this->addSql('ALTER TABLE user_details DROP CONSTRAINT FK_2A2B1580A76ED395');
        $this->addSql('ALTER TABLE user_groupe DROP CONSTRAINT FK_61EB971CA76ED395');
        $this->addSql('ALTER TABLE user_groupe DROP CONSTRAINT FK_61EB971C7A45358C');
        $this->addSql('ALTER TABLE user_groupe DROP CONSTRAINT FK_61EB971CD60322AC');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_game');
        $this->addSql('DROP TABLE event_attendee');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_category');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_role');
        $this->addSql('DROP TABLE reminder');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_details');
        $this->addSql('DROP TABLE user_groupe');
    }
}
