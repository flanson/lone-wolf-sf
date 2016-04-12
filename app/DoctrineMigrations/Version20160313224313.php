<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160313224313 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE hero ADD hero_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD currentChapter INT NOT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E8645B0BCD FOREIGN KEY (hero_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E8645B0BCD ON hero (hero_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT FK_51CE6E8645B0BCD');
        $this->addSql('DROP INDEX UNIQ_51CE6E8645B0BCD');
        $this->addSql('ALTER TABLE hero DROP hero_id');
        $this->addSql('ALTER TABLE hero DROP currentChapter');
    }
}
