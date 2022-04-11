<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410132445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (guid UUID NOT NULL, round UUID DEFAULT NULL, home_team UUID DEFAULT NULL, guest_team UUID DEFAULT NULL, status VARCHAR(255) NOT NULL, home_goals INT NOT NULL, guest_goals INT NOT NULL, PRIMARY KEY(guid))');
        $this->addSql('CREATE INDEX IDX_232B318CC5EEEA34 ON game (round)');
        $this->addSql('CREATE INDEX IDX_232B318CE5C617D0 ON game (home_team)');
        $this->addSql('CREATE INDEX IDX_232B318CE05AFB42 ON game (guest_team)');
        $this->addSql('CREATE UNIQUE INDEX unique__game__round_home_team_guest_team ON game (round, home_team, guest_team)');
        $this->addSql('CREATE TABLE round (guid UUID NOT NULL, tournament UUID DEFAULT NULL, next_round_id UUID DEFAULT NULL, status VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(guid))');
        $this->addSql('CREATE INDEX IDX_C5EEEA34BD5FB8D9 ON round (tournament)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C5EEEA349E1A104E ON round (next_round_id)');
        $this->addSql('CREATE UNIQUE INDEX unique__round__tournament_position ON round (tournament, position)');
        $this->addSql('CREATE TABLE team (guid UUID NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(guid))');
        $this->addSql('CREATE TABLE tournament (guid UUID NOT NULL, current_round_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(guid))');
        $this->addSql('CREATE INDEX IDX_BD5FB8D93B78268A ON tournament (current_round_id)');
        $this->addSql('CREATE TABLE tournament_team (guid UUID NOT NULL, tournament UUID DEFAULT NULL, team UUID DEFAULT NULL, PRIMARY KEY(guid))');
        $this->addSql('CREATE INDEX IDX_F36D1421BD5FB8D9 ON tournament_team (tournament)');
        $this->addSql('CREATE INDEX IDX_F36D1421C4E0A61F ON tournament_team (team)');
        $this->addSql('CREATE UNIQUE INDEX unique__tournament_team__tournament_team ON tournament_team (tournament, team)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC5EEEA34 FOREIGN KEY (round) REFERENCES round (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE5C617D0 FOREIGN KEY (home_team) REFERENCES team (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE05AFB42 FOREIGN KEY (guest_team) REFERENCES team (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA34BD5FB8D9 FOREIGN KEY (tournament) REFERENCES tournament (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA349E1A104E FOREIGN KEY (next_round_id) REFERENCES round (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D93B78268A FOREIGN KEY (current_round_id) REFERENCES round (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_team ADD CONSTRAINT FK_F36D1421BD5FB8D9 FOREIGN KEY (tournament) REFERENCES tournament (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_team ADD CONSTRAINT FK_F36D1421C4E0A61F FOREIGN KEY (team) REFERENCES team (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CC5EEEA34');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA349E1A104E');
        $this->addSql('ALTER TABLE tournament DROP CONSTRAINT FK_BD5FB8D93B78268A');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CE5C617D0');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CE05AFB42');
        $this->addSql('ALTER TABLE tournament_team DROP CONSTRAINT FK_F36D1421C4E0A61F');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA34BD5FB8D9');
        $this->addSql('ALTER TABLE tournament_team DROP CONSTRAINT FK_F36D1421BD5FB8D9');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE round');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_team');
    }
}
