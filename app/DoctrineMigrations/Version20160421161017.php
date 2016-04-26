<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160421161017 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE enemy DROP life');
        $this->addSql('ALTER TABLE etape DROP CONSTRAINT fk_285f75ddaa5d4036');
        $this->addSql('DROP INDEX idx_285f75ddaa5d4036');
        $this->addSql('ALTER TABLE etape RENAME COLUMN story_id TO adventure_id');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DD55CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_285F75DD55CF40F9 ON etape (adventure_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE enemy ADD life INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etape DROP CONSTRAINT FK_285F75DD55CF40F9');
        $this->addSql('DROP INDEX IDX_285F75DD55CF40F9');
        $this->addSql('ALTER TABLE etape RENAME COLUMN adventure_id TO story_id');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT fk_285f75ddaa5d4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_285f75ddaa5d4036 ON etape (story_id)');
    }
}
