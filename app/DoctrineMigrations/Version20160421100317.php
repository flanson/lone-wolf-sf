<?php

namespace LoneWolf\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160421100317 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE adventure_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE combat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE etape_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE adventure (id INT NOT NULL, story_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9E858E0FAA5D4036 ON adventure (story_id)');
        $this->addSql('CREATE TABLE combat (id INT NOT NULL, name VARCHAR(255) NOT NULL, combatSkill INT NOT NULL, enduranceMax INT NOT NULL, life INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE etape (id INT NOT NULL, story_id INT NOT NULL, chapterValue INT NOT NULL, enemies_defeated TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_285F75DDAA5D4036 ON etape (story_id)');
        $this->addSql('COMMENT ON COLUMN etape.enemies_defeated IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT FK_9E858E0FAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE etape ADD CONSTRAINT FK_285F75DDAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT fk_51ce6e86900c982f');
        $this->addSql('DROP INDEX uniq_51ce6e86900c982f');
        $this->addSql('ALTER TABLE hero ADD combat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero RENAME COLUMN enemy_id TO adventure_id');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E8655CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E86FC7EEDB8 FOREIGN KEY (combat_id) REFERENCES combat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E8655CF40F9 ON hero (adventure_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51CE6E86FC7EEDB8 ON hero (combat_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT FK_51CE6E8655CF40F9');
        $this->addSql('ALTER TABLE hero DROP CONSTRAINT FK_51CE6E86FC7EEDB8');
        $this->addSql('DROP SEQUENCE adventure_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE combat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE etape_id_seq CASCADE');
        $this->addSql('DROP TABLE adventure');
        $this->addSql('DROP TABLE combat');
        $this->addSql('DROP TABLE etape');
        $this->addSql('DROP INDEX UNIQ_51CE6E8655CF40F9');
        $this->addSql('DROP INDEX UNIQ_51CE6E86FC7EEDB8');
        $this->addSql('ALTER TABLE hero ADD enemy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero DROP adventure_id');
        $this->addSql('ALTER TABLE hero DROP combat_id');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT fk_51ce6e86900c982f FOREIGN KEY (enemy_id) REFERENCES enemy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_51ce6e86900c982f ON hero (enemy_id)');
    }
}
