<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410173059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament DROP CONSTRAINT FK_BD5FB8D93B78268A');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D93B78268A FOREIGN KEY (current_round_id) REFERENCES round (guid) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tournament DROP CONSTRAINT fk_bd5fb8d93b78268a');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT fk_bd5fb8d93b78268a FOREIGN KEY (current_round_id) REFERENCES round (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
