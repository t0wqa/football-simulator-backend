<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410173137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE round DROP CONSTRAINT FK_C5EEEA349E1A104E');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT FK_C5EEEA349E1A104E FOREIGN KEY (next_round_id) REFERENCES round (guid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE round DROP CONSTRAINT fk_c5eeea349e1a104e');
        $this->addSql('ALTER TABLE round ADD CONSTRAINT fk_c5eeea349e1a104e FOREIGN KEY (next_round_id) REFERENCES round (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
