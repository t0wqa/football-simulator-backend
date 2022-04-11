<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411164534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team_statistics (guid UUID NOT NULL, team UUID DEFAULT NULL, wins_count INT NOT NULL, draws_count INT NOT NULL, defeats_count INT NOT NULL, goals_scored INT NOT NULL, goals_missed INT NOT NULL, PRIMARY KEY(guid))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC0AF9E7C4E0A61F ON team_statistics (team)');
        $this->addSql('ALTER TABLE team_statistics ADD CONSTRAINT FK_DC0AF9E7C4E0A61F FOREIGN KEY (team) REFERENCES team (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE team_statistics');
    }
}
