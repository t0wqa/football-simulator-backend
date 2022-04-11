<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410160957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CC5EEEA34');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC5EEEA34 FOREIGN KEY (round) REFERENCES round (guid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318cc5eeea34');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318cc5eeea34 FOREIGN KEY (round) REFERENCES round (guid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
