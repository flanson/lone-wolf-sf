<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160421160437 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE hero DROP CONSTRAINT fk_51ce6e86aa5d4036');
        $this->addSql('DROP INDEX idx_51ce6e86aa5d4036');
        $this->addSql('ALTER TABLE hero DROP story_id');
        $this->addSql('ALTER TABLE hero DROP currentchapter');
        $this->addSql('ALTER TABLE adventure ADD etape_id INT NOT NULL');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT FK_9E858E0F4A8CA2AD FOREIGN KEY (etape_id) REFERENCES etape (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E858E0F4A8CA2AD ON adventure (etape_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE adventure DROP CONSTRAINT FK_9E858E0F4A8CA2AD');
        $this->addSql('DROP INDEX UNIQ_9E858E0F4A8CA2AD');
        $this->addSql('ALTER TABLE adventure DROP etape_id');
        $this->addSql('ALTER TABLE hero ADD story_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD currentchapter INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT fk_51ce6e86aa5d4036 FOREIGN KEY (story_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_51ce6e86aa5d4036 ON hero (story_id)');
    }
}
